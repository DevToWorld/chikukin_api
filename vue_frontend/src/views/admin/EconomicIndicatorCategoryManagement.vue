<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">経済指標カテゴリ管理</h1>
        <button @click="openCreateModal" class="add-btn">新規追加</button>
      </div>

      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>表示順</th>
              <th>カテゴリ名</th>
              <th>スラッグ</th>
              <th>状態</th>
              <th>指標数</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in categories" :key="c.id">
              <td>{{ c.sort_order }}</td>
              <td>{{ c.name }}</td>
              <td>{{ c.slug }}</td>
              <td>
                <span :class="['status-badge', c.is_active ? 'published' : 'draft']">{{ c.is_active ? '有効' : '無効' }}</span>
              </td>
              <td>{{ c.indicator_count ?? '-' }}</td>
              <td class="actions">
                <button @click="editItem(c)" class="action-btn edit">編集</button>
                <button @click="deleteItem(c)" class="action-btn delete">削除</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h2>{{ editingItem ? 'カテゴリ編集' : 'カテゴリ新規作成' }}</h2>
          <button @click="closeModal" class="modal-close">&times;</button>
        </div>

        <form @submit.prevent="saveItem" class="modal-form">
          <div class="form-row">
            <div class="form-group">
              <label>カテゴリ名 <span class="required">*</span></label>
              <input v-model="formData.name" type="text" required class="form-input" placeholder="例）景気">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group half">
              <label>スラッグ</label>
              <input v-model="formData.slug" type="text" class="form-input" placeholder="自動生成可（空欄でOK）">
            </div>
            <div class="form-group half">
              <label>表示順</label>
              <input v-model.number="formData.sort_order" type="number" min="0" class="form-input">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group half">
              <label>カラーコード</label>
              <input v-model="formData.color_code" type="text" class="form-input" placeholder="#DA5761 など">
            </div>
            <div class="form-group half">
              <label>アイコン</label>
              <input v-model="formData.icon" type="text" class="form-input" placeholder="アイコン名 or URL">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>説明</label>
              <textarea v-model="formData.description" class="form-textarea" rows="3"></textarea>
            </div>
          </div>
          <div class="form-row">
            <label class="checkbox-label">
              <input type="checkbox" v-model="formData.is_active"> 有効にする
            </label>
          </div>
          <div class="modal-actions">
            <button type="button" class="cancel-btn" @click="closeModal">キャンセル</button>
            <button type="submit" class="save-btn">保存</button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '@/services/apiClient.js'

export default {
  name: 'EconomicIndicatorCategoryManagement',
  components: { AdminLayout },
  data() {
    return {
      loading: false,
      error: '',
      categories: [],
      showModal: false,
      editingItem: null,
      formData: {
        name: '', slug: '', description: '', color_code: '#DA5761', icon: '', is_active: true, sort_order: 0
      }
    }
  },
  async mounted() { await this.loadCategories() },
  methods: {
    async loadCategories() {
      this.loading = true
      try {
        const res = await apiClient.getAdminIndicatorCategories()
        if (res.success) this.categories = res.data
        else this.error = res.error || '読み込みに失敗しました'
      } catch (e) { this.error = '読み込みに失敗しました' } finally { this.loading = false }
    },
    openCreateModal() {
      this.editingItem = null
      this.formData = { name: '', slug: '', description: '', color_code: '#DA5761', icon: '', is_active: true, sort_order: 0 }
      this.showModal = true
    },
    editItem(item) { this.editingItem = item; this.formData = { ...item }; this.showModal = true },
    closeModal() { this.showModal = false },
    async saveItem() {
      try {
        const payload = { ...this.formData }
        let res
        if (this.editingItem) res = await apiClient.updateAdminIndicatorCategory(this.editingItem.id, payload)
        else res = await apiClient.createAdminIndicatorCategory(payload)
        if (!res.success) throw new Error(res.error || '保存に失敗しました')
        this.showModal = false
        await this.loadCategories()
      } catch (e) { alert(e.message || '保存に失敗しました') }
    },
    async deleteItem(item) {
      if (!confirm('削除しますか？')) return
      try {
        const res = await apiClient.deleteAdminIndicatorCategory(item.id)
        if (!res.success) throw new Error(res.error || '削除に失敗しました')
        await this.loadCategories()
      } catch (e) { alert(e.message || '削除に失敗しました') }
    }
  }
}
</script>

<style scoped>
.dashboard { background-color: white; border-radius: 8px; overflow: hidden; }
.page-header { display:flex; justify-content: space-between; align-items:center; padding:20px 24px; border-bottom:1px solid #e5e5e5; }
.page-title { font-size:24px; font-weight:600; color:#1A1A1A; margin:0; }
.add-btn { background-color:#da5761; color:white; border:none; padding:8px 16px; border-radius:4px; font-size:14px; cursor:pointer; }
.add-btn:hover { background-color:#c44853; }
.table-container { padding: 0 24px 24px; }
.data-table { width: 100%; border-collapse: collapse; background:white; }
.data-table th, .data-table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
.status-badge { padding:2px 8px; border-radius:12px; font-size:12px; }
.status-badge.published { background:#e6f4ea; color:#1e7e34; }
.status-badge.draft { background:#f8d7da; color:#842029; }
.actions { display:flex; gap:8px; }
.action-btn { border:none; border-radius:4px; padding:6px 10px; cursor:pointer; font-size:13px; }
.action-btn.edit { background:#f0f0f0; }
.action-btn.delete { background:#ffe3e6; color:#b30019; }
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; }
.modal-content { width:660px; background:white; border-radius:8px; overflow:hidden; }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #eee; }
.modal-close { border:none; background:transparent; font-size:24px; cursor:pointer; }
.modal-form { padding:20px; }
.form-row { display:flex; gap:16px; margin-bottom:12px; align-items:flex-start; }
.form-group { flex:1; display:flex; flex-direction:column; gap:6px; }
.form-group.half { flex: 1; }
.form-input, .form-select, .form-textarea { border:1px solid #d0d0d0; border-radius:4px; padding:8px 10px; font-size:14px; }
.form-textarea { resize:vertical; }
.checkbox-label { display:flex; align-items:center; gap:8px; }
.modal-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:8px; }
.cancel-btn { background:#f0f0f0; border:none; border-radius:4px; padding:8px 12px; cursor:pointer; }
.save-btn { background:#da5761; color:white; border:none; border-radius:4px; padding:8px 12px; cursor:pointer; }
</style>

