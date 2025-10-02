<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">経済指標管理</h1>
        <div class="header-actions">
          <router-link to="/admin/economic-indicator-categories" class="add-btn" style="margin-right:8px;">カテゴリー管理</router-link>
          <button @click="openCreateModal" class="add-btn">新規追加</button>
        </div>
      </div>

      <div class="search-section">
        <div class="search-container">
          <span class="search-label">検索する</span>
          <input v-model="filters.search" type="text" placeholder="指標名・説明・出所を入力" class="search-input" @keyup.enter="loadIndicators">
          <button @click="loadIndicators" class="search-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" fill="currentColor"/>
            </svg>
          </button>
        </div>

        <div class="filter-section">
          <div class="filter-group">
            <label>カテゴリー</label>
            <select v-model="filters.category" @change="loadIndicators" class="filter-select">
              <option value="">全て</option>
              <option v-for="c in categories" :key="c.slug" :value="c.slug">{{ c.name }}</option>
            </select>
          </div>
          <div class="filter-group">
            <label>状態</label>
            <select v-model="filters.is_active" @change="loadIndicators" class="filter-select">
              <option value="">全て</option>
              <option :value="true">有効</option>
              <option :value="false">無効</option>
            </select>
          </div>
        </div>
      </div>

      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>表示順</th>
              <th>指標名</th>
              <th>カテゴリー</th>
              <th>頻度</th>
              <th>出所</th>
              <th>公表日</th>
              <th>状態</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in indicators" :key="item.id">
              <td>{{ item.sort_order }}</td>
              <td>
                <div class="title-content">
                  <span class="title">{{ item.name }}</span>
                  <div class="sub">{{ item.description }}</div>
                </div>
              </td>
              <td>{{ displayCategory(item.category) }}</td>
              <td>{{ displayFrequency(item.frequency) }}</td>
              <td>{{ item.source }}</td>
              <td>{{ formatDate(item.publication_date) }}</td>
              <td>
                <span :class="['status-badge', item.is_active ? 'published' : 'draft']">{{ item.is_active ? '有効' : '無効' }}</span>
              </td>
              <td class="actions">
                <button @click="editItem(item)" class="action-btn edit">編集</button>
                <button @click="deleteItem(item)" class="action-btn delete">削除</button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="pagination.total_pages > 1" class="pagination">
          <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="pagination-btn">前へ</button>
          <span class="pagination-info">{{ pagination.current_page }} / {{ pagination.total_pages }}</span>
          <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.total_pages" class="pagination-btn">次へ</button>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h2>{{ editingItem ? '指標編集' : '指標新規作成' }}</h2>
          <button @click="closeModal" class="modal-close">&times;</button>
        </div>

        <form @submit.prevent="saveItem" class="modal-form">
          <div class="form-row">
            <div class="form-group">
              <label>指標名 <span class="required">*</span></label>
              <input v-model="formData.name" type="text" required class="form-input" placeholder="例）景気動向指数">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>カテゴリー <span class="required">*</span></label>
              <select v-model="formData.category" required class="form-select">
                <option value="">選択してください</option>
                <option v-for="c in categories" :key="c.slug" :value="c.slug">{{ c.name }}</option>
              </select>
            </div>
            <div class="form-group half">
              <label>表示順</label>
              <input v-model.number="formData.sort_order" type="number" min="0" class="form-input">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>頻度</label>
              <select v-model="formData.frequency" class="form-select">
                <option value="monthly">月次</option>
                <option value="quarterly">四半期</option>
                <option value="annual">年次</option>
              </select>
            </div>
            <div class="form-group half">
              <label>単位</label>
              <input v-model="formData.unit" type="text" class="form-input" placeholder="例）% / ポイント / 円 など">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label>出所</label>
              <input v-model="formData.source" type="text" class="form-input" placeholder="例）内閣府">
            </div>
            <div class="form-group half">
              <label>公表日</label>
              <input v-model="formData.publication_date" type="date" class="form-input">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>リンク先URL</label>
              <input v-model="formData.link_url" type="url" class="form-input" placeholder="https://...">
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
  name: 'EconomicIndicatorManagement',
  components: { AdminLayout },
  data() {
    return {
      loading: false,
      error: '',
      indicators: [],
      categories: [],
      pagination: { current_page: 1, total_pages: 1 },
      filters: { search: '', category: '', is_active: '' },
      showModal: false,
      editingItem: null,
      formData: {
        name: '', category: '', description: '', source: '', frequency: 'monthly', unit: '',
        link_url: '', publication_date: '', is_active: true, sort_order: 0
      }
    }
  },
  async mounted() {
    await Promise.all([this.loadCategories(), this.loadIndicators()])
  },
  methods: {
    async loadCategories() {
      try {
        const res = await apiClient.getIndicatorCategories()
        if (res.success) this.categories = res.data
      } catch (e) { console.error(e) }
    },
    async loadIndicators(page = 1) {
      this.loading = true
      try {
        const params = { page, per_page: 20 }
        if (this.filters.search) params.search = this.filters.search
        if (this.filters.category) params.category = this.filters.category
        if (this.filters.is_active !== '') params.is_active = this.filters.is_active
        const res = await apiClient.getAdminIndicators(params)
        if (res.success) {
          this.indicators = res.data.indicators
          this.pagination = res.data.pagination
        } else {
          this.error = res.error || '読み込みに失敗しました'
        }
      } catch (e) {
        this.error = '読み込みに失敗しました'
      } finally {
        this.loading = false
      }
    },
    displayCategory(slug) {
      const c = this.categories.find(c => c.slug === slug)
      return c ? c.name : slug
    },
    displayFrequency(f) {
      return f === 'monthly' ? '月次' : f === 'quarterly' ? '四半期' : '年次'
    },
    formatDate(d) {
      if (!d) return '-'
      try { return new Date(d).toLocaleDateString('ja-JP') } catch(e) { return d }
    },
    changePage(p) {
      if (p >= 1 && p <= this.pagination.total_pages) this.loadIndicators(p)
    },
    openCreateModal() {
      this.editingItem = null
      this.formData = { name: '', category: '', description: '', source: '', frequency: 'monthly', unit: '', link_url: '', publication_date: '', is_active: true, sort_order: 0 }
      this.showModal = true
    },
    editItem(item) {
      this.editingItem = item
      this.formData = { ...item }
      this.showModal = true
    },
    closeModal() { this.showModal = false },
    async saveItem() {
      try {
        const payload = { ...this.formData }
        let res
        if (this.editingItem) res = await apiClient.updateAdminIndicator(this.editingItem.id, payload)
        else res = await apiClient.createAdminIndicator(payload)
        if (!res.success) throw new Error(res.error || '保存に失敗しました')
        this.showModal = false
        await this.loadIndicators(this.pagination.current_page)
      } catch (e) {
        alert(e.message || '保存に失敗しました')
      }
    },
    async deleteItem(item) {
      if (!confirm('削除しますか？')) return
      try {
        const res = await apiClient.deleteAdminIndicator(item.id)
        if (!res.success) throw new Error(res.error || '削除に失敗しました')
        await this.loadIndicators(this.pagination.current_page)
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
.header-actions { display:flex; align-items:center; }
.search-section { padding: 16px 24px; border-bottom: 1px solid #e5e5e5; }
.search-container { display:flex; align-items:center; gap:12px; max-width:500px; margin-left:auto; }
.search-label { font-size:14px; color:#1A1A1A; white-space:nowrap; }
.search-input { flex:1; border:1px solid #d0d0d0; border-radius:4px; padding:8px 12px; font-size:14px; }
.search-btn { background:#1A1A1A; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; }
.filter-section { display:flex; align-items:center; gap:16px; margin-top:12px; }
.filter-group { display:flex; flex-direction:column; gap:6px; }
.filter-select { background:white; border:1px solid #d0d0d0; border-radius:4px; padding:8px 12px; font-size:14px; min-width:180px; }
.table-container { padding: 0 24px 24px; }
.data-table { width: 100%; border-collapse: collapse; background:white; }
.data-table th, .data-table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
.title-content .title { font-weight:600; }
.title-content .sub { font-size:12px; color:#666; margin-top:4px; }
.status-badge { padding:2px 8px; border-radius:12px; font-size:12px; }
.status-badge.published { background:#e6f4ea; color:#1e7e34; }
.status-badge.draft { background:#f8d7da; color:#842029; }
.actions { display:flex; gap:8px; }
.action-btn { border:none; border-radius:4px; padding:6px 10px; cursor:pointer; font-size:13px; }
.action-btn.edit { background:#f0f0f0; }
.action-btn.delete { background:#ffe3e6; color:#b30019; }
.pagination { display:flex; justify-content:center; align-items:center; gap:12px; padding-top:16px; }
.pagination-btn { padding:6px 12px; border-radius:4px; border:1px solid #ddd; background:white; cursor:pointer; }
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; }
.modal-content { width:720px; background:white; border-radius:8px; overflow:hidden; }
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

