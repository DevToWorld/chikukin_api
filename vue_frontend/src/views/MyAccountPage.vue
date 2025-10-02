<template>
  <div class="my-account-page">
    <Navigation />
    <div class="account-container">
      <div class="account-sidebar">
        <div class="sidebar-header">
          <div class="user-avatar">
            <span>{{ userInitial }}</span>
          </div>
          <h3 class="user-name">{{ memberInfo?.name || 'ゲスト' }}</h3>
          <p class="user-email">{{ memberInfo?.email || '' }}</p>
        </div>
        
        <nav class="sidebar-nav">
          <a 
            v-for="item in menuItems" 
            :key="item.id"
            :class="['nav-item', { active: activeTab === item.id }]"
            @click="activeTab = item.id"
          >
            <span class="nav-icon">{{ item.icon }}</span>
            <span>{{ item.label }}</span>
          </a>
        </nav>
        
        <div class="sidebar-footer">
          <button @click="handleLogout" class="logout-button">
            ログアウト
          </button>
        </div>
      </div>
      
      <div class="account-content">
        <!-- ダッシュボードサマリー -->
        <div v-if="dashboard" class="summary-cards">
          <div class="card">
            <div class="card-title">会員プラン</div>
            <div class="card-value">
              <span :class="['membership-badge', `membership-${dashboard.member.membership_type}`]">
                {{ getMembershipLabel(dashboard.member.membership_type) }}
              </span>
            </div>
            <div class="card-sub" v-if="dashboard.member.membership_expires_at">
              期限: {{ formatDate(dashboard.member.membership_expires_at) }}
            </div>
          </div>
          <div class="card">
            <div class="card-title">お気に入り</div>
            <div class="card-value">{{ dashboard.stats.favorites_count }} 件</div>
          </div>
          <div class="card">
            <div class="card-title">近日のセミナー</div>
            <div class="card-value">{{ dashboard.stats.upcoming_seminars_count }} 件</div>
          </div>
        </div>

        <!-- セミナー系タブ -->
        <div v-if="activeTab==='seminars'" class="content-section">
          <h2>{{ cms?.labels?.headings?.seminars || 'セミナー一覧' }}</h2>
          <MemberSeminarsTab 
            :labels="cms?.labels?.tables?.seminars || null"
            @reservation-made="onReservationMade" 
            @seminar-favorite-updated="onSeminarFavoriteUpdated" 
          />
        </div>

        <div v-if="activeTab==='seminar-favorites'" class="content-section">
          <h2>{{ cms?.labels?.headings?.seminarFavorites || 'お気に入りセミナー' }}</h2>
          <MemberSeminarFavoritesTab 
            ref="seminarFavoritesTab" 
            :labels="cms?.labels?.tables?.seminarFavorites || null"
          />
        </div>

        <div v-if="activeTab==='registrations'" class="content-section">
          <h2>{{ cms?.labels?.headings?.registrations || '申込状況' }}</h2>
          <MemberSeminarRegistrationsTab 
            ref="registrationsTab"
            :labels="cms?.labels?.tables?.registrations || null"
          />
        </div>

        <!-- アカウント情報タブ -->
        <div v-if="activeTab === 'profile'" class="content-section">
          <h2>{{ cms?.labels?.headings?.profile || 'アカウント情報' }}</h2>
          
          <!-- 表示モード -->
          <div v-if="!editMode" class="info-card">
            <div class="info-row">
              <label>会社名</label>
              <div class="info-value">{{ memberInfo?.company_name || '未登録' }}</div>
            </div>
            <div class="info-row">
              <label>代表者名</label>
              <div class="info-value">{{ memberInfo?.representative_name || '未登録' }}</div>
            </div>
            <div class="info-row">
              <label>メールアドレス</label>
              <div class="info-value">{{ memberInfo?.email }}</div>
            </div>
            <div class="info-row">
              <label>電話番号</label>
              <div class="info-value">{{ memberInfo?.phone || '未登録' }}</div>
            </div>
            <div class="info-row">
              <label>郵便番号</label>
              <div class="info-value">{{ memberInfo?.postal_code || '未登録' }}</div>
            </div>
            <!-- 住所は非表示に変更 -->
            <div class="info-row">
              <label>役職</label>
              <div class="info-value">{{ memberInfo?.position || '未登録' }}</div>
            </div>
            <!-- 部署は非表示に変更 -->
            <div class="info-row">
              <label>資本金</label>
              <div class="info-value">{{ formatCapital(memberInfo?.capital) }}</div>
            </div>
            <div class="info-row">
              <label>業種</label>
              <div class="info-value">{{ memberInfo?.industry || '未登録' }}</div>
            </div>
            <!-- 会社所在地（県名）は非表示に変更 -->
            <div class="info-row">
              <label>お困りごと</label>
              <div class="info-value">{{ memberInfo?.concerns || '未登録' }}</div>
            </div>
            <div class="info-row">
              <label>備考</label>
              <div class="info-value">{{ memberInfo?.notes || '未登録' }}</div>
            </div>
            <div class="info-row">
              <label>会員種別</label>
              <div class="info-value">
                <span :class="['membership-badge', `membership-${memberInfo?.membership_type}`]">
                  {{ getMembershipLabel(memberInfo?.membership_type) }}
                </span>
              </div>
            </div>
            <div class="info-row">
              <label>登録日</label>
              <div class="info-value">{{ formatDate(memberInfo?.created_at) }}</div>
            </div>
          </div>
          
          <!-- 編集モード -->
          <div v-else class="edit-form">
            <form @submit.prevent="saveProfile">
              <div class="form-row">
                <div class="form-group">
                  <label>会社名 *</label>
                  <input 
                    v-model="editForm.company_name" 
                    type="text" 
                    class="form-input"
                    required
                  >
                </div>
                <div class="form-group">
                  <label>代表者名 *</label>
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
                  <label>メールアドレス *</label>
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
                    placeholder="例: 123-4567"
                  >
                </div>
              </div>

              <!-- 住所入力は非表示に変更 -->

              <div class="form-row">
                <div class="form-group">
                  <label>役職</label>
                  <input v-model="editForm.position" type="text" class="form-input" />
                </div>
                <!-- 部署入力は非表示に変更 -->
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>資本金（円）</label>
                  <input v-model.number="editForm.capital" type="number" min="0" step="1" class="form-input" />
                </div>
                <div class="form-group">
                  <label>業種</label>
                  <select v-model="editForm.industry" class="form-input">
                    <option value="">未選択</option>
                    <option v-for="i in industryOptions" :key="i" :value="i">{{ i }}</option>
                  </select>
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

              <div class="form-actions">
                <button type="button" @click="cancelEdit" class="cancel-button">
                  {{ cms?.labels?.buttons?.cancel || 'キャンセル' }}
                </button>
                <button type="submit" class="save-button" :disabled="saving">
                  {{ saving ? (cms?.labels?.buttons?.saving || '保存中...') : (cms?.labels?.buttons?.save || '保存する') }}
                </button>
              </div>
            </form>
          </div>
          
          <div v-if="!editMode" class="profile-actions">
            <button @click="startEdit" class="edit-button">{{ cms?.labels?.buttons?.editProfile || 'プロフィールを編集' }}</button>
            <button @click="showPasswordForm = true" class="password-button">{{ cms?.labels?.buttons?.changePassword || 'パスワード変更' }}</button>
          </div>
        </div>
        
        <!-- 会員プランタブ -->
        <div v-if="activeTab === 'membership'" class="content-section">
          <h2>{{ cms?.labels?.headings?.membership || '会員プラン' }}</h2>
          
          <div class="membership-card" :class="`membership-${memberInfo?.membershipType}`">
            <div class="membership-header">
              <h3>{{ getMembershipLabel(memberInfo?.membershipType) }}</h3>
              <span class="membership-badge">現在のプラン</span>
            </div>
            
            <div class="membership-features">
              <h4>利用可能な機能</h4>
              <ul>
                <li v-for="feature in getMembershipFeatures(memberInfo?.membershipType)" :key="feature">
                  ✓ {{ feature }}
                </li>
              </ul>
            </div>
            
            <div v-if="memberInfo?.membershipType !== 'premium'" class="upgrade-section">
              <p>{{ cms?.labels?.texts?.upgradeNotice || 'より多くのコンテンツにアクセスしたい場合は、プランをアップグレードしてください。' }}</p>
              <button @click="goToUpgrade" class="upgrade-button">
                {{ cms?.labels?.buttons?.upgrade || 'プランをアップグレード' }}
              </button>
            </div>
            
            <div v-if="memberInfo?.expiryDate" class="expiry-info">
              <span>有効期限: {{ formatDate(memberInfo.expiryDate) }}</span>
            </div>

            
          </div>
        </div>
        
        <!-- ダウンロード履歴タブ -->
        <div v-if="activeTab === 'downloads'" class="content-section">
          <h2>{{ cms?.labels?.headings?.downloads || 'ダウンロード履歴' }}</h2>
          
          <div class="downloads-list">
            <div class="download-item" v-for="item in downloadHistory" :key="item.id">
              <div class="download-info">
                <h4>{{ item.title }}</h4>
                <span class="download-date">{{ formatDate(item.downloadedAt) }}</span>
              </div>
              <button @click="redownload(item)" class="secondary-button">
                {{ cms?.labels?.buttons?.redownload || '再ダウンロード' }}
              </button>
            </div>
            
            <div v-if="downloadHistory.length === 0" class="empty-state">
              <p>ダウンロード履歴はありません</p>
            </div>
          </div>
        </div>
        
        <!-- お気に入りタブ -->
        <div v-if="activeTab === 'favorites'" class="content-section">
          <div class="favorites-header">
            <h2>{{ cms?.labels?.headings?.favoriteMembers || 'お気に入り会員' }}</h2>
            <div class="header-actions">
              <button @click="$router.push('/member-favorites')" class="primary-button">{{ cms?.labels?.buttons?.goFavorites || 'お気に入り一覧ページへ' }}</button>
              <button @click="$router.push('/member-directory')" class="primary-button">{{ cms?.labels?.buttons?.viewDirectory || '会員名簿を見る' }}</button>
            </div>
          </div>
          
          <div v-if="loadingFavorites" class="loading">読み込み中...</div>
          <div v-else-if="favoritesError" class="error">{{ favoritesError }}</div>
          <div v-else>
            <div v-if="favoriteMembers.length === 0" class="empty-state">
              <h3>{{ cms?.labels?.empty?.favoriteMembersTitle || 'お気に入り会員はまだありません' }}</h3>
              <p>{{ cms?.labels?.empty?.favoriteMembersText || '会員名簿からお気に入りの会員を登録してみましょう。' }}</p>
              <button @click="$router.push('/member-directory')" class="primary-button">
                {{ cms?.labels?.buttons?.viewDirectory || '会員名簿を見る' }}
              </button>
            </div>
            
            <div v-else class="favorites-list">
              <div 
                v-for="favorite in favoriteMembers" 
                :key="favorite.id"
                class="favorite-item"
              >
                <div class="favorite-info">
                  <h4>{{ favorite.company_name }}</h4>
                  <p>{{ favorite.representative_name }}</p>
                  <span class="membership-type">{{ getMembershipLabel(favorite.membership_type) }}</span>
                </div>
                <div class="favorite-actions">
                  <button @click="viewFavoriteDetail(favorite)" class="secondary-button">{{ cms?.labels?.buttons?.detail || '詳細' }}</button>
                  <button @click="removeFavorite(favorite)" class="danger-outline-button">{{ cms?.labels?.buttons?.remove || '削除' }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 設定タブ -->
        <div v-if="activeTab === 'settings'" class="content-section">
          <h2>{{ cms?.labels?.headings?.settings || '設定' }}</h2>
          
          <div class="settings-card">
            <h3>メール通知設定</h3>
            
            <div class="setting-item">
              <label class="setting-label">
                <input type="checkbox" v-model="settings.newsletter" />
                <span>新着刊行物のお知らせ</span>
              </label>
            </div>
            
            <div class="setting-item">
              <label class="setting-label">
                <input type="checkbox" v-model="settings.seminar" />
                <span>セミナー情報のお知らせ</span>
              </label>
            </div>
            
            <div class="setting-item">
              <label class="setting-label">
                <input type="checkbox" v-model="settings.promotion" />
                <span>キャンペーン情報</span>
              </label>
            </div>
            
            <button @click="saveSettings" class="save-button">
              {{ cms?.labels?.buttons?.save || '設定を保存' }}
            </button>
          </div>
          
          <div class="settings-card danger-zone">
            <h3>アカウントの削除</h3>
            <p>アカウントを削除すると、すべてのデータが失われます。この操作は取り消せません。</p>
            <button class="delete-button">アカウントを削除</button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 予約モーダル -->
    <div v-if="showReservationModal" class="modal-backdrop" @click.self="closeReservationModal">
      <div class="modal">
        <h3>予約フォーム</h3>
        <form @submit.prevent="submitReservation">
          <div class="form-row">
            <div class="form-group">
              <label>お名前 *</label>
              <input v-model="reservationForm.name" type="text" class="form-input" required />
            </div>
            <div class="form-group">
              <label>メールアドレス *</label>
              <input v-model="reservationForm.email" type="email" class="form-input" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>会社名</label>
              <input v-model="reservationForm.company" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label>電話番号</label>
              <input v-model="reservationForm.phone" type="text" class="form-input" />
            </div>
          </div>
          <div class="form-group">
            <label>ご要望・備考</label>
            <textarea v-model="reservationForm.special_requests" class="form-textarea" rows="4" placeholder="ご要望などがあればご記入ください"></textarea>
          </div>

          <p v-if="reservationError" class="error-text">{{ reservationError }}</p>

          <div class="form-actions">
            <button type="button" class="cancel-button" @click="closeReservationModal" :disabled="reservationLoading">{{ cms?.labels?.buttons?.cancel || 'キャンセル' }}</button>
            <button type="submit" class="save-button" :disabled="reservationLoading">{{ reservationLoading ? (cms?.labels?.buttons?.submitting || '送信中...') : (cms?.labels?.buttons?.submit || '送信する') }}</button>
          </div>
        </form>
      </div>
    </div>
    <Footer v-bind="frame132131753022Props" />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
// import { useRouter } from 'vue-router' // Vue 2では利用不可
import Navigation from '@/components/Navigation.vue'
import Footer from '@/components/Footer.vue'
import PublicationCard from '@/components/PublicationCard.vue'
import { useMemberAuth } from '@/composables/useMemberAuth'
import apiClient from '@/services/apiClient.js'
import { frame132131753022Data } from '../data.js'

export default {
  name: 'MyAccountPage',
  components: {
    Navigation,
    Footer,
    PublicationCard,
    MemberSeminarsTab: () => import('./partials/MemberSeminarsTab.vue'),
    MemberSeminarFavoritesTab: () => import('./partials/MemberSeminarFavoritesTab.vue'),
    MemberSeminarRegistrationsTab: () => import('./partials/MemberSeminarRegistrationsTab.vue')
  },
  data() {
    return {
      activeTab: 'profile',
      memberInfo: null,
      dashboard: null,
      cms: null,
      frame132131753022Props: frame132131753022Data,
      downloadHistory: [],
      favoriteMembers: [],
      settings: {
        // 表示している3つのトグル
        newsletter: true,
        seminar: true,
        promotion: false,
        // 拡張用（UI非表示）
        emailNotifications: true,
        smsNotifications: false
      },
      
      // プロフィール編集
      editMode: false,
      editForm: {},
      saving: false,
      
      // パスワード変更
      showPasswordForm: false,
      passwordForm: {
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      },
      changingPassword: false,
      
      // お気に入り
      loadingFavorites: false,
      favoritesError: '',

      // 予約モーダル
      showReservationModal: false,
      reservationLoading: false,
      reservationError: '',
      reservationTarget: null,
      reservationForm: {
        name: '',
        email: '',
        company: '',
        phone: '',
        special_requests: ''
      },
      // マスタ
      industryOptions: []
    }
  },
  computed: {
    userInitial() {
      return this.memberInfo?.name?.charAt(0) || 'G'
    },
    menuItems() {
      const t = (key, def) => this.cms?.labels?.tabs?.[key] || def
      return [
        { id: 'profile', label: t('profile', 'アカウント情報'), icon: '' },
        { id: 'seminars', label: t('seminars', 'セミナー'), icon: '' },
        { id: 'seminar-favorites', label: t('seminarFavorites', 'セミナーお気に入り'), icon: '' },
        { id: 'registrations', label: t('registrations', '申込状況'), icon: '' },
        { id: 'membership', label: t('membership', '会員プラン'), icon: '' },
        { id: 'downloads', label: t('downloads', 'ダウンロード履歴'), icon: '' },
        { id: 'favorites', label: t('favorites', 'お気に入り'), icon: '' },
        { id: 'settings', label: t('settings', '設定'), icon: '' }
      ]
    }
  },
  async mounted() {
    // プロフィール・ダッシュボード・お気に入りを並行ロード
    await this.fetchInitialData()
  },
  methods: {
    onReservationMade(payload) {
      // 申込状況タブに切替え、描画後にリロード
      this.activeTab = 'registrations'
      this.$nextTick(() => {
        if (this.$refs && this.$refs.registrationsTab && typeof this.$refs.registrationsTab.load === 'function') {
          this.$refs.registrationsTab.load()
        }
      })
    },
    onSeminarFavoriteUpdated() {
      if (this.$refs && this.$refs.seminarFavoritesTab && typeof this.$refs.seminarFavoritesTab.load === 'function') {
        this.$refs.seminarFavoritesTab.load()
      }
    },
    async fetchInitialData() {
      try {
        const [profileRes, dashboardRes, favoritesRes, cmsRes, industriesRes] = await Promise.all([
          apiClient.get('/api/member/my-profile'),
          apiClient.get('/api/member/dashboard'),
          apiClient.get('/api/member/favorites'),
          apiClient.getPageContent('my-account'),
          apiClient.getIndustries()
        ])

        // プロフィール
        if (profileRes && profileRes.success) {
          this.memberInfo = profileRes.data
        } else {
          this.$router.push('/login?redirect=/my-account')
          return
        }

        // ダッシュボード
        if (dashboardRes && dashboardRes.success) {
          this.dashboard = dashboardRes.data
        }

        // お気に入り
        if (favoritesRes && favoritesRes.success) {
          this.favoriteMembers = favoritesRes.data
        } else if (favoritesRes && !favoritesRes.success) {
          this.favoritesError = favoritesRes.message || 'お気に入り一覧の取得に失敗しました'
        }

        // CMS（文言/ラベル類）
        if (cmsRes && cmsRes.success) {
          const page = cmsRes.data?.page || cmsRes.data?.data?.page || null
          const content = page?.content || null
          this.cms = (content && content.labels) ? content : { labels: content?.labels || {} }
        }

        // マスタ
        if (industriesRes && industriesRes.success) {
          this.industryOptions = (industriesRes.data?.industries || industriesRes.data || []).map(i => i.name || i).filter(Boolean)
        }

        // ダウンロード履歴（API未提供のためダミー）
        this.loadDownloadHistory()
      } catch (error) {
        console.error('初期データ取得に失敗:', error)
        this.$router.push('/login?redirect=/my-account')
      }
    },
    async loadDashboard() {
      try {
        const res = await apiClient.get('/api/member/dashboard')
        if (res && res.success) {
          this.dashboard = res.data
        }
      } catch (e) {
        console.warn('Failed to load dashboard', e)
      }
    },
    async initializeAuth() {
      try {
        const response = await apiClient.get('/api/member/my-profile')
        if (response.success) {
          this.memberInfo = response.data
        } else {
          this.$router.push('/login?redirect=/my-account')
        }
      } catch (error) {
        this.$router.push('/login?redirect=/my-account')
      }
    },

    // プロフィール編集
    startEdit() {
      this.editForm = {
        company_name: this.memberInfo.company_name || '',
        representative_name: this.memberInfo.representative_name || '',
        email: this.memberInfo.email || '',
        phone: this.memberInfo.phone || '',
        postal_code: this.memberInfo.postal_code || '',
        position: this.memberInfo.position || '',
        capital: this.memberInfo.capital ?? null,
        industry: this.memberInfo.industry || '',
        concerns: this.memberInfo.concerns || '',
        notes: this.memberInfo.notes || ''
      }
      this.editMode = true
    },

    cancelEdit() {
      this.editMode = false
      this.editForm = {}
    },

    async saveProfile() {
      this.saving = true
      
      try {
        const response = await apiClient.put('/api/member/my-profile', this.editForm)
        
        if (response.success) {
          this.memberInfo = response.data
          this.editMode = false
          alert('プロフィールを更新しました')
        } else {
          alert(response.message || 'プロフィールの更新に失敗しました')
        }
      } catch (error) {
        console.error('プロフィール更新に失敗:', error)
        alert('サーバーエラーが発生しました')
      } finally {
        this.saving = false
      }
    },

    // お気に入り会員
    async loadFavoriteMembers() {
      this.loadingFavorites = true
      this.favoritesError = ''

      try {
        const response = await apiClient.get('/api/member/favorites')
        
        if (response.success) {
          this.favoriteMembers = response.data
        } else {
          this.favoritesError = response.message || 'お気に入り一覧の取得に失敗しました'
        }
      } catch (error) {
        this.favoritesError = 'サーバーエラーが発生しました'
        console.error('お気に入り取得に失敗:', error)
      } finally {
        this.loadingFavorites = false
      }
    },

    async removeFavorite(favorite) {
      if (!confirm(`${favorite.company_name} をお気に入りから削除しますか？`)) {
        return
      }

      try {
        const response = await apiClient.delete(`/api/member/favorites/${favorite.id}`)
        
        if (response.success) {
          this.favoriteMembers = this.favoriteMembers.filter(f => f.id !== favorite.id)
          alert('お気に入りから削除しました')
        } else {
          alert(response.message || 'お気に入りの削除に失敗しました')
        }
      } catch (error) {
        console.error('お気に入り削除に失敗:', error)
        alert('サーバーエラーが発生しました')
      }
    },

    viewFavoriteDetail(favorite) {
      // 会員名簿ページに遷移（詳細表示）
      this.$router.push(`/member-directory?view=${favorite.id}`)
    },

    // ダウンロード履歴（API）
    async loadDownloadHistory() {
      try {
        const res = await apiClient.getMemberDownloadHistory({ limit: 50 })
        if (res && res.success && res.data && Array.isArray(res.data.downloads)) {
          this.downloadHistory = res.data.downloads.map(d => ({
            id: d.content_id,
            title: d.title,
            downloadedAt: this.formatDate(d.downloaded_at),
            type: 'PDF',
            size: ''
          }))
        } else {
          this.downloadHistory = []
        }
      } catch (e) {
        console.error('ダウンロード履歴の取得に失敗:', e)
        this.downloadHistory = []
      }
    },

    handleLogout() {
      if (confirm('ログアウトしますか？')) {
        const { logout } = useMemberAuth()
        try { apiClient.post('/api/member-auth/logout') } catch(e) {}
        logout()
        localStorage.removeItem('auth_token')
        this.$router.push('/')
      }
    },
    
    goToUpgrade() {
      this.$router.push('/premium')
    },
    
    async redownload(item) {
      try {
        const res = await apiClient.downloadPublication(item.id)
        if (res && res.success) {
          const url = res.data?.download_url || res.data?.url || res.download_url
          if (url) {
            window.open(url, '_blank')
            alert('ダウンロードを開始します')
          } else {
            alert('ダウンロードURLが取得できませんでした')
          }
        } else {
          alert(res?.message || 'ダウンロードに失敗しました')
        }
      } catch (e) {
        console.error('ダウンロードエラー:', e)
        alert('サーバーエラーが発生しました')
      }
    },
    
    getMembershipLabel(type) {
      const { getMembershipLabel } = useMemberAuth()
      return getMembershipLabel(type)
    },
    
    getMembershipFeatures(type) {
      const features = {
        basic: [
          'ベーシック向け刊行物の閲覧',
          'セミナー情報の確認',
          'メール配信サービス'
        ],
        premium: [
          'すべての刊行物への無制限アクセス',
          'セミナーの優先参加',
          '限定レポートの配信',
          '経済統計データの詳細分析',
          '個別相談サービス'
        ]
      }
      return features[type] || []
    },
    
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('ja-JP')
    },
    formatCapital(val) {
      if (val === null || val === undefined || val === '') return '未登録'
      const n = Number(val)
      if (isNaN(n)) return String(val)
      return n.toLocaleString('ja-JP') + '円'
    },
    
    saveSettings() {
      // Vueのリアクティブラッパを外したプレーンオブジェクトに変換
      const payload = JSON.parse(JSON.stringify(this.settings))
      try {
        // まだAPIがないため、一時的にlocalStorageへ保存
        localStorage.setItem('member_settings', JSON.stringify(payload))
        console.log('設定を保存:', payload)
        alert('設定を保存しました')
      } catch (e) {
        console.error('設定の保存に失敗:', e)
        alert('設定の保存に失敗しました')
      }
    },

    // 予約モーダル
    openReservationModal(target = null) {
      this.reservationTarget = target
      this.reservationError = ''
      this.reservationForm = {
        name: this.memberInfo?.representative_name || this.memberInfo?.name || '',
        email: this.memberInfo?.email || '',
        company: this.memberInfo?.company_name || '',
        phone: this.memberInfo?.phone || '',
        special_requests: ''
      }
      this.showReservationModal = true
    },
    closeReservationModal() {
      if (this.reservationLoading) return
      this.showReservationModal = false
    },
    async submitReservation() {
      this.reservationLoading = true
      this.reservationError = ''
      try {
        if (this.reservationTarget?.seminarId) {
          const res = await apiClient.registerForSeminar(this.reservationTarget.seminarId, this.reservationForm)
          if (res && res.success) {
            this.showReservationModal = false
            alert('予約を受け付けました')
          } else {
            this.reservationError = res?.message || '予約に失敗しました'
          }
        } else {
          const res = await apiClient.submitInquiry({
            name: this.reservationForm.name,
            email: this.reservationForm.email,
            phone: this.reservationForm.phone,
            company: this.reservationForm.company,
            subject: '個別相談の予約',
            message: this.reservationForm.special_requests || '個別相談の予約を希望します。',
            inquiry_type: 'reservation'
          })
          if (res && res.success) {
            this.showReservationModal = false
            alert('予約申請を送信しました')
          } else {
            this.reservationError = res?.message || '予約申請の送信に失敗しました'
          }
        }
      } catch (e) {
        console.error('予約送信エラー:', e)
        this.reservationError = 'サーバーエラーが発生しました'
      } finally {
        this.reservationLoading = false
      }
    }
  }
}
</script>

