<template>
  <div>
    <router-view />
    <EditModeToggle />
  </div>
</template>

<script>
import EditModeToggle from './components/EditModeToggle.vue'
import { refreshLoadedPages } from '@/composables/usePageText'
export default {
  name: "App",
  components: { EditModeToggle },
  data() {
    return {
      _refreshLock: false,
      _lastRefreshAt: 0,
    }
  },
  mounted() {
    // Global listener: when media registry updates, force a rerender of the whole app tree
    try { window.addEventListener('cms-media-updated', this._onMediaUpdated) } catch (_) {}
  },
  beforeDestroy() {
    try { window.removeEventListener('cms-media-updated', this._onMediaUpdated) } catch (_) {}
  },
  methods: {
    _hasAdminToken() {
      try { return !!localStorage.getItem('admin_token') } catch (_) { return false }
    },
    _hasPreviewFlag() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsPreview') || params.has('cmsEdit') || params.get('cmsPreview') === 'edit'
      } catch (_) { return false }
    },
    async _onMediaUpdated() {
      try { this.$forceUpdate() } catch (_) {}
      // Avoid hammering API for public users. Only refresh PageContent
      // when admin/preview is active, and throttle bursts.
      const isAdmin = this._hasAdminToken()
      const isPreview = this._hasPreviewFlag()
      if (!isAdmin && !isPreview) return
      const now = Date.now()
      if (this._refreshLock || (now - this._lastRefreshAt) < 3000) return
      this._refreshLock = true
      try {
        await refreshLoadedPages({ preferAdmin: isAdmin })
      } catch (_) { /* noop */ }
      finally {
        this._lastRefreshAt = Date.now()
        this._refreshLock = false
      }
    }
  }
};
</script>
