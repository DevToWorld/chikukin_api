<template>
  <div class="cms-block-wrapper">
    <!-- 編集モードでは、本文が空でもエディタを表示して新規入力できるようにする -->
    <div 
      v-if="isEditMode"
      :class="['cms-block-editable', wrapperClass, dirty ? 'is-dirty' : null]"
      ref="editable"
      contenteditable
      v-html="html"
      @input="onEditInput"
    ></div>
    <!-- 通常表示（公開 or プレビュー閲覧） -->
    <template v-else>
      <div v-if="html" :class="wrapperClass" v-html="html"></div>
      <div v-else><slot></slot></div>
    </template>

    <!-- インライン保存ツールバー（編集時のみ表示） -->
    <div 
      v-if="isEditMode && dirty"
      class="cms-block-toolbar"
    >
      <button class="btn btn-save" @click.prevent="save" :disabled="saving">{{ saving ? '保存中...' : '保存' }}</button>
      <button class="btn btn-cancel" @click.prevent="cancel" :disabled="saving">キャンセル</button>
      <span v-if="error" class="error">{{ error }}</span>
      <span v-if="message" class="ok">{{ message }}</span>
    </div>
  </div>
</template>

<script>
import apiClient from '@/services/apiClient'

export default {
  name: 'CmsBlock',
  props: {
    pageKey: { type: String, required: true },
    wrapperClass: { type: String, default: '' }
  },
  data() {
    return { html: '', _previewHandler: null, dirty: false, saving: false, error: '', message: '' }
  },
  async mounted() {
    const pickHtml = (payload) => {
      const body = payload?.data || payload
      const page = body?.page || body?.data?.page || null
      const content = page?.content || body?.content || null
      if (!content) return ''
      // 優先: content.html → content.htmls.body → content.htmls（文字列互換）
      if (typeof content.html === 'string' && content.html.trim()) return content.html
      if (content.htmls && typeof content.htmls.body === 'string' && content.htmls.body.trim()) return content.htmls.body
      if (typeof content.htmls === 'string' && content.htmls.trim()) return content.htmls
      return ''
    }

    const hasPreview = this._hasCmsPreviewFlag()
    // インライン編集廃止に伴い、プレビュー経由の上書きや管理APIの強制取得は無効化
    // 常に公開APIからの読み取りのみを行う
    if (false && hasPreview) { /* disabled preview/edit path */ }
    try {
      const res = await apiClient.getPageContent(this.pageKey)
      const html = pickHtml(res)
      if (typeof html === 'string') this.html = html
    } catch (e) { /* noop */ }
  },
  beforeDestroy() {
    if (this._previewHandler) {
      window.removeEventListener('message', this._previewHandler)
      this._previewHandler = null
    }
  },
  methods: {
    _lsKey() { return `cms_preview_${this.pageKey}` },
    _hasCmsPreviewFlag() {
      // インライン編集・プレビュー連携は停止
      return false
    },
    _hasCmsEditFlag() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : window.location.search.slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsEdit') || params.get('cmsPreview') === 'edit'
      } catch (_) { return false }
    },
    _loadPreviewFromStorage() {
      try {
        const v = localStorage.getItem(this._lsKey())
        if (typeof v === 'string') this.html = v
      } catch(_) {}
    },
    onEditInput() {
      try {
        const current = this.$refs.editable ? this.$refs.editable.innerHTML : ''
        // 親（管理画面）へ送信
        if (window.parent) {
          window.parent.postMessage({ type: 'cms-edit', pageKey: this.pageKey, html: current }, '*')
        }
        // ローカルにも保存
        try { localStorage.setItem(this._lsKey(), current) } catch(_) {}
        this.dirty = true
        this.error = ''
        this.message = ''
      } catch(_) {}
    },
    async save() {
      if (this.saving || !this.isEditMode) return
      this.saving = true
      this.error = ''
      this.message = ''
      try {
        const current = this.$refs.editable ? this.$refs.editable.innerHTML : (this.html || '')
        // 管理APIへ保存（html と htmls.body 両方に格納して互換性維持）
        const payload = { content: { html: current, htmls: { body: current } } }
        const res = await apiClient.put(`/api/admin/pages/${this.pageKey}`, payload)
        if (res && res.success === false) throw new Error(res.error || '保存に失敗しました')
        this.html = current
        try { localStorage.setItem(this._lsKey(), current) } catch(_) {}
        this.dirty = false
        this.message = '保存しました'
        // 軽く消えるトースト
        setTimeout(() => { this.message = '' }, 2000)
      } catch (e) {
        this.error = e?.message || '保存に失敗しました'
      } finally {
        this.saving = false
      }
    },
    cancel() {
      try {
        const v = localStorage.getItem(this._lsKey())
        if (typeof v === 'string') {
          this.html = v
        }
        // 直近状態に戻したのち dirty を解除
        this.dirty = false
        this.error = ''
        this.message = ''
      } catch(_) { this.dirty = false }
    }
  },
  computed: {
    isEditMode() {
      // 編集UIは常に無効化
      return false
    }
  }
}
</script>

<style scoped>
/* No default styles */
.cms-block-wrapper { position: relative; }
.cms-block-editable { outline: 1px dashed rgba(64,158,255,0.8); outline-offset: 2px; background: rgba(64,158,255,0.05); min-height: 120px; }
.cms-block-editable.is-dirty { box-shadow: inset 0 0 0 2px rgba(255,165,0,0.35); }
.cms-block-toolbar {
  position: sticky;
  bottom: 10px;
  display: inline-flex;
  gap: 6px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 6px 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  z-index: 50;
  margin-top: 6px;
}
.btn { font-size: 12px; padding: 4px 8px; border-radius: 4px; cursor: pointer; }
.btn-save { background: #409eff; color: #fff; border: 1px solid #409eff; }
.btn-cancel { background: #fff; color: #666; border: 1px solid #ddd; }
.error { color: #d33; margin-left: 6px; font-size: 12px; }
.ok { color: #059669; margin-left: 6px; font-size: 12px; }
</style>
