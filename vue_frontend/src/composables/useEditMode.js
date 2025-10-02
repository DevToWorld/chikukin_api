import { reactive, toRefs } from 'vue'

// Global inline edit mode state (admin-only UI)
const state = reactive({
  enabled: false,
})

function _hasCmsEditFlag() {
  // Inline 編集は無効化（ソフト停止）
  return false
}

// Restore previous session state
try {
  // 強制無効（セッション・URLフラグを無視）
  state.enabled = false
  sessionStorage.setItem('cms_edit_mode', '0')
} catch (_) {
  state.enabled = false
}

export function useEditMode() {
  const enable = () => {
    state.enabled = true
    try { sessionStorage.setItem('cms_edit_mode', '1') } catch (_) {}
  }
  const disable = () => {
    state.enabled = false
    try { sessionStorage.setItem('cms_edit_mode', '0') } catch (_) {}
  }
  const toggle = () => (state.enabled ? disable() : enable())

  return {
    ...toRefs(state),
    enable,
    disable,
    toggle,
  }
}

export default useEditMode
