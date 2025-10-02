<template>
  <div class="seminar-page">
    <!-- Navigation -->
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      heroImage="/img/Image_fx6.jpg"
      mediaKey="hero_seminars_current"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー', pageTitle]" />

    <!-- CMS Body (optional) -->
    <!-- CMS Body removed -->

    <!-- Current Seminars Section -->
    <div class="current-seminars-section">
      <div class="seminars-container">
        <div class="section-header">
          <h2 class="section-title">現在予約受付中のセミナー</h2>
          <div class="section-divider">
            <div class="divider-line"></div>
            <span class="divider-text">seminar</span>
            <div class="divider-line"></div>
          </div>
          <p class="section-description">現在予約受付中のセミナーです。ご興味の方は、ご予約をしてください</p>
        </div>

        <div class="seminars-content">
          <div 
            class="seminar-card" 
            v-for="(seminar, index) in currentSeminars" 
            :key="seminar.id || index"
          >
            <div class="seminar-image" :class="{ blurred: shouldBlur(seminar) }">
              <img :src="seminar.image" :alt="seminar.title" />
              <MembershipBadge 
                v-if="seminar.membershipRequirement && seminar.membershipRequirement !== 'free'" 
                :level="seminar.membershipRequirement" 
                class="seminar-badge"
              />
            </div>
            <div class="seminar-details">
              <div class="seminar-info">
                <div class="info-row">
                  <div class="info-row-first">
                    <div class="info-label">
                      <span class="label-text">セミナー予約受付時間</span>
                    </div>
                    <div class="info-value">{{ seminar.reservationPeriod }}</div>
                  </div>
                </div>
                <div class="info-row">
                  <div class="info-row-second">
                    <div class="info-label info-label-date">
                      <span class="label-text">セミナー日時</span>
                    </div>
                    <div class="info-value">{{ seminar.date }}</div>
                  </div>
                </div>
                <div class="info-row">
                  <div class="info-row-third">
                    <div class="info-label info-label-title">
                      <span class="label-text">セミナータイトル</span>
                    </div>
                    <div class="info-value">{{ seminar.title }}</div>
                  </div>
                </div>
                <div class="info-row content-row">
                  <div class="info-row-fourth">
                    <div class="info-label">
                      <span class="label-text">セミナー内容</span>
                    </div>
                    <div class="info-value content-text">{{ seminar.content }}</div>
                  </div>
                </div>
              </div>
              <button 
                class="reserve-btn" 
                :class="{ disabled: shouldBlur(seminar) }"
                @click="handleReservation(seminar)"
              >
                <span>{{ getReservationButtonText() }}</span>
                <svg width="18" height="19" viewBox="0 0 18 19" fill="none">
                  <rect y="0.5" width="18" height="18" rx="5" fill="white"/>
                  <path d="M13.7193 9.84548L10.2194 13.4453C10.1464 13.5203 10.0475 13.5625 9.94427 13.5625C9.84107 13.5625 9.74211 13.5203 9.66914 13.4453C9.59617 13.3702 9.55517 13.2684 9.55517 13.1623C9.55517 13.0562 9.59617 12.9544 9.66914 12.8793L12.5055 9.96248L4.88888 9.96248C4.78574 9.96248 4.68683 9.92034 4.6139 9.84533C4.54097 9.77032 4.5 9.66858 4.5 9.5625C4.5 9.45642 4.54097 9.35468 4.6139 9.27967C4.68683 9.20466 4.78574 9.16252 4.88888 9.16252L12.5055 9.16252L9.66914 6.24568C9.59617 6.17063 9.55517 6.06884 9.55517 5.9627C9.55517 5.85656 9.59617 5.75477 9.66914 5.67972C9.74211 5.60466 9.84107 5.5625 9.94427 5.5625C10.0475 5.5625 10.1464 5.60466 10.2194 5.67972L13.7193 9.27951C13.7554 9.31666 13.7841 9.36078 13.8037 9.40933C13.8233 9.45789 13.8333 9.50994 13.8333 9.5625C13.8333 9.61506 13.8233 9.66711 13.8037 9.71567C13.7841 9.76422 13.7554 9.80834 13.7193 9.84548Z" fill="#9C3940"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Pagination -->
          <div class="pagination">
            <button 
              class="pagination-btn" 
              :class="{ active: currentPage === 1 }"
              @click="goToPage(1)"
            >
              1
            </button>
            <button 
              class="pagination-btn" 
              :class="{ active: currentPage === 2 }"
              @click="goToPage(2)"
            >
              2
            </button>
            <button 
              class="pagination-btn" 
              :class="{ active: currentPage === 3 }"
              @click="goToPage(3)"
            >
              3
            </button>
            <span class="pagination-dots">...</span>
            <button 
              class="pagination-btn" 
              :class="{ active: currentPage === 10 }"
              @click="goToPage(10)"
            >
              10
            </button>
            <button 
              class="pagination-btn next-btn"
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage >= 10"
            >
              最後
            </button>
          </div>

          <!-- Back Button -->
          <div class="back-container">
            <button class="back-btn" @click="goBack">前に戻る</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Section -->
    <ContactSection />

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer Navigation -->
    <Footer v-bind="frame132131753022Props" />

    <!-- Fixed Side Buttons -->
    <FixedSideButtons position="bottom" />
  </div>
