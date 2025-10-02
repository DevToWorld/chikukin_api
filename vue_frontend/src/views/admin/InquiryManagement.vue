<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <h1 class="page-title">お問い合わせ管理</h1>
        <div class="header-actions">
          <button @click="deleteSelectedInquiries" class="delete-btn" :disabled="selectedInquiries.length === 0">
            選択削除
          </button>
        </div>
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
              <option value="">対応状況</option>
              <option value="new">未対応</option>
              <option value="in_progress">対応中</option>
              <option value="responded">対応済み</option>
            </select>
          </div>
          <div class="filter-group">
            <select v-model="filters.type" class="filter-select">
              <option value="">問い合わせ種別</option>
              <option value="general">一般問い合わせ</option>
              <option value="technical">技術的問題</option>
              <option value="membership">会員について</option>
              <option value="seminar">セミナーについて</option>
              <option value="publication">刊行物について</option>
              <option value="other">その他</option>
            </select>
          </div>
          <button @click="applyFilters" class="apply-btn">絞り込み</button>
        </div>
      </div>

      <!-- 検索セクション -->
      <div class="search-section">
        <div class="search-container">
          <span class="search-label">検索する</span>
          <input 
            v-model="searchKeyword" 
            type="text" 
            placeholder="件名、会社名、お名前で検索"
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
                <th>
                  <input 
                    type="checkbox" 
                    @change="toggleSelectAll"
                    :checked="selectedInquiries.length === inquiries.length && inquiries.length > 0"
                  >
                </th>
                <th>受付日時</th>
                <th>問い合わせID</th>
                <th>お名前</th>
                <th>会社名</th>
                <th>件名</th>
                <th>問い合わせ種別</th>
                <th>対応状況</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="inquiry in inquiries" :key="inquiry.id" class="table-row">
                <td>
                  <input 
                    type="checkbox" 
                    :value="inquiry.id"
                    v-model="selectedInquiries"
                  >
                </td>
                <td>{{ formatDate(inquiry.created_at) }}</td>
                <td class="inquiry-id">#{{ String(inquiry.id).padStart(6, '0') }}</td>
                <td>{{ inquiry.name }}</td>
                <td>{{ inquiry.company || '-' }}</td>
                <td class="subject-cell">
                  <span class="subject-text" :title="inquiry.subject">{{ inquiry.subject }}</span>
                </td>
                <td>
                  <span class="type-badge" :class="inquiry.inquiry_type">
                    {{ getInquiryTypeText(inquiry.inquiry_type) }}
                  </span>
                </td>
                <td>
                  <span class="status-badge" :class="inquiry.status">
                    {{ getStatusText(inquiry.status) }}
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button @click="viewInquiry(inquiry)" class="view-btn" title="詳細表示">
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8 3.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9zM1.5 8a6.5 6.5 0 1 1 13 0 6.5 6.5 0 0 1-13 0z" fill="currentColor"/>
                        <path d="M8 6a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" fill="currentColor"/>
                      </svg>
                    </button>
                    <button 
                      v-if="inquiry.status === 'new' || inquiry.status === 'in_progress'" 
                      @click="markAsResponded(inquiry)" 
                      class="respond-btn" 
                      title="対応済みにする"
                    >
                      ✓
                    </button>
                    <button @click="deleteInquiry(inquiry)" class="delete-btn" title="削除">
                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5zM3.5 6a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.5-.5zm2 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5zm-2 3a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.5-.5z" fill="currentColor"/>
                      </svg>
                    </button>
                  </div>
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
    </div>

    <!-- 詳細モーダル -->
    <div v-if="selectedInquiry" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>お問い合わせ詳細</h3>
          <button @click="closeModal" class="close-btn">×</button>
        </div>
        <div class="modal-body">
          <div class="inquiry-details">
            <div class="detail-row">
              <label>問い合わせID:</label>
              <span>#{{ String(selectedInquiry.id).padStart(6, '0') }}</span>
            </div>
            <div class="detail-row">
              <label>受付日時:</label>
              <span>{{ formatDate(selectedInquiry.created_at) }}</span>
            </div>
            <div class="detail-row">
              <label>お名前:</label>
              <span>{{ selectedInquiry.name }}</span>
            </div>
            <div class="detail-row">
              <label>メールアドレス:</label>
              <span>{{ selectedInquiry.email }}</span>
            </div>
            <div class="detail-row">
              <label>電話番号:</label>
              <span>{{ selectedInquiry.phone || '-' }}</span>
            </div>
            <div class="detail-row">
              <label>会社名:</label>
              <span>{{ selectedInquiry.company || '-' }}</span>
            </div>
            <div class="detail-row">
              <label>問い合わせ種別:</label>
              <span>{{ getInquiryTypeText(selectedInquiry.inquiry_type) }}</span>
            </div>
            <div class="detail-row">
              <label>件名:</label>
              <span>{{ selectedInquiry.subject }}</span>
            </div>
            <div class="detail-row">
              <label>内容:</label>
              <div class="message-content">{{ selectedInquiry.message }}</div>
            </div>
            <div class="detail-row">
              <label>対応状況:</label>
              <span class="status-badge" :class="selectedInquiry.status">
                {{ getStatusText(selectedInquiry.status) }}
              </span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button 
            v-if="selectedInquiry.status === 'new' || selectedInquiry.status === 'in_progress'" 
            @click="markAsResponded(selectedInquiry)"
            class="respond-btn"
          >
            対応済みにする
          </button>
          <button @click="closeModal" class="cancel-btn">閉じる</button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '@/services/apiClient'
