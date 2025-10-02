<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="経済・調査統計"
      subtitle="economic statistics"
      heroImage="/img/Image_fx6.jpg"
      mediaKey="hero_economic_statistics"
      cms-page-key="economic-statistics"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['経済・調査統計']" />

    <!-- Economic Statistics Detail Section -->
    <section class="statistics-detail-section" v-if="statistics">
      <div class="section-header">
        <h2 class="section-title">経済・調査統計</h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">economic statistics</span>
          <div class="divider-line"></div>
        </div>
      </div>

      <!-- Statistics Details Card -->
      <div class="statistics-detail-card">
          <div class="statistics-content">
                         <div class="statistics-info">
              <div class="statistics-details">
                             <div class="detail-row">
                  <span class="detail-label">タイトル</span>
                  <span class="detail-value">{{ statistics.title || '経済統計レポート' }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">カテゴリー</span>
                  <span class="detail-value">{{ statistics.category || '四半期経済レポート' }}</span>
                </div>
                
                <div class="detail-row">
                  <span class="detail-label">詳細</span>
                  <span class="detail-value">{{ statistics.description || '詳細情報がここに表示されます。' }}</span>
                </div>
                
                <div class="detail-row">
                  <span class="detail-label">備考</span>
                  <span class="detail-value">{{ statistics.notes || '備考情報がここに表示されます。' }}</span>
                </div>
                
                <div class="detail-row">
                  <span class="detail-label">CHECK</span>
                  <span class="detail-value">{{ statistics.check || 'チェック項目がここに表示されます。' }}</span>
                </div>
               
               
             </div>
           </div>
           
           <div class="statistics-image">
             <img :src="statistics.image || '/img/image-1.png'" :alt="statistics.title" />
           </div>
         </div>



        <!-- Login Button -->
        <div class="login-section">
          <div class="login-btn" @click="goToLogin">
            <div class="text-44 valign-text-middle inter-bold-white-15px">ログインする</div>
            <div class="pdf-icon-wrapper">
              <img class="pdf-icon" src="/img/login-arrow-icon.svg" alt="ログイン" width="24" height="24" />
            </div>
          </div>
        </div>

      </div>
      </section>

<!-- Action Button Section -->
<ActionButton 
  primary-text="お問い合わせはこちら"
  secondary-text="メンバー登録はこちら"
  max-width="1500px"
  @primary-click="handleContactClick"
  @secondary-click="handleJoinClick"
/>

    <!-- Contact Section -->
    <ContactSection cms-page-key="economic-statistics" />

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
import Frame13213176122 from "./Frame13213176122.vue";
import ActionButton from "./ActionButton.vue";
import { frame132131753022Data } from "../data";
import apiClient from '../services/apiClient.js';

export default {
  name: "EconomicStatisticsDetailPageGuest",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ContactSection,
    Frame13213176122,
    ActionButton
  },
  data() {
    return {
      frame132131753022Props: frame132131753022Data,
      statistics: null,
      loading: true,
      error: null
    };
  },
  async mounted() {
    await this.loadStatistics();
  },
  methods: {
    async loadStatistics() {
      try {
        this.loading = true;
        const statisticsId = this.$route.params.id;
        
        const response = await apiClient.getPublication(statisticsId);
        
        if (response.success && response.data && response.data.publication) {
          this.statistics = this.formatStatisticsData(response.data.publication);
        } else {
          // Fallback to mock data
          this.loadFallbackData();
        }
      } catch (error) {
        console.error('Error loading statistics:', error);
        this.loadFallbackData();
      } finally {
        this.loading = false;
      }
    },
    
    loadFallbackData() {
      // Mock data for demonstration
             this.statistics = {
         id: this.$route.params.id,
         title: '雇用（有効求人倍率、パート有効求人数）',
         category: '四半期経済レポート',
         description: '雇用（有効求人倍率、パート有効求人数）を更新しました。2025年3月の福岡県の有効求人倍率は前月を0.02ポイント上回り1.20倍、パートタイム有効求人数は前年同月比1.6%減の45,783人となりました。',
         notes: 'この統計は毎月更新されます。最新の雇用動向を把握するためにご活用ください。',
         check: 'データの正確性を確認済み。公開可能な状態です。',
         membersOnly: true,
         image: '/img/image-1.png',
         isDownloadable: true
       };
    },
    
    formatStatisticsData(statisticsData) {
      return {
        ...statisticsData,
        isDownloadable: statisticsData.is_downloadable || false,
        membersOnly: statisticsData.members_only || false
      };
    },
    
    formatDetailDate(dateString) {
      if (!dateString) return '';
      return dateString;
    },
    
    goToLogin() {
      const redirect = encodeURIComponent(this.$route.fullPath)
      this.$router.push(`/member-login?redirect=${redirect}`)
    },
    
    goToContact() {
      this.$router.push('/contact');
    },
    
    goToMember() {
      this.$router.push('/membership');
    },
    handleContactClick() {
      this.$router.push('/contact');
    },
    handleJoinClick() {
      this.$router.push('/application-form');
    }
  }
};
</script>