</template>

<script>
import Navigation from "./Navigation";
import Footer from "./Footer";
import Group27 from "./Group27";
import AccessSection from "./AccessSection.vue";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import ContactSection from "./ContactSection.vue";
import CmsBlock from './CmsBlock.vue'
import { frame132131753022Data } from "../data";
import apiClient from '@/services/apiClient.js'
import MembershipBadge from './MembershipBadge.vue'
import { usePageText } from '@/composables/usePageText'

export default {
  name: "CurrentSeminarsPage",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ContactSection,
    CmsBlock,
    MembershipBadge
  },
  data() {
    return {
      pageKey: 'seminars-current',
      frame132131753022Props: frame132131753022Data,
      currentPage: 1,
      itemsPerPage: 10,
      allCurrentSeminars: [],
      seminarsFromServer: []
    };
  },
  async mounted() {
    await this.loadSeminars()
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '受付中のセミナー') || '受付中のセミナー' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'current seminars') || 'current seminars' },
    currentSeminars() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.seminarsFromServer.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.seminarsFromServer.length / this.itemsPerPage) || 1;
    },
    // ローカルキャッシュベースの簡易ログイン判定
    loggedInCached() {
      try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken')
        const user = localStorage.getItem('memberUser')
        return !!token && !!user
      } catch (e) { return false }
    }
  },
  methods: {
    async loadSeminars() {
      try {
        // API優先: 開催予定/開催中のみ取得
        const params = { per_page: 50 }
        const res = await apiClient.getSeminars(params)
        if (res.success && res.data && Array.isArray(res.data.seminars)) {
          const toTs = (s) => {
            const ts = Date.parse(`${s.date} ${s.start_time || '00:00'}`)
            return isNaN(ts) ? Date.parse(s.date) : ts
          }
          const currentSeminars = res.data.seminars
            .filter(s => ['scheduled', 'ongoing'].includes(s.status))
            .sort((a, b) => toTs(b) - toTs(a))
          this.seminarsFromServer = currentSeminars.map(s => ({
            id: s.id,
            image: s.featured_image_url || s.featured_image || '/img/image-1.png',
            reservationPeriod: `${s.start_time || '10:00'}〜${s.end_time || '12:00'}`,
            date: this.formatDate(s.date),
            title: s.title,
            content: s.description,
            membershipRequirement: s.membership_requirement || 'free'
          }))
        }
      } catch (error) {
        console.error('セミナーデータの取得に失敗:', error)
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      const year = date.getFullYear()
      const month = date.getMonth() + 1
      const day = date.getDate()
      return `${year}年${month}月${day}日`
    },
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    goToSeminarDetail(seminar) {
      // 実際のIDを使用（APIからのデータに含まれている）
      const seminarId = seminar.id || this.generateSeminarId(seminar);
      this.$router.push(`/seminar/${seminarId}`);
    },
    goBack() {
      if (window.history.length > 1) this.$router.go(-1)
      else this.$router.push('/')
    },
    generateSeminarId(seminar) {
      // セミナーのタイトルと日付からIDを生成（フォールバック用）
      return encodeURIComponent(seminar.title + '-' + seminar.date);
    },
    canAccessSeminar(seminar) {
      const requiredLevel = seminar.membershipRequirement || 'free';
      return this.$store.getters['auth/canAccess'](requiredLevel);
    },
    getReservationButtonText() {
      return '予約する';
    },
    shouldBlur(seminar) {
      const level = seminar.membershipRequirement || 'free'
      if (!['premium','standard'].includes(level)) return false
      return !this.$store.getters['auth/canAccess'](level)
    },
    getMembershipText(level) {
      switch (level) {
        case 'standard':
          return 'スタンダード';
        case 'premium':
          return 'プレミアム';
        default:
          return '会員';
      }
    },
    handleReservation(seminar) {
      // 一覧からは常に詳細ページへ
      const id = seminar.id || this.generateSeminarId(seminar)
      this.$router.push(`/seminar/${id}`)
    },
    async directRegister(seminar) {
      try {
        const profile = this.getLoggedInProfile()
        if (!profile || !profile.email) {
          this.$router.push('/member-login');
          return
        }
        const payload = {
          name: profile.name || '会員',
          email: profile.email,
          company: profile.company || '',
          phone: profile.phone || '',
          special_requests: ''
        }
        const id = seminar.id || this.generateSeminarId(seminar)
        const res = await apiClient.registerForSeminar(id, payload)
        if (res && res.success) {
          alert('予約を受け付けました。マイページで確認できます。')
          // マイページに反映：バックエンド登録済みなので、必要ならリロード後に遷移
          // this.$router.push('/myaccount')
        } else {
          throw new Error(res?.error || '予約に失敗しました')
        }
      } catch (e) {
        console.error('directRegister error', e)
        alert('予約に失敗しました。時間をおいて再度お試しください。')
      }
    },
    getLoggedInProfile() {
      try {
        // Vuexにユーザーがある場合はそちらを優先
        const user = this.$store?.state?.auth?.user || this.$store?.state?.auth?.member
        if (user && user.email) {
          return {
            name: user.full_name || user.name || user.company_name || '会員',
            email: user.email,
            company: user.company_name || '',
            phone: user.phone || ''
          }
        }
        // localStorage フォールバック
        const ls = localStorage.getItem('memberUser')
        if (ls) {
          const u = JSON.parse(ls)
          return {
            name: u.full_name || u.name || u.company_name || '会員',
            email: u.email,
            company: u.company_name || '',
            phone: u.phone || ''
          }
        }
      } catch (_) {}
      return null
    }
  }
};
</script>

