<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <h1 class="page-title">セミナー管理</h1>
        <button @click="createNewSeminar" class="add-btn">追加</button>
      </div>

      <!-- フィルターセクション -->
      <div class="filter-section">
        <div class="filter-row">
          <div class="filter-group">
            <select v-model="filters.year" class="filter-select">
              <option value="">年度</option>
              <option value="2025">2025</option>
              <option value="2024">2024</option>
              <option value="2023">2023</option>
            </select>
          </div>
          <div class="filter-group">
            <select v-model="filters.month" class="filter-select">
              <option value="">月</option>
              <option value="1">1月</option>
              <option value="2">2月</option>
              <option value="3">3月</option>
              <option value="4">4月</option>
              <option value="5">5月</option>
              <option value="6">6月</option>
              <option value="7">7月</option>
              <option value="8">8月</option>
              <option value="9">9月</option>
              <option value="10">10月</option>
              <option value="11">11月</option>
              <option value="12">12月</option>
            </select>
          </div>
          <div class="filter-group">
            <select v-model="filters.status" class="filter-select">
              <option value="">ステータス</option>
              <option value="scheduled">予定</option>
              <option value="ongoing">申し込み受付中</option>
              <option value="completed">終了</option>
              <option value="cancelled">中止</option>
            </select>
          </div>
          <div class="filter-group">
            <select v-model="filters.category" class="filter-select">
              <option value="">カテゴリー</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
          <button @click="applyFilters" class="apply-btn">絞り込み</button>
        </div>
        <!-- カテゴリー管理 -->
        <div class="category-manager">
          <div class="cat-header">
            <h3>カテゴリー管理</h3>
            <div class="cat-actions">
              <input v-model="cat.newName" class="cat-input" placeholder="新しいカテゴリー名" />
              <button @click="addCategory" class="cat-add-btn">追加</button>
            </div>
          </div>
          <div class="cat-list" v-if="categories.length">
            <div class="cat-item" v-for="c in categories" :key="c.id">
              <template v-if="cat.editingId===c.id">
                <input v-model="cat.editingName" class="cat-input" />
                <button @click="saveCategory(c)" class="cat-save-btn">保存</button>
                <button @click="cancelEditCategory" class="cat-cancel-btn">取消</button>
              </template>
              <template v-else>
                <span class="cat-name">{{ c.name }}</span>
                <button @click="startEditCategory(c)" class="cat-edit-btn">編集</button>
                <button @click="deleteCategory(c)" class="cat-del-btn">削除</button>
              </template>
            </div>
          </div>
          <div v-else class="cat-empty">カテゴリーがありません</div>
        </div>
      </div>

      <!-- 検索セクション -->
      <div class="search-section">
        <div class="search-container">
          <span class="search-label">検索する</span>
          <input 
            v-model="searchKeyword" 
            type="text" 
            placeholder="キーワードを入力する"
            class="search-input"
            @keyup.enter="performSearch"
          >
          <button @click="performSearch" class="search-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" fill="currentColor"/>
            </svg>
          </button>
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
                <th>セミナー名</th>
                <th>日時</th>
                <th>会場情報</th>
                <th>ステータス</th>
                <th>受講可</th>
                <th>募集人数</th>
                <th>管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="seminar in filteredSeminars" :key="seminar.id">
                <td class="seminar-name">{{ seminar.title }}</td>
                <td class="seminar-date">
                  {{ formatDate(seminar.date) }}<br>
                  <span class="time">{{ seminar.time || '16:00～ 17:00' }}</span>
                </td>
                <td class="venue">{{ seminar.venue || 'ZOOM（福岡県）' }}</td>
                <td>
                  <span class="status-badge" :class="getStatusClass(seminar.status)">
                    {{ getStatusText(seminar.status) }}
                  </span>
                </td>
                <td class="membership">{{ seminar.membership || '一般' }}</td>
                <td class="capacity">{{ seminar.capacity || '200' }}名</td>
                <td>
                  <button @click="editSeminar(seminar)" class="edit-btn">編集</button>
                  <button @click="openRegistrations(seminar)" class="reg-btn">申込一覧</button>
                </td>
                <td>
                  <input 
                    type="checkbox" 
                    :value="seminar.id" 
                    v-model="selectedSeminars"
                    class="checkbox"
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ページネーション -->
      <AdminPagination
        :page.sync="currentPage"
        :last-page="totalPages"
        @change="changePage"
      />

      <!-- Registrations Modal -->
      <div v-if="showRegModal" class="modal-overlay" @click.self="closeRegModal">
        <div class="modal-box">
          <div class="modal-header">
            <h3>申込一覧 - {{ currentSeminar?.title }}</h3>
            <button class="close" @click="closeRegModal">×</button>
          </div>
          <div class="modal-body">
            <div class="reg-filters">
              <label>
                ステータス:
                <select v-model="regFilter.status" @change="loadRegistrations()">
                  <option value="">すべて</option>
                  <option value="pending">承認待ち</option>
                  <option value="approved">承認</option>
                  <option value="rejected">却下</option>
                </select>
              </label>
              <button class="bulk-approve" @click="bulkApprove" :disabled="regSelection.length===0">選択を承認</button>
            </div>

            <div v-if="regLoading" class="loading">読み込み中...</div>
            <div v-else-if="regError" class="error">{{ regError }}</div>
            <table v-else class="data-table">
              <thead>
                <tr>
                  <th></th>
                  <th>申込番号</th>
                  <th>氏名</th>
                  <th>会社</th>
                  <th>メール</th>
                  <th>電話</th>
                  <th>承認</th>
                  <th>申込日時</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="r in regItems" :key="r.id">
                  <td><input type="checkbox" v-model="regSelection" :value="r.id"></td>
                  <td>{{ r.registration_number }}</td>
                  <td>{{ r.name }}</td>
                  <td>{{ r.company || '-' }}</td>
                  <td>{{ r.email }}</td>
                  <td>{{ r.phone || '-' }}</td>
                  <td>{{ approvalText(r.approval_status) }}</td>
                  <td>{{ formatDateTime(r.created_at) }}</td>
                  <td>
                    <button v-if="r.approval_status!=='approved'" class="approve-btn" @click="approve(r)">承認</button>
                    <button v-if="r.approval_status!=='rejected'" class="reject-btn" @click="reject(r)">却下</button>
                  </td>
                </tr>
                <tr v-if="regItems.length===0"><td colspan="9" class="empty">申込はありません</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import AdminPagination from '@/components/admin/AdminPagination.vue'