<style scoped>
.my-account-page {
  min-height: 100vh;
  background: #f5f5f5;
}

.account-container {
  max-width: 2000px;
  margin: 0 auto;
  padding: 40px 20px;
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 30px;
  margin-top: 50px;
}

/* ダッシュボードサマリー */
.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
  margin-bottom: 20px;
}
.summary-cards .card {
  background: #fff;
  border-radius: 10px;
  padding: 16px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.summary-cards .card-title {
  font-size: 12px;
  color: #666;
}
.summary-cards .card-value {
  margin-top: 6px;
  font-size: 20px;
  font-weight: 600;
  color: #1A1A1A;
}
.summary-cards .card-sub {
  margin-top: 4px;
  font-size: 12px;
  color: #777;
}

/* サイドバー */
.account-sidebar {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  padding: 24px;
  height: fit-content;
  position: sticky;
  top: 20px;
}

.sidebar-header {
  text-align: center;
  padding-bottom: 24px;
  border-bottom: 1px solid #e0e0e0;
  margin-bottom: 24px;
}

.user-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--mandy) 0%, var(--hot-pink) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 16px;
  font-size: 32px;
  color: white;
  font-weight: 600;
}

.user-name {
  font-size: 18px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0 0 4px 0;
}

.user-email {
  font-size: 14px;
  color: #666;
  margin: 0;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 8px;
  color: #666;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.nav-item:hover {
  background: #f5f5f5;
  color: #1A1A1A;
}

