<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <h1 class="page-title">会員管理</h1>
        <div class="header-actions">
          <button @click="showAddMemberModal" class="add-btn">
            新規会員追加
          </button>
          <button @click="exportCsv" class="refresh-btn" :disabled="loading">
            CSV一括DL
          </button>
          <button @click="refreshMembers" class="refresh-btn" :disabled="loading">
            {{ loading ? '読み込み中...' : '更新' }}
          </button>
        </div>
      </div>

      <!-- フィルター -->
      <div class="filters-section">
        <!-- 検索バー -->
        <div class="search-filter">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="会社名、代表者名、メールアドレスで検索..."
            class="search-input"
            @input="debouncedSearch"
          >
        </div>

        <!-- 会員種別フィルター -->
        <div class="membership-filter">
          <label>会員種別:</label>
          <select v-model="selectedMembershipType" @change="loadMembers">
            <option value="">全て</option>
            <option value="free">無料</option>
            <option value="basic">ベーシック</option>
            <option value="standard">スタンダード</option>
            <option value="premium">プレミアム</option>
          </select>
        </div>

        <!-- ステータスフィルター -->
        <div class="status-filter">
          <label>ステータス:</label>
          <select v-model="selectedStatus" @change="loadMembers">
            <option value="">全て</option>
            <option value="pending">承認待ち</option>
            <option value="active">アクティブ</option>
            <option value="suspended">停止中</option>
            <option value="cancelled">解約</option>
          </select>
        </div>
      </div>

      <!-- データテーブル -->
      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>会社名</th>
                <th>代表者名</th>
                <th>メールアドレス</th>
                <th>会員種別</th>
                <th>ステータス</th>
                <th>有効期限</th>
                <th>登録日</th>
                <th>管理</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="member in members" :key="member.id" :class="{ 'expiring-soon': member.is_expiring_soon }">
                <td class="company-name">{{ member.company_name }}</td>
                <td class="representative-name">{{ member.representative_name }}</td>
                <td class="email">{{ member.email }}</td>
                <td class="membership-type">
                  <span :class="['membership-badge', `membership-${member.membership_type}`]">
                    {{ getMembershipTypeLabel(member.membership_type) }}
                  </span>
                </td>
                <td class="status">
                  <span :class="['status-badge', `status-${member.status}`]">
                    {{ getStatusLabel(member.status) }}
                  </span>
                </td>
                <td class="expiry">
                  <span v-if="member.membership_expires_at" :class="{ 'text-warning': member.is_expiring_soon }">
                    {{ formatDate(member.membership_expires_at) }}
                  </span>
                  <span v-else class="text-muted">無期限</span>
                </td>
                <td class="joined-date">{{ formatDate(member.created_at) }}</td>
                <td class="actions">
                  <button @click="editMember(member)" class="edit-btn">編集</button>
                  <button @click="viewDetails(member)" class="detail-btn">詳細</button>
                  <button @click="autoPassword(member)" class="detail-btn">PW自動生成</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ページネーション -->
      <AdminPagination
        :page="pagination.current_page"
        :last-page="pagination.last_page"
        @update:page="p => (pagination.current_page = p)"
        @change="loadMembers"
      />
    </div>

    <!-- 会員詳細モーダル -->
    <div v-if="viewingMember" class="modal-overlay" @click="closeDetailModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>会員詳細</h3>
          <button @click="closeDetailModal" class="close-btn">×</button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group">
              <label>会社名</label>
              <div class="readonly-text">{{ viewingMember.company_name || '-' }}</div>
            </div>
            <div class="form-group">
              <label>代表者名</label>
              <div class="readonly-text">{{ viewingMember.representative_name || '-' }}</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>メールアドレス</label>
              <div class="readonly-text">{{ viewingMember.email || '-' }}</div>
            </div>
            <div class="form-group">
              <label>電話番号</label>
              <div class="readonly-text">{{ viewingMember.phone || '-' }}</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>郵便番号</label>
              <div class="readonly-text">{{ viewingMember.postal_code || '-' }}</div>
            </div>
            <div class="form-group">
              <label>役職</label>
              <div class="readonly-text">{{ viewingMember.position || '-' }}</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>会員種別</label>
              <div class="readonly-text">{{ getMembershipTypeLabel(viewingMember.membership_type) }}</div>
            </div>
            <div class="form-group">
              <label>ステータス</label>
              <div class="readonly-text">{{ getStatusLabel(viewingMember.status) }}</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>有効期限</label>
              <div class="readonly-text">
                <template v-if="viewingMember.membership_expires_at">{{ formatDate(viewingMember.membership_expires_at) }}</template>
                <template v-else>無期限</template>
              </div>
            </div>
            <div class="form-group">
              <label>登録日</label>
              <div class="readonly-text">{{ formatDate(viewingMember.created_at) }}</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>資本金（円）</label>
              <div class="readonly-text">{{ viewingMember.capital != null ? viewingMember.capital : '-' }}</div>
            </div>
            <div class="form-group">
              <label>業種</label>
              <div class="readonly-text">{{ viewingMember.industry || '-' }}</div>
            </div>
          </div>
          <div class="form-group">
            <label>お困りごと</label>
            <div class="readonly-text">{{ viewingMember.concerns || '-' }}</div>
          </div>
          <div class="form-group">
            <label>備考</label>
            <div class="readonly-text">{{ viewingMember.notes || '-' }}</div>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeDetailModal" class="cancel-btn">閉じる</button>
          <button @click="editMember(viewingMember)" class="save-btn">編集する</button>
        </div>
      </div>
    </div>

    <!-- 会員編集モーダル -->
    <div v-if="editingMember" class="modal-overlay" @click="closeEditModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>会員情報編集</h3>
          <button @click="closeEditModal" class="close-btn">×</button>
        </div>
        
        <div class="modal-body">
          <form @submit.prevent="saveMember">
            <div class="form-row">
              <div class="form-group">
                <label>会社名</label>
                <input 
                  v-model="editForm.company_name" 
                  type="text" 
                  class="form-input"
                  required
                >
              </div>
              <div class="form-group">
                <label>代表者名</label>
                <input 
                  v-model="editForm.representative_name" 
                  type="text" 
                  class="form-input"
                  required
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>メールアドレス</label>
                <input 
                  v-model="editForm.email" 
                  type="email" 
                  class="form-input"
                  required
                >
              </div>
              <div class="form-group">
                <label>電話番号</label>
                <input 
                  v-model="editForm.phone" 
                  type="text" 
                  class="form-input"
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>郵便番号</label>
                <input 
                  v-model="editForm.postal_code" 
                  type="text" 
                  class="form-input"
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>会員種別</label>
                <select v-model="editForm.membership_type" class="form-select">
                  <option value="free">無料</option>
                  <option value="basic">ベーシック</option>
                  <option value="standard">スタンダード</option>
                  <option value="premium">プレミアム</option>
                </select>
              </div>
              <div class="form-group">
                <label>ステータス</label>
                <select v-model="editForm.status" class="form-select">
                  <option value="pending">承認待ち</option>
                  <option value="active">アクティブ</option>
                  <option value="suspended">停止中</option>
                  <option value="cancelled">解約</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>有効期限</label>
                <input 
                  v-model="editForm.membership_expires_at" 
                  type="datetime-local" 
                  class="form-input"
                >
              </div>
              <div class="form-group">
                <label>アクティブ</label>
                <input 
                  v-model="editForm.is_active" 
                  type="checkbox" 
                  class="form-checkbox"
                >
              </div>
            </div>

            <!-- 住所は編集対象外 -->

            <div class="form-row">
              <div class="form-group">
                <label>役職</label>
                <input v-model="editForm.position" type="text" class="form-input" />
              </div>
              <!-- 部署は編集対象外 -->
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>資本金（円）</label>
                <input v-model.number="editForm.capital" type="number" min="0" step="1" class="form-input" />
              </div>
              <div class="form-group">
                <label>業種</label>
                <template v-if="industryOptions.length">
                  <select v-model="editForm.industry" class="form-select">
                    <option value="">未選択</option>
                    <option v-for="i in industryOptions" :key="i" :value="i">{{ i }}</option>
                  </select>
                </template>
                <template v-else>
                  <input v-model="editForm.industry" type="text" class="form-input" />
                </template>
              </div>
            </div>

            <div class="form-group">
              <label>お困りごと</label>
              <textarea v-model="editForm.concerns" class="form-textarea" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label>備考</label>
              <textarea v-model="editForm.notes" class="form-textarea" rows="3"></textarea>
            </div>
          </form>
        </div>
        
        <div class="modal-footer">
          <button @click="closeEditModal" class="cancel-btn">キャンセル</button>
          <button @click="saveMember" class="save-btn" :disabled="saving">
            {{ saving ? '保存中...' : '保存' }}
          </button>
          <button @click="extendMembership" class="extend-btn" v-if="editingMember.membership_type !== 'free'">
            期限延長
          </button>
        </div>
      </div>
    </div>

    <!-- 期限延長モーダル -->
    <div v-if="showExtendModal" class="modal-overlay" @click="closeExtendModal">
      <div class="modal-content extend-modal" @click.stop>
        <div class="modal-header">
          <h3>会員期限延長</h3>
          <button @click="closeExtendModal" class="close-btn">×</button>
        </div>
        
        <div class="modal-body">
          <p>{{ editingMember.company_name }} の会員期限を延長します。</p>
          <div class="form-group">
            <label>延長期間（月）</label>
            <select v-model="extendMonths" class="form-select">
              <option value="1">1ヶ月</option>
              <option value="3">3ヶ月</option>
              <option value="6">6ヶ月</option>
              <option value="12">12ヶ月</option>
            </select>
          </div>
        </div>
        
        <div class="modal-footer">
          <button @click="closeExtendModal" class="cancel-btn">キャンセル</button>
          <button @click="confirmExtendMembership" class="save-btn" :disabled="extending">
            {{ extending ? '延長中...' : '延長実行' }}
          </button>
        </div>
      </div>
    </div>

    <!-- 新規会員追加モーダル -->
    <div v-if="showAddModal" class="modal-overlay" @click="closeAddModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>新規会員追加</h3>
          <button @click="closeAddModal" class="close-btn">×</button>
        </div>
        
        <div class="modal-body">
          <form @submit.prevent="addMember">
            <div class="form-row">
              <div class="form-group">
                <label>会社名 <span class="required">*</span></label>
                <input 
                  v-model="addForm.company_name" 
                  type="text" 
                  class="form-input"
                  required
                >
              </div>
              <div class="form-group">
                <label>代表者名 <span class="required">*</span></label>
                <input 
                  v-model="addForm.representative_name" 
                  type="text" 
                  class="form-input"
                  required
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>メールアドレス <span class="required">*</span></label>
                <input 
                  v-model="addForm.email" 
                  type="email" 
                  class="form-input"
                  required
                >
              </div>
              <div class="form-group">
                <label>電話番号</label>
                <input 
                  v-model="addForm.phone" 
                  type="text" 
                  class="form-input"
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>郵便番号</label>
                <input 
                  v-model="addForm.postal_code" 
                  type="text" 
                  class="form-input"
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>パスワード <span class="required">*</span></label>
                <input 
                  v-model="addForm.password" 
                  type="password" 
                  class="form-input"
                  required
                  minlength="8"
                >
              </div>
              <div class="form-group">
                <label>パスワード確認 <span class="required">*</span></label>
                <input 
                  v-model="addForm.password_confirmation" 
                  type="password" 
                  class="form-input"
                  required
                >
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>会員種別</label>
                <select v-model="addForm.membership_type" class="form-select">
                  <option value="free">無料</option>
                  <option value="basic">ベーシック</option>
                  <option value="standard">スタンダード</option>
                  <option value="premium">プレミアム</option>
                </select>
              </div>
              <div class="form-group">
                <label>ステータス</label>
                <select v-model="addForm.status" class="form-select">
                  <option value="pending">承認待ち</option>
                  <option value="active">アクティブ</option>
                  <option value="suspended">停止中</option>
                  <option value="cancelled">解約</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>有効期限</label>
                <input 
                  v-model="addForm.membership_expires_at" 
                  type="datetime-local" 
                  class="form-input"
                >
              </div>
              <div class="form-group">
                <label>アクティブ</label>
                <input 
                  v-model="addForm.is_active" 
                  type="checkbox" 
                  class="form-checkbox"
                >
              </div>
            </div>

            <!-- 住所は編集対象外 -->

            <div class="form-row">
              <div class="form-group">
                <label>役職</label>
                <input v-model="addForm.position" type="text" class="form-input" />
              </div>
              <!-- 部署は編集対象外 -->
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>資本金（円）</label>
                <input v-model.number="addForm.capital" type="number" min="0" step="1" class="form-input" />
              </div>
              <div class="form-group">
                <label>業種</label>
                <template v-if="industryOptions.length">
                  <select v-model="addForm.industry" class="form-select">
                    <option value="">未選択</option>
                    <option v-for="i in industryOptions" :key="i" :value="i">{{ i }}</option>
                  </select>
                </template>
                <template v-else>
                  <input v-model="addForm.industry" type="text" class="form-input" />
                </template>
              </div>
            </div>

            <div class="form-group">
              <label>お困りごと</label>
              <textarea v-model="addForm.concerns" class="form-textarea" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label>備考</label>
              <textarea v-model="addForm.notes" class="form-textarea" rows="3"></textarea>
            </div>
          </form>
        </div>
        
        <div class="modal-footer">
          <button @click="closeAddModal" class="cancel-btn">キャンセル</button>
          <button @click="addMember" class="save-btn" :disabled="adding">
            {{ adding ? '追加中...' : '追加' }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '../../services/apiClient.js'
import AdminPagination from '@/components/admin/AdminPagination.vue'
import { getMembershipOptions, getMembershipLabel } from '@/utils/membershipTypes'

export default {
  name: 'MemberManagement',
  components: {
    AdminLayout,
    AdminPagination
  },
  data() {
    return {
      members: [],
      loading: false,
      error: '',
      
      // フィルター
      searchQuery: '',
      selectedMembershipType: '',
      selectedStatus: '',
      
      // ページネーション
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0
      },
      
      // 編集関連
      editingMember: null,
      editForm: {},
      saving: false,
      // 詳細表示
      viewingMember: null,
      
      // 期限延長関連
      showExtendModal: false,
      extendMonths: 12,
      extending: false,
      
      // 新規追加関連
      showAddModal: false,
      addForm: {
        company_name: '',
        representative_name: '',
        email: '',
        phone: '',
        postal_code: '',
        position: '',
        // department: '', // 編集不可
        capital: null,
        industry: '',
        password: '',
        password_confirmation: '',
        membership_type: 'free',
        status: 'active',
        membership_expires_at: '',
        is_active: true,
        // address: '', // 編集不可
        concerns: '',
        notes: ''
      },
      adding: false,
      // masters
      industryOptions: []
    }
  },
  computed: {
    paginationPages() {
      const pages = []
      const maxShow = 5
      const start = Math.max(1, this.pagination.current_page - Math.floor(maxShow / 2))
      const end = Math.min(this.pagination.last_page, start + maxShow - 1)
      
      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      return pages
    }
  },
  mounted() {
    this.loadMembers()
    this.loadMasters()
  },
  methods: {
    async loadMasters() {
      try {
        const industriesRes = await apiClient.getIndustries()
        if (industriesRes && industriesRes.success) {
          this.industryOptions = (industriesRes.data?.industries || industriesRes.data || []).map(i => i.name || i).filter(Boolean)
        }
      } catch (e) {
        console.warn('Failed to load masters', e)
      }
    },
    async loadMembers(page = 1) {
      this.loading = true
      this.error = ''
      
      try {
        const params = {
          page,
          per_page: this.pagination.per_page
        }
        
        if (this.searchQuery) {
          params.search = this.searchQuery
        }
        if (this.selectedMembershipType) {
          params.membership_type = this.selectedMembershipType
        }
        if (this.selectedStatus) {
          params.status = this.selectedStatus
        }
        
        const response = await apiClient.getAdminMembers(params)
        
        if (response.success) {
          this.members = response.data.data
          this.pagination = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
            total: response.data.total
          }
        } else {
          this.error = response.message || '会員データの取得に失敗しました'
        }
      } catch (error) {
        this.error = 'サーバーエラーが発生しました'
        console.error('Failed to load members:', error)
      } finally {
        this.loading = false
      }
    },
    async exportCsv() {
      try {
        const params = {}
        if (this.searchQuery) params.search = this.searchQuery
        if (this.selectedMembershipType) params.membership_type = this.selectedMembershipType
        if (this.selectedStatus) params.status = this.selectedStatus
        const res = await apiClient.exportAdminMembersCsv(params)
        if (!res || res.success === false) {
          alert(res?.message || 'CSVの生成に失敗しました')
          return
        }
        const csvText = typeof res.data === 'string' ? res.data : (res || '')
        const blob = new Blob([csvText], { type: 'text/csv;charset=utf-8;' })
        const url = window.URL.createObjectURL(blob)
        const a = document.createElement('a')
        a.href = url
        const ts = new Date().toISOString().replace(/[:T]/g,'-').slice(0,19)
        a.download = `members_${ts}.csv`
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)
        window.URL.revokeObjectURL(url)
      } catch (e) {
        console.error('export csv failed', e)
        alert('CSV出力に失敗しました')
      }
    },
    async autoPassword(member) {
      if (!confirm(`${member.company_name} のパスワードを自動生成して更新しますか？`)) return
      try {
        const res = await apiClient.resetAdminMemberPassword(member.id, 12)
        if (res && res.success) {
          const pwd = res.generated_password || '(取得不可)'
          try { await navigator.clipboard.writeText(pwd) } catch(e) {}
          alert(`新しいパスワード: ${pwd}\nクリップボードにコピーしました。`)
        } else {
          alert(res?.message || '自動生成に失敗しました')
        }
      } catch(e) {
        console.error('auto password failed', e)
        alert('サーバーエラーが発生しました')
      }
    },
    
    debouncedSearch() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.loadMembers()
      }, 500)
    },
    
    refreshMembers() {
      this.loadMembers(this.pagination.current_page)
    },
    
    editMember(member) {
      this.editingMember = { ...member }
      this.editForm = {
        company_name: member.company_name,
        representative_name: member.representative_name,
        email: member.email,
        phone: member.phone,
        postal_code: member.postal_code,
        position: member.position,
        // department: member.department, // 編集不可
        capital: member.capital,
        industry: member.industry,
        concerns: member.concerns,
        notes: member.notes,
        membership_type: member.membership_type,
        status: member.status,
        membership_expires_at: member.membership_expires_at ? 
          new Date(member.membership_expires_at).toISOString().slice(0, 16) : '',
        is_active: member.is_active
      }
    },
    
    closeEditModal() {
      this.editingMember = null
      this.editForm = {}
    },
    
    async saveMember() {
      this.saving = true
      
      try {
        // サーバーの期待フォーマットに合わせて整形
        const payload = { ...this.editForm }
        if (payload.membership_expires_at) {
          // 受け取りは "YYYY-MM-DDTHH:mm" or Date なので "YYYY-MM-DD HH:mm:ss" に変換
          const dt = new Date(payload.membership_expires_at)
          if (!isNaN(dt.getTime())) {
            const pad = n => String(n).padStart(2, '0')
            const y = dt.getFullYear()
            const m = pad(dt.getMonth() + 1)
            const d = pad(dt.getDate())
            const hh = pad(dt.getHours())
            const mm = pad(dt.getMinutes())
            const ss = pad(dt.getSeconds())
            payload.membership_expires_at = `${y}-${m}-${d} ${hh}:${mm}:${ss}`
          }
        }
        const response = await apiClient.updateAdminMember(this.editingMember.id, payload)
        
        if (response.success) {
          alert('会員情報を更新しました')
          this.closeEditModal()
          this.loadMembers(this.pagination.current_page)
        } else {
          this.error = response.message || '更新に失敗しました'
        }
      } catch (error) {
        this.error = 'サーバーエラーが発生しました'
        console.error('Failed to save member:', error)
      } finally {
        this.saving = false
      }
    },
    
    extendMembership() {
      this.showExtendModal = true
    },
    
    closeExtendModal() {
      this.showExtendModal = false
      this.extendMonths = 12
    },
    
    async confirmExtendMembership() {
      this.extending = true
      
      try {
        const response = await apiClient.extendAdminMember(this.editingMember.id, this.extendMonths)
        
        if (response.success) {
          alert(response.message)
          this.closeExtendModal()
          this.closeEditModal()
          this.loadMembers(this.pagination.current_page)
        } else {
          this.error = response.message || '期限延長に失敗しました'
        }
      } catch (error) {
        this.error = 'サーバーエラーが発生しました'
        console.error('Failed to extend membership:', error)
      } finally {
        this.extending = false
      }
    },
    
    viewDetails(member) {
      this.viewingMember = { ...member }
    },
    closeDetailModal() {
      this.viewingMember = null
    },
    
    getMembershipTypeLabel(type) {
      const labels = {
        'free': '無料',
        'basic': 'ベーシック',
        'standard': 'スタンダード',
        'premium': 'プレミアム'
      }
      return labels[type] || type
    },
    
    getStatusLabel(status) {
      const labels = {
        'pending': '承認待ち',
        'active': 'アクティブ',
        'suspended': '停止中',
        'cancelled': '解約'
      }
      return labels[status] || status
    },
    
    formatDate(dateString) {
      if (!dateString) return '-'
      const date = new Date(dateString)
      return date.toLocaleDateString('ja-JP')
    },
    
    // 新規会員追加関連メソッド
    showAddMemberModal() {
      this.showAddModal = true
      this.resetAddForm()
    },
    
    closeAddModal() {
      this.showAddModal = false
      this.resetAddForm()
    },
    
    resetAddForm() {
      this.addForm = {
        company_name: '',
        representative_name: '',
        email: '',
        phone: '',
        postal_code: '',
        position: '',
        capital: null,
        industry: '',
        password: '',
        password_confirmation: '',
        membership_type: 'free',
        status: 'active',
        membership_expires_at: '',
        is_active: true,
        concerns: '',
        notes: ''
      }
    },
    
    async addMember() {
      // パスワード確認
      if (this.addForm.password !== this.addForm.password_confirmation) {
        alert('パスワードが一致しません')
        return
      }
      
      this.adding = true
      
      try {
        console.log('Sending member data:', this.addForm)
        const response = await apiClient.createAdminMember(this.addForm)
        
        if (response.success) {
          alert('新規会員を追加しました')
          this.closeAddModal()
          this.loadMembers(1) // 最初のページに戻る
        } else {
          console.error('Validation errors:', response.errors)
          console.error('Debug info:', response.debug)
          
          // バリデーションエラーの詳細表示
          let errorMessage = 'バリデーションエラー:\n'
          if (response.errors) {
            Object.keys(response.errors).forEach(field => {
              errorMessage += `${field}: ${response.errors[field].join(', ')}\n`
            })
          }
          alert(errorMessage)
        }
      } catch (error) {
        console.error('Failed to add member:', error)
        alert('サーバーエラーが発生しました: ' + (error.message || '不明なエラー'))
      } finally {
        this.adding = false
      }
    }
  }
}
</script>

