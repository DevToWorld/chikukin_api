<template>
  <div class="member-directory-page">
    <Navigation />
    
    <div class="page-content">
      <div class="container">
        <!-- ページヘッダー -->
        <div class="page-header">
          <h1 class="page-title">会員名簿</h1>
          <p class="page-description">
            スタンダード会員以上の方は、会員名簿を閲覧できます。
            <span v-if="memberInfo && memberInfo.membership_type === 'premium'" class="premium-note">
              プレミアム会員はCSVエクスポートも可能です。
            </span>
          </p>
        </div>

        <!-- アクセス制限メッセージ -->
        <div v-if="!hasDirectoryAccess" class="access-restricted">
          <div class="restriction-card">
            <h3>🔒 アクセス制限</h3>
            <p>会員名簿の閲覧はスタンダード会員以上でご利用いただけます。</p>
            <p>現在の会員種別: {{ getMembershipLabel(memberInfo?.membership_type) }}</p>
            <div class="actions">
              <button v-if="!memberInfo" @click="$router.push('/login?redirect=/member-directory')" class="login-btn">
                ログインする
                <div class="pdf-icon-wrapper">
                  <img class="pdf-icon" src="/img/login-arrow-icon.svg" alt="ログイン" width="24" height="24" />
                </div>
              </button>
              <button @click="$router.push('/upgrade')" class="upgrade-btn">
                アップグレードする
              </button>
            </div>
          </div>
        </div>

        <!-- 会員名簿本体 -->
        <div v-else>
          <!-- 検索・フィルター -->
          <div class="search-filters">
            <div class="search-row">
              <div class="search-input-group">
                <input 
                  v-model="searchQuery" 
                  type="text" 
                  placeholder="会社名、代表者名、住所で検索..."
                  class="search-input"
                  @input="debouncedSearch"
                >
                <button @click="loadMembers" class="search-btn">検索</button>
              </div>
              
              <div class="filter-group">
                <select v-model="membershipFilter" @change="loadMembers" class="filter-select">
                  <option v-for="option in membershipOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
              </div>
              <div class="filter-group">
                <select v-model="regionFilter" @change="loadMembers" class="filter-select">
                  <option v-for="option in regionOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
              </div>
              <div class="filter-group">
                <select v-model="industryFilter" @change="loadMembers" class="filter-select">
                  <option v-for="option in industryOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
              </div>

              <div class="actions-group">
                <button @click="toggleFavoritesOnly" :class="['filter-btn', { active: showFavoritesOnly }]">
                  {{ showFavoritesOnly ? 'すべて表示' : 'お気に入りのみ' }}
                </button>
                
                <button 
                  v-if="memberInfo && canAccess(memberInfo.membership_type, 'premium', true)" 
                  @click="exportCSV" 
                  class="export-btn"
                  :disabled="exporting"
                >
                  {{ exporting ? 'エクスポート中...' : 'CSV出力' }}
                </button>
              </div>
            </div>
          </div>

          <!-- ローディング・エラー -->
          <div v-if="loading" class="loading">読み込み中...</div>
          <div v-else-if="error" class="error">{{ error }}</div>

          <!-- 会員一覧 -->
          <div v-else class="members-grid">
            <div 
              v-for="member in members" 
              :key="member.id" 
              class="member-card"
              @click="viewMemberDetail(member)"
            >
              <div class="member-header">
                <div class="member-info">
                  <h3 class="company-name">{{ member.company_name }}</h3>
                  <p class="representative-name">{{ member.representative_name }}</p>
                </div>
                <div class="member-actions">
                  <button 
                    @click.stop="toggleFavorite(member)"
                    :class="['favorite-btn', { active: member.is_favorite }]"
                    :disabled="member.favoriteLoading"
                  >
                    {{ member.is_favorite ? '★' : '☆' }}
                  </button>
                  <span :class="['membership-badge', `membership-${member.membership_type}`]">
                    {{ getMembershipLabel(member.membership_type) }}
                  </span>
                </div>
              </div>
              
              <div class="member-details">
                <p class="contact-info">
                  <span class="icon">📧</span>
                  {{ member.email }}
                </p>
                <p v-if="member.phone" class="contact-info">
                  <span class="icon">📞</span>
                  {{ member.phone }}
                </p>
                <p v-if="member.address" class="address">
                  <span class="icon">📍</span>
                  {{ member.address }}
                </p>
                <p class="join-date">
                  入会日: {{ formatDate(member.joined_at) }}
                </p>
              </div>
            </div>

            <!-- 空の状態 -->
            <div v-if="members.length === 0" class="empty-state">
              <h3>会員が見つかりません</h3>
              <p v-if="showFavoritesOnly">お気に入りに登録された会員はいません。</p>
              <p v-else-if="searchQuery">検索条件に一致する会員が見つかりません。</p>
              <p v-else>会員がいません。</p>
            </div>
          </div>

          <!-- ページネーション -->
          <div v-if="pagination.total > pagination.per_page" class="pagination">
            <button 
              v-for="page in paginationPages" 
              :key="page"
              @click="loadMembers(page)"
              :class="['page-btn', { active: page === pagination.current_page }]"
              :disabled="loading"
            >
              {{ page }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 会員詳細モーダル -->
    <div v-if="selectedMember" class="modal-overlay" @click="closeDetailModal">
      <div class="modal-content member-detail-modal" @click.stop>
        <div class="modal-header">
          <h3>会員詳細</h3>
          <button @click="closeDetailModal" class="close-btn">×</button>
        </div>
        
        <div class="modal-body">
          <div class="member-detail-content">
            <div class="detail-section">
              <h4>基本情報</h4>
              <div class="detail-grid">
                <div class="detail-item">
                  <label>会社名</label>
                  <span>{{ selectedMember.company_name }}</span>
                </div>
                <div class="detail-item">
                  <label>代表者名</label>
                  <span>{{ selectedMember.representative_name }}</span>
                </div>
                <div class="detail-item">
                  <label>会員種別</label>
                  <span :class="['membership-badge', `membership-${selectedMember.membership_type}`]">
                    {{ getMembershipLabel(selectedMember.membership_type) }}
                  </span>
                </div>
              </div>
            </div>

            <div class="detail-section">
              <h4>連絡先</h4>
              <div class="detail-grid">
                <div class="detail-item">
                  <label>メールアドレス</label>
                  <span>{{ selectedMember.email }}</span>
                </div>
                <div v-if="selectedMember.phone" class="detail-item">
                  <label>電話番号</label>
                  <span>{{ selectedMember.phone }}</span>
                </div>
                <div v-if="selectedMember.address" class="detail-item">
                  <label>住所</label>
                  <span>{{ selectedMember.address }}</span>
                </div>
              </div>
            </div>

            <div class="detail-section">
              <h4>その他</h4>
              <div class="detail-grid">
                <div class="detail-item">
                  <label>入会日</label>
                  <span>{{ formatDate(selectedMember.joined_at) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button @click="closeDetailModal" class="cancel-btn">閉じる</button>
          <button 
            @click="toggleFavorite(selectedMember)"
            :class="['favorite-action-btn', { active: selectedMember.is_favorite }]"
            :disabled="selectedMember.favoriteLoading"
          >
            {{ selectedMember.is_favorite ? 'お気に入りから削除' : 'お気に入りに追加' }}
          </button>
        </div>
      </div>
    </div>

    <FooterComplete />
  </div>
</template>

<script>
import Navigation from '@/components/Navigation.vue'
import FooterComplete from '@/components/FooterComplete.vue'
import { useMemberAuth } from '@/composables/useMemberAuth'
import apiClient from '@/services/apiClient.js'
import { getMembershipOptions, getMembershipLabel, canAccess } from '@/utils/membershipTypes'

export default {
  name: 'MemberDirectoryPage',
  components: {
    Navigation,
    FooterComplete
  },
  data() {
    return {
      memberInfo: null,
      hasDirectoryAccess: false,
      loading: false,
      error: '',
      exporting: false,
      
      // 検索・フィルター
      searchQuery: '',
      membershipFilter: '',
      showFavoritesOnly: false,
      
      // データ
      members: [],
      
      // ページネーション
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 20,
        total: 0
      },
      
      // モーダル
      selectedMember: null,
      
      // デバウンス用
      searchTimeout: null,
      
      // 会員種別選択肢
      membershipOptions: getMembershipOptions(),
      // 地域・業種フィルター
      regionFilter: '',
      industryFilter: '',
      regionOptions: [
        { value: '', label: '全ての地域' },
        { value: '福岡', label: '福岡' },
        { value: '佐賀', label: '佐賀' },
        { value: '長崎', label: '長崎' },
        { value: '大分', label: '大分' },
        { value: '熊本', label: '熊本' },
        { value: '宮崎', label: '宮崎' },
        { value: '鹿児島', label: '鹿児島' }
      ],
      industryOptions: [
        { value: '', label: '全ての業種' },
        { value: '製造業', label: '製造業' },
        { value: '鉱業', label: '鉱業' },
        { value: '建設業', label: '建設業' },
        { value: '運輸交通業', label: '運輸交通業' },
        { value: '官公署', label: '官公署' },
        { value: '貨物取扱業', label: '貨物取扱業' },
        { value: '農林業', label: '農林業' },
        { value: '畜産・水産業', label: '畜産・水産業' },
        { value: '商業', label: '商業' },
        { value: '金融・広告業', label: '金融・広告業' },
        { value: '清掃・と畜業', label: '清掃・と畜業' },
        { value: '映画・演劇業', label: '映画・演劇業' },
        { value: '通信業', label: '通信業' },
        { value: '教育・研究業', label: '教育・研究業' },
        { value: '保健衛生業', label: '保健衛生業' },
        { value: '接客娯楽業', label: '接客娯楽業' },
        { value: 'その他の事業', label: 'その他の事業' }
      ]
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
  async mounted() {
    await this.initializeAuth()
    if (this.hasDirectoryAccess) {
      this.loadMembers()
    }
  },
  methods: {
    async initializeAuth() {
      const { getMemberInfo } = useMemberAuth()
      try {
        // ローカル優先
        this.memberInfo = getMemberInfo()
        const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken')
        if (!this.memberInfo && token) {
          // 1st: member/my-profile（推奨）
          let res = await apiClient.get('/api/member/my-profile')
          if (res && res.success && res.data?.membership_type) {
            this.memberInfo = res.data
            localStorage.setItem('memberUser', JSON.stringify(res.data))
          } else {
            // 2nd: member-auth/me（互換）
            res = await apiClient.get('/api/member-auth/me')
            if (res && res.success && res.member?.membership_type) {
              this.memberInfo = res.member
              localStorage.setItem('memberUser', JSON.stringify(res.member))
            }
          }
        }
      } catch (e) {
        console.warn('会員情報の取得に失敗:', e)
      }

      // アクセス権判定
      this.hasDirectoryAccess = !!(this.memberInfo && canAccess(this.memberInfo.membership_type, 'standard'))
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
        if (this.membershipFilter) params.membership_type = this.membershipFilter
        if (this.regionFilter) params.region = this.regionFilter
        if (this.industryFilter) params.industry = this.industryFilter

        const response = await apiClient.get('/api/member/directory', { params })

        if (response.success) {
          let membersData = response.data
          
          // お気に入りのみ表示フィルター
          if (this.showFavoritesOnly) {
            membersData = membersData.filter(member => member.is_favorite)
          }

          this.members = membersData.map(member => ({
            ...member,
            favoriteLoading: false
          }))
          
          this.pagination = {
            current_page: response.current_page,
            last_page: response.last_page,
            per_page: response.per_page,
            total: response.total
          }
        } else {
          this.error = response.message || '会員名簿の取得に失敗しました'
        }
      } catch (error) {
        this.error = 'サーバーエラーが発生しました'
        console.error('Failed to load members:', error)
      } finally {
        this.loading = false
      }
    },

    debouncedSearch() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.loadMembers()
      }, 500)
    },

    toggleFavoritesOnly() {
      this.showFavoritesOnly = !this.showFavoritesOnly
      this.loadMembers()
    },

    async toggleFavorite(member) {
      if (member.favoriteLoading) return

      member.favoriteLoading = true

      try {
        if (member.is_favorite) {
          // お気に入りから削除
          const response = await apiClient.delete(`/api/member/favorites/${member.id}`)
          if (response.success) {
            member.is_favorite = false
          } else {
            throw new Error(response.message)
          }
        } else {
          // お気に入りに追加
          const response = await apiClient.post(`/api/member/favorites/${member.id}`)
          if (response.success) {
            member.is_favorite = true
          } else {
            throw new Error(response.message)
          }
        }
      } catch (error) {
        console.error('お気に入り操作に失敗:', error)
        alert('お気に入り操作に失敗しました')
      } finally {
        member.favoriteLoading = false
      }
    },

    async exportCSV() {
      this.exporting = true
      
      try {
        const params = {}
        if (this.searchQuery) params.search = this.searchQuery
        if (this.membershipFilter) params.membership_type = this.membershipFilter
        if (this.regionFilter) params.region = this.regionFilter
        if (this.industryFilter) params.industry = this.industryFilter
        if (this.showFavoritesOnly) params.favorites_only = 1

        const response = await apiClient.get('/api/member/directory/export/csv', { params, responseType: 'blob' })

        // CSVファイルをダウンロード
        const blob = response.data instanceof Blob ? response.data : new Blob([response.data || ''], { type: 'text/csv' })
        const url = window.URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `会員名簿_${new Date().toISOString().slice(0, 10)}.csv`
        link.click()
        window.URL.revokeObjectURL(url)
      } catch (error) {
        console.error('CSVエクスポートに失敗:', error)
        alert('CSVエクスポートに失敗しました')
      } finally {
        this.exporting = false
      }
    },

    viewMemberDetail(member) {
      this.selectedMember = { ...member }
    },

    closeDetailModal() {
      this.selectedMember = null
    },

    getMembershipLabel(type) {
      return getMembershipLabel(type)
    },
    
    canAccess(currentType, requiredType, exact = false) {
      return canAccess(currentType, requiredType, exact)
    },

    formatDate(dateString) {
      if (!dateString) return '-'
      const date = new Date(dateString)
      return date.toLocaleDateString('ja-JP')
    }
  }
}
</script>