<style scoped>
:root {
  --color-primary: #1A1A1A;
  --color-secondary: #3F3F3F;
  --color-accent: #DA5761;
  --color-accent-dark: #9C3940;
  --color-gray: #727272;
  --color-light-gray: #F6F6F6;
  --color-light-pink: #FDF6F7;
  --color-border: #CFCFCF;
  --color-white: #FFFFFF;
  --color-background: #ECECEC;
}

.seminar-page {
  width: 100%;
  background-color: #ECECEC;
  position: relative;
}

/* Hero Section and Breadcrumb styles are now handled by components */

/* Section Headers */
.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 50px;
}

.section-title {
  color: #1A1A1A;
  font-size: 36px;
  font-weight: 700;
  text-align: center;
  letter-spacing: -0.72px;
}

.section-divider {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
}

.divider-line {
  width: 69px;
  height: 2px;
  background: #DA5761;
}

.divider-text {
  color: #DA5761;
  font-size: 20px;
  font-weight: 700;
}

.section-description {
  color: var(--color-primary);
  text-align: center;
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  letter-spacing: -0.36px;
  width: 1014px;
  margin: 0;
}

/* Current Seminars Section */
.current-seminars-section {
  padding: 50px;
  background: var(--color-background);
}

.seminars-container {
  max-width: 2000px;
  margin: 0 auto;
}

