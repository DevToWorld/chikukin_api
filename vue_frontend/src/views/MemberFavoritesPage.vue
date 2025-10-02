<template>
  <div class="member-favorites-page">
    <Navigation />
    
    <div class="page-content">
      <div class="container">
        <!-- ページヘッダー -->
        <div class="page-header">
          <h1 class="page-title">お気に入り会員</h1>
          <p class="page-description">
            お気に入りに登録した会員一覧です。
          </p>
        </div>

        <!-- 認証チェック -->
        <div v-if="!memberInfo" class="auth-required">
          <div class="auth-card">
            <h3>ログインが必要です</h3>
            <p>お気に入り機能をご利用いただくには、ログインが必要です。</p>
            <button @click="$router.push('/member-login')" class="login-btn">
              ログイン
            </button>
          </div>
        </div>

        <!-- お気に入り一覧 -->
        <div v-else>
          <!-- ローディング・エラー -->
          <div v-if="loading" class="loading">読み込み中...</div>
          <div v-else-if="error" class="error">{{ error }}</div>

          <!-- お気に入り会員一覧 -->
          <div v-else>
            <div class="favorites-header">
              <div class="count-info">
                <span class="count">{{ favorites.length }}</span>
                <span class="label">件のお気に入り会員</span>
              </div>
              <button @click="loadFavorites" class="refresh-btn" :disabled="loading">
                更新
              </button>
            </div>

            <!-- 空の状態 -->
            <div v-if="favorites.length === 0" class="empty-state">
              <div class="empty-icon">💫</div>
              <h3>お気に入りはまだありません</h3>
              <p>会員名簿からお気に入りの会員を登録してみましょう。</p>
              <button @click="$router.push('/member-directory')" class="directory-btn">
                会員名簿を見る
              </button>
            </div>

            <!-- お気に入り一覧グリッド -->
            <div v-else class="favorites-grid">
              <div 
                v-for="favorite in favorites" 
                :key="favorite.id" 
                class="favorite-card"
              >
                <div class="favorite-header">
                  <div class="favorite-info">
                    <h3 class="company-name">{{ favorite.company_name }}</h3>
                    <p class="representative-name">{{ favorite.representative_name }}</p>
                  </div>
                  <div class="favorite-actions">
                    <button 
                      @click="removeFavorite(favorite)"
                      class="remove-btn"
                      :disabled="favorite.removing"
                      title="お気に入りから削除"
                    >
                      {{ favorite.removing ? '削除中...' : '★' }}
                    </button>
                    <span :class="['membership-badge', `membership-${favorite.membership_type}`]">
                      {{ getMembershipLabel(favorite.membership_type) }}
                    </span>
                  </div>
                </div>
                
                <div class="favorite-details">
                  <div class="contact-info">
                    <span class="icon">📧</span>
                    <a :href="`mailto:${favorite.email}`" class="email-link">
                      {{ favorite.email }}
                    </a>
                  </div>
                  <div v-if="favorite.phone" class="contact-info">
                    <span class="icon">📞</span>
                    <a :href="`tel:${favorite.phone}`" class="phone-link">
                      {{ favorite.phone }}
                    </a>
                  </div>
                  <div v-if="favorite.address" class="address">
                    <span class="icon">📍</span>
                    <span>{{ favorite.address }}</span>
                  </div>
                  <div class="favorite-date">
                    <span class="icon">⭐</span>
                    <span>{{ formatDate(favorite.favorited_at) }} にお気に入り登録</span>
                  </div>
                </div>

                <div class="favorite-card-actions">
                  <button @click="viewDetail(favorite)" class="detail-btn">
                    詳細を見る
                  </button>
                  <button @click="contactMember(favorite)" class="contact-btn">
                    連絡する
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 会員詳細モーダル -->
    <div v-if="selectedFavorite" class="modal-overlay" @click="closeDetailModal">
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
                  <span>{{ selectedFavorite.company_name }}</span>
                </div>
                <div class="detail-item">
                  <label>代表者名</label>
                  <span>{{ selectedFavorite.representative_name }}</span>
                </div>
                <div class="detail-item">
                  <label>会員種別</label>
                  <span :class="['membership-badge', `membership-${selectedFavorite.membership_type}`]">
                    {{ getMembershipLabel(selectedFavorite.membership_type) }}
                  </span>
                </div>
              </div>
            </div>

            <div class="detail-section">
              <h4>連絡先</h4>
              <div class="detail-grid">
                <div class="detail-item">
                  <label>メールアドレス</label>
                  <a :href="`mailto:${selectedFavorite.email}`" class="contact-link">
                    {{ selectedFavorite.email }}
                  </a>
                </div>
                <div v-if="selectedFavorite.phone" class="detail-item">
                  <label>電話番号</label>
                  <a :href="`tel:${selectedFavorite.phone}`" class="contact-link">
                    {{ selectedFavorite.phone }}
                  </a>
                </div>
                <div v-if="selectedFavorite.address" class="detail-item">
                  <label>住所</label>
                  <span>{{ selectedFavorite.address }}</span>
                </div>
              </div>
            </div>

            <div class="detail-section">
              <h4>お気に入り情報</h4>
              <div class="detail-grid">
                <div class="detail-item">
                  <label>お気に入り登録日</label>
                  <span>{{ formatDate(selectedFavorite.favorited_at) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button @click="closeDetailModal" class="cancel-btn">閉じる</button>
          <button @click="contactMember(selectedFavorite)" class="contact-btn">
            連絡する
          </button>
          <button 
            @click="removeFavorite(selectedFavorite)"
            class="remove-action-btn"
            :disabled="selectedFavorite.removing"
          >
            {{ selectedFavorite.removing ? '削除中...' : 'お気に入りから削除' }}
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

export default {
  name: 'MemberFavoritesPage',
  components: {
    Navigation,
    FooterComplete
  },
  data() {
    return {
      memberInfo: null,
      loading: false,
      error: '',
      favorites: [],
      selectedFavorite: null
    }
  },
  async mounted() {
    await this.initializeAuth()
    if (this.memberInfo) {
      this.loadFavorites()
    }
  },
  methods: {
    async initializeAuth() {
      const { getMemberInfo, isLoggedIn } = useMemberAuth()
      
      if (!isLoggedIn()) {
        return
      }

      try {
        this.memberInfo = await getMemberInfo()
      } catch (error) {
        console.error('認証情報の取得に失敗:', error)
      }
    },

    async loadFavorites() {
      this.loading = true
      this.error = ''

      try {
        const response = await apiClient.get('/api/member/favorites')

        if (response.success) {
          this.favorites = response.data.map(favorite => ({
            ...favorite,
            removing: false
          }))
        } else {
          this.error = response.message || 'お気に入り一覧の取得に失敗しました'
        }
      } catch (error) {
        this.error = 'サーバーエラーが発生しました'
        console.error('Failed to load favorites:', error)
      } finally {
        this.loading = false
      }
    },

    async removeFavorite(favorite) {
      if (favorite.removing) return

      if (!confirm(`${favorite.company_name} をお気に入りから削除しますか？`)) {
        return
      }

      favorite.removing = true

      try {
        const response = await apiClient.delete(`/api/member/favorites/${favorite.id}`)
        
        if (response.success) {
          // 一覧から削除
          this.favorites = this.favorites.filter(f => f.id !== favorite.id)
          
          // モーダルが開いている場合は閉じる
          if (this.selectedFavorite && this.selectedFavorite.id === favorite.id) {
            this.closeDetailModal()
          }
          
          alert('お気に入りから削除しました')
        } else {
          throw new Error(response.message)
        }
      } catch (error) {
        console.error('お気に入り削除に失敗:', error)
        alert('お気に入りの削除に失敗しました')
      } finally {
        favorite.removing = false
      }
    },

    viewDetail(favorite) {
      this.selectedFavorite = { ...favorite }
    },

    closeDetailModal() {
      this.selectedFavorite = null
    },

    contactMember(favorite) {
      // メール送信
      window.location.href = `mailto:${favorite.email}?subject=お問い合わせ&body=こんにちは、${favorite.company_name} の ${favorite.representative_name} 様`
    },

    getMembershipLabel(type) {
      const labels = {
        'free': '無料',
        'standard': 'スタンダード',
        'premium': 'プレミアム'
      }
      return labels[type] || type
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
.member-favorites-page {
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

/* 認証必須 */
.auth-required {
  display: flex;
  justify-content: center;
  margin-top: 60px;
}

.auth-card {
  background: white;
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-width: 400px;
}

.auth-card h3 {
  font-size: 24px;
  margin: 0 0 16px 0;
  color: #1a1a1a;
}

.auth-card p {
  margin: 8px 0 20px 0;
  color: #666;
}

.login-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
}

.login-btn:hover {
  opacity: 0.8;
}

/* お気に入りヘッダー */
.favorites-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding: 16px 0;
}

.count-info {
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.count {
  font-size: 24px;
  font-weight: 700;
  color: #007bff;
}

.label {
  font-size: 16px;
  color: #666;
}

.refresh-btn {
  padding: 8px 16px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}

.refresh-btn:disabled {
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

/* 空の状態 */
.empty-state {
  text-align: center;
  padding: 80px 20px;
}

.empty-icon {
  font-size: 64px;
  margin-bottom: 24px;
}

.empty-state h3 {
  font-size: 24px;
  color: #1a1a1a;
  margin: 0 0 12px 0;
}

.empty-state p {
  color: #666;
  margin: 0 0 24px 0;
  font-size: 16px;
}

.directory-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
}

.directory-btn:hover {
  background-color: #0056b3;
}

/* お気に入り一覧 */
.favorites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 24px;
}

.favorite-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.2s;
}

.favorite-card:hover {
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
  transform: translateY(-2px);
}

.favorite-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.favorite-info h3 {
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

.favorite-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.remove-btn {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  color: #ffc107;
  transition: color 0.2s;
  padding: 4px;
}

.remove-btn:hover {
  color: #dc3545;
}

.remove-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
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

.favorite-details {
  margin-bottom: 20px;
}

.contact-info, .address, .favorite-date {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #666;
  margin: 8px 0;
}

.icon {
  width: 16px;
  flex-shrink: 0;
}

.email-link, .phone-link, .contact-link {
  color: #007bff;
  text-decoration: none;
}

.email-link:hover, .phone-link:hover, .contact-link:hover {
  text-decoration: underline;
}

.favorite-card-actions {
  display: flex;
  gap: 12px;
  padding-top: 16px;
  border-top: 1px solid #eee;
}

.detail-btn, .contact-btn {
  flex: 1;
  padding: 8px 16px;
  border: 1px solid #ddd;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  text-align: center;
}

.detail-btn {
  background: white;
  color: #666;
}

.contact-btn {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.detail-btn:hover {
  background: #f5f5f5;
}

.contact-btn:hover {
  opacity: 0.8;
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

.cancel-btn, .remove-action-btn {
  padding: 8px 16px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
}

.remove-action-btn {
  background: #dc3545;
  color: white;
  border-color: #dc3545;
}

.remove-action-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .favorites-header {
    flex-direction: column;
    gap: 16px;
    text-align: center;
  }
  
  .favorites-grid {
    grid-template-columns: 1fr;
  }
  
  .favorite-header {
    flex-direction: column;
    gap: 12px;
  }
  
  .favorite-card-actions {
    flex-direction: column;
  }
  
  .detail-grid {
    grid-template-columns: 1fr;
  }
}
</style>
