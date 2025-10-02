<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">メールグループ管理</h1>
        <div class="header-actions">
          <button class="add-btn" @click="toggleInlineCreate">グループ追加</button>
        </div>
      </div>

      <!-- Inline create panel (wireframe style) -->
      <div v-if="showInlineCreate" class="inline-create">
        <div class="group-name">
          <div class="label">グループ名</div>
          <input v-model="inlineForm.name" class="form-input" placeholder="例）スタンダード会員" />
        </div>
        <div class="emails">
          <div class="label">メールアドレス</div>
          <!-- chips -->
          <div class="chips">
            <span v-for="(m,i) in inlineForm.selectedMembers" :key="m.id" class="chip">{{ m.company_name || m.email || ('ID:' + m.id) }}
              <button class="chip-x" @click="removeSelectedMember(i)">×</button>
            </span>
            <input v-model="memberSuggestQuery" class="chip-input" placeholder="会社名/メールで検索して追加" @keyup.enter="searchMembers" />
            <button class="small-btn" @click="searchMembers" :disabled="suggestLoading">{{ suggestLoading ? '検索中...' : '検索' }}</button>
          </div>
          <!-- suggestions -->
          <div v-if="memberSuggestions.length" class="suggest-box">
            <div v-for="s in memberSuggestions" :key="s.id" class="suggest-item" @click="addMemberSuggestion(s)">
              <div class="suggest-title">{{ s.company_name || s.email }}</div>
              <div class="suggest-sub">{{ s.representative_name }} / {{ s.email }}</div>
            </div>
          </div>
          <div class="hint">検索して候補をクリックすると宛先に追加されます。</div>
        </div>
        <div class="inline-actions">
          <button class="primary" :disabled="inlineSaving" @click="createGroupInline">{{ inlineSaving ? '作成中...' : 'グループ作成' }}</button>
          <button class="ghost" @click="toggleInlineCreate">キャンセル</button>
        </div>
      </div>

      <div class="filters-section">
        <div class="search-filter">
          <label class="search-label">検索する</label>
          <input v-model="search" class="search-input" placeholder="グループ名で検索" @keyup.enter="loadGroups" />
        </div>
        <button class="refresh-btn" @click="loadGroups" :disabled="loading">{{ loading ? '更新中...' : '更新' }}</button>
      </div>

      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th class="narrow"></th>
                <th>グループ名</th>
                <th>送信先メールアドレス</th>
                <th>編集する</th>
                <th>削除</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="g in groups" :key="g.id">
                <td class="narrow"><input type="checkbox" :value="g.id" v-model="selectedGroupIds" :disabled="isVirtual(g)" /></td>
                <td>{{ g.name }}</td>
                <td class="desc">
                  <template v-if="g.id < 0">{{ g.description }}（{{ g.members_count || 0 }}件）</template>
                  <template v-else>メンバー数: {{ g.members_count || 0 }}</template>
                </td>
                <td><button class="link-btn" @click="openMembersModal(g)">編集する</button></td>
                <td><button class="small-btn danger" @click="deleteGroup(g)" :disabled="isVirtual(g)">削除</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="pagination" v-if="pagination.total > pagination.per_page">
        <button v-for="p in paginationPages" :key="p" @click="loadGroups(p)" :class="['page-btn', { active: p === pagination.current_page }]">{{ p }}</button>
      </div>

      <div class="bulk-actions">
        <button class="danger" :disabled="selectedGroupIds.length===0" @click="bulkDelete">削除する</button>
      </div>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreate" class="modal-overlay" @click="showCreate=false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>新規グループ</h3>
          <button class="close-btn" @click="showCreate=false">×</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>グループ名</label>
            <input v-model="createForm.name" class="form-input" />
          </div>
          <div class="form-group">
            <label>説明</label>
            <textarea v-model="createForm.description" class="form-textarea" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="cancel-btn" @click="showCreate=false">キャンセル</button>
          <button class="save-btn" :disabled="saving" @click="createGroup">{{ saving ? '作成中...' : '作成' }}</button>
        </div>
      </div>
    </div>

    <!-- Members Modal -->
    <div v-if="showMembers" class="modal-overlay" @click="closeMembersModal">
      <div class="modal-content large" @click.stop>
        <div class="modal-header">
          <h3>メンバー編集: {{ currentGroup?.name }}</h3>
          <button class="close-btn" @click="closeMembersModal">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <input v-model="memberSearch" class="form-input" placeholder="会社名/代表者/メールで検索" @keyup.enter="loadMemberCandidates()" />
            <select v-model="memberTypeFilter" class="form-select" @change="loadMemberCandidates()">
              <option value="">会員種別（全て）</option>
              <option value="free">無料</option>
              <option value="standard">スタンダード</option>
              <option value="premium">プレミアム</option>
            </select>
            <button class="save-btn" @click="loadMemberCandidates">検索</button>
          </div>
          <div class="form-row" style="align-items:center; gap:8px;">
            <label style="min-width:140px;">CSVで追加</label>
            <input ref="csvInput" type="file" accept=".csv,text/csv" @change="onCsvSelected" />
            <button class="small-btn" :disabled="!csvFile || uploadingCsv" @click="uploadCsv">{{ uploadingCsv ? 'アップロード中...' : '取り込み' }}</button>
          </div>
          <div class="candidate-list">
            <table class="data-table">
              <thead>
                <tr>
                  <th><input type="checkbox" :checked="allChecked" @change="toggleAllCandidates($event)" /></th>
                  <th>ID</th>
                  <th>会社名</th>
                  <th>代表者名</th>
                  <th>メール</th>
                  <th>種別</th>
                  <th>状態</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="m in candidates" :key="m.id">
                  <td><input type="checkbox" :value="m.id" v-model="selectedCandidateIds" /></td>
                  <td>{{ m.id }}</td>
                  <td>{{ m.company_name }}</td>
                  <td>{{ m.representative_name }}</td>
                  <td>{{ m.email }}</td>
                  <td>{{ m.membership_type }}</td>
                  <td>{{ groupMemberIds.has(m.id) ? '登録済' : '' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="form-row">
            <button class="save-btn" :disabled="memberSaving || selectedCandidateIds.length===0" @click="addSelectedToGroup">選択を追加</button>
            <button class="cancel-btn" :disabled="memberSaving || removeIds.length===0" @click="removeSelectedFromGroup">選択を削除</button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '@/services/apiClient.js'

export default {
  name: 'MailGroupManagement',
  components: { AdminLayout },
  data() {
    return {
      loading: false,
      saving: false,
      memberSaving: false,
      error: '',
      search: '',
      groups: [],
      pagination: { current_page: 1, last_page: 1, per_page: 20, total: 0 },
      // inline create panel
      showInlineCreate: false,
      inlineForm: { name: '', selectedMembers: [] },
      memberSuggestQuery: '',
      memberSuggestions: [],
      suggestLoading: false,
      inlineSaving: false,
      // legacy modal (kept)
      showCreate: false,
      createForm: { name: '', description: '' },
      showMembers: false,
      currentGroup: null,
      memberIdsText: '',
      memberSearch: '',
      memberTypeFilter: '',
      candidates: [],
      selectedCandidateIds: [],
      groupMemberIds: new Set(),
      csvFile: null,
      uploadingCsv: false,
      // bulk select
      selectedGroupIds: [],
    }
  },
  computed: {
    paginationPages() {
      const pages = []
      const maxShow = 5
      const start = Math.max(1, this.pagination.current_page - Math.floor(maxShow / 2))
      const end = Math.min(this.pagination.last_page, start + maxShow - 1)
      for (let i = start; i <= end; i++) pages.push(i)
      return pages
    },
    allChecked() {
      const candidates = this.candidates || []
      const selected = this.selectedCandidateIds || []
      return candidates.length > 0 && selected.length === candidates.length
    },
    addIds() {
      const selected = this.selectedCandidateIds || []
      const memberSet = this.groupMemberIds || new Set()
      return selected.filter(id => !memberSet.has(id))
    },
    removeIds() {
      const selected = this.selectedCandidateIds || []
      const memberSet = this.groupMemberIds || new Set()
      return selected.filter(id => memberSet.has(id))
    },
  },
  mounted() {
    this.loadGroups()
  },
  methods: {
    isVirtual(g){ return (g && g.id && Number(g.id) < 0) },
    toggleInlineCreate(){ this.showInlineCreate = !this.showInlineCreate },
    async searchMembers(){
      const q = (this.memberSuggestQuery || '').trim()
      if (!q) { this.memberSuggestions = []; return }
      this.suggestLoading = true
      try {
        const res = await apiClient.getAdminMembers({ search: q, per_page: 10 })
        if (res.success) this.memberSuggestions = (res.data && res.data.data) || []
      } catch(e){ console.warn(e) } finally { this.suggestLoading = false }
    },
    addMemberSuggestion(m){
      if (!this.inlineForm.selectedMembers.find(x=>x.id===m.id)) this.inlineForm.selectedMembers.push(m)
      this.memberSuggestions = []
      this.memberSuggestQuery = ''
    },
    removeSelectedMember(i){ this.inlineForm.selectedMembers.splice(i,1) },
    async createGroupInline(){
      if (!this.inlineForm.name.trim()) { alert('グループ名を入力してください'); return }
      this.inlineSaving = true
      try {
        const res = await apiClient.createMailGroup({ name: this.inlineForm.name })
        if (res.success) {
          const group = res.data
          const memberIds = this.inlineForm.selectedMembers.map(m=>m.id)
          if (memberIds.length>0) {
            try { await apiClient.bulkEditMailGroupMembers(group.id, 'add', memberIds) } catch(e){ console.warn('failed to add members', e) }
          }
          this.inlineForm = { name: '', selectedMembers: [] }
          this.showInlineCreate = false
          await this.loadGroups(this.pagination.current_page)
        } else {
          alert(res.error || '作成に失敗しました')
        }
      } catch(e){ alert('作成に失敗しました') } finally { this.inlineSaving = false }
    },
    formatDate(s) { return s ? new Date(s).toLocaleDateString('ja-JP') : '-' },
    async loadGroups(page = 1) {
      this.loading = true
      this.error = ''
      try {
        const res = await apiClient.getMailGroups({ page, per_page: this.pagination.per_page, search: this.search })
        if (res.success) {
          const p = res.data
          this.groups = p.data || []
          this.pagination = { current_page: p.current_page, last_page: p.last_page, per_page: p.per_page, total: p.total }
        } else {
          throw new Error(res.error || res.message)
        }
      } catch (e) {
        this.error = '読み込みに失敗しました'
        console.error(e)
      } finally { this.loading = false }
    },
    onCsvSelected(e) {
      const f = e.target.files && e.target.files[0]
      this.csvFile = f || null
    },
    async uploadCsv() {
      if (!this.currentGroup || !this.csvFile) return
      this.uploadingCsv = true
      try {
        const res = await apiClient.uploadMailGroupCsv(this.currentGroup.id, this.csvFile)
        if (res.success) {
          alert(`取り込み完了: ${res.inserted || 0}件追加（候補: ${res.total_ids || 0}件）`)
          await this.loadGroupMembers()
          this.$refs.csvInput.value = ''
          this.csvFile = null
        } else {
          alert(res.error || res.message || '取り込みに失敗しました')
        }
      } catch(e) {
        alert('取り込みに失敗しました')
      } finally { this.uploadingCsv = false }
    },
    openCreateModal() { this.showCreate = true },
    async createGroup() {
      this.saving = true
      try {
        const res = await apiClient.createMailGroup(this.createForm)
        if (res.success) {
          this.showCreate = false
          this.createForm = { name: '', description: '' }
          this.loadGroups(this.pagination.current_page)
        } else { alert(res.error || '作成に失敗しました') }
      } catch (e) { alert('作成に失敗しました') } finally { this.saving = false }
    },
    async deleteGroup(group) {
      if (!confirm(`グループ「${group.name}」を削除しますか？`)) return
      try {
        const res = await apiClient.deleteMailGroup(group.id)
        if (res.success) this.loadGroups(this.pagination.current_page)
        else alert(res.error || '削除に失敗しました')
      } catch (e) { alert('削除に失敗しました') }
    },
    async bulkDelete(){
      const ids = (this.selectedGroupIds || []).filter(id => Number(id) > 0)
      if (ids.length===0) { alert('削除対象がありません'); return }
      if (!confirm(`${ids.length}件のグループを削除しますか？`)) return
      for (const id of ids) {
        try { await apiClient.deleteMailGroup(id) } catch(e) { console.warn('failed delete', id, e) }
      }
      this.selectedGroupIds = []
      this.loadGroups(this.pagination.current_page)
    },
    openMembersModal(group) { this.currentGroup = group; this.memberIdsText = ''; this.showMembers = true; this.selectedCandidateIds=[]; this.loadGroupMembers(); this.loadMemberCandidates(); },
    closeMembersModal() { this.showMembers = false; this.currentGroup = null },
    async loadGroupMembers() {
      try {
        if (!this.currentGroup) return
        const res = await apiClient.get(`/api/admin/mail-groups/${this.currentGroup.id}`)
        if (res.success) {
          const ids = (res.data.members || []).map(x => x.member_id)
          this.groupMemberIds = new Set(ids)
        }
      } catch(e) { console.warn('Failed to load group members', e) }
    },
    async loadMemberCandidates(page=1) {
      try {
        const params = { page, per_page: 20 }
        if (this.memberSearch) params.search = this.memberSearch
        if (this.memberTypeFilter) params.membership_type = this.memberTypeFilter
        // NOTE: endpoint fix - use official admin members list API
        const res = await apiClient.getAdminMembers(params)
        if (res.success) {
          const p = res.data || {}
          this.candidates = p.data || []
        }
      } catch(e) { console.warn('Failed to load candidates', e) }
    },
    toggleAllCandidates(ev) {
      if (ev.target.checked) this.selectedCandidateIds = this.candidates.map(c=>c.id)
      else this.selectedCandidateIds = []
    },
    async addSelectedToGroup() {
      if (!this.currentGroup) return
      const ids = this.addIds
      if (ids.length===0) { alert('追加対象がありません'); return }
      this.memberSaving = true
      try {
        const res = await apiClient.bulkEditMailGroupMembers(this.currentGroup.id, 'add', ids)
        if (res.success) { await this.loadGroupMembers(); alert('追加しました') }
      } catch(e) { alert('追加に失敗しました') } finally { this.memberSaving = false }
    },
    async removeSelectedFromGroup() {
      if (!this.currentGroup) return
      const ids = this.removeIds
      if (ids.length===0) { alert('削除対象がありません'); return }
      this.memberSaving = true
      try {
        const res = await apiClient.bulkEditMailGroupMembers(this.currentGroup.id, 'remove', ids)
        if (res.success) { await this.loadGroupMembers(); alert('削除しました') }
      } catch(e) { alert('削除に失敗しました') } finally { this.memberSaving = false }
    }
  }
}
</script>

<style scoped>
.dashboard { background: white; border-radius: 8px; overflow: hidden; }
.page-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e5e5e5; }
.page-title { font-size: 24px; font-weight: 600; }
.header-actions { display: flex; gap: 12px; }
.add-btn, .refresh-btn { padding: 8px 16px; border: none; border-radius: 4px; background: #f1f1f1; color: #1a1a1a; cursor: pointer; }
.filters-section { padding: 16px 24px; border-bottom: 1px solid #e5e5e5; display: flex; gap: 12px; align-items: center; }
.search-label { margin-right: 8px; color: #555; }
.search-input { padding: 8px 12px; border: 1px solid #d0d0d0; border-radius: 4px; }
.inline-create { padding: 16px 24px; border-bottom: 1px solid #eee; display: grid; gap: 12px; }
.inline-create .label { background: #f3f3f3; border: 1px solid #ddd; padding: 8px 12px; color: #333; width: fit-content; margin-bottom: 8px; }
.inline-create .group-name, .inline-create .emails { display: block; }
.chips { display:flex; flex-wrap:wrap; gap:6px; padding: 8px; border:1px solid #e0e0e0; border-radius:6px; }
.chip { background:#fff; border:1px solid #ddd; border-radius:16px; padding:4px 10px; font-size:12px; }
.chip-x { border:none; background:transparent; margin-left:6px; cursor:pointer; }
.chip-input { border:none; outline:none; padding:4px 8px; min-width:180px; }
.suggest-box { border:1px solid #eee; border-radius:6px; margin-top:8px; max-height:200px; overflow:auto; }
.suggest-item { padding:8px 10px; cursor:pointer; }
.suggest-item:hover { background:#fafafa; }
.suggest-title { font-weight:600; }
.suggest-sub { font-size:12px; color:#666; }
.inline-actions { display:flex; gap:8px; justify-content:flex-end; }
.inline-actions .primary { padding: 8px 16px; border: 1px solid #1a1a1a; border-radius: 4px; background: #1a1a1a; color: white; }
.inline-actions .ghost { padding: 8px 16px; border: 1px solid #d0d0d0; border-radius: 4px; background: white; color: #333; }
.table-container { overflow-x: auto; }
.loading, .error { padding: 40px; text-align: center; }
.error { color: #da5761; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { border-bottom: 1px solid #e5e5e5; padding: 12px 16px; text-align: left; }
.data-table th.narrow, .data-table td.narrow { width: 32px; }
.desc { max-width: 540px; color: #555; }
.small-btn { padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; background: white; cursor: pointer; margin-right: 6px; }
.small-btn.danger { border-color: #dc3545; color: #dc3545; }
.link-btn { background:none; border:none; color:#007bff; cursor:pointer; text-decoration:underline; }
.pagination { padding: 16px 24px; }
.page-btn { margin-right: 6px; padding: 6px 10px; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display: flex; justify-content: center; align-items: center; }
.modal-content { background: white; border-radius: 8px; width: 560px; max-width: 95%; overflow: hidden; }
.modal-content.large { width: 720px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #eee; }
.modal-body { padding: 16px 20px; }
.candidate-list { max-height: 420px; overflow: auto; margin: 12px 0; }
.modal-footer { padding: 12px 20px; display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #eee; }
.close-btn { background: none; border: none; font-size: 20px; cursor: pointer; color: #666; }
.form-group { margin-bottom: 12px; }
.form-input, .form-textarea { width: 100%; padding: 8px 12px; border: 1px solid #d0d0d0; border-radius: 4px; }
.cancel-btn { padding: 8px 16px; border: 1px solid #6c757d; border-radius: 4px; background: white; color: #6c757d; }
.save-btn { padding: 8px 16px; border: 1px solid #28a745; border-radius: 4px; background: #28a745; color: white; }
.bulk-actions { display:flex; justify-content:flex-end; padding: 16px 24px; }
.bulk-actions .danger { padding: 8px 16px; border: 1px solid #dc3545; border-radius: 4px; background: white; color: #dc3545; }
</style>