.seminars-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
  padding: 50px;
  border-radius: 20px;
  background: #FFFFFF;
}

.seminar-card {
  display: flex;
  width: 100%;
  align-items: stretch;
  border-radius: 20px;
  background: #FDF6F7;
}

.seminar-image {
  width: 30%;
  flex-shrink: 0;
  border-radius: 20px 0 0 20px;
  overflow: hidden;
  position: relative;
}

.seminar-image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.seminar-details {
  display: flex;
  width: 70%;
  padding: 30px;
  flex-direction: column;
  align-items: flex-end;
  gap: 20px;
  flex-shrink: 0;
}

.seminar-info {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  align-self: stretch;
  border-top: 0.5px dashed var(--color-accent);
}

.info-row {
  display: flex;
  padding: 15px 0;
  align-items: center;
  gap: 50px;
  border-bottom: 0.5px dashed #DA5761;
  width: 100%;
}

.info-row:first-child {
  border-top: 0.5px dashed #DA5761;
}

.info-row-first {
  display: flex;
  align-items: center;
  gap: 30px;
}

.info-row-second {
  display: flex;
  align-items: center;
  gap: 30px;
}

.info-row-third {
  display: flex;
  align-items: center;
  gap: 30px;
}

.info-row-fourth {
  display: flex;
  align-items: flex-start;
  gap: 30px;
}

.content-row {
  align-items: center;
  min-height: 96px;
}

.info-label {
  display: flex;
  padding: 10px 30px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 5px;
  background: #727272;
  white-space: nowrap;
  width: 250px;
}

.info-label-date {
  width: 250px;
}

.info-label-title {
  width: 250px;
}

.label-text {
  color: #FFFFFF;
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
}

.info-value {
  color: var(--color-secondary);
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
}

.content-text {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.reserve-btn {
  display: flex;
  width: 300px;
  padding: 10px 0;
  align-items: center;
  justify-content: center;
  gap: 10px;
  border-radius: 10px;
  background: #9C3940;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.reserve-btn span {
  color: #FFFFFF;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.reserve-btn:hover {
  background: var(--color-accent);
}

/* Disabled visual state (clickable for navigation) */
.reserve-btn.disabled {
  background: #9C3940;
  cursor: not-allowed;
}

.show-more-btn {
  display: flex;
  width: 300px;
  padding: 10px 0;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 10px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  color: #ffffff;
}

.show-more-btn span {
  color: #ffffff;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.show-more-btn:hover {
  background: var(--color-secondary);
}

/* Back Button */
.back-container {
  display: flex;
  justify-content: center;
  margin-top: 14px;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 24px;
  min-width: 180px;
  border-radius: 10px;
  border: none;
  color: #fff;
  background: #1A1A1A;
  font-weight: 700;
  cursor: pointer;
  transition: background-color .2s ease, opacity .2s ease;
}

.back-btn:hover {
  background: #333333;
}

/* Membership Badge Styles */
.seminar-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 2;
}

/* Restricted Content Styles */
.restricted-content {
  position: relative;
  filter: blur(4px);
  user-select: none;
  pointer-events: none;
}

.restriction-overlay-inline {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #333;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 3;
  pointer-events: none;
}

.lock-icon {
  font-size: 16px;
}

/* Disabled Button Styles */
.reserve-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  background-color: #e0e0e0;
}

.seminar-card {
  position: relative;
}

.seminar-image {
  position: relative;
}

/* 会員限定で未アクセス時は画像のみぼかす（/seminar と同仕様） */
.seminar-image.blurred img {
  filter: blur(4px);
  transform: scale(1.03);
}

/* Pagination Styles */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-top: 30px;
}

.pagination-btn {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border-radius: 5px;
  background: #FFFFFF;
  border: 1px solid #CFCFCF;
  cursor: pointer;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 500;
  color: var(--color-secondary);
  transition: all 0.3s ease;
}