import apiClient from '../../services/apiClient.js'
import mockServer from '@/mockServer'

export default {
  name: 'SeminarManagement',
  components: {
    AdminLayout,
    AdminPagination
  },
  data() {
    return {
      seminars: [],
      loading: false,
      error: '',
      searchKeyword: '',
      selectedSeminars: [],
      filters: {
        year: '',
        month: '',
        status: '',
        category: ''
      },
      categories: [],
      cat: { newName:'', editingId:null, editingName:'' },
      currentPage: 1,
      totalPages: 1,
      itemsPerPage: 20,
      authToken: null,
      // registrations modal
      showRegModal: false,
      currentSeminar: null,
      regLoading: false,
      regError: '',
      regItems: [],
      regFilter: { status: '', per_page: 50 },
      regSelection: []
    }
  },
  async mounted() {
    await Promise.all([this.loadCategories(), this.loadSeminars()])
  },
  computed: {
    filteredSeminars() {
      let result = this.seminars
      
      if (this.searchKeyword) {
        const keyword = this.searchKeyword.toLowerCase()
        result = result.filter(seminar => 
          seminar.title.toLowerCase().includes(keyword) ||
          seminar.venue.toLowerCase().includes(keyword)
        )
      }
      
      if (this.filters.status) {
        result = result.filter(seminar => seminar.status === this.filters.status)
      }
      
      return result
    }
  },
  methods: {
    async loadSeminars() {
      this.loading = true
      try {
        // まずmockServerから取得を試みる
        try {
          const allSeminars = await mockServer.getSeminars()
          if (allSeminars && allSeminars.length > 0) {
            const start = (this.currentPage - 1) * this.itemsPerPage
            const end = start + this.itemsPerPage
            
            this.seminars = allSeminars.map(seminar => ({
              id: seminar.id,
              title: seminar.title,
              date: seminar.date,
              time: `${seminar.start_time || '16:00'}～${seminar.end_time || '17:00'}`,
              venue: seminar.location || 'ZOOM（福岡県）',
              status: seminar.status || 'scheduled',
              membership: this.getMembershipText(seminar.membership_requirement),
              capacity: seminar.capacity || 30,
              current_participants: seminar.current_participants || 0,
              description: seminar.description,
              detailed_description: seminar.detailed_description,
              featured_image: seminar.featured_image
            }))
            
            this.totalPages = Math.ceil(this.seminars.length / this.itemsPerPage)
            return
          }
        } catch (mockError) {
          console.log('MockServer failed, trying API')
        }

        // APIから取得
        this.authToken = localStorage.getItem('admin_token')
        if (!this.authToken) {
          throw new Error('管理者認証が必要です')
        }
        
        const params = {
          page: this.currentPage,
          per_page: this.itemsPerPage,
          ...this.filters
        }
        
        const response = await apiClient.getAdminSeminars(params) // トークンは自動で付与される
        if (response.success && response.data) {
          this.seminars = response.data.seminars.map(seminar => ({
            id: seminar.id,
            title: seminar.title,
            date: seminar.date,
            time: `${seminar.start_time || '16:00'}～${seminar.end_time || '17:00'}`,
            venue: seminar.location || 'ZOOM（福岡県）',
            status: seminar.status || 'scheduled',
            membership: this.getMembershipText(seminar.membership_requirement),
            capacity: seminar.capacity || 30,
            current_participants: seminar.current_participants || 0,
            description: seminar.description,
            detailed_description: seminar.detailed_description,
            featured_image: seminar.featured_image
          }))
          
          this.totalPages = response.data.pagination.total_pages
        } else {
          throw new Error('セミナーデータの取得に失敗しました')
        }
      } catch (err) {
        this.error = err.message || 'セミナーデータの読み込みに失敗しました'
        console.error('セミナー読み込みエラー:', err)
      } finally {
        this.loading = false
      }
    },
    async loadCategories() {
      try {
        const res = await apiClient.getSeminarCategories()
        this.categories = (res && res.success) ? (res.data || []) : []
      } catch(e){ this.categories = [] }
    },
    async addCategory(){ if(!this.cat.newName.trim()) return; const r=await apiClient.createSeminarCategory({ name:this.cat.newName }); if(r&&r.success){ this.cat.newName=''; this.loadCategories() } else { alert(r?.message||'追加に失敗') } },
    startEditCategory(c){ this.cat.editingId=c.id; this.cat.editingName=c.name },
    cancelEditCategory(){ this.cat.editingId=null; this.cat.editingName='' },
    async saveCategory(c){ const r=await apiClient.updateSeminarCategory(c.id,{ name:this.cat.editingName }); if(r&&r.success){ this.cancelEditCategory(); this.loadCategories() } else { alert(r?.message||'更新に失敗') } },
    async deleteCategory(c){ if(!confirm(`${c.name} を削除しますか？`)) return; const r=await apiClient.deleteSeminarCategory(c.id); if(r&&r.success){ this.loadCategories() } else { alert(r?.message||'削除に失敗') } },
    
    getMembershipText(requirement) {
      switch (requirement) {
        case 'none': return '一般'
        case 'basic': return 'ベーシック会員以上'
        case 'standard': return 'スタンダード会員以上'
        case 'premium': return 'プレミアム会員限定'
        default: return '一般'
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      const year = date.getFullYear()
      const month = date.getMonth() + 1
      const day = date.getDate()
      const weekdays = ['日', '月', '火', '水', '木', '金', '土']
      const weekday = weekdays[date.getDay()]
      
      return `${year}年${month}月${day}日(${weekday})`
    },
    getStatusClass(status) {
      switch (status) {
        case 'completed': return 'status-completed'
        case 'ongoing': return 'status-ongoing'
        case 'scheduled': return 'status-scheduled'
        case 'cancelled': return 'status-cancelled'
        default: return ''
      }
    },
    getStatusText(status) {
      switch (status) {
        case 'completed': return '終了'
        case 'ongoing': return '申し込み受付中'
        case 'scheduled': return '予定'
        case 'cancelled': return '中止'
        default: return '不明'
      }
    },
    editSeminar(seminar) {
      this.$router.push(`/admin/seminar/edit/${seminar.id}`)
    },
    createNewSeminar() {
      this.$router.push('/admin/seminar/register')
    },
    openRegistrations(seminar) {
      this.currentSeminar = seminar
      this.showRegModal = true
      this.regSelection = []
      this.loadRegistrations()
    },
    closeRegModal() { this.showRegModal = false },
    async loadRegistrations() {
      if (!this.currentSeminar) return
      this.regLoading = true
      this.regError = ''
      try {
        const res = await apiClient.getAdminSeminarRegistrations(this.currentSeminar.id, this.regFilter)
        if (res && res.success) {
          // API returns paginator
          const data = res.data?.data || res.data || []
          this.regItems = Array.isArray(data) ? data : []
        } else {
          this.regError = res?.message || '申込の取得に失敗しました'
        }
      } catch (e) {
        this.regError = 'サーバーエラーが発生しました'
        console.error(e)
      } finally { this.regLoading = false }
    },
    approvalText(s) { return { pending: '承認待ち', approved: '承認', rejected: '却下' }[s] || s },
    formatDateTime(ts) { try { return new Date(ts).toLocaleString('ja-JP') } catch(e) { return '-' } },
    async approve(r) {
      try {
        const res = await apiClient.approveAdminSeminarRegistration(this.currentSeminar.id, r.id)
        if (res && res.success) { r.approval_status = 'approved' }
      } catch(e) { alert('承認に失敗しました') }
    },
    async reject(r) {
      const reason = prompt('却下理由（任意）') || ''
      try {
        const res = await apiClient.rejectAdminSeminarRegistration(this.currentSeminar.id, r.id, reason)
        if (res && res.success) { r.approval_status = 'rejected' }
      } catch(e) { alert('却下に失敗しました') }
    },
    async bulkApprove() {
      if (this.regSelection.length === 0) return
      try {
        const res = await apiClient.bulkApproveAdminSeminarRegistrations(this.currentSeminar.id, this.regSelection)
        if (res && res.success) {
          this.regItems = this.regItems.map(i => this.regSelection.includes(i.id) ? { ...i, approval_status: 'approved' } : i)
          this.regSelection = []
        }
      } catch(e) { alert('一括承認に失敗しました') }
    },
    async applyFilters() {
      console.log('Applying filters:', this.filters)
      this.currentPage = 1
      await this.loadSeminars()
    },
    
    async performSearch() {
      console.log('Searching for:', this.searchKeyword)
      this.currentPage = 1
      await this.loadSeminars()
    },
    
    async deleteSelectedSeminars() {
      if (this.selectedSeminars.length === 0) {
        alert('削除するセミナーを選択してください。')
        return
      }
      
      if (!confirm(`選択した${this.selectedSeminars.length}件のセミナーを削除しますか？`)) {
        return
      }
      
      try {
        for (const seminarId of this.selectedSeminars) {
          await apiClient.deleteSeminar(seminarId, this.authToken)
        }
        
        alert('選択したセミナーを削除しました。')
        this.selectedSeminars = []
        await this.loadSeminars()
      } catch (err) {
        console.error('セミナー削除エラー:', err)
        alert('セミナーの削除に失敗しました。')
      }
    },
    
    toggleSelectAll() {
      if (this.selectedSeminars.length === this.seminars.length) {
        this.selectedSeminars = []
      } else {
        this.selectedSeminars = this.seminars.map(s => s.id)
      }
    },
    
    async changePage(page) {
      if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
        this.currentPage = page
        await this.loadSeminars()
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

.add-btn {
  background-color: #da5761;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-btn:hover {
  background-color: #c44853;
}

.filter-section {
  padding: 16px 24px;
  background-color: #f8f8f8;
  border-bottom: 1px solid #e5e5e5;
}
.category-manager { margin-top:12px; background:#fff; border:1px solid #eee; border-radius:8px; padding:12px; }
.cat-header { display:flex; justify-content:space-between; align-items:center; gap:12px; }
.cat-header h3 { margin:0; font-size:15px; color:#1A1A1A; }
.cat-actions { display:flex; gap:8px; }
.cat-input { padding:6px 10px; border:1px solid #d0d0d0; border-radius:6px; font-size:13px; }
.cat-add-btn, .cat-save-btn, .cat-cancel-btn, .cat-edit-btn, .cat-del-btn { padding:6px 10px; border:none; border-radius:6px; cursor:pointer; font-size:12px; }
.cat-add-btn, .cat-save-btn { background:#da5761; color:#fff; }
.cat-edit-btn { background:#1A1A1A; color:#fff; }
.cat-del-btn { background:#fff; color:#da5761; border:1px solid #da5761; }
.cat-del-btn:hover { background:#da5761; color:#fff; }
.cat-list { display:flex; flex-wrap:wrap; gap:8px; margin-top:8px; }
.cat-item { background:#fafafa; border:1px solid #eee; border-radius:8px; padding:8px; display:flex; gap:8px; align-items:center; }
.cat-name { font-size:13px; color:#333; }
.cat-empty { color:#777; font-size:13px; margin-top:8px; }

.filter-row {
  display: flex;
  align-items: center;
  gap: 16px;
}

.filter-group {
  position: relative;
}

.filter-select {
  background-color: white;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  padding: 8px 12px;
  font-size: 14px;
  color: #1A1A1A;
  min-width: 120px;
  cursor: pointer;
}

.apply-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.apply-btn:hover {
  background-color: #555;
}

.search-section {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.search-container {
  display: flex;
  align-items: center;
  gap: 12px;
  max-width: 500px;
  margin-left: auto;
}

.search-label {
  font-size: 14px;
  color: #1A1A1A;
  white-space: nowrap;
}

.search-input {
  flex: 1;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  padding: 8px 12px;
  font-size: 14px;
}

.search-btn {
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
  color: #666;
  transition: color 0.2s;
}

.search-btn:hover {
  color: #1A1A1A;
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

.seminar-name {
  color: #1A1A1A;
  font-weight: 500;
  max-width: 300px;
}

.seminar-date {
  color: #666;
}

.venue {
  color: #666;
  max-width: 200px;
  font-size: 12px;
}

.time {
  font-size: 12px;
  color: #999;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.status-completed {
  background-color: #e5e7eb;
  color: #374151;
}

.status-ongoing {
  background-color: #dbeafe;
  color: #1e40af;
}

.status-scheduled {
  background-color: #fef3c7;
  color: #92400e;
}

.status-cancelled {
  background-color: #fee2e2;
  color: #dc2626;
}

.membership {
  color: #666;
  font-size: 12px;
}

.capacity {
  color: #666;
  font-size: 12px;
}

.edit-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.reg-btn {
  margin-left: 8px;
  background-color: #da5761;
  color: #fff;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.reg-btn:hover {
  background-color: #c44853;
}

.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; z-index: 2000; }
.modal-box { width: min(980px, 96vw); background:#fff; border-radius: 10px; overflow: hidden; }
.modal-header { display:flex; align-items:center; justify-content: space-between; padding: 12px 16px; background:#f8f8f8; border-bottom:1px solid #eee; }
.modal-header h3 { margin: 0; font-size: 16px; }
.modal-header .close { background:none; border:none; font-size:18px; cursor:pointer; }
.modal-body { padding: 16px; }
.reg-filters { display:flex; align-items:center; gap: 12px; margin-bottom: 12px; }
.bulk-approve { background:#da5761; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer; transition: background-color 0.2s; }
.bulk-approve:hover { background:#c44853; }

.edit-btn:hover {
  background-color: #555;
}

.checkbox {
  width: 16px;
  height: 16px;
  cursor: pointer;
}

.pagination {
  padding: 16px 24px;
  text-align: center;
  border-top: 1px solid #e5e5e5;
}

.page-btn {
  background-color: white;
  border: 1px solid #d0d0d0;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 14px;
  color: #1A1A1A;
  cursor: pointer;
  transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
  background-color: #f8f8f8;
  border-color: #1A1A1A;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-number {
  background-color: white;
  border: 1px solid #d0d0d0;
  padding: 6px 12px;
  margin: 0 4px;
  border-radius: 4px;
  font-size: 14px;
  color: #1A1A1A;
  cursor: pointer;
  transition: all 0.2s;
}

.page-number:hover {
  background-color: #f8f8f8;
  border-color: #1A1A1A;
}

.page-number.active {
  background-color: #da5761;
  border-color: #da5761;
  color: white;
}

.page-dots {
  padding: 6px 8px;
  color: #666;
}
</style>
