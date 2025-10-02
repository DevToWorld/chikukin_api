<template>
  <div class="seminar-page">
    <!-- Navigation -->
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="セミナー"
      subtitle="seminar"
      heroImage="/img/Image_fx6.jpg"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー（一覧）']" />

    <!-- Introduction Section -->
    <div class="intro-section">
      <div class="intro-container">
        <div class="intro-content">
          <div class="intro-text">
            ちくぎん地域経済研究所の研究員、コンサルタントが講師を務める<br>
            セミナー・イベントの情報や、スペシャリストレポートへの出演情報をご紹介します。
          </div>
          <div class="intro-buttons">
            <button class="intro-btn upcoming-btn" @click="goToCurrentSeminars">
              <span>開催予定のセミナー</span>
              <div class="btn-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                  <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#1A1A1A"/>
                </svg>
              </div>
            </button>
            <button class="intro-btn past-btn" @click="goToPastSeminars">
              <span>過去の開催されたセミナー</span>
              <div class="btn-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                  <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#1A1A1A"/>
                </svg>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>

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
          <div class="seminar-card" v-for="(seminar, index) in currentSeminars" :key="index">
            <div class="seminar-image" :class="{ blurred: shouldBlur(seminar) }">
              <img :src="seminar.image" :alt="seminar.title" />
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
              <button class="reserve-btn" :class="{ disabled: isRestricted(seminar) }" @click="handleReservation(seminar)">
                <span>{{ getReservationButtonText() }}</span>
                <svg width="18" height="19" viewBox="0 0 18 19" fill="none">
                  <rect y="0.5" width="18" height="18" rx="5" fill="white"/>
                  <path d="M13.7193 9.84548L10.2194 13.4453C10.1464 13.5203 10.0475 13.5625 9.94427 13.5625C9.84107 13.5625 9.74211 13.5203 9.66914 13.4453C9.59617 13.3702 9.55517 13.2684 9.55517 13.1623C9.55517 13.0562 9.59617 12.9544 9.66914 12.8793L12.5055 9.96248L4.88888 9.96248C4.78574 9.96248 4.68683 9.92034 4.6139 9.84533C4.54097 9.77032 4.5 9.66858 4.5 9.5625C4.5 9.45642 4.54097 9.35468 4.6139 9.27967C4.68683 9.20466 4.78574 9.16252 4.88888 9.16252L12.5055 9.16252L9.66914 6.24568C9.59617 6.17063 9.55517 6.06884 9.55517 5.9627C9.55517 5.85656 9.59617 5.75477 9.66914 5.67972C9.74211 5.60466 9.84107 5.5625 9.94427 5.5625C10.0475 5.5625 10.1464 5.60466 10.2194 5.67972L13.7193 9.27951C13.7554 9.31666 13.7841 9.36078 13.8037 9.40933C13.8233 9.45789 13.8333 9.50994 13.8333 9.5625C13.8333 9.61506 13.8233 9.66711 13.8037 9.71567C13.7841 9.76422 13.7554 9.80834 13.7193 9.84548Z" fill="#9C3940"/>
                </svg>
              </button>
            </div>
          </div>

          <button class="show-more-btn" @click="goToCurrentSeminars">
            <span>さらに表示</span>
            <svg width="19" height="19" viewBox="0 0 19 19" fill="none">
              <rect x="0.5" y="0.5" width="18" height="18" rx="5" fill="white"/>
              <path d="M13.7193 10.2752L10.2194 13.875C10.1464 13.95 10.0475 13.9922 9.94427 13.9922C9.84107 13.9922 9.74211 13.95 9.66914 13.875C9.59617 13.7999 9.55517 13.6981 9.55517 13.592C9.55517 13.4858 9.59617 13.3841 9.66914 13.309L12.5055 10.3922L4.88888 10.3922C4.78574 10.3922 4.68683 10.35 4.6139 10.275C4.54097 10.2 4.5 10.0983 4.5 9.99219C4.5 9.88611 4.54097 9.78437 4.6139 9.70936C4.68683 9.63435 4.78574 9.59221 4.88888 9.59221L12.5055 9.59221L9.66914 6.67537C9.59617 6.60032 9.55517 6.49853 9.55517 6.39239C9.55517 6.28625 9.59617 6.18446 9.66914 6.1094C9.74211 6.03435 9.84107 5.99219 9.94427 5.99219C10.0475 5.99219 10.1464 6.03435 10.2194 6.1094L13.7193 9.7092C13.7554 9.74635 13.7841 9.79046 13.8037 9.83902C13.8233 9.88758 13.8333 9.93962 13.8333 9.99219C13.8333 10.0448 13.8233 10.0968 13.8037 10.1454C13.7841 10.1939 13.7554 10.238 13.7193 10.2752Z" fill="#1A1A1A"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Past Seminars Section -->
    <div class="past-seminars-section">
      <div class="seminars-container">
        <div class="section-header">
          <h2 class="section-title">過去のセミナー</h2>
          <div class="section-divider">
            <div class="divider-line"></div>
            <span class="divider-text">seminar</span>
            <div class="divider-line"></div>
          </div>
          <p class="section-description">以前行われたセミナーのバックナンバーです。</p>
        </div>

        <div class="past-seminars-content">
          <!-- 過去セミナーがある場合 -->
          <template v-if="pastSeminars.length > 0">
            <div class="past-seminar-card" v-for="(seminar, index) in pastSeminars" :key="index" @click="goToSeminarDetail(seminar)">
              <div class="past-seminar-info">
                <div class="past-info-row">
                  <div class="info-label info-label-date">
                    <span class="label-text">セミナー日時</span>
                  </div>
                  <div class="info-value">{{ seminar.date }}</div>
                </div>
                <div class="past-info-row">
                  <div class="info-label info-label-title">
                    <span class="label-text">セミナータイトル</span>
                  </div>
                  <div class="info-value">{{ seminar.title }}</div>
                </div>
                <div class="past-info-row content-row">
                  <div class="info-label">
                    <span class="label-text">セミナー内容</span>
                  </div>
                  <div class="info-value content-text">{{ seminar.content }}</div>
                </div>
              </div>
            </div>

            <button class="show-more-btn past-show-more" @click="goToPastSeminars">
              <span>さらに表示</span>
              <svg width="19" height="19" viewBox="0 0 19 19" fill="none">
                <rect x="0.5" y="0.5" width="18" height="18" rx="5" fill="white"/>
                <path d="M13.7193 10.2752L10.2194 13.875C10.1464 13.95 10.0475 13.9922 9.94427 13.9922C9.84107 13.9922 9.74211 13.95 9.66914 13.875C9.59617 13.7999 9.55517 13.6981 9.55517 13.592C9.55517 13.4858 9.59617 13.3841 9.66914 13.309L12.5055 10.3922L4.88888 10.3922C4.78574 10.3922 4.68683 10.35 4.6139 10.275C4.54097 10.2 4.5 10.0983 4.5 9.99219C4.5 9.88611 4.54097 9.78437 4.6139 9.70936C4.68683 9.63435 4.78574 9.59221 4.88888 9.59221L12.5055 9.59221L9.66914 6.67537C9.59617 6.60032 9.55517 6.49853 9.55517 6.39239C9.55517 6.28625 9.59617 6.18446 9.66914 6.1094C9.74211 6.03435 9.84107 5.99219 9.94427 5.99219C10.0475 5.99219 10.1464 6.03435 10.2194 6.1094L13.7193 9.7092C13.7554 9.74635 13.7841 9.79046 13.8037 9.83902C13.8233 9.88758 13.8333 9.93962 13.8333 9.99219C13.8333 10.0448 13.8233 10.0968 13.8037 10.1454C13.7841 10.1939 13.7554 10.238 13.7193 10.2752Z" fill="#1A1A1A"/>
              </svg>
            </button>
          </template>
          
          <!-- 過去セミナーがない場合 -->
          <template v-else>
            <div class="no-past-seminars">
              <p class="no-seminars-message">過去セミナーはありません</p>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Contact Section -->
    <ContactSection cms-page-key="seminars" />

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
import { frame132131753022Data } from "../data";
import apiClient from '../services/apiClient.js';