.pagination-btn:hover {
  background: var(--color-accent);
  color: #FFFFFF;
  border-color: var(--color-accent);
}

.pagination-btn.active {
  background: #1A1A1A;
  color: #FFFFFF;
  border-color: #1A1A1A;
}

.pagination-btn:disabled {
  background: #F6F6F6;
  color: #B2B2B2;
  cursor: not-allowed;
  border-color: #E0E0E0;
}

.pagination-dots {
  color: var(--color-secondary);
  font-size: 14px;
  font-weight: 500;
}

.next-btn {
  width: 60px;
}

/* Contact Banner styles are now handled by ContactSection component */

/* Access Section styles are now handled by AccessSection component */

/* Floating Action Buttons styles are now handled by FixedSideButtons component */

/* Footer Navigation */

/* Responsive Design */
@media (max-width: 1150px) {
  /* セクションのパディング調整 */
  .current-seminars-section {
    padding: 50px 30px !important;
  }

  .seminars-content {
    padding: 40px !important;
  }

  /* レイアウトの縦並び化 */
  .seminar-card {
    flex-direction: column !important;
    gap: 0 !important;
  }

  /* 要素の幅調整 */
  .seminar-image,
  .seminar-details {
    width: 100% !important;
  }
  
  /* 画像の高さ固定 */
  .seminar-image {
    height: 300px !important;
    border-radius: 20px 20px 0 0 !important;
  }
  
  .seminar-details {
    border-radius: 0 0 20px 20px !important;
    padding: 40px !important;
  }
  
  /* フォントサイズ調整 */
  .section-title {
    font-size: 32px !important;
  }

  .divider-text {
    font-size: 18px !important;
  }

  .section-description {
    font-size: 18px !important;
  }
  
  .label-text {
    font-size: 18px !important;
  }
  
  .info-value {
    font-size: 18px !important;
  }
  
  .reserve-btn span {
    font-size: 18px !important;
  }
  
  /* 情報行の縦並び化 */
  .info-row {
    flex-direction: column !important;
    gap: 15px !important;
    padding: 20px 0 !important;
    align-items: flex-start !important;
  }

  .info-row-first,
  .info-row-second,
  .info-row-third,
  .info-row-fourth {
    flex-direction: column !important;
    gap: 10px !important;
    align-items: flex-start !important;
    width: 100% !important;
  }

  .info-label {
    width: 250px !important;
    justify-content: flex-start !important;
    text-align: left !important;
    flex-shrink: 0 !important;
    min-width: 250px !important;
  }

  .info-label-date,
  .info-label-title {
    width: 250px !important;
    flex-shrink: 0 !important;
    min-width: 250px !important;
  }

  .info-value {
    width: 100% !important;
    padding-left: 0 !important;
    text-align: left !important;
  }
  
  .pagination-btn {
    width: 38px !important;
    height: 38px !important;
    font-size: 13px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 25px !important;
  }
}

@media (max-width: 900px) {
  /* セクションのパディング調整 */
  .current-seminars-section {
    padding: 30px 20px !important;
  }

  .seminars-content {
    padding: 35px !important;
  }

  /* レイアウトの縦並び化 */
  .seminar-card {
    flex-direction: column !important;
    gap: 0 !important;
  }

  /* 要素の幅調整 */
  .seminar-image,
  .seminar-details {
    width: 100% !important;
  }
  
  /* 画像の高さ固定 */
  .seminar-image {
    height: 280px !important;
    border-radius: 20px 20px 0 0 !important;
  }
  
  .seminar-details {
    border-radius: 0 0 20px 20px !important;
    padding: 35px !important;
  }
  
  /* フォントサイズ調整 */
  .section-title {
    font-size: 29px !important;
  }

  .divider-text {
    font-size: 17px !important;
  }

  .section-description {
    font-size: 17px !important;
  }
  
  .label-text {
    font-size: 17px !important;
  }
  
  .info-value {
    font-size: 17px !important;
  }
  
  .reserve-btn span {
    font-size: 17px !important;
  }
  
  .pagination-btn {
    width: 36px !important;
    height: 36px !important;
    font-size: 12px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 22px !important;
  }
  
  /* 情報行の縦並び化 */
  .info-row {
    flex-direction: column !important;
    gap: 15px !important;
    padding: 20px 0 !important;
    align-items: flex-start !important;
  }

  .info-row-first,
  .info-row-second,
  .info-row-third,
  .info-row-fourth {
    flex-direction: column !important;
    gap: 10px !important;
    align-items: flex-start !important;
    width: 100% !important;
  }

  .info-label {
    width: 250px !important;
    justify-content: flex-start !important;
    text-align: left !important;
    flex-shrink: 0 !important;
    min-width: 250px !important;
  }

  .info-label-date,
  .info-label-title {
    width: 250px !important;
    flex-shrink: 0 !important;
    min-width: 250px !important;
  }

  .info-value {
    width: 100% !important;
    padding-left: 0 !important;
    text-align: left !important;
  }
}