.nav-item.active {
  background: rgba(218, 87, 97, 0.1);
  color: var(--mandy);
  font-weight: 600;
}

.nav-icon {
  font-size: 20px;
}

.sidebar-footer {
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid #e0e0e0;
}

.logout-button {
  width: 100%;
  padding: 12px;
  background: white;
  color: #666;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.logout-button:hover {
  background: #f5f5f5;
  color: var(--mandy);
  border-color: var(--mandy);
}

/* コンテンツエリア */
.account-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  padding: 32px;
}

.content-section h2 {
  font-size: 24px;
  color: #1A1A1A;
  margin: 0 0 24px 0;
  padding-bottom: 12px;
  border-bottom: 2px solid #f0f0f0;
}

/* アカウント情報 */
.info-card {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 24px;
  margin-bottom: 24px;
}

.info-row {
  display: flex;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #e0e0e0;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row label {
  flex: 0 0 150px;
  font-size: 14px;
  color: #666;
  font-weight: 500;
}

.info-value {
  flex: 1;
  font-size: 15px;
  color: #1A1A1A;
}

.edit-button {
  padding: 12px 32px;
  background: var(--mandy);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.edit-button:hover {
  background: var(--hot-pink);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(218, 87, 97, 0.3);
}

/* ボタン統一（黒/ピンク） */
.primary-button,
.save-button,
.edit-button,
.upgrade-button {
  background: var(--mandy, #DA5761);
  color: #fff;
  border: none;
  outline: none;
  border-radius: 8px;
  box-shadow: 0px 1px 2px #0000000d;
  padding: 12px 24px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
}

.primary-button:hover,
.save-button:hover,
.edit-button:hover,
.upgrade-button:hover {
  background: var(--hot-pink, #E56B75);
  transform: translateY(-2px);
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
}

.secondary-button,
.password-button,
.redownload-button,
.view-btn,
.directory-link-btn {
  background: #fff;
  color: var(--black, #1A1A1A);
  border: none;
  outline: none;
  border-radius: 8px;
  padding: 10px 16px;
  font-weight: 600;
  box-shadow: 0px 1px 2px #0000000d;
  transition: all 0.3s ease;
}

.secondary-button:hover,
.password-button:hover,
.redownload-button:hover,
.view-btn:hover,
.directory-link-btn:hover {
  background: #f2f2f2;
  transform: translateY(-1px);
}

.cancel-button {
  background: #f2f2f2;
  color: #333;
  border: none;
  border-radius: 8px;
  padding: 12px 20px;
  box-shadow: 0px 1px 2px #0000000d;
}

.cancel-button:hover {
  background: #e8e8e8;
}

.danger-outline-button,
.remove-btn {
  background: #fff;
  color: var(--mandy, #DA5761);
  border: 1px solid var(--mandy, #DA5761);
  border-radius: 8px;
  padding: 10px 16px;
  font-weight: 600;
}

.danger-outline-button:hover,
.remove-btn:hover {
  background: var(--mandy, #DA5761);
  color: #fff;
}

/* 会員プラン */
.membership-card {
  border-radius: 12px;
  padding: 24px;
  background: linear-gradient(135deg, #f5f5f5 0%, #ebebeb 100%);
}

/* 編集フォーム（２枚目の画像の改善） */
.edit-form .form-row { display: flex; gap: 16px; }
.edit-form .form-group { flex: 1; display: flex; flex-direction: column; gap: 6px; margin-bottom: 12px; }
.edit-form label { font-size: 14px; color: #555; font-weight: 600; }
.edit-form .form-input, .edit-form .form-textarea {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 14px;
  outline: none;
  transition: border-color .2s ease, box-shadow .2s ease;
}
.edit-form .form-input:focus, .edit-form .form-textarea:focus {
  border-color: var(--mandy, #DA5761);
  box-shadow: 0 0 0 3px rgba(218,87,97,0.15);
}
.edit-form .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
@media (max-width: 768px) { .edit-form .form-row { flex-direction: column; } }

.membership-card.membership-basic {
  background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
}

.membership-card.membership-standard {
  background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
}

.membership-card.membership-premium {
  background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
}

.membership-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.membership-header h3 {
  font-size: 20px;
  color: #1A1A1A;
  margin: 0;
}

.membership-badge {
  padding: 6px 16px;
  background: white;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  color: #666;
}

.membership-features {
  margin-bottom: 24px;
}

.membership-features h4 {
  font-size: 16px;
  color: #1A1A1A;
  margin: 0 0 12px 0;
}

.membership-features ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.membership-features li {
  padding: 8px 0;
  color: #666;
  font-size: 14px;
}

.upgrade-section {
  background: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 16px;
}

.upgrade-section p {
  font-size: 14px;
  color: #666;
  margin: 0 0 16px 0;
}

.upgrade-button {
  padding: 12px 24px;
  background: linear-gradient(135deg, var(--mandy) 0%, var(--hot-pink) 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.upgrade-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(218, 87, 97, 0.3);
}

.expiry-info {
  text-align: center;
  padding-top: 16px;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  color: #666;
  font-size: 14px;
}

/* ダウンロード履歴 */
.downloads-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.download-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  background: #f9f9f9;
  border-radius: 8px;
  transition: background 0.3s ease;
}

.download-item:hover {
  background: #f0f0f0;
}

.download-info h4 {
  font-size: 15px;
  color: #1A1A1A;
  margin: 0 0 4px 0;
}

.download-date {
  font-size: 13px;
  color: #666;
}

/* redownloadはsecondary-buttonの共通ルールを使用 */

/* お気に入り */
.favorites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

/* 設定 */
.settings-card {
  background: #f9f9f9;
  border-radius: 8px;
  padding: 24px;
  margin-bottom: 24px;
}

.settings-card h3 {
  font-size: 18px;
  color: #1A1A1A;
  margin: 0 0 20px 0;
}

.setting-item {
  margin-bottom: 16px;
}

.setting-label {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  font-size: 15px;
  color: #1A1A1A;
}

.setting-label input[type="checkbox"] {
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.save-button {
  padding: 12px 32px;
  background: var(--mandy);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.save-button:hover {
  background: var(--hot-pink);
}

.danger-zone {
  background: #fff5f5;
  border: 1px solid #ffcccc;
}

.danger-zone p {
  font-size: 14px;
  color: #666;
  margin: 0 0 16px 0;
}

.delete-button {
  padding: 12px 24px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.delete-button:hover {
  background: #c82333;
}

/* モーダル */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal {
  background: #fff;
  width: min(680px, 92vw);
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.modal h3 {
  margin: 0 0 16px 0;
}
.error-text {
  color: #d32f2f;
  font-size: 14px;
}

/* 空状態 */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #999;
}

.empty-state p {
  font-size: 16px;
  margin: 0;
}

/* レスポンシブ */
@media (max-width: 768px) {
  .account-container {
    grid-template-columns: 1fr;
  }
  
  .account-sidebar {
    position: static;
  }
  
  .favorites-grid {
    grid-template-columns: 1fr;
  }
}
</style>
