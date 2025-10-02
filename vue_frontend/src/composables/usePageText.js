// Small utility to load page content and safely read structured texts/links/images
// Provides fallback-first getters to avoid layout/XSS risks.
import { reactive, toRefs } from 'vue'
import { useEditMode } from '@/composables/useEditMode'
import apiClient from '@/services/apiClient'

// Simple sanitizer: allowlisted tags and attributes only
function sanitizeHtml(input = '') {
  if (typeof input !== 'string') return ''

  // Drop script/style tags entirely
  let html = input.replace(/<\/(?:script|style)>/gi, '')
  html = html.replace(/<(?:script|style)[^>]*>[\s\S]*?<\/(?:script|style)>/gi, '')

  // Remove on* event handlers
  html = html.replace(/\son[a-z]+\s*=\s*"[^"]*"/gi, '')
  html = html.replace(/\son[a-z]+\s*=\s*'[^']*'/gi, '')
  html = html.replace(/\son[a-z]+\s*=\s*[^\s>]+/gi, '')

  // Allowlist tags and prune others
  // Expanded to reflect common CMS content while staying safe
  const allowedTags = [
    'b','strong','i','em','u','br','p','ul','ol','li','a','img',
    'h1','h2','h3','h4','h5','h6','div','span','blockquote','code','pre','hr','section','header','footer'
  ]

  // Replace disallowed tags with their text content (strip tags)
  html = html.replace(/<\/?([a-z0-9-]+)([^>]*)>/gi, (match, tag, attrs) => {
    const t = String(tag || '').toLowerCase()
    if (!allowedTags.includes(t)) {
      // strip the whole tag
      return ''
    }

    if (t === 'a') {
      // keep only safe href, target, rel
      let href = ''
      const hrefMatch = attrs.match(/href\s*=\s*("([^"]*)"|'([^']*)'|([^\s>]+))/i)
      if (hrefMatch) {
        href = hrefMatch[2] || hrefMatch[3] || hrefMatch[4] || ''
      }
      // block javascript: and data: except images via / path
      const safe = href.startsWith('http://') || href.startsWith('https://') || href.startsWith('/')
      const safeHref = safe ? ` href="${href.replace(/"/g, '&quot;')}"` : ''
      return `<a${safeHref} target="_blank" rel="noopener noreferrer">`
    }

    if (t === 'img') {
      // allow only safe src and optional alt; drop others
      let src = ''
      let alt = ''
      const srcMatch = attrs.match(/src\s*=\s*("([^"]*)"|'([^']*)'|([^\s>]+))/i)
      if (srcMatch) {
        src = srcMatch[2] || srcMatch[3] || srcMatch[4] || ''
      }
      const altMatch = attrs.match(/alt\s*=\s*("([^"]*)"|'([^']*)')/i)
      if (altMatch) {
        alt = altMatch[2] || altMatch[3] || ''
      }
      const isSafeSrc = src.startsWith('https://') || src.startsWith('http://') || src.startsWith('/api/public/m/') || src.startsWith('/')
      if (!isSafeSrc) return ''
      const safeSrc = src.replace(/"/g, '&quot;')
      const safeAlt = (alt || '').replace(/</g,'').replace(/>/g,'')
      return `<img src="${safeSrc}" alt="${safeAlt}">`
    }

    // for others, drop attributes
    return `<${t}>`
  })

  // Close orphaned open tags for <a> in a naive way
  // This is intentionally simple and defensive; complex HTML should use CmsBlock
  return html
}

const state = reactive({
  pages: {}, // key -> { loading, error, page }
})

export function usePageText(pageKey) {
  if (!state.pages[pageKey]) {
    state.pages[pageKey] = { loading: false, error: null, page: null, _loadingPromise: null }
  }

  const load = async (options = {}) => {
    const { preferAdmin = false, force = false } = options || {}
    const entry = state.pages[pageKey]

    // If a request is in-flight, reuse it unless force is explicitly requested
    if (entry._loadingPromise && !force) {
      try { await entry._loadingPromise } catch (_) {}
      return
    }

    // If already loading without a tracked promise, short-circuit unless force
    if (entry.loading && !force) return

    entry.loading = true
    entry.error = null
    const { enabled } = useEditMode()
    entry._loadingPromise = (async () => {
    try {
      // In preview mode with admin auth, fetch via admin endpoint to see unpublished changes
      const isBrowser = typeof window !== 'undefined'
      // Detect preview/edit flags from hash or search (hash-mode friendly)
      let isPreview = false
      if (isBrowser) {
        try {
          const hash = window.location.hash || ''
          const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
          const params = new URLSearchParams(qs)
          const hasPreview = params.has('cmsPreview')
          const isEdit = params.has('cmsEdit') || params.get('cmsPreview') === 'edit'
          isPreview = !!(hasPreview || isEdit)
        } catch (_) {
          isPreview = (window.location.search || '').includes('cmsPreview=1')
        }
      }
      const adminToken = isBrowser ? (localStorage.getItem('admin_token') || '') : ''
      const isEditing = !!(enabled && enabled.value)

      // 1) Instant local cache hydration (no flicker)
      if (isBrowser && !force) {
        try {
          const raw = localStorage.getItem('page_content_cache:' + pageKey)
          if (raw && !entry.page) {
            const cached = JSON.parse(raw)
            if (cached && typeof cached === 'object') {
              entry.page = cached
            }
          }
        } catch (_) { /* ignore cache read errors */ }
      }

      // 2) Fetch fresh from API
      let res
      // Prefer admin endpoint when: explicit preferAdmin, or URL preview/edit, or edit mode, and token present
      if ((preferAdmin || isPreview || isEditing) && adminToken) {
        // Use admin endpoint for preview to bypass is_published filter
        // Only add cache-buster when force=true or in production to avoid excessive requests
        const params = (force || process.env.NODE_ENV === 'production') ? { _t: Date.now() } : {}
        res = await apiClient.get(`/api/admin/pages/${pageKey}`, { silent: true, params })
      } else {
        // Public endpoint for normal visitors - only cache-bust when force=true
        const params = force ? { _t: Date.now() } : {}
        res = await apiClient.get(`/api/public/pages/${pageKey}`, { silent: true, params })
      }

      // Accept both wrapped and direct payload shapes
      const body = res?.data || res
      const page = body?.page || body?.data?.page || null
      entry.page = page

      // 3) Write-through cache for next reload
      if (isBrowser && page) {
        try { localStorage.setItem('page_content_cache:' + pageKey, JSON.stringify(page)) } catch (_) {}
      }
    } catch (e) {
      entry.error = e?.message || 'Failed to load page content'
    } finally {
      state.pages[pageKey].loading = false
      entry._loadingPromise = null
    }
    })()
    await entry._loadingPromise
  }

  const getText = (key, fallback = '', options = {}) => {
    const allowEmpty = !!options.allowEmpty
    const page = state.pages[pageKey]?.page
    const texts = page?.content?.texts
    const val = texts && Object.prototype.hasOwnProperty.call(texts, key) ? texts[key] : undefined
    if (allowEmpty) {
      return typeof val === 'string' ? val : fallback
    }
    return (typeof val === 'string' && val.length > 0) ? val : fallback
  }

  const getHtml = (key, fallback = '', options = {}) => {
    const allowEmpty = !!options.allowEmpty
    const allowBodyFallback = options.allowBodyFallback !== false // only allow content.html fallback for 'body' key by default
    const page = state.pages[pageKey]?.page
    const htmls = page?.content?.htmls || page?.content?.rich || null
    let raw = htmls && Object.prototype.hasOwnProperty.call(htmls, key) ? htmls[key] : undefined
    // Fallback to texts[key] when htmls[key] is not provided (editor differences)
    if ((raw === undefined || raw === null || raw === '') && page?.content?.texts && Object.prototype.hasOwnProperty.call(page.content.texts, key)) {
      const tval = page.content.texts[key]
      if (typeof tval === 'string') raw = tval
    }
    // フォールバック: 'body' キーのみ単体の content.html を本文として扱う
    if ((raw === undefined || raw === null || raw === '') && key === 'body' && allowBodyFallback && typeof page?.content?.html === 'string') {
      raw = page.content.html
    }

    // 文字列でない場合は即フォールバック
    const primary = (typeof raw === 'string' && raw.trim().length > 0) ? raw : fallback
    let sanitized = sanitizeHtml(primary)

    // 許容しない場合、サニタイズ後の中身が「テキスト無し（タグのみ）」ならフォールバック
    if (!allowEmpty) {
      const noWhitespace = (typeof sanitized === 'string') ? sanitized.replace(/[\s\u00A0]/g, '') : ''
      const textOnly = noWhitespace.replace(/<[^>]*>/g, '')
      const isEffectivelyEmpty = textOnly.length === 0
      if (isEffectivelyEmpty) {
        sanitized = sanitizeHtml(typeof fallback === 'string' ? fallback : '')
      }
    }
    return sanitized
  }

  const getLink = (key, fallback = '', options = {}) => {
    const allowEmpty = !!options.allowEmpty
    const page = state.pages[pageKey]?.page
    const links = page?.content?.links
    const val = links && Object.prototype.hasOwnProperty.call(links, key) ? links[key] : undefined
    if (typeof val !== 'string') return fallback
    if (allowEmpty && val === '') return ''
    // Allow only app-relative or http(s)
    return val.startsWith('/') || val.startsWith('http://') || val.startsWith('https://') ? val : fallback
  }

  return {
    ...toRefs(state.pages[pageKey]),
    load,
    getText,
    getHtml,
    getLink,
  }
}

export default usePageText

// Utility: force-reload all pages currently present in the cache
export async function refreshLoadedPages(options = {}) {
  try {
    const keys = Object.keys(state.pages || {})
    for (const k of keys) {
      try {
        const inst = usePageText(k)
        await inst.load({ force: true, ...(options || {}) })
      } catch (_) { /* ignore individual failures */ }
    }
  } catch (_) { /* noop */ }
}