import AdminPagination from '@/components/admin/AdminPagination.vue'

export default {
  name: 'InquiryManagement',
  components: {
    AdminLayout,
    AdminPagination
  },
  data() {
    return {
      inquiries: [],
      loading: false,
      error: '',
      authToken: null,
      searchKeyword: '',
      selectedInquiries: [],
      selectedInquiry: null,
      currentPage: 1,
      totalPages: 1,
      filters: {
        year: '',
        month: '',
        status: '',
        type: ''
      }
    }
  },
  async mounted() {
    await this.loadInquiries()
  },
  methods: {
    async loadInquiries() {
      this.loading = true
      this.error = ''
      
      try {
        this.authToken = localStorage.getItem('admin_token')
        if (!this.authToken) {
          throw new Error('管理者認証が必要です')
        }

        const params = {
          page: this.currentPage,
          per_page: 20
        }

        // フィルター追加
        if (this.searchKeyword) {
          params.search = this.searchKeyword
        }
        
        if (this.filters.year) {
          params.year = this.filters.year
        }
        
        if (this.filters.month) {
          params.month = this.filters.month
        }
        
        if (this.filters.status) {
          params.status = this.filters.status
        }
        
        if (this.filters.type) {
          params.inquiry_type = this.filters.type
        }

        const response = await apiClient.getAdminInquiries(params, this.authToken)
        
        if (response.success && response.data) {
          this.inquiries = response.data.inquiries
          this.totalPages = response.data.pagination.total_pages
        } else {
          throw new Error('お問い合わせデータの取得に失敗しました')
        }
      } catch (err) {
        this.error = err.message || 'お問い合わせデータの読み込みに失敗しました'
        console.error('お問い合わせ読み込みエラー:', err)
        
        // フォールバックデータ（開発用）
        this.inquiries = [
          {
            id: 1,
            name: '田中 太郎',
            email: 'tanaka@example.com',
            phone: '090-1234-5678',
            company: '株式会社サンプル',
            subject: 'セミナーに関するお問い合わせ',
            message: 'セミナーの開催日程について教えてください。',
            inquiry_type: 'seminar',
            status: 'pending',
            created_at: '2025-01-28T10:30:00Z'
          },
          {
            id: 2,
            name: '佐藤 花子',
            email: 'sato@example.com',
            phone: '080-9876-5432',
            company: 'サンプル企業',
            subject: '会員登録について',
            message: '会員登録の手続きについて詳しく教えてください。',
            inquiry_type: 'membership',
            status: 'responded',
            created_at: '2025-01-27T14:15:00Z'
          },
          {
            id: 3,
            name: '山田 次郎',
            email: 'yamada@test.com',
            phone: '',
            company: '',
            subject: 'システムの不具合について',
            message: 'ログイン時にエラーが発生します。解決方法を教えてください。',
            inquiry_type: 'technical',
            status: 'pending',
            created_at: '2025-01-26T09:45:00Z'
          }
        ]
        this.totalPages = 1
      } finally {
        this.loading = false
      }
    },

    formatDate(dateString) {
      const date = new Date(dateString)
      const year = date.getFullYear()
      const month = date.getMonth() + 1
      const day = date.getDate()
      const hours = date.getHours()
      const minutes = date.getMinutes()
      
      return `${year}年${month}月${day}日 ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`
    },

    getInquiryTypeText(type) {
      switch (type) {
        case 'general': return '一般問い合わせ'
        case 'technical': return '技術的問題'
        case 'membership': return '会員について'
        case 'seminar': return 'セミナーについて'
        case 'publication': return '刊行物について'
        case 'other': return 'その他'
        default: return type
      }
    },

    getStatusText(status) {
      switch (status) {
        case 'new': return '未対応'
        case 'in_progress': return '対応中'
        case 'responded': return '対応済み'
        default: return status
      }
    },

    async applyFilters() {
      console.log('Applying filters:', this.filters)
      this.currentPage = 1
      await this.loadInquiries()
    },

    async performSearch() {
      console.log('Searching for:', this.searchKeyword)
      this.currentPage = 1
      await this.loadInquiries()
    },

    viewInquiry(inquiry) {
      this.selectedInquiry = inquiry
    },

    closeModal() {
      this.selectedInquiry = null
    },

    async markAsResponded(inquiry) {
      try {
        const response = await apiClient.markInquiryAsResponded(inquiry.id, this.authToken)
        
        if (response.success) {
          inquiry.status = 'responded'
          if (this.selectedInquiry && this.selectedInquiry.id === inquiry.id) {
            this.selectedInquiry.status = 'responded'
          }
          alert('対応済みにマークしました')
        } else {
          throw new Error('更新に失敗しました')
        }
      } catch (err) {
        console.error('ステータス更新エラー:', err)
        alert('ステータスの更新に失敗しました')
      }
    },

    async deleteInquiry(inquiry) {
      if (!confirm(`お問い合わせ「${inquiry.subject}」を削除しますか？`)) {
        return
      }

      try {
        const response = await apiClient.deleteInquiry(inquiry.id, this.authToken)
        
        if (response.success) {
          alert('お問い合わせを削除しました')
          await this.loadInquiries()
        } else {
          throw new Error('削除に失敗しました')
        }
      } catch (err) {
        console.error('お問い合わせ削除エラー:', err)
        alert('お問い合わせの削除に失敗しました')
      }
    },

    async deleteSelectedInquiries() {
      if (this.selectedInquiries.length === 0) {
        alert('削除するお問い合わせを選択してください。')
        return
      }

      if (!confirm(`選択した${this.selectedInquiries.length}件のお問い合わせを削除しますか？`)) {
        return
      }

      try {
        for (const inquiryId of this.selectedInquiries) {
          await apiClient.deleteInquiry(inquiryId, this.authToken)
        }

        alert('選択したお問い合わせを削除しました。')
        this.selectedInquiries = []
        await this.loadInquiries()
      } catch (err) {
        console.error('お問い合わせ削除エラー:', err)
        alert('お問い合わせの削除に失敗しました。')
      }
    },

    toggleSelectAll() {
      if (this.selectedInquiries.length === this.inquiries.length) {
        this.selectedInquiries = []
      } else {
        this.selectedInquiries = this.inquiries.map(i => i.id)
      }
    },

    async changePage(page) {
      if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
        this.currentPage = page
        await this.loadInquiries()
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
  font-size: 20px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.delete-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.filter-section {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.filter-row {
  display: flex;
  gap: 12px;
  align-items: center;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  min-width: 120px;
}

.apply-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.2s;
}

.apply-btn:hover {
  background-color: #555;
}

.search-section {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.search-container {
  display: flex;
  align-items: center;
  gap: 12px;
}

.search-label {
  font-size: 14px;
  color: #666;
  white-space: nowrap;
}

.search-input {
  flex: 1;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.search-btn {
  background-color: #da5761;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.search-btn:hover {
  background-color: #c44853;
}

.table-container {
  padding: 20px 24px;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
}

.error {
  text-align: center;
  padding: 40px;
  color: #dc3545;
}

.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #e5e5e5;
}

.data-table th {
  background-color: #f8f9fa;
  font-weight: 600;
  color: #1A1A1A;
}

.table-row:hover {
  background-color: #f8f9fa;
}

.inquiry-id {
  font-family: monospace;
  font-weight: 600;
  color: #007bff;
}

.subject-cell {
  max-width: 200px;
}

.subject-text {
  display: block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.type-badge,
.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
}

.type-badge.general { background-color: #e3f2fd; color: #1976d2; }
.type-badge.technical { background-color: #fff3e0; color: #f57c00; }
.type-badge.membership { background-color: #f3e5f5; color: #7b1fa2; }
.type-badge.seminar { background-color: #e8f5e8; color: #388e3c; }
.type-badge.publication { background-color: #fff8e1; color: #f57f17; }
.type-badge.other { background-color: #f5f5f5; color: #616161; }

.status-badge.pending { background-color: #ffebee; color: #d32f2f; }
.status-badge.new { background-color: #ffebee; color: #d32f2f; }
.status-badge.in_progress { background-color: #fff8e1; color: #f57c00; }
.status-badge.responded { background-color: #e8f5e8; color: #388e3c; }

.action-buttons {
  display: flex;
  gap: 8px;
}

.view-btn,
.respond-btn,
.delete-btn {
  background: none;
  border: 1px solid #ddd;
  padding: 6px;
  border-radius: 4px;
  cursor: pointer;
  color: #666;
  display: flex;
  align-items: center;
  justify-content: center;
}

.view-btn:hover { color: #007bff; border-color: #007bff; }
.respond-btn:hover { color: #28a745; border-color: #28a745; }
.delete-btn:hover { color: #dc3545; border-color: #dc3545; }

.respond-btn {
  font-weight: bold;
  font-size: 14px;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  padding: 20px 24px;
  border-top: 1px solid #e5e5e5;
}

.page-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.page-btn:hover:not(:disabled) {
  background-color: #555;
}

.page-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.page-info {
  font-size: 14px;
  color: #666;
}

/* モーダルスタイル */
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
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 80vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
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
  color: #666;
}

.close-btn:hover {
  color: #1A1A1A;
}

.modal-body {
  padding: 20px 24px;
}

.inquiry-details {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.detail-row {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.detail-row label {
  font-weight: 600;
  color: #1A1A1A;
  font-size: 14px;
}

.detail-row span {
  color: #666;
}

.message-content {
  background-color: #f8f9fa;
  padding: 12px;
  border-radius: 4px;
  white-space: pre-wrap;
  line-height: 1.5;
  color: #1A1A1A;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 20px 24px;
  border-top: 1px solid #e5e5e5;
}

.respond-btn {
  background-color: #da5761;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.respond-btn:hover {
  background-color: #c44853;
}

.cancel-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.cancel-btn:hover {
  background-color: #555;
}
</style>