<style scoped>
.dashboard {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.page-title {
  font-size: 24px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0;
}

.region-filter {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.region-tabs {
  display: flex;
  gap: 24px;
}

.region-tab {
  background: none;
  border: none;
  font-size: 14px;
  color: #666;
  cursor: pointer;
  padding: 8px 0;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
}

.region-tab.active {
  color: #1A1A1A;
  border-bottom-color: #da5761;
  font-weight: 600;
}

.region-tab:hover {
  color: #1A1A1A;
}

.industry-filter {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.industry-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 8px;
}

.industry-btn {
  background-color: #f0f0f0;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  padding: 8px 12px;
  font-size: 12px;
  color: #1A1A1A;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
}

.industry-btn.active {
  background-color: #da5761;
  color: white;
  border-color: #da5761;
}

.industry-btn:hover {
  background-color: #e8e8e8;
}

.industry-btn.active:hover {
  background-color: #c44853;
}

.table-container {
  overflow-x: auto;
}

.loading, .error {
  padding: 40px;
  text-align: center;
  color: #666;
}

.error {
  color: #da5761;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  background-color: #f8f8f8;
  border-bottom: 1px solid #e5e5e5;
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 14px;
  color: #1A1A1A;
}

.data-table td {
  border-bottom: 1px solid #e5e5e5;
  padding: 16px;
  font-size: 14px;
}

.company-name {
  color: #1A1A1A;
  font-weight: 500;
}

.overview {
  color: #666;
}

.capital {
  color: #666;
}

.concern {
  color: #666;
  max-width: 200px;
}

.note {
  color: #666;
}

.detail-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.detail-btn:hover {
  background-color: #555;
}

.pagination {
  padding: 16px 24px;
  text-align: center;
  border-top: 1px solid #e5e5e5;
}

.page-info {
  font-size: 14px;
  color: #da5761;
}

/* 新しいフィルター・UI スタイル */
.filters-section {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e5e5;
  display: flex;
  gap: 20px;
  align-items: center;
  flex-wrap: wrap;
}

.search-filter {
  flex: 1;
  min-width: 300px;
}

.search-input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  font-size: 14px;
}

.membership-filter, .status-filter {
  display: flex;
  align-items: center;
  gap: 8px;
}

.membership-filter label, .status-filter label {
  font-size: 14px;
  font-weight: 500;
  color: #333;
}

.membership-filter select, .status-filter select {
  padding: 6px 12px;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  font-size: 14px;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.add-btn {
  padding: 8px 16px;
  background-color: #DA5761;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.2s;
}

.add-btn:hover {
  background-color: #9C3940;
}

.refresh-btn {
  padding: 8px 16px;
  background-color: #1A1A1A;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.refresh-btn:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

/* バッジスタイル */
.membership-badge, .status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  text-align: center;
}

.membership-free { background-color: #f8f9fa; color: #6c757d; }
.membership-basic { background-color: #e3f2fd; color: #1976d2; }
.membership-standard { background-color: #e8f5e8; color: #388e3c; }
.membership-premium { background-color: #fff3e0; color: #f57c00; }

.status-pending { background-color: #fff3cd; color: #856404; }
.status-active { background-color: #d4edda; color: #155724; }
.status-suspended { background-color: #f8d7da; color: #721c24; }
.status-cancelled { background-color: #f1f3f4; color: #5f6368; }

.expiring-soon {
  background-color: #fff3cd;
}

.text-warning {
  color: #856404;
  font-weight: 500;
}

.text-muted {
  color: #6c757d;
}

/* アクションボタン */
.actions {
  display: flex;
  gap: 8px;
}

.edit-btn {
  padding: 4px 8px;
  border: 1px solid #1A1A1A;
  border-radius: 4px;
  background-color: #1A1A1A;
  color: white;
  cursor: pointer;
  font-size: 12px;
  transition: background-color 0.2s;
}

.edit-btn:hover {
  background-color: #555;
  border-color: #555;
}

/* ページネーション */
.page-btn {
  padding: 8px 12px;
  border: 1px solid #dee2e6;
  background-color: white;
  cursor: pointer;
  border-radius: 4px;
  margin: 0 2px;
}

.page-btn:hover {
  background-color: #e9ecef;
}

.page-btn.active {
  background-color: #da5761;
  color: white;
  border-color: #da5761;
}

/* モーダル */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #6c757d;
}

.modal-body {
  padding: 24px;
}

.modal-footer {
  padding: 20px 24px;
  border-top: 1px solid #e5e5e5;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

/* フォーム */
.form-row {
  display: flex;
  gap: 16px;
  margin-bottom: 16px;
}

.form-group {
  flex: 1;
}

.form-group label {
  display: block;
  margin-bottom: 4px;
  font-weight: 500;
  font-size: 14px;
  color: #333;
}

.form-input, .form-select, .form-textarea {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  font-size: 14px;
}

.readonly-text {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #e5e5e5;
  border-radius: 4px;
  background-color: #f8f9fa;
  color: #333;
  min-height: 36px;
  display: flex;
  align-items: center;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: #007bff;
}

.form-checkbox {
  width: auto;
}

.required {
  color: #da5761;
  font-weight: bold;
}

.cancel-btn, .save-btn, .extend-btn {
  padding: 8px 16px;
  border: 1px solid;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.cancel-btn {
  background-color: #1A1A1A;
  color: white;
  border-color: #1A1A1A;
}

.save-btn {
  background-color: #DA5761;
  color: white;
  border-color: #DA5761;
}

.extend-btn {
  background-color: #9C3940;
  color: white;
  border-color: #9C3940;
}

.cancel-btn:hover {
  background-color: #333333;
  color: white;
}

.save-btn:hover {
  background-color: #9C3940;
}

.extend-btn:hover {
  background-color: #7a2d32;
}

.save-btn:disabled, .extend-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.edit-btn, .detail-btn {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  margin-right: 8px;
  transition: background-color 0.2s;
}

.edit-btn {
  background-color: #DA5761;
  color: white;
}

.edit-btn:hover {
  background-color: #9C3940;
}

.detail-btn {
  background-color: #1A1A1A;
  color: white;
}

.detail-btn:hover {
  background-color: #333333;
}

.extend-modal {
  max-width: 400px;
}

@media (max-width: 768px) {
  .filters-section {
    flex-direction: column;
    align-items: stretch;
  }
  
  .form-row {
    flex-direction: column;
  }
  
  .modal-content {
    width: 95%;
  }
  
  .industry-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .region-tabs {
    flex-wrap: wrap;
    gap: 12px;
  }
}
</style>