<style scoped>

.page-container {
  min-height: 100vh;
  background-color: #ECECEC;
}

/* Hero Section and Breadcrumb styles are now handled by components */

/* Statistics Detail Section */
.statistics-detail-section {
  padding: 70px 50px 50px 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
  background: #ECECEC;
}

.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
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

/* Statistics Detail Card */
.statistics-detail-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 1500px;
  padding: 50px;
}

.statistics-content {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  gap: 50px;
  height: auto;
}

.statistics-image {
  width: 40%;
  height: auto;
  overflow: hidden;
  flex-shrink: 0;
  align-self: stretch;
}

.statistics-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  min-height: 400px;
}

.statistics-info {
  width: 60%;
  flex: 1;
  height: 100%;
}

.statistics-details {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.detail-row {
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
  align-items: flex-start;
  border-bottom: 0.5px dashed #D0D0D0;
  padding-bottom: 15px;
}

 .detail-row:first-child {
   border-top: 0.5px dashed #D0D0D0;
   padding-top: 15px;
 }

 .detail-row:last-child {
   padding-bottom: 0;
   margin-bottom: 0;
 }

 .detail-label {
  width: 200px;
  font-weight: normal;
  color: white;
  font-size: 0.9rem;
  background-color: #727272;
  padding: 5px 0;
  border-radius: 5px;
  text-align: center;
  margin-bottom: 5px;
}

.detail-value {
  width: 100%;
  color: #666;
  font-size: 0.9rem;
  line-height: 1.5;
  padding-left: 10px;
}

/* Login Section */
.login-section {
  text-align: center;
  margin-top: 30px;
}

.login-btn {
  align-items: center;
  background-color: #DA5761;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  border: none;
  cursor: pointer;
  color: white;
  font-family: var(--font-family-inter);
  font-size: 1.1rem;
  font-weight: bold;
  transition: all 0.3s;
  margin: 0 auto;
}

.login-btn:hover {
  opacity: 0.8;
}

.login-btn .pdf-icon {
  color: #DA5761;
}

.text-44 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: 0px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
  display: flex;
  align-items: center;
  color: var(--white);
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 700;
}

.loading {
  text-align: center;
  padding: 60px;
  color: #666;
  font-size: 1.1rem;
}

/* Contact Banner styles are now handled by ContactSection component */

/* Access Section styles are now handled by AccessSection component */

/* Floating Action Buttons styles are now handled by FixedSideButtons component */

/* Footer Navigation */

/* Responsive Design */
@media (max-width: 768px) {
  .statistics-detail-section {
    padding: 30px 20px 0 20px;
  }
  
  .statistics-detail-card {
    padding: 30px 20px;
  }
  
  .statistics-content {
    flex-direction: column;
  }
  
  .statistics-image {
    width: 100%;
    height: 300px;
  }
  
  .statistics-info {
    width: 100%;
  }
  
  .detail-label {
    width: 100%;
  }
  
  .button-section {
    padding: 50px 20px;
  }
  
  .cta-button {
    width: 100%;
  }
}
</style>
