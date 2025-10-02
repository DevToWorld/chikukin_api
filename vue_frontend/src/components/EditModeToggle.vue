<template>
  <div v-if="show" class="edit-toggle" :title="enabled ? '編集モード: ON' : '編集モード: OFF'">
    <button class="toggle" @click="toggle">
      <span class="dot" :class="enabled ? 'on' : 'off'" />
      <span class="label">Inline Edit</span>
    </button>
  </div>
  
</template>

<script>
import { computed } from 'vue'
import { useEditMode } from '@/composables/useEditMode'
import { useAdminAuth } from '@/composables/useAdminAuth'
import router from '@/router'

export default {
  name: 'EditModeToggle',
  setup() {
    const { enabled, toggle } = useEditMode()
    const { checkAuth } = useAdminAuth()
    const show = computed(() => {
      // 管理ログイン済み かつ /admin 配下のみ表示（hashモード対応）
      const path = (router?.currentRoute && router.currentRoute.path) || ''
      return !!checkAuth() && path.startsWith('/admin')
    })
    return { enabled, toggle, show }
  }
}
</script>

<style scoped>
.edit-toggle {
  position: fixed;
  right: 16px;
  bottom: 16px;
  z-index: 2000;
}
.toggle {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #fff;
  border: 1px solid #e0e0e0;
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
  padding: 8px 10px;
  border-radius: 999px;
  cursor: pointer;
}
.dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.dot.on { background: #10b981; }
.dot.off { background: #9ca3af; }
.label { font-size: 12px; color: #333; }
</style>
