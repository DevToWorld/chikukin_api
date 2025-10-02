<template>
  <div class="seminar-page">
    <!-- Navigation -->
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="過去のセミナー"
      subtitle="past seminars"
      heroImage="https://api.builder.io/api/v1/image/assets/TEMP/ab5db9916398054424d59236a434310786cb8146?width=2880"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー', '過去のセミナー']" />

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

          <!-- Pagination (dynamic) -->
          <div class="pagination" v-if="totalPages > 1">
            <button class="pagination-btn" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">‹</button>
            <template v-for="(p, i) in pagesToShow">
              <span v-if="p === '…'" class="pagination-dots" :key="`dots-${i}`">…</span>
              <button v-else class="pagination-btn" :class="{ active: currentPage === p }" @click="goToPage(p)" :key="`page-${p}`">{{ p }}</button>
            </template>
            <button class="pagination-btn" @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">›</button>
            <button class="pagination-btn next-btn" @click="goToPage(totalPages)" :disabled="currentPage === totalPages">最後</button>
          </div>

          <!-- Back Button -->
          <div class="back-container">
            <button class="back-btn" @click="goBack">前に戻る</button>
          </div>
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
import apiClient from '@/services/apiClient.js'

export default {
  name: "PastSeminarsPage",
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
      currentPage: 1,
      itemsPerPage: 10,
      allPastSeminars: []
    };
  },
  async mounted() {
    await this.loadPastSeminars()
  },
  computed: {
    pastSeminars() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.allPastSeminars.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.allPastSeminars.length / this.itemsPerPage) || 1;
    },
    pagesToShow() {
      const total = this.totalPages
      const current = this.currentPage
      if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)
      const pages = []
      const push = v => pages.push(v)
      push(1)
      if (current > 4) push('…')
      const start = Math.max(2, current - 1)
      const end = Math.min(total - 1, current + 1)
      for (let p = start; p <= end; p++) push(p)
      if (current < total - 3) push('…')
      push(total)
      return pages
    }
  },
  methods: {
    async loadPastSeminars() {
      try {
        const res = await apiClient.get('/api/seminars?status=completed&per_page=100')
        if (res.success && res.data && Array.isArray(res.data.seminars)) {
          this.allPastSeminars = res.data.seminars.map(s => ({
            id: s.id,
            date: this.formatDate(s.date),
            title: s.title,
            content: s.description
          }))
        } else {
          this.allPastSeminars = []
        }
      } catch (e) {
        console.error('過去セミナー取得失敗:', e)
        this.allPastSeminars = []
      }
    },
    formatDate(dateString) {
      const d = new Date(dateString)
      if (isNaN(d.getTime())) return dateString
      return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日`
    },
    goToPage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    goBack() {
      if (window.history.length > 1) this.$router.go(-1)
      else this.$router.push('/')
    },
    goToSeminarDetail(seminar) {
      // セミナー詳細ページに遷移
      this.$router.push(`/seminar/${seminar.id}`);
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

/* Past Seminars Section */
.past-seminars-section {
  padding: 50px 50px 80px 50px;
  background: var(--color-background);
}

.seminars-container {
  max-width: 2000px;
  margin: 0 auto;
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
  background: #F5F5F5;
  border-color: #B0B0B0;
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
  .past-seminars-section {
    padding: 50px 30px !important;
  }
  
  .past-seminars-content {
    padding: 40px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
  
  .section-description {
    font-size: 18px !important;
  }
  
  /* 情報行の縦並び化 */
  .past-info-row {
    flex-direction: column !important;
    gap: 15px !important;
    padding: 20px 0 !important;
    align-items: flex-start !important;
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
  
  .label-text {
    font-size: 18px !important;
  }
  
  .info-value {
    font-size: 18px !important;
  }
  
  .show-more-btn span {
    font-size: 18px !important;
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
  .past-seminars-section {
    padding: 30px 20px !important;
  }
  
  .past-seminars-content {
    padding: 35px !important;
  }
  
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
  
  .show-more-btn span {
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
}

@media (max-width: 768px) {
  .past-seminars-section {
    padding: 30px 20px !important;
  }
  
  .past-seminars-content {
    padding: 30px !important;
  }
  
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
  
  .show-more-btn span {
    font-size: 16px !important;
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

@media (max-width: 480px) {
  .past-seminars-section {
    padding: 20px 15px !important;
  }
  
  .past-seminars-content {
    padding: 20px !important;
  }
  
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
  
  .show-more-btn span {
    font-size: 13px !important;
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
  .past-info-row {
    flex-direction: column !important;
    gap: 10px !important;
    padding: 20px 0 !important;
    align-items: flex-start !important;
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
