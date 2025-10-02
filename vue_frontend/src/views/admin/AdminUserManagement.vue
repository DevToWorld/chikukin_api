<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">管理者管理</h1>
        <div class="header-actions">
          <button class="add-btn" @click="openCreate">新規管理者追加</button>
          <button class="refresh-btn" :disabled="loading" @click="loadAdmins">{{ loading ? '読み込み中...' : '更新' }}</button>
        </div>
      </div>

      <div class="filters-section">
        <div class="search-filter">
          <input v-model="search" type="text" placeholder="氏名/ID/メールで検索..." class="search-input" @keyup.enter="applySearch" />
          <button class="search-btn" @click="applySearch">検索</button>
        </div>
      </div>

      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>管理者名</th>
                <th>ID</th>
                <th>メールアドレス</th>
                <th>権限</th>
                <th>ステータス</th>
                <th>最終ログイン</th>
                <th>管理</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="a in admins" :key="a.id">
                <td>{{ a.full_name }}</td>
                <td class="mono">{{ a.username }}</td>
                <td class="mono">{{ a.email }}</td>
                <td>
                  <span class="role-badge" :class="`role-${a.role}`">{{ roleLabel(a.role) }}</span>
                </td>
                <td>
                  <span :class="['status-badge', a.is_active ? 'status-active' : 'status-inactive']">
                    {{ a.is_active ? '有効' : '無効' }}
                  </span>
                </td>
                <td>{{ formatDateTime(a.last_login_at) || '-' }}</td>
                <td class="actions">
                  <button class="edit-btn" @click="openEdit(a)">編集</button>
                  <button class="delete-btn" @click="confirmDelete(a)" :disabled="isSelf(a)">削除</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <AdminPagination
        v-if="pagination && pagination.last_page > 1"
        :page="pagination.current_page"
        :last-page="pagination.last_page"
        @update:page="p => (pagination.current_page = p)"
        @change="loadAdmins"
      />

      <!-- Create/Edit Modal -->
      <div v-if="open" class="modal-overlay" @click="close">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>{{ editing ? '管理者編集' : '新規管理者追加' }}</h3>
            <button class="close-btn" @click="close">×</button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submit">
              <div class="form-row">
                <div class="form-group">
                  <label>管理者名</label>
                  <input v-model="form.full_name" type="text" class="form-input" required />
                </div>
                <div class="form-group">
                  <label>ID（ユーザー名）</label>
                  <input v-model="form.username" type="text" class="form-input" required :readonly="editing" />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>メールアドレス</label>
                  <input v-model="form.email" type="email" class="form-input" required />
                </div>
                <div class="form-group">
                  <label>権限レベル</label>
                  <select v-model="form.role" class="form-select" required>
                    <option value="super_admin">スーパーアドミン</option>
                    <option value="admin">アドミン</option>
                    <option value="editor">編集</option>
                    <option value="viewer">閲覧</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>パスワード <span v-if="editing" class="muted">（変更時のみ）</span></label>
                  <input v-model="form.password" :required="!editing" type="password" class="form-input" placeholder="********" />
                </div>
                <div class="form-group">
                  <label>有効</label>
                  <input v-model="form.is_active" type="checkbox" />
                </div>
              </div>

              <div v-if="serverErrors" class="server-errors">
                <div v-for="(errs, key) in serverErrors" :key="key">{{ errs[0] }}</div>
              </div>

              <div class="modal-footer">
                <button type="button" class="cancel-btn" @click="close">キャンセル</button>
                <button type="submit" class="save-btn" :disabled="saving">{{ saving ? '保存中...' : '保存' }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Delete confirm -->
      <div v-if="confirming && target" class="modal-overlay" @click="confirming=false">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>管理者削除の確認</h3>
            <button class="close-btn" @click="confirming=false">×</button>
          </div>
          <div class="modal-body">
            <p>
              「{{ target.full_name }}（{{ target.email }}）」を削除しますか？<br />
              この操作は取り消せません。
            </p>
            <div class="modal-footer">
              <button class="cancel-btn" @click="confirming=false">キャンセル</button>
              <button class="delete-btn" @click="doDelete" :disabled="deleting">{{ deleting ? '削除中...' : '削除' }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
  
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import api from '@/services/apiClient'
import AdminPagination from '@/components/admin/AdminPagination.vue'

export default {
  name: 'AdminUserManagement',
  components: { AdminLayout, AdminPagination },
  data() {
    return {
      admins: [],
      pagination: { current_page: 1, last_page: 1, per_page: 20 },
      loading: false,
      error: '',
      search: '',
      // modal state
      open: false,
      editing: false,
      form: { id: null, full_name: '', username: '', email: '', role: 'admin', is_active: true, password: '' },
      serverErrors: null,
      saving: false,
      // delete
      confirming: false,
      target: null,
      deleting: false,
    }
  },
  mounted() {
    if (!api.isAdminAuthenticated()) {
      this.$router.push('/admin')
      return
    }
    this.loadAdmins()
  },
  methods: {
    async loadAdmins() {
      this.loading = true
      this.error = ''
      const params = {
        page: this.pagination.current_page,
        per_page: this.pagination.per_page,
      }
      if (this.search) params.search = this.search
      const res = await api.listAdminUsers(params)
      if (!res.success) {
        this.error = res.error || '読み込みに失敗しました'
      } else {
        const p = res.data
        this.admins = Array.isArray(p.data) ? p.data : []
        this.pagination = {
          current_page: p.current_page || 1,
          last_page: p.last_page || 1,
          per_page: p.per_page || 20,
        }
      }
      this.loading = false
    },
    applySearch() {
      this.pagination.current_page = 1
      this.loadAdmins()
    },
    roleLabel(v) {
      return ({ super_admin: 'スーパーアドミン', admin: 'アドミン', editor: '編集', viewer: '閲覧' }[v]) || v
    },
    formatDateTime(v) {
      if (!v) return ''
      try { const d = new Date(v); return `${d.toLocaleDateString('ja-JP')} ${d.toLocaleTimeString('ja-JP')}` } catch { return v }
    },
    isSelf(a) {
      try { const me = JSON.parse(localStorage.getItem('adminUser') || '{}'); return me && me.id === a.id } catch { return false }
    },
    openCreate() {
      this.editing = false
      this.form = { id: null, full_name: '', username: '', email: '', role: 'admin', is_active: true, password: '' }
      this.serverErrors = null
      this.open = true
    },
    openEdit(a) {
      this.editing = true
      this.form = { id: a.id, full_name: a.full_name || '', username: a.username || '', email: a.email || '', role: a.role || 'admin', is_active: !!a.is_active, password: '' }
      this.serverErrors = null
      this.open = true
    },
    close() { this.open = false },
    async submit() {
      this.saving = true
      this.serverErrors = null
      const payload = {
        full_name: this.form.full_name,
        username: this.form.username,
        email: this.form.email,
        role: this.form.role,
        is_active: !!this.form.is_active,
      }
      if (!this.editing || this.form.password) payload.password = this.form.password
      const res = this.editing
        ? await api.updateAdminUser(this.form.id, payload)
        : await api.createAdminUser(payload)
      if (!res.success) {
        // Laravel Validator returns { errors: {...} }
        this.serverErrors = res.errors || res.details || { message: [res.error || '保存に失敗しました'] }
      } else {
        this.open = false
        this.loadAdmins()
      }
      this.saving = false
    },
    confirmDelete(a) { this.target = a; this.confirming = true },
    async doDelete() {
      if (!this.target) return
      this.deleting = true
      const res = await api.deleteAdminUser(this.target.id)
      if (!res.success) {
        alert(res.message || res.error || '削除に失敗しました')
      } else {
        this.confirming = false
        this.loadAdmins()
      }
      this.deleting = false
    },
  }
}
</script>

<style scoped>
.dashboard { background: white; border-radius: 8px; }
.page-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid #e5e5e5; }
.page-title { margin:0; font-size:18px; font-weight:600; }
.header-actions { display:flex; gap:8px; }
.add-btn, .refresh-btn, .search-btn, .edit-btn, .delete-btn, .save-btn, .cancel-btn { cursor:pointer; padding:8px 12px; border-radius:4px; border:1px solid #ddd; background:#fff; }
.add-btn { background:#ff6b9d; color:#fff; border:none; }
.add-btn:hover { background:#ff5a8c }
.refresh-btn { background:#f8f8f8 }

.filters-section { display:flex; gap:12px; padding:16px 24px; border-bottom:1px solid #eee; }
.search-filter { display:flex; gap:8px; align-items:center; width:100%; }
.search-input { flex:1; padding:8px 10px; border:1px solid #ddd; border-radius:4px; }

.table-container { padding: 12px 24px 24px; }
.table-wrapper { overflow:auto; background:white; border-radius:8px; }
.data-table { width:100%; border-collapse:collapse; }
.data-table th, .data-table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
.data-table thead { background:#fafafa }
.mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 13px; }
.actions { display:flex; gap:8px; }

.role-badge { padding:4px 8px; border-radius:999px; font-size:12px; background:#eee; }
.role-super_admin { background:#222; color:#fff }
.role-admin { background:#2563eb; color:#fff }
.role-editor { background:#059669; color:#fff }
.role-viewer { background:#6b7280; color:#fff }

.status-badge { padding:4px 8px; border-radius:999px; font-size:12px; }
.status-active { background:#d1fae5; color:#065f46 }
.status-inactive { background:#fee2e2; color:#991b1b }

/* Modal */
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; z-index:1000; }
.modal-content { width: 720px; max-width: 95vw; background:white; border-radius:8px; box-shadow:0 10px 40px rgba(0,0,0,0.2); overflow:hidden; }
.modal-header { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-bottom:1px solid #eee; }
.modal-body { padding:16px; }
.modal-footer { display:flex; justify-content:flex-end; gap:8px; margin-top:12px; }
.close-btn { border:none; background:transparent; font-size:20px; cursor:pointer }
.form-row { display:flex; gap:16px; margin-bottom:12px; }
.form-group { flex:1; display:flex; flex-direction:column; gap:6px; }
.form-input, .form-select, textarea { padding:8px 10px; border:1px solid #ddd; border-radius:4px; }
.muted { color:#666; font-size:12px; }
.server-errors { color:#c2410c; background:#fff7ed; border:1px solid #fed7aa; padding:8px; border-radius:4px; margin-top:8px; }

@media (max-width: 680px) {
  .form-row { flex-direction: column; }
}
</style>
