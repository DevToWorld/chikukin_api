<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">メール配信</h1>
      </div>

      <!-- 作成フォーム（改善版） -->
      <div class="composer">
        <div class="form-row">
          <div class="form-group">
            <label>メールタイトル（件名）</label>
            <input v-model="form.subject" class="form-input" placeholder="例）Hot Information Vol.XXX掲載しました！" />
          </div>
          <div class="form-group" style="align-self:flex-end;">
            <button class="small-btn" @click="openTemplates">テンプレートから作成</button>
            <button class="small-btn" style="margin-left:8px" @click="goManageGroups">グループを管理</button>
          </div>
        </div>

        <!-- 宛先（直打ち） -->
        <div class="form-row">
          <div class="form-group full">
            <label>送信先メールアドレス（直打ち）</label>
            <div class="inline-add">
              <input v-model="extraEmailInput" class="form-input" placeholder="example@example.com" @keyup.enter="addExtraEmail" />
              <button class="small-btn" @click="addExtraEmail">追加する</button>
            </div>
            <div class="chips" v-if="extraEmailList.length">
              <span v-for="(e,i) in extraEmailList" :key="e+i" class="chip">{{ e }} <button class="chip-x" @click="removeExtraEmail(i)">×</button></span>
            </div>
          </div>
        </div>

        <!-- 宛先（グループ） -->
        <div class="form-row">
          <div class="form-group full">
            <label>メールグループ</label>
            <select v-model="form.groups" class="form-select" multiple>
              <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }} ({{ g.members_count || 0 }})</option>
            </select>
            <div class="hint">複数選択可。概算: グループ合計 {{ estimatedGroupCount }} 件 + 直打ち {{ extraEmailCount }} 件（重複は除外前の数）</div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group full">
            <label>メール内容（本文HTML）</label>
            <MinimalEditor v-model="form.body_html" placeholder="本文を入力..." />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group" style="display:flex; align-items:center; gap:8px;">
            <input id="saveAsTemplate" type="checkbox" v-model="saveAsTemplate" />
            <label for="saveAsTemplate" style="margin:0;">この内容をテンプレートとして保存</label>
          </div>
          <div class="form-group">
            <div class="hint">テンプレートは「件名・本文・添付」を保存します。宛先（直打ち/グループ）は保存されません。</div>
          </div>
        </div>

        <div class="form-actions">
          <button class="save-btn" :disabled="saving" @click="createCampaign">{{ saving ? '作成中...' : 'キャンペーン作成' }}</button>
          <button class="refresh-btn" @click="loadData">再読込</button>
        </div>
      </div>

      <!-- 一覧/操作 -->
      <div class="filters-section">
        <div class="status-filter">
          <label>ステータス</label>
          <select v-model="statusFilter" class="filter-select" @change="loadCampaigns">
            <option value="">すべて</option>
            <option value="draft">下書き</option>
            <option value="scheduled">予約</option>
            <option value="sending">送信中</option>
            <option value="sent">送信済</option>
            <option value="failed">失敗</option>
          </select>
        </div>
      </div>

      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>件名</th>
                <th>ステータス</th>
                <th>予約時刻</th>
                <th>作成日</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in campaigns" :key="c.id">
                <td>{{ c.id }}</td>
                <td>{{ c.subject }}</td>
                <td>{{ c.status }}</td>
                <td>{{ formatDateTime(c.scheduled_at) }}</td>
                <td>{{ formatDateTime(c.created_at) }}</td>
                <td>
                  <button class="small-btn" @click="preview(c)">プレビュー</button>
                  <button class="small-btn" @click="openRecipients(c)">受信者</button>
                  <button class="small-btn" @click="openAttachments(c)">添付</button>
                  <button class="small-btn" @click="duplicate(c)">複製</button>
                  <button class="small-btn" @click="toggleTemplate(c)">{{ c.is_template ? 'テンプレ解除' : 'テンプレ化' }}</button>
                  <button class="small-btn" @click="schedule(c)">予約</button>
                  <button class="small-btn danger" @click="sendNow(c)">即時送信</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="pagination" v-if="pagination.total > pagination.per_page">
        <button v-for="p in paginationPages" :key="p" @click="loadCampaigns(p)" :class="['page-btn', { active: p === pagination.current_page }]">{{ p }}</button>
      </div>
    </div>

    <!-- プレビューモーダル -->
    <div v-if="showPreview" class="modal-overlay" @click="showPreview=false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>プレビュー: {{ previewSubject }}</h3>
          <button class="close-btn" @click="showPreview=false">×</button>
        </div>
        <div class="modal-body">
          <div class="preview-html" v-html="previewHtml"></div>
        </div>
      </div>
    </div>

    <!-- 受信者モーダル -->
    <div v-if="showRecipients" class="modal-overlay" @click="showRecipients=false">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>受信者一覧</h3>
          <button class="close-btn" @click="showRecipients=false">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <select v-model="recipientStatusFilter" class="form-select" @change="loadRecipients()">
              <option value="">すべて</option>
              <option value="pending">pending</option>
              <option value="sent">sent</option>
              <option value="failed">failed</option>
            </select>
            <button class="save-btn" @click="resendFailed" :disabled="recipientsLoading">失敗を再送待ちに戻す</button>
          </div>
          <div v-if="recipientsLoading" class="loading">読み込み中...</div>
          <table class="data-table" v-else>
            <thead>
              <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Member</th>
                <th>Status</th>
                <th>Sent At</th>
                <th>Error</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in recipients" :key="r.id">
                <td>{{ r.id }}</td>
                <td>{{ r.email }}</td>
                <td>{{ r.member_id || '-' }}</td>
                <td>{{ r.status }}</td>
                <td>{{ formatDateTime(r.sent_at) }}</td>
                <td>{{ r.error && r.error.length > 120 ? (r.error.slice(0,120) + '...') : (r.error || '') }}</td>
                <td>
                  <button class="small-btn" :disabled="r.status==='pending'" @click="resendRecipient(r)">再送待ちへ</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- 添付ファイルモーダル -->
    <div v-if="showAttachments" class="modal-overlay" @click="closeAttachments">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>添付ファイル: キャンペーン #{{ currentCampaign?.id }}</h3>
          <button class="close-btn" @click="closeAttachments">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row" style="align-items:center; gap:8px;">
            <input ref="attInput" type="file" @change="onAttachmentSelected" />
            <button class="small-btn" :disabled="!attachmentFile || uploadingAttachment" @click="uploadAttachment">{{ uploadingAttachment ? 'アップロード中...' : '追加' }}</button>
          </div>
          <table class="data-table" v-if="attachments.length">
            <thead>
              <tr>
                <th>ID</th>
                <th>ファイル名</th>
                <th>サイズ</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="a in attachments" :key="a.id">
                <td>{{ a.id }}</td>
                <td>{{ a.filename }}</td>
                <td>{{ formatSize(a.size) }}</td>
                <td><button class="small-btn danger" @click="deleteAttachment(a)">削除</button></td>
              </tr>
            </tbody>
          </table>
          <div v-else class="error">添付はありません</div>
        </div>
      </div>
    </div>

    <!-- テンプレートモーダル -->
    <div v-if="showTemplates" class="modal-overlay" @click="closeTemplates">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>テンプレート一覧</h3>
          <button class="close-btn" @click="closeTemplates">×</button>
        </div>
        <div class="modal-body">
          <div v-if="templatesLoading" class="loading">読み込み中...</div>
          <div v-else>
            <table class="data-table" v-if="templates.length">
              <thead><tr><th>ID</th><th>件名</th><th>更新日</th><th>操作</th></tr></thead>
              <tbody>
                <tr v-for="t in templates" :key="t.id">
                  <td>{{ t.id }}</td><td>{{ t.subject }}</td><td>{{ formatDateTime(t.updated_at) }}</td>
                  <td><button class="small-btn" @click="createFromTemplate(t)">このテンプレで作成</button></td>
                </tr>
              </tbody>
            </table>
            <div v-else class="error">テンプレートがありません</div>
          </div>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import MinimalEditor from '@/components/MinimalEditor.vue'
