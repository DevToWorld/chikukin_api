<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <h1 class="page-title">お知らせ管理</h1>
        <button @click="createNewNotice" class="add-btn">追加</button>
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
              <option v-for="category in categories" :key="category.id" :value="category.slug">
                {{ category.name }}
              </option>
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
                <th>刊行物名</th>
                <th>投稿日時</th>
                <th>カテゴリー</th>
                <th>管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="notice in filteredNotices" :key="notice.id">
                <td class="notice-title">{{ notice.title }}</td>
                <td class="notice-date">
                  {{ formatDate(notice.date) }}<br>
                  <span class="time">16:00～ 17:00</span>
                </td>
                <td class="category">{{ notice.category }}</td>
                <td>
                  <button @click="editNotice(notice)" class="edit-btn">編集</button>
                </td>
                <td>
                  <input 
                    type="checkbox" 
                    :value="notice.id" 
                    v-model="selectedNotices"
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
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '../../services/apiClient.js'
import AdminPagination from '@/components/admin/AdminPagination.vue'

export default {
  name: 'NoticeManagement',
  components: {
    AdminLayout,
    AdminPagination
  },
  data() {
    return {
      notices: [],
      loading: false,
      error: '',
      searchKeyword: '',
      selectedNotices: [],
      filters: {
        year: '',
        month: '',
        category: ''
      },
      categories: [],
      currentPage: 1,
      totalPages: 1,
      itemsPerPage: 20,
      authToken: null,
      cat: { newName:'', editingId:null, editingName:'' }
    }
  },
  async mounted() {
    await Promise.all([
      this.loadCategories(),
      this.loadNotices()
    ])
  },
  computed: {
    filteredNotices() {
      let result = this.notices
      
      if (this.searchKeyword) {
        const keyword = this.searchKeyword.toLowerCase()
        result = result.filter(notice => 
          notice.title.toLowerCase().includes(keyword) ||
          notice.description.toLowerCase().includes(keyword)
        )
      }
      
      if (this.filters.year) {
        result = result.filter(notice => new Date(notice.date).getFullYear().toString() === this.filters.year)
      }
      
      if (this.filters.month) {
        result = result.filter(notice => (new Date(notice.date).getMonth() + 1).toString() === this.filters.month)
      }
      
      if (this.filters.category) {
        result = result.filter(notice => notice.category === this.filters.category)
      }
      
      return result
    }
  },
  methods: {
    async loadCategories() {
      try {
        const res = await apiClient.getNewsCategories()
        const list = (res && res.success) ? (Array.isArray(res.data) ? res.data : []) : []
        this.categories = list
      } catch (err) {
        console.error('カテゴリー読み込みエラー:', err)
        this.categories = []
      }
    },
    async addCategory() {
      if (!this.cat.newName.trim()) return
      const res = await apiClient.createNoticeCategory({ name: this.cat.newName })
      if (res && res.success) { this.cat.newName=''; this.loadCategories() } else { alert(res?.message||'追加に失敗しました') }
    },
    startEditCategory(c){ this.cat.editingId=c.id; this.cat.editingName=c.name },
    cancelEditCategory(){ this.cat.editingId=null; this.cat.editingName='' },
    async saveCategory(c){ const res= await apiClient.updateNoticeCategory(c.id,{ name:this.cat.editingName }); if(res&&res.success){ this.cancelEditCategory(); this.loadCategories() } else { alert(res?.message||'更新に失敗しました') } },
    async deleteCategory(c){ if(!confirm(`${c.name} を削除しますか？`)) return; const res= await apiClient.deleteNoticeCategory(c.id); if(res&&res.success){ this.loadCategories() } else { alert(res?.message||'削除に失敗しました') } },

    async loadNotices() {
      this.loading = true
      try {
        const params = { page: this.currentPage, per_page: this.itemsPerPage }
        const response = await apiClient.get('/api/admin/notices', { params })
        // apiClient wraps non-success into {success:true, data:paginator}
        const paginator = response?.data || response
        const items = paginator?.data || []
        this.notices = items.map(n => ({
          id: n.id,
          title: n.title,
          date: n.published_at || n.created_at,
          category: n.category || 'notice',
          description: n.summary || n.content || '',
          status: n.is_published ? 'published' : 'draft',
          is_featured: !!n.is_featured
        }))
        this.totalPages = paginator?.last_page || 1
      } catch (err) {
        this.error = err.message || 'お知らせデータの読み込みに失敗しました'
        console.error('お知らせ読み込みエラー:', err)
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
    editNotice(notice) {
      this.$router.push(`/admin/news/edit/${notice.id}`)
    },
    createNewNotice() {
      this.$router.push('/admin/news/register')
    },
    applyFilters() {
      this.currentPage = 1
      this.loadNotices()
    },
    performSearch() {
      this.currentPage = 1
      this.loadNotices()
    },
    getCategoryName(slug) {
      const category = this.categories.find(c => c.slug === slug)
      return category ? category.name : slug
    },
    getCategoryColor(slug) {
      const category = this.categories.find(c => c.slug === slug)
      return category ? category.color : '#da5761'
    },
    async changePage(page) {
      if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
        this.currentPage = page
        await this.loadNotices()
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

.notice-title {
  color: #1A1A1A;
  font-weight: 500;
  max-width: 400px;
}

.notice-date {
  color: #666;
}

.category {
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