export default {
  name: "SeminarPage",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ContactSection,
  },
  data() {
    return {
      frame132131753022Props: frame132131753022Data,
      // APIのみ表示（フォールバック無し）
      currentSeminars: [],
      pastSeminars: []
    };
  },
  async mounted() {
    await Promise.all([
      this.loadCurrentSeminars(),
      this.loadPastSeminars()
    ]);
  },
  methods: {
    async loadCurrentSeminars() {
      try {
        // APIから取得（公開かつ開催予定/開催中を優先表示）
        const res = await apiClient.getSeminars({ per_page: 50 });
        if (res.success && res.data && Array.isArray(res.data.seminars)) {
          const toTs = (s) => {
            const ts = Date.parse(`${s.date} ${s.start_time || '00:00'}`)
            return isNaN(ts) ? Date.parse(s.date) : ts
          }
          const upcoming = res.data.seminars
            .filter(s => ['scheduled', 'ongoing'].includes(s.status))
            .sort((a, b) => toTs(b) - toTs(a))
            .slice(0, 4)
            .map(s => ({
              id: s.id,
              image: s.featured_image_url || s.featured_image || '/img/image-1.png',
              reservationPeriod: `${(s.start_time || '10:00')}～${(s.end_time || '12:00')}`,
              date: this.formatToJaDate(s.date),
              title: s.title,
              content: s.description || '',
              membershipRequirement: s.membership_requirement || 'free'
            }));
          if (upcoming.length > 0) {
            this.currentSeminars = upcoming;
            return;
          }
        }
        // APIのみ運用のためフォールバック無し
      } catch (e) {
        // APIのみ運用のため表示は空
        console.error('セミナーの読み込みに失敗:', e);
      }
    },
    async loadPastSeminars() {
      try {
        // 完了ステータスのセミナーを取得
        const res = await apiClient.getSeminars({ status: 'completed', per_page: 4 });
        if (res.success && res.data && Array.isArray(res.data.seminars)) {
          this.pastSeminars = res.data.seminars.map(s => ({
            id: s.id,
            date: this.formatToJaDate(s.date),
            title: s.title,
            content: s.description || ''
          }));
        }
      } catch (e) {
        console.error('過去セミナーの読み込みに失敗:', e);
        this.pastSeminars = [];
      }
    },
    formatToJaDate(dateString) {
      if (!dateString) return '';
      const d = new Date(dateString);
      if (isNaN(d.getTime())) return dateString;
      return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日`;
    },
    canAccessSeminar(seminar) {
      const level = seminar.membershipRequirement || 'free'
      if (['premium','standard'].includes(level)) {
        return this.$store.getters['auth/canAccess'](level)
      }
      return true
    },
    isRestricted(seminar) {
      const level = seminar.membershipRequirement || 'free'
      if (!['premium','standard'].includes(level)) return false
      return !this.$store.getters['auth/canAccess'](level)
    },
    shouldBlur(seminar) {
      const level = seminar.membershipRequirement || 'free'
      if (!['premium','standard'].includes(level)) return false
      return !this.$store.getters['auth/canAccess'](level)
    },
    getReservationButtonText() {
      return '予約する';
    },
    handleReservation(seminar) {
      // 一覧からは常に詳細ページへ遷移
      const id = seminar.id || this.generateSeminarId(seminar);
      this.$router.push(`/seminar/${id}`);
    },
    goToCurrentSeminars() {
      this.$router.push('/seminars/current');
    },
    goToPastSeminars() {
      this.$router.push('/seminar/archive');
    },
    goToSeminarDetail(seminar) {
      // 実際のIDを使用（APIからのデータに含まれている）
      const seminarId = seminar.id || this.generateSeminarId(seminar);
      this.$router.push(`/seminar/${seminarId}`);
    },
    generateSeminarId(seminar) {
      // セミナーのタイトルと日付からIDを生成（フォールバック用）
      return encodeURIComponent(seminar.title + '-' + seminar.date);
    },
    goToCurrentSeminars() {
      this.$router.push('/seminars/current');
    },
    goToPastSeminars() {
      this.$router.push('/seminar/archive');
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

/* Introduction Section */
.intro-section {
  padding: 70px 50px 50px 50px;
  background: var(--color-white);
}

.intro-container {
  max-width: 2000px;
  margin: 0 auto;
}

.intro-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 30px;
  padding: 50px;
  border-radius: 20px;
  background: #FFFFFF;
}

.intro-text {
  color: var(--color-primary);
  text-align: center;
  font-family: 'Inter', sans-serif;
  font-size: 20px;
  font-weight: 400;
  line-height: 150%;
  max-width: 800px;
  width: 100%;
}

.intro-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 50px;
  width: 100%;
}

.intro-btn {
  display: flex;
  width: 50%;
  padding: 20px 100px;
  justify-content: center;
  align-items: center;
  gap: 20px;
  border-radius: 15px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.intro-btn span {
  color: #FFFFFF;
  font-family: 'Inter', sans-serif;
  font-size: 20px;
  font-weight: 700;
  line-height: 150%;
}

.intro-btn:hover {
  opacity: 0.8;
}

.btn-arrow {
  width: 23px;
  height: 23px;
  flex-shrink: 0;
}

/* Section Headers */
.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 29px;
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
  max-width: 1014px;
  width: 100%;
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

.seminar-image.blurred img {
  filter: blur(4px);
  transform: scale(1.03);
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
  flex-shrink: 0;
  min-width: 250px;
}

.info-label-date {
  width: 250px;
  flex-shrink: 0;
  min-width: 250px;
}

.info-label-title {
  width: 250px;
  flex-shrink: 0;
  min-width: 250px;
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

.reserve-btn.disabled {
  background: #9C3940;
  cursor: not-allowed;
}

.reserve-btn span {
  color: #FFFFFF;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.reserve-btn:hover {
  opacity: 0.8;
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
  transition: all 0.3s ease;
}

.show-more-btn span {
  color: #ffffff;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.show-more-btn:hover {
  opacity: 0.8;
}

/* Past Seminars Section */
.past-seminars-section {
  padding: 50px 50px 80px 50px;
  background: var(--color-background);
}

.past-seminars-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
  padding: 50px;
  border-radius: 20px;
  background: #FFFFFF;
}

.past-seminar-card {
  display: flex;
  padding: 30px;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
  border-radius: 20px;
  background: #F6F6F6;
  width: 100%;
  cursor: pointer;
}

.past-seminar-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  border-top: 0.5px dashed var(--color-secondary);
  width: 100%;
}

.past-seminar-info .info-row {
  border-bottom: 0.5px dashed var(--color-secondary);
}

.past-info-row {
  display: flex;
  padding: 15px 0;
  align-items: center;
  gap: 50px;
  border-bottom: 0.5px dashed #B0B0B0;
  width: 100%;
}

.past-info-row:first-child {
  border-top: 0.5px dashed #B0B0B0;
}

/* Responsive Design */
@media (max-width: 1150px) {
  /* セクションのパディング調整 */
  .intro-section {
    padding: 50px 30px !important;
  }
  
  .current-seminars-section,
  .past-seminars-section {
    padding: 50px 30px !important;
  }

  .intro-content {
    padding: 40px !important;
  }

  .seminars-content,
  .past-seminars-content {
    padding: 40px !important;
  }

  /* レイアウトの縦並び化 */
  .intro-buttons {
    flex-direction: column !important;
    gap: 20px !important;
  }

  .intro-btn {
    width: 100% !important;
    max-width: 590px !important;
  }

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

  .intro-text {
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
  
  .intro-btn span {
    font-size: 18px !important;
  }
  
  .reserve-btn span {
    font-size: 18px !important;
  }
  
  .show-more-btn span {
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

  .past-info-row {
    flex-direction: column !important;
    gap: 10px !important;
    padding: 20px 0 !important;
    align-items: flex-start !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 25px !important;
  }
}

@media (max-width: 900px) {
  /* セクションのパディング調整 */
  .intro-section {
    padding: 30px 20px !important;
  }
  
  .current-seminars-section,
  .past-seminars-section {
    padding: 30px 20px !important;
  }

  .intro-content {
    padding: 35px !important;
  }

  .seminars-content,
  .past-seminars-content {
    padding: 35px !important;
  }
  
  /* 900px以下でbrタグを非表示 */
  .intro-text br {
    display: none !important;
  }

  /* レイアウトの縦並び化 */
  .intro-buttons {
    flex-direction: column !important;
    gap: 20px !important;
  }

  .intro-btn {
    width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

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

  .intro-text {
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
  
  .intro-btn span {
    font-size: 17px !important;
  }
  
  .reserve-btn span {
    font-size: 17px !important;
  }
  
  .show-more-btn span {
    font-size: 17px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 22px !important;
  }
  
  /* 過去セミナーの情報行を縦並び化 */
  .past-info-row {
    flex-direction: column !important;
    gap: 10px !important;
    padding: 20px 0 !important;
    align-items: flex-start !important;
  }
  
  .past-info-row .info-label {
    justify-content: flex-start !important;
    text-align: left !important;
    flex-shrink: 0 !important;
    min-width: auto !important;
  }
  
  .past-info-row .info-value {
    width: 100% !important;
    padding-left: 0 !important;
    text-align: left !important;
  }
}

@media (max-width: 768px) {
  /* セクションのパディング調整 */
  .intro-section {
    padding: 30px 20px !important;
  }
  
  .current-seminars-section,
  .past-seminars-section {
    padding: 30px 20px !important;
  }

  .intro-content {
    padding: 30px !important;
  }

  .seminars-content,
  .past-seminars-content {
    padding: 30px !important;
  }

  /* レイアウトの縦並び化 */
  .intro-buttons {
    flex-direction: column !important;
    gap: 20px !important;
  }

  .intro-btn {
    width: 100% !important;
    max-width: 590px !important;
  }

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

  .intro-text {
    font-size: 16px !important;
  }
  
  .section-description {
    font-size: 16px !important;
  }
  
  .label-text {
    font-size: 16px !important;
  }
  
  .info-value {
    font-size: 16px !important;
  }
  
  .intro-btn span {
    font-size: 16px !important;
  }
  
  .reserve-btn span {
    font-size: 16px !important;
  }
  
  .show-more-btn span {
    font-size: 16px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 20px !important;
  }
}

@media (max-width: 480px) {
  /* セクションのパディング調整 */
  .intro-section {
    padding: 20px 15px !important;
  }
  
  .current-seminars-section,
  .past-seminars-section {
    padding: 20px 15px !important;
  }

  .intro-content {
    padding: 20px !important;
  }

  .seminars-content,
  .past-seminars-content {
    padding: 20px !important;
  }

  /* レイアウトの縦並び化 */
  .intro-buttons {
    flex-direction: column !important;
    gap: 20px !important;
  }

  .intro-btn {
    width: 100% !important;
    max-width: 590px !important;
    padding: 15px 50px !important;
  }

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

  .intro-text {
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
  
  .intro-btn span {
    font-size: 13px !important;
  }
  
  .reserve-btn span {
    font-size: 13px !important;
  }
  
  .show-more-btn span {
    font-size: 13px !important;
  }
  
  /* セクションヘッダーのギャップ調整 */
  .section-header {
    gap: 18px !important;
  }
  
  /* ボタンのサイズ調整 */
  .reserve-btn {
    width: 100% !important;
    padding: 8px 0 !important;
  }
  
  .show-more-btn {
    width: 250px !important;
  }

  .show-more-btn {
    width: 100% !important;
  }

  .past-info-row .info-label {
    width: auto !important;
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