import apiClient from '@/services/apiClient.js'

export default {
  name: 'EmailCampaignManagement',
  components: { AdminLayout, MinimalEditor },
  data() {
    return {
      loading: false,
      saving: false,
      error: '',
      campaigns: [],
      groups: [],
      pagination: { current_page: 1, last_page: 1, per_page: 20, total: 0 },
      statusFilter: '',
      form: { subject: '', body_html: '', groups: [] },
      // direct input addresses (chip style)
      extraEmailInput: '',
      extraEmailList: [],
      saveAsTemplate: false,
      showPreview: false,
      previewHtml: '',
      previewSubject: '',
      showAttachments: false,
      currentCampaign: null,
      attachments: [],
      attachmentFile: null,
      uploadingAttachment: false,
      showTemplates: false,
      templatesLoading: false,
      templates: [],
      // recipients modal state
      showRecipients: false,
      recipientsLoading: false,
      recipientStatusFilter: '',
      recipients: [],
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
    estimatedGroupCount() {
      if (!Array.isArray(this.form.groups) || this.form.groups.length === 0) return 0
      const selected = new Set(this.form.groups.map(x => Number(x)))
      return (this.groups || [])
        .filter(g => selected.has(Number(g.id)))
        .reduce((sum, g) => sum + (Number(g.members_count) || 0), 0)
    },
    extraEmailCount() { return this.extraEmailList.length }
  },
  mounted() { this.loadData() },
  methods: {
    goManageGroups() {
      if (this.$route && this.$route.path === '/admin/mailmagazine/group') return
      this.$router.push('/admin/mailmagazine/group')
    },
    async loadData() { await Promise.all([this.loadGroups(), this.loadCampaigns()]) },
    async loadGroups() {
      try {
        const res = await apiClient.getMailGroups({ per_page: 100 })
        if (res.success) this.groups = res.data.data || []
      } catch (e) { console.warn('Failed to load groups', e) }
    },
    async loadCampaigns(page = 1) {
      this.loading = true
      this.error = ''
      try {
        const res = await apiClient.getEmailCampaigns({ page, per_page: this.pagination.per_page, status: this.statusFilter })
        if (res.success) {
          const p = res.data
          this.campaigns = p.data || []
          this.pagination = { current_page: p.current_page, last_page: p.last_page, per_page: p.per_page, total: p.total }
        } else { throw new Error(res.error || res.message) }
      } catch (e) { this.error = '読み込みに失敗しました' } finally { this.loading = false }
    },
    validEmail(s) {
      // simple RFC-like check
      return /^(?!.{255,})([A-Za-z0-9._%+-]+)@([A-Za-z0-9.-]+)\.[A-Za-z]{2,}$/.test(s)
    },
    addExtraEmail() {
      const v = (this.extraEmailInput || '').trim()
      if (!v) return
      // allow comma-separated quick add
      const parts = v.split(',').map(s=>s.trim()).filter(Boolean)
      let added = 0
      for (const p of parts) {
        if (this.validEmail(p) && !this.extraEmailList.includes(p)) { this.extraEmailList.push(p); added++ }
      }
      if (added===0 && parts.length===1) alert('メールアドレスの形式を確認してください')
      this.extraEmailInput = ''
    },
    removeExtraEmail(i) { this.extraEmailList.splice(i,1) },
    async createCampaign() {
      this.saving = true
      try {
        const payload = { ...this.form, extra_emails: this.extraEmailList }
        if ((!payload.groups || payload.groups.length===0) && (!payload.extra_emails || payload.extra_emails.length===0)) {
          alert('宛先が未指定です。直打ちかグループを設定してください。')
          this.saving = false
          return
        }
        const res = await apiClient.createEmailCampaign(payload)
        if (res.success) {
          // Mark as template if user requested
          if (this.saveAsTemplate && res.data && res.data.id) {
            try { await apiClient.markEmailTemplate(res.data.id) } catch(e) { console.warn('Failed to mark template', e) }
          }
          alert('作成しました')
          this.form = { subject: '', body_html: '', groups: [] }
          this.extraEmailList = []
          this.saveAsTemplate = false
          await this.loadCampaigns(this.pagination.current_page)
        } else { alert(res.error || '作成に失敗しました') }
      } catch (e) { alert('作成に失敗しました') } finally { this.saving = false }
    },
    async preview(c) {
      try {
        const res = await apiClient.previewEmailCampaign(c.id)
        if (res.success) {
          this.previewSubject = c.subject
          this.previewHtml = res.data.body_html || ''
          this.showPreview = true
        }
      } catch (e) { alert('プレビューに失敗しました') }
    },
    async schedule(c) {
      const when = prompt('送信予約日時を入力 (YYYY-MM-DD HH:mm:ss)')
      if (!when) return
      try {
        const res = await apiClient.scheduleEmailCampaign(c.id, when)
        if (res.success) { alert('予約しました'); this.loadCampaigns(this.pagination.current_page) }
      } catch (e) { alert('予約に失敗しました') }
    },
    async sendNow(c) {
      if (!confirm('今すぐ送信しますか？')) return
      try {
        const res = await apiClient.sendEmailCampaignNow(c.id)
        if (res.success) { alert('送信しました'); this.loadCampaigns(this.pagination.current_page) }
        else alert(res.error || '送信に失敗しました')
      } catch (e) { alert('送信に失敗しました') }
    },
    async duplicate(c) {
      if (!confirm('このキャンペーンを複製しますか？（受信者はコピーされません）')) return
      try {
        const res = await apiClient.duplicateEmailCampaign(c.id)
        if (res.success) { alert('複製しました'); this.loadCampaigns(this.pagination.current_page) }
      } catch(e) { alert('複製に失敗しました') }
    },
    openAttachments(c) {
      this.currentCampaign = c
      this.showAttachments = true
      this.loadAttachments()
    },
    closeAttachments() {
      this.showAttachments = false
      this.currentCampaign = null
      this.attachments = []
      this.attachmentFile = null
      if (this.$refs.attInput) this.$refs.attInput.value = ''
    },
    async loadAttachments() {
      if (!this.currentCampaign) return
      try {
        const res = await apiClient.listEmailAttachments(this.currentCampaign.id)
        if (res.success) this.attachments = res.data || []
      } catch(e) { console.warn('Failed to load attachments', e) }
    },
    onAttachmentSelected(e) { this.attachmentFile = (e.target.files && e.target.files[0]) || null },
    async uploadAttachment() {
      if (!this.currentCampaign || !this.attachmentFile) return
      this.uploadingAttachment = true
      try {
        const res = await apiClient.uploadEmailAttachment(this.currentCampaign.id, this.attachmentFile)
        if (res.success) {
          await this.loadAttachments()
          if (this.$refs.attInput) this.$refs.attInput.value = ''
          this.attachmentFile = null
        } else { alert(res.error || res.message || 'アップロードに失敗しました') }
      } catch(e) { alert('アップロードに失敗しました') } finally { this.uploadingAttachment = false }
    },
    async deleteAttachment(a) {
      if (!confirm(`添付「${a.filename}」を削除しますか？`)) return
      try {
        const res = await apiClient.deleteEmailAttachment(this.currentCampaign.id, a.id)
        if (res.success) this.attachments = this.attachments.filter(x => x.id !== a.id)
      } catch(e) { alert('削除に失敗しました') }
    },
    // Recipients modal
    async openRecipients(c) {
      this.currentCampaign = c
      this.showRecipients = true
      this.recipientStatusFilter = ''
      await this.loadRecipients()
    },
    async loadRecipients() {
      if (!this.currentCampaign) return
      this.recipientsLoading = true
      try {
        const params = {}
        if (this.recipientStatusFilter) params.status = this.recipientStatusFilter
        const res = await apiClient.getEmailCampaign(this.currentCampaign.id, params)
        if (res.success) {
          this.recipients = (res.data && res.data.recipients && res.data.recipients.data) || []
        }
      } catch(e) { console.warn('Failed to load recipients', e) } finally { this.recipientsLoading = false }
    },
    async resendFailed() {
      if (!this.currentCampaign) return
      try {
        const res = await apiClient.resendFailedRecipients(this.currentCampaign.id)
        if (res.success) { await this.loadRecipients() }
      } catch(e) { alert('再送待ちへの変更に失敗しました') }
    },
    async resendRecipient(r) {
      if (!this.currentCampaign || !r) return
      try {
        const res = await apiClient.resendRecipient(this.currentCampaign.id, r.id)
        if (res.success) { await this.loadRecipients() }
      } catch(e) { alert('再送待ちへの変更に失敗しました') }
    },
    async toggleTemplate(c) {
      try {
        const res = c.is_template ? await apiClient.unmarkEmailTemplate(c.id) : await apiClient.markEmailTemplate(c.id)
        if (res.success) { await this.loadCampaigns(this.pagination.current_page) }
      } catch(e) { alert('更新に失敗しました') }
    },
    openTemplates() {
      this.showTemplates = true
      this.loadTemplates()
    },
    closeTemplates() { this.showTemplates = false; this.templates = [] },
    async loadTemplates() {
      this.templatesLoading = true
      try {
        const res = await apiClient.listEmailTemplates()
        if (res.success) this.templates = res.data || []
      } catch(e) { console.warn('Failed to load templates', e) } finally { this.templatesLoading = false }
    },
    async createFromTemplate(t) {
      try {
        const res = await apiClient.createCampaignFromTemplate(t.id)
        if (res.success) { alert('テンプレートから作成しました'); this.closeTemplates(); this.loadCampaigns(1) }
      } catch(e) { alert('作成に失敗しました') }
    },
    formatDateTime(s) { return s ? new Date(s).toLocaleString('ja-JP') : '-' }
    ,formatSize(n) { if (!n && n!==0) return '-' ; const u=['B','KB','MB','GB']; let i=0; let v=n; while(v>=1024 && i<u.length-1){v/=1024;i++} return `${v.toFixed(v>=100?0: v>=10?1:2)} ${u[i]}` }
  }
}
</script>

<style scoped>
.dashboard { background: white; border-radius: 8px; overflow: hidden; }
.page-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e5e5e5; }
.page-title { font-size: 24px; font-weight: 600; }
.composer { padding: 16px 24px; border-bottom: 1px solid #e5e5e5; }
.form-row { display: flex; gap: 16px; margin-bottom: 12px; }
.form-group { flex: 1; }
.form-group.full { flex: 1 1 100%; }
.form-input, .form-select, .form-textarea { width: 100%; padding: 8px 12px; border: 1px solid #d0d0d0; border-radius: 4px; }
.inline-add { display:flex; gap:8px; }
.chips { margin-top:8px; display:flex; flex-wrap:wrap; gap:6px; }
.chip { background:#f1f1f1; border:1px solid #ddd; border-radius:16px; padding:4px 8px; font-size:12px; }
.chip-x { border:none; background:transparent; margin-left:6px; cursor:pointer; }
.form-actions { display: flex; gap: 12px; }
.save-btn { padding: 8px 16px; border: 1px solid #DA5761; background: #DA5761; color: white; border-radius: 4px; cursor: pointer; transition: background-color 0.2s; }
.save-btn:hover { background: #9C3940; border-color: #9C3940; }
.refresh-btn { padding: 8px 16px; border: 1px solid #1A1A1A; background: #1A1A1A; color: white; border-radius: 4px; cursor: pointer; transition: background-color 0.2s; }
.refresh-btn:hover { background: #333333; border-color: #333333; }
.filters-section { padding: 16px 24px; display: flex; gap: 16px; align-items: center; border-bottom: 1px solid #e5e5e5; }
.filter-select { padding: 8px 12px; border: 1px solid #d0d0d0; border-radius: 4px; }
.table-container { overflow-x: auto; }
.loading, .error { padding: 40px; text-align: center; }
.error { color: #da5761; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { border-bottom: 1px solid #e5e5e5; padding: 12px 16px; text-align: left; }
.small-btn { padding: 6px 10px; border: 1px solid #DA5761; border-radius: 4px; background: #DA5761; color: white; cursor: pointer; margin-right: 6px; transition: background-color 0.2s; }
.small-btn:hover { background: #9C3940; border-color: #9C3940; }
.small-btn.danger { border-color: #1A1A1A; background: #1A1A1A; color: white; }
.small-btn.danger:hover { background: #333333; border-color: #333333; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display: flex; justify-content: center; align-items: center; }
.modal-content { background: white; border-radius: 8px; width: 720px; max-width: 95%; overflow: hidden; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #eee; }
.modal-body { padding: 16px 20px; }
.close-btn { background: none; border: none; font-size: 20px; cursor: pointer; color: #666; }
.preview-html { border: 1px solid #eee; padding: 12px; border-radius: 6px; background: #fafafa; min-height: 200px; }
</style>
