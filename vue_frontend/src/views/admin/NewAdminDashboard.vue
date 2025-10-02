<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <h1 class="page-title">各ページ管理</h1>
        <button @click="createNewPage" class="add-btn">新規ページ作成</button>
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
            <select v-model="filters.category" class="filter-select">
              <option value="">カテゴリー</option>
              <option value="news">お知らせ</option>
              <option value="research">研究レポート</option>
              <option value="seminar">セミナー</option>
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
                <th>ページ</th>
                <th>アップロード日時</th>
                <th>管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="page in paginatedPages" :key="page.pageKey">
                <td class="page-name">
                  <div>{{ page.title || page.pageKey }}</div>
                  <div style="font-size:12px;color:#666;">key: {{ page.pageKey }}</div>
                </td>
                <td class="upload-date">
                  {{ formatDate(page.lastUpdated) }}<br>
                  <span class="time">16:00～ 17:00</span>
                </td>
                <td>
                  <button @click="editPage(page)" class="edit-btn">編集</button>
                  <button @click="editInCms(page)" class="edit-btn" style="margin-left:8px;">CMS編集</button>
                </td>
                <td>
                  <input 
                    type="checkbox" 
                    :value="page.pageKey" 
                    v-model="selectedPages"
                    class="checkbox"
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ページネーション -->
      <div class="pagination" v-if="totalPages > 1">
        <button 
          @click="currentPage = 1" 
          :disabled="currentPage === 1"
          class="page-btn"
        >
          最初
        </button>
        <button 
          @click="currentPage--" 
          :disabled="currentPage === 1"
          class="page-btn"
        >
          前へ
        </button>
        
        <template v-for="page in totalPages">
          <button 
            v-if="page <= 3 || page > totalPages - 3 || Math.abs(page - currentPage) <= 1"
            @click="currentPage = page"
            :class="['page-number', { active: page === currentPage }]"
            :key="`btn-${page}`"
          >
            {{ page }}
          </button>
          <span 
            v-else-if="page === 4 && currentPage > 5"
            class="page-dots"
            :key="`dots-${page}-start`"
          >
            ...
          </span>
          <span 
            v-else-if="page === totalPages - 3 && currentPage < totalPages - 4"
            class="page-dots"
            :key="`dots-${page}-end`"
          >
            ...
          </span>
        </template>
        
        <button 
          @click="currentPage++" 
          :disabled="currentPage === totalPages"
          class="page-btn"
        >
          次へ
        </button>
        <button 
          @click="currentPage = totalPages" 
          :disabled="currentPage === totalPages"
          class="page-btn"
        >
          最後
        </button>
        
        <span class="page-info">{{ filteredPages.length }}件中 {{ (currentPage - 1) * itemsPerPage + 1 }}-{{ Math.min(currentPage * itemsPerPage, filteredPages.length) }}件を表示</span>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import mockServer from '@/mockServer'

export default {
  name: 'NewAdminDashboard',
  components: {
    AdminLayout
  },
  data() {
    return {
      pages: [],
      loading: false,
      error: '',
      searchKeyword: '',
      selectedPages: [],
      filters: {
        year: '',
        month: '',
        category: ''
      },
      currentPage: 1,
      itemsPerPage: 10
    }
  },
  computed: {
    filteredPages() {
      let result = this.pages
      
      if (this.searchKeyword) {
        const keyword = this.searchKeyword.toLowerCase()
        result = result.filter(page => 
          (page.title && page.title.toLowerCase().includes(keyword)) ||
          (page.pageKey && page.pageKey.toLowerCase().includes(keyword))
        )
      }
      
      if (this.filters.year) {
        result = result.filter(page => {
          const date = new Date(page.lastUpdated)
          return date.getFullYear().toString() === this.filters.year
        })
      }
      
      if (this.filters.month) {
        result = result.filter(page => {
          const date = new Date(page.lastUpdated)
          return (date.getMonth() + 1).toString() === this.filters.month
        })
      }
      
      if (this.filters.category) {
        result = result.filter(page => {
          if (this.filters.category === 'news' && page.pageKey.includes('news')) return true
          if (this.filters.category === 'research' && page.pageKey.includes('publication')) return true
          if (this.filters.category === 'seminar' && page.pageKey.includes('seminar')) return true
          return false
        })
      }
      
      return result
    },
    paginatedPages() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredPages.slice(start, end)
    },
    totalPages() {
      return Math.ceil(this.filteredPages.length / this.itemsPerPage)
    }
  },
  async mounted() {
    console.log('NewAdminDashboard mounted')
    console.log('localStorage keys:', Object.keys(localStorage))
    console.log('admin_token:', localStorage.getItem('admin_token'))
    console.log('admin_token check:', localStorage.getItem('admin_token'))
    await this.fetchPages()
  },
  methods: {
    async fetchPages() {
      this.loading = true
      this.error = ''

      try {
        console.log('Fetching pages from mockServer...')
        const data = await mockServer.getPages()
        console.log('Pages received:', data)
        this.pages = data
        console.log('Pages set to component:', this.pages)
      } catch (err) {
        if (err.message === 'Admin token not found') {
          console.log('Redirecting to login page...')
          this.$router.push('/admin')
          return
        }
        this.error = 'ページの取得に失敗しました'
        console.error('Error fetching pages:', err)
      } finally {
        this.loading = false
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
    editPage(page) {
      this.$router.push(`/admin/pages/${page.pageKey}/edit`)
    },
    editInCms(page) {
      const slug = page.pageKey || page.slug || ''
      if (!slug) return
      this.$router.push(`/admin/cms-v2/${slug}`)
    },
    createNewPage() {
      this.$router.push('/admin/pages/new')
    },
    applyFilters() {
      this.currentPage = 1
      console.log('Applying filters:', this.filters)
    },
    performSearch() {
      // 検索処理は computed で自動的に処理される
      console.log('Searching for:', this.searchKeyword)
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

.page-name {
  color: #1A1A1A;
  font-weight: 500;
}

.upload-date {
  color: #666;
}

.time {
  font-size: 12px;
  color: #999;
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
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  border-top: 1px solid #e5e5e5;
  flex-wrap: wrap;
}

.page-btn {
  padding: 6px 12px;
  border: 1px solid #d0d0d0;
  background: white;
  color: #1A1A1A;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
  background-color: #f8f8f8;
  border-color: #da5761;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-number {
  padding: 6px 10px;
  border: 1px solid #d0d0d0;
  background: white;
  color: #1A1A1A;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  min-width: 32px;
  transition: all 0.2s;
}

.page-number:hover {
  background-color: #f8f8f8;
  border-color: #da5761;
}

.page-number.active {
  background-color: #da5761;
  color: white;
  border-color: #da5761;
}

.page-dots {
  padding: 0 4px;
  color: #666;
}

.page-info {
  margin-left: 16px;
  font-size: 14px;
  color: #666;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    gap: 16px;
    align-items: stretch;
  }
  
  .filter-row {
    flex-direction: column;
    gap: 12px;
  }
  
  .search-container {
    max-width: 100%;
  }
  
  .table-container {
    font-size: 12px;
  }
  
  .data-table th,
  .data-table td {
    padding: 8px;
  }
  
  .page-name {
    max-width: 200px;
    word-break: break-word;
  }
}

@media (max-width: 480px) {
  .page-header,
  .filter-section,
  .search-section {
    padding: 12px 16px;
  }
  
  .filter-select {
    min-width: 100px;
  }
  
  .search-input {
    font-size: 16px; /* iOS zoom防止 */
  }
}
</style>
