<template>
  <div>
    <component
      :is="tag"
      :class="wrapperClass"
      :contenteditable="isEditing ? (type === 'html' ? 'true' : 'plaintext-only') : undefined"
      :data-cms-key="fieldKey"
      :data-cms-type="type"
      :spellcheck="false"
      ref="el"
      @input="onInput"
      @blur="onBlur"
      @keydown="onKeydown"
    >
      <slot>{{ displayValue }}</slot>
    </component>
    <div v-if="isEditing && dirty" class="inline-toolbar">
      <button class="btn btn-save" @click.prevent="save" :disabled="saving">保存</button>
      <button class="btn btn-cancel" @click.prevent="cancel" :disabled="saving">キャンセル</button>
      <span v-if="error" class="error">{{ error }}</span>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'
import { useEditMode } from '@/composables/useEditMode'
import { useAdminAuth } from '@/composables/useAdminAuth'
import usePageText from '@/composables/usePageText'
import apiClient from '@/services/apiClient'

export default {
  name: 'InlineEditable',
  props: {
    pageKey: { type: String, required: true },
    fieldKey: { type: String, required: true },
    type: { type: String, default: 'text' }, // 'text' | 'html' | 'link'
    tag: { type: String, default: 'span' },
    value: { type: String, default: '' }, // optional fallback display
    placeholder: { type: String, default: '' },
    class: { type: String, default: '' },
    allowEmpty: { type: Boolean, default: false },
  },
  setup(props) {
    const { enabled } = useEditMode()
    const { checkAuth } = useAdminAuth()
    const el = ref(null)
    const saving = ref(false)
    const error = ref('')
    const dirty = ref(false)
    const local = ref('')

    const pageText = usePageText(props.pageKey)

    const isEditing = computed(() => enabled.value && checkAuth())
    const wrapperClass = computed(() => [
      'inline-editable',
      props.class,
      isEditing.value ? 'is-editing' : null,
      dirty.value ? 'is-dirty' : null,
    ].filter(Boolean).join(' '))

    const cmsValue = computed(() => {
      if (props.type === 'html') return pageText.getHtml(props.fieldKey, '', { allowEmpty: props.allowEmpty })
      if (props.type === 'link') return pageText.getLink(props.fieldKey, '', { allowEmpty: props.allowEmpty })
      return pageText.getText(props.fieldKey, '', { allowEmpty: props.allowEmpty })
    })

    const displayValue = computed(() => {
      const v = cmsValue.value || props.value || props.placeholder || ''
      return v
    })

    const syncFromDisplay = () => {
      local.value = displayValue.value
      if (el.value) {
        if (props.type === 'html') el.value.innerHTML = local.value
        else el.value.innerText = local.value
      }
      dirty.value = false
      error.value = ''
    }

    let msgHandler = null
    onMounted(async () => {
      try {
        let opts = {}
        try {
          const t = (typeof window !== 'undefined') ? (localStorage.getItem('admin_token') || '') : ''
          if (t && t.length > 0) opts.preferAdmin = true
        } catch (_) {}
        await pageText.load(opts)
      } catch (_) {}
      syncFromDisplay()

      // Listen for parent admin request to save all dirty fields from live preview
      msgHandler = (ev) => {
        const data = ev?.data || {}
        if (data && data.type === 'cms-save-all') {
          if (isEditing.value && dirty.value && !saving.value) {
            save()
          }
        }
      }
      try { window.addEventListener('message', msgHandler) } catch (_) {}
    })

    onBeforeUnmount(() => {
      try { if (msgHandler) window.removeEventListener('message', msgHandler) } catch (_) {}
      msgHandler = null
    })

    watch(() => cmsValue.value, () => {
      if (!dirty.value) syncFromDisplay()
    })

    const onInput = (e) => {
      const tgt = e?.target
      const raw = props.type === 'html' ? (tgt?.innerHTML ?? '') : (tgt?.innerText ?? '')
      local.value = raw.trimStart()
      dirty.value = (local.value !== (cmsValue.value || props.value || ''))
    }
    const onBlur = () => {/* keep toolbar visible until save/cancel */}
    const onKeydown = (e) => {
      if (e.key === 'Enter') {
        e.preventDefault()
        save()
      } else if (e.key === 'Escape') {
        e.preventDefault()
        cancel()
      }
    }

    const save = async () => {
      if (!dirty.value || saving.value) return
      saving.value = true
      error.value = ''
      try {
        const page = pageText.page?.value
        const content = (page?.content && typeof page.content === 'object') ? { ...page.content } : {}
        const bucket = props.type === 'html' ? 'htmls' : (props.type === 'link' ? 'links' : 'texts')
        content[bucket] = { ...(content[bucket] || {}) }

        // Keep previous value for potential rollback
        const prevValue = content[bucket][props.fieldKey]

        // Optimistic update: reflect immediately across the page
        content[bucket][props.fieldKey] = local.value
        try {
          if (!pageText.page.value) pageText.page.value = { content: {} }
          const current = (typeof pageText.page.value.content === 'object') ? pageText.page.value.content : {}
          const next = { ...(current || {}) }
          next[bucket] = { ...(next[bucket] || {}) }
          next[bucket][props.fieldKey] = local.value
          pageText.page.value = { ...(pageText.page.value || {}), content: next }
        } catch (_) { /* noop */ }

        // 一部のページキーがスネークケースで渡るケースがあるため安全に正規化
        const normalizedKey = (props.pageKey || '').replace(/_/g, '-').trim() || props.pageKey

        let res = await apiClient.put(`/api/admin/pages/${normalizedKey}`, { content })
        if (res && res.success === false) {
          // ページが存在しない（404）の場合は作成を試みる
          if (String(res.code || '').startsWith('404')) {
            const nowIso = new Date().toISOString()
            const title = (page?.title || normalizedKey || 'ページ')
            const createPayload = {
              page_key: normalizedKey,
              title,
              content,
              is_published: true,
              published_at: nowIso,
            }
            const created = await apiClient.post('/api/admin/pages', createPayload)
            if (created && created.success === false) {
              throw new Error(created.error || '保存に失敗しました（作成）')
            }
            res = created
          } else {
            throw new Error(res.error || '保存に失敗しました')
          }
        }
        // API応答から更新後のページを反映（形状の差異に対応）
        try {
          const body = res?.data || res
          const updatedPage = body?.page || body?.data?.page || null
          if (updatedPage) {
            pageText.page.value = updatedPage
            // Persist to local cache to eliminate reload flicker
            try { localStorage.setItem('page_content_cache:' + normalizedKey, JSON.stringify(updatedPage)) } catch(_) {}
          } else {
            // 念のため管理APIで再取得（公開APIで上書きされないようpreferAdmin + force）
            await pageText.load({ preferAdmin: true, force: true })
            try {
              const pg = pageText.page?.value
              if (pg) localStorage.setItem('page_content_cache:' + normalizedKey, JSON.stringify(pg))
            } catch(_) {}
          }
        } catch (_) {
          await pageText.load({ preferAdmin: true, force: true })
          try {
            const pg = pageText.page?.value
            if (pg) localStorage.setItem('page_content_cache:' + normalizedKey, JSON.stringify(pg))
          } catch(_) {}
        }
        syncFromDisplay()
      } catch (e) {
        // Rollback optimistic change on error
        try {
          const page = pageText.page?.value
          const current = (typeof page?.content === 'object') ? { ...page.content } : {}
          const bucket = props.type === 'html' ? 'htmls' : (props.type === 'link' ? 'links' : 'texts')
          if (!current[bucket]) current[bucket] = {}
          // Note: prevValue may be undefined; that is fine
          const prevVal = (page && page.content && page.content[bucket]) ? page.content[bucket][props.fieldKey] : undefined
          current[bucket][props.fieldKey] = prevVal
          if (pageText.page.value) {
            pageText.page.value = { ...(pageText.page.value || {}), content: current }
          }
        } catch (_) { /* noop */ }
        error.value = e?.message || '保存に失敗しました'
      } finally {
        saving.value = false
      }
    }

    const cancel = () => {
      syncFromDisplay()
    }

    return { el, isEditing, wrapperClass, displayValue, onInput, onBlur, onKeydown, save, cancel, saving, error, dirty }
  }
}
</script>

<style scoped>
.inline-editable {
  position: relative;
}
.inline-editable.is-editing {
  outline: 1px dashed #409eff;
  outline-offset: 2px;
  cursor: text;
  background: rgba(64, 158, 255, 0.06);
}
.inline-toolbar {
  position: absolute;
  z-index: 5;
  margin-top: 4px;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 6px;
  padding: 4px 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.btn { font-size: 12px; padding: 4px 8px; border-radius: 4px; margin-right: 4px; cursor: pointer; }
.btn-save { background: #409eff; color: #fff; border: 1px solid #409eff; }
.btn-cancel { background: #fff; color: #666; border: 1px solid #ddd; }
.error { color: #d33; margin-left: 6px; font-size: 12px; }
</style>