<style scoped>
.member-directory-page {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.page-content {
  padding: 40px 0;
  min-height: calc(100vh - 120px);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* ページヘッダー */
.page-header {
  text-align: center;
  margin-bottom: 40px;
}

.page-title {
  font-size: 32px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 16px 0;
}

.page-description {
  font-size: 16px;
  color: #666;
  margin: 0;
}

.premium-note {
  color: #f57c00;
  font-weight: 500;
}

/* アクセス制限 */
.access-restricted {
  display: flex;
  justify-content: center;
  margin-top: 60px;
}

.restriction-card {
  background: white;
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-width: 400px;
}

.restriction-card h3 {
  font-size: 24px;
  margin: 0 0 16px 0;
  color: #1a1a1a;
}

.restriction-card p {
  margin: 8px 0;
  color: #666;
}

.upgrade-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  margin-top: 20px;
}

.upgrade-btn:hover {
  background-color: #0056b3;
}

/* 検索・フィルター */
.search-filters {
  background: white;
  border-radius: 8px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.search-row {
  display: flex;
  gap: 16px;
  align-items: center;
  flex-wrap: wrap;
}

.search-input-group {
  flex: 1;
  display: flex;
  gap: 8px;
  min-width: 300px;
}

.search-input {
  flex: 1;
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
}

.search-btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}

.filter-group, .actions-group {
  display: flex;
  gap: 12px;
}

.filter-select {
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
}

.filter-btn, .export-btn {
  padding: 10px 16px;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: white;
  cursor: pointer;
  font-size: 14px;
}

.filter-btn.active {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
}

.export-btn {
  background-color: #28a745;
  color: white;
  border-color: #28a745;
}

.export-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* ローディング・エラー */
.loading, .error {
  text-align: center;
  padding: 40px;
  font-size: 16px;
}

.error {
  color: #dc3545;
  background: #f8d7da;
  border-radius: 6px;
}

/* 会員一覧 */
.members-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.member-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.2s;
}