@media (max-width: 768px) {
  /* セクションのパディング調整 */
  .current-seminars-section {
    padding: 30px 20px !important;
  }

  .seminars-content {
    padding: 30px !important;
  }

  /* レイアウトの縦並び化 */
  .seminar-card {
    flex-direction: column !important;
    gap: 0 !important;
  }

  /* 要素の幅調整 */
  .seminar-image,
  .seminar-details {
    width: 100% !important;
  }
  
  /* 画像の高さ固定 */
  .seminar-image {
    height: 250px !important;
    border-radius: 20px 20px 0 0 !important;
  }
  
  .seminar-details {
    border-radius: 0 0 20px 20px !important;
    padding: 30px !important;
  }
  
  /* フォントサイズ調整 */
  .section-title {
    font-size: 27px !important;
  }

  .divider-text {
    font-size: 16px !important;
  }

  .section-description {
    width: 100% !important;
    font-size: 16px !important;
  }
  
  .label-text {
    font-size: 16px !important;
  }
  
  .info-value {
    font-size: 16px !important;
  }
  
  .reserve-btn span {
    font-size: 16px !important;
  }
  
  .reserve-btn {
    padding: 10px 80px !important;
  }
  
  .pagination-btn {
    width: 34px !important;
    height: 34px !important;
    font-size: 11px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 20px !important;
  }
}

@media (max-width: 480px) {
  /* セクションのパディング調整 */
  .current-seminars-section {
    padding: 20px 15px !important;
  }

  .seminars-content {
    padding: 20px !important;
  }

  /* レイアウトの縦並び化 */
  .seminar-card {
    flex-direction: column !important;
    gap: 0 !important;
  }

  /* 要素の幅調整 */
  .seminar-image,
  .seminar-details {
    width: 100% !important;
  }
  
  /* 画像の高さ固定 */
  .seminar-image {
    height: 200px !important;
    border-radius: 15px 15px 0 0 !important;
  }
  
  .seminar-details {
    border-radius: 0 0 15px 15px !important;
    padding: 20px !important;
  }
  
  /* フォントサイズ調整 */
  .section-title {
    font-size: 22px !important;
  }

  .divider-text {
    font-size: 13px !important;
  }

  .section-description {
    font-size: 13px !important;
  }
  
  .label-text {
    font-size: 13px !important;
  }
  
  .info-value {
    font-size: 13px !important;
  }
  
  .reserve-btn span {
    font-size: 13px !important;
  }
  
  .reserve-btn {
    width: 100% !important;
    padding: 8px 0 !important;
  }
  
  .pagination-btn {
    width: 32px !important;
    height: 32px !important;
    font-size: 10px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 18px !important;
  }
  
  /* 情報行のギャップ調整 */
  .info-row {
    gap: 20px !important;
    padding: 10px 0 !important;
  }
  
  .info-label {
    padding: 8px 20px !important;
    width: auto !important;
    flex-shrink: 0 !important;
    min-width: auto !important;
  }
  
  .info-label-date,
  .info-label-title {
    width: auto !important;
    flex-shrink: 0 !important;
    min-width: auto !important;
  }
}
</style>
