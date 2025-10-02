import { reactive } from 'vue'
import { useMedia } from '@/composables/useMedia'
import { usePageText } from '@/composables/usePageText'
import { resolveMediaUrl } from '@/utils/url.js'

// Micro-cache shared across usePageMedia callers so we don't hammer the CMS API
const v2ImageCache = new Map() // slug -> images map | null
const missingV2Slugs = new Set() // slug -> fetched 404, skip further fetches unless forced

function hasAdminToken() {
  if (typeof window === 'undefined') return false
  try { return !!localStorage.getItem('admin_token') } catch (_) { return false }
}

function hasPreviewFlag() {
  if (typeof window === 'undefined') return false
  try {
    const hash = window.location.hash || ''
    const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
    const params = new URLSearchParams(qs)
    if (params.has('cmsPreview')) return true
    if (params.has('cmsEdit')) return true
    return params.get('cmsPreview') === 'edit'
  } catch (_) {
    return false
  }
}

// Provide per-page media key mapping over the global media registry
// PageContent.content.media_keys: { slotKey: mediaKey }

export function usePageMedia() {
  const state = reactive({
    pageKey: '',
    _media: null,
    _pageText: null,
    _v2images: null, // images resolved from CMS v2 snapshot (e.g., KV)
    ready: false,
  })

  // Map legacy PageContent page_key -> CMS v2 slug
  const toCmsSlug = (key) => {
    const k = String(key || '').toLowerCase()
    const map = {
      'company-profile': 'company',
      'about-institute': 'aboutus',
      // Some pages share the same slug/key; leave them as-is
      // Provide a couple of common aliases defensively
      'premium-membership': 'premium-membership',
      'standard-membership': 'standard-membership',
      'cri-consulting': 'cri-consulting',
      'membership': 'membership',
      'services': 'services',
      'about': 'about',
      'contact': 'contact',
      'home': 'home',
    }
    return map[k] || k
  }

  const ensure = async (pageKey, options = {}) => {
    try {
      state.pageKey = pageKey || ''
      // media registry
      state._media = useMedia()
      await state._media.ensure()
      // page mapping (optional)
      if (state.pageKey) {
        state._pageText = usePageText(state.pageKey)

        const detectedPreview = hasPreviewFlag()
        const detectedAdmin = hasAdminToken()
        const force = options.force === true || (!!options.preferAdmin && !options.force) || (!('preferAdmin' in options) && (detectedPreview || detectedAdmin))
        const preferAdmin = options.preferAdmin === true || (!('preferAdmin' in options) && (detectedPreview || detectedAdmin))

        const loadOpts = {}
        if (force) loadOpts.force = true
        if (preferAdmin) loadOpts.preferAdmin = true
        // Respect cache when not forcing; usePageText handles concurrent callers gracefully
        if (Object.keys(loadOpts).length > 0) {
          await state._pageText.load(loadOpts)
        } else {
          await state._pageText.load()
        }

        // Also try CMS v2 public snapshot to capture KV image (hero)
        try {
          const api = await import('@/services/apiClient')
          const apiClient = api.default || api
          const apiConfig = await import('@/config/api.js')
          const slug = toCmsSlug(state.pageKey)

          if (!force) {
            if (v2ImageCache.has(slug)) {
              state._v2images = v2ImageCache.get(slug)
              state.ready = true
              return
            }
            if (missingV2Slugs.has(slug)) {
              state._v2images = null
              state.ready = true
              return
            }
          }

          // Only add cache-buster when force=true AND in production to avoid excessive requests
          // Check both NODE_ENV and hostname to be more precise
          const isProduction = process.env.NODE_ENV === 'production' && 
                              (typeof window === 'undefined' || window.location.hostname !== 'localhost')
          const params = (force && isProduction) ? { _t: Date.now() } : {}
          const res = await apiClient.get(`/api/public/pages-v2/${slug}`, { silent: true, params })

          if (!res || res.success === false) {
            if (!force && Number(res?.code) === 404) missingV2Slugs.add(slug)
            state._v2images = v2ImageCache.get(slug) || null
          } else {
            const payload = res.data || res
            const sections = payload?.sections || payload?.data?.sections || payload?.data?.data?.sections || res?.sections || []
            const map = {}
            if (Array.isArray(sections)) {
              for (const s of sections) {
                const type = (s?.component_type || '').toUpperCase()
                const props = s?.props || s?.props_json || {}
                if (type === 'KV') {
                  const id = props?.image_id || props?.id
                  const ext = props?.ext || 'jpg'
                  if (id) {
                    const url = apiConfig.getApiUrl(`/api/public/m/${encodeURIComponent(id)}/md.${encodeURIComponent(ext)}`)
                    map['hero'] = url
                  }
                }
              }
            }
            const normalized = Object.keys(map).length ? map : null
            state._v2images = normalized
            if (normalized === null) {
              if (!force) missingV2Slugs.add(slug)
              else {
                v2ImageCache.delete(slug)
                missingV2Slugs.add(slug)
              }
            } else {
              v2ImageCache.set(slug, normalized)
              missingV2Slugs.delete(slug)
            }
          }
        } catch (_) {
          const slug = toCmsSlug(state.pageKey)
          if (!force) missingV2Slugs.add(slug)
          state._v2images = state._v2images || null
        }
      }
      state.ready = true
    } catch (_) { state.ready = true }
  }

  const getMapping = () => {
    try {
      const page = state._pageText?.page?.value
      const m = page?.content?.media_keys
      return m && typeof m === 'object' ? m : {}
    } catch (_) { return {} }
  }

  const resolveMediaKey = (slotKey, defaultMediaKey = '') => {
    const map = getMapping()
    return (map && typeof map === 'object' && map[slotKey]) ? map[slotKey] : (defaultMediaKey || '')
  }

  const getSlot = (slotKey, defaultMediaKey = '', fallback = '') => {
    // Prefer page-managed images (PageContent.content.images[slotKey])
    try {
      const page = state._pageText && state._pageText.page && state._pageText.page.value
      const imgs = page && page.content && page.content.images
      if (imgs && Object.prototype.hasOwnProperty.call(imgs, slotKey)) {
        const v = imgs[slotKey]
        let url = (v && typeof v === 'object') ? (v.url || '') : (typeof v === 'string' ? v : '')
        // Add cache-buster for storage file when uploaded_at is present
        try {
          const meta = (v && typeof v === 'object') ? v : null
          const ver = meta && meta.uploaded_at ? (Date.parse(meta.uploaded_at) || Date.now()) : null
          if (ver && typeof url === 'string' && url.startsWith('/storage/')) {
            url += (url.includes('?') ? '&' : '?') + '_t=' + encodeURIComponent(String(ver))
          }
        } catch (_) {}
        if (typeof url === 'string' && url.length) return resolveMediaUrl(url)
      }
    } catch (_) { /* ignore and fallback to registry */ }

    // Next, CMS v2 snapshot-derived images (e.g., hero from KV)
    try {
      const v2 = state._v2images
      const url2 = v2 && v2[slotKey]
      if (typeof url2 === 'string' && url2.length) return url2
    } catch (_) {}

    const mk = resolveMediaKey(slotKey, defaultMediaKey)
    return state._media ? state._media.getImage(mk, fallback) : (fallback || '')
  }

  const getResponsiveSlot = (slotKey, defaultMediaKey = '', fallback = '') => {
    // Prefer page-managed images with simple responsive suffix convention
    try {
      const page = state._pageText && state._pageText.page && state._pageText.page.value
      const imgs = page && page.content && page.content.images
      if (imgs && typeof imgs === 'object') {
        // Try viewport-specific variants then base key
        let vp = 'desktop'
        try {
          const w = typeof window !== 'undefined' ? window.innerWidth : 1200
          vp = (w <= 600) ? 'mobile' : (w <= 1024 ? 'tablet' : 'desktop')
        } catch (_) {}
        const tryKeys = vp === 'mobile'
          ? [ `${slotKey}_mobile`, `${slotKey}_sm`, `${slotKey}@mobile`, slotKey ]
          : vp === 'tablet'
          ? [ `${slotKey}_tablet`, `${slotKey}_md`, slotKey ]
          : [ slotKey ]
        for (const k of tryKeys) {
          if (!Object.prototype.hasOwnProperty.call(imgs, k)) continue
          const v = imgs[k]
          let url = (v && typeof v === 'object') ? (v.url || '') : (typeof v === 'string' ? v : '')
          try {
            const meta = (v && typeof v === 'object') ? v : null
            const ver = meta && meta.uploaded_at ? (Date.parse(meta.uploaded_at) || Date.now()) : null
            if (ver && typeof url === 'string' && url.startsWith('/storage/')) {
              url += (url.includes('?') ? '&' : '?') + '_t=' + encodeURIComponent(String(ver))
            }
          } catch (_) {}
          if (typeof url === 'string' && url.length) return resolveMediaUrl(url)
        }
      }
    } catch (_) { /* ignore and fallback to registry */ }

    // Then try CMS v2 snapshot-derived images
    try {
      const v2 = state._v2images
      const url2 = v2 && v2[slotKey]
      if (typeof url2 === 'string' && url2.length) return url2
    } catch (_) {}

    const mk = resolveMediaKey(slotKey, defaultMediaKey)
    return state._media ? state._media.getResponsiveImage(mk, fallback) : (fallback || '')
  }

  return { ensure, getSlot, getResponsiveSlot, _media: state._media }
}

export default usePageMedia