.member-card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  transform: translateY(-2px);
}

.member-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.member-info h3 {
  font-size: 18px;
  font-weight: 600;
  margin: 0 0 4px 0;
  color: #1a1a1a;
}

.representative-name {
  font-size: 14px;
  color: #666;
  margin: 0;
}

.member-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.favorite-btn {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: #ddd;
  transition: color 0.2s;
}

.favorite-btn.active {
  color: #ffc107;
}

.membership-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

.membership-free { background-color: #f8f9fa; color: #6c757d; }
.membership-standard { background-color: #e8f5e8; color: #388e3c; }
.membership-premium { background-color: #fff3e0; color: #f57c00; }

.member-details {
  space-y: 8px;
}

.contact-info, .address, .join-date {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #666;
  margin: 8px 0;
}

.icon {
  width: 16px;
}

/* 空の状態 */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  grid-column: 1 / -1;
}

.empty-state h3 {
  font-size: 20px;
  color: #1a1a1a;
  margin: 0 0 8px 0;
}

.empty-state p {
  color: #666;
  margin: 0;
}

/* ページネーション */
.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 40px;
}

.page-btn {
  padding: 8px 12px;
  border: 1px solid #ddd;
  background: white;
  cursor: pointer;
  border-radius: 4px;
}

.page-btn:hover {
  background-color: #f5f5f5;
}

.page-btn.active {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
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
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.member-detail-modal {
  max-width: 700px;
}

.modal-header {
  padding: 20px 24px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
}

.modal-body {
  padding: 24px;
}

.detail-section {
  margin-bottom: 24px;
}

.detail-section h4 {
  font-size: 16px;
  font-weight: 600;
  margin: 0 0 12px 0;
  color: #1a1a1a;
}

.detail-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.detail-item label {
  font-size: 12px;
  font-weight: 500;
  color: #666;
  text-transform: uppercase;
}

.detail-item span {
  font-size: 14px;
  color: #1a1a1a;
}

.modal-footer {
  padding: 20px 24px;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.cancel-btn {
  padding: 8px 16px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
}

.favorite-action-btn {
  padding: 8px 16px;
  border: 1px solid #007bff;
  background: white;
  color: #007bff;
  border-radius: 4px;
  cursor: pointer;
}

.favorite-action-btn.active {
  background: #dc3545;
  color: white;
  border-color: #dc3545;
}

@media (max-width: 768px) {
  .search-row {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-input-group {
    min-width: auto;
  }
  
  .members-grid {
    grid-template-columns: 1fr;
  }
  
  .member-header {
    flex-direction: column;
    gap: 12px;
  }
  
  .detail-grid {
    grid-template-columns: 1fr;
  }
}
</style>
