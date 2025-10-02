<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="セミナー詳細"
      subtitle="seminar"
      heroImage="/img/Image_fx6.jpg"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー', 'セミナー詳細（予約済み）']" />

    <!-- Seminar Detail Section -->
    <section class="seminar-detail-section" v-if="seminar">
      <div class="section-header">
        <h2 class="section-title">セミナー詳細</h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">seminar</span>
          <div class="divider-line"></div>
        </div>
      </div>

      <!-- Seminar Details Card -->
      <div class="seminar-detail-card">
          <div class="seminar-content">
                         <div class="seminar-info">
              <div class="seminar-details">
              <div class="detail-row">
                 <span class="detail-label">セミナー名</span>
                 <span class="detail-value">{{ seminar.title || 'セミナー名' }}</span>
               </div>

               <div class="detail-row">
                 <span class="detail-label">講師</span>
                 <span class="detail-value">{{ seminar.instructor || 'ちくぎん地域経済研究所' }}</span>
               </div>
               
               <div class="detail-row">
                 <span class="detail-label">セミナー日時</span>
                 <span class="detail-value">{{ formatDetailDate(seminar.date) }}</span>
               </div>
               
               <div class="detail-row">
                 <span class="detail-label">開催場所</span>
                 <span class="detail-value">{{ seminar.venue || '久留米リサーチ・パーク' }}</span>
               </div>
               
                               <div class="detail-row">
                  <span class="detail-label">定員</span>
                  <span class="detail-value">{{ seminar.capacity || '30名' }}</span>
                </div>
               
               <div class="detail-row">
                 <span class="detail-label">参加費</span>
                 <span class="detail-value">{{ seminar.fee || '会員無料' }}</span>
               </div>
             </div>
           </div>
           
           <div class="seminar-image">
             <img :src="seminar.image || '/img/image-1.png'" :alt="seminar.title" />
           </div>
         </div>

        <div class="detail-row">
          <span class="detail-label">講師紹介</span>
          <span class="detail-value">{{ seminar.fullDescription || seminar.description }}</span>
        </div>

        <div class="detail-row">
          <span class="detail-label">プログラム</span>
          <span class="detail-value">{{ seminar.fullDescription || seminar.description }}</span>
        </div>

        <!-- Registration Button -->
        <div class="registration-section" v-if="seminar.status === 'current'">
          <div class="registration-btn" @click="registerSeminar">
            <div class="text-44 valign-text-middle inter-bold-white-15px">セミナーを予約する</div>
            <frame13213176122 />
          </div>
        </div>
        
        <div class="ended-section" v-else>
          <p class="ended-notice">このセミナーは終了しました</p>
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
    <ContactSection />

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer Navigation -->
    <div class="navigation-footer">
      <Footer v-bind="frame132131753022Props" />
      <div class="vector-7-1"></div>
      <Group27 />
    </div>

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
  name: "SeminarDetailReservedPage",
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
      seminar: null,
      loading: true,
      error: null
    };
  },
  async mounted() {
    await this.loadSeminar();
  },
  methods: {
    async loadSeminar() {
      try {
        this.loading = true;
        const seminarId = this.$route.params.id;
        
        const response = await apiClient.getSeminar(seminarId);
        
        if (response.success && response.data && response.data.seminar) {
          this.seminar = this.formatSeminarData(response.data.seminar);
        } else {
          // Fallback to mock data
          this.loadFallbackData();
        }
      } catch (error) {
        console.error('Error loading seminar:', error);
        this.loadFallbackData();
      } finally {
        this.loading = false;
      }
    },
    
    loadFallbackData() {
      // Mock data for demonstration
      this.seminar = {
        id: this.$route.params.id,
        title: '手形・小切手の全面的な電子化セミナー',
        instructor: 'ちくぎん地域経済研究所',
        date: '2025年7月15日（火）14:00～16:00',
        venue: '久留米リサーチ・パーク',
        capacity: '30名',
        fee: '会員無料',
        description: '当セミナーでは、手形の電子化に向けた金融界の取組みや、代替手段である「でんさい」や「法人インターネットバンキング（ビジネスWeb）」の仕組みや導入方法、でんさいの基本的な操作方法についてご説明します。',
        fullDescription: '当セミナーでは、手形の電子化に向けた金融界の取組みや、代替手段である「でんさい」や「法人インターネットバンキング（ビジネスWeb）」の仕組みや導入方法、でんさいの基本的な操作方法についてご説明します。',
        image: '/img/image-1.png',
        status: 'reserved' // 予約済みステータス
      };
    },
    
    formatSeminarData(seminarData) {
      return {
        ...seminarData,
        status: 'reserved' // 予約済みステータスに設定
      };
    },
    
    formatDetailDate(dateString) {
      if (!dateString) return '';
      return dateString;
    },
    
    registerSeminar() {
      // 予約済みの場合は参加ページに遷移
      this.$router.push(`/seminar-join/${this.seminar.id}`);
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
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-container {
  min-height: 100vh;
  background-color: #ECECEC;
}

/* Hero Section and Breadcrumb styles are now handled by components */

/* Seminar Detail Section */
.seminar-detail-section {
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

/* Seminar Detail Card */
.seminar-detail-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 1500px;
  padding: 50px;
}

.seminar-content {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  gap: 50px;
  height: auto;
}

.seminar-image {
  width: 40%;
  height: auto;
  overflow: hidden;
  flex-shrink: 0;
  align-self: stretch;
}

.seminar-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  min-height: 400px;
}

.seminar-info {
  width: 60%;
  flex: 1;
  height: 100%;
}

.seminar-details {
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



/* Registration Section */
.registration-section {
  text-align: center;
  margin-top: 30px;
}

.registration-btn {
  align-items: center;
  background-color: var(--mandy);
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

.registration-btn:hover {
  background: #c44853;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(218, 87, 97, 0.3);
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

.ended-section {
  text-align: center;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 10px;
}

.ended-notice {
  color: #666;
  font-size: 1rem;
  font-weight: 500;
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
.navigation-footer {
  align-items: center;
  background-color: var(--celeste);
  display: flex;
  flex-direction: column;
  gap: 50px;
  padding: 100px;
  position: relative;
  width: 100%;
  max-width: 100vw;
  z-index: 4;
  box-sizing: border-box;
}

.vector-7-1 {
  height: 1px;
  background-color: #B2B2B2;
  position: relative;
  width: 100%;
  max-width: 1240px;
}

/* Responsive Design */

/* 1150px以下（タブレット横向き） */
@media (max-width: 1150px) {
  .seminar-detail-section {
    padding: 50px 30px !important;
  }
  
  .seminar-detail-card {
    padding: 40px !important;
  }
  
  .seminar-content {
    flex-direction: column !important;
    gap: 0 !important;
  }
  
  .seminar-image {
    width: 100% !important;
    height: 300px !important;
    border-radius: 20px 20px 0 0 !important;
    order: -1 !important;
  }
  
  .seminar-info {
    width: 100% !important;
    border-radius: 0 0 20px 20px !important;
    padding: 30px 0 0 0 !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 18px !important;
  }
  
  .section-header {
    gap: 25px !important;
  }
}

/* 900px以下（タブレット） */
@media (max-width: 900px) {
  .seminar-detail-section {
    padding: 30px 20px !important;
  }
  
  .seminar-detail-card {
    padding: 35px !important;
  }
  
  .seminar-image {
    height: 280px !important;
    order: -1 !important;
  }
  
  .seminar-info {
    padding: 30px 0 0 0 !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 29px !important;
  }
  
  .divider-text {
    font-size: 17px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 17px !important;
  }
  
  .section-header {
    gap: 22px !important;
  }
}

/* 768px以下（タブレット縦向き） */
@media (max-width: 768px) {
  .seminar-detail-section {
    padding: 30px 20px !important;
  }
  
  .seminar-detail-card {
    padding: 30px !important;
  }
  
  .seminar-content {
    flex-direction: column !important;
    gap: 0 !important;
  }
  
  .seminar-image {
    width: 100% !important;
    height: 250px !important;
    border-radius: 20px 20px 0 0 !important;
    order: -1 !important;
  }
  
  .seminar-info {
    width: 100% !important;
    padding: 30px 0 0 0 !important;
    border-radius: 0 0 20px 20px !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 27px !important;
  }
  
  .divider-text {
    font-size: 16px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 16px !important;
  }
  
  .section-header {
    gap: 20px !important;
  }
}

/* 480px以下（スマートフォン） */
@media (max-width: 480px) {
  .seminar-detail-section {
    padding: 20px 15px !important;
  }
  
  .seminar-detail-card {
    padding: 20px !important;
  }
  
  .seminar-image {
    height: 200px !important;
    border-radius: 15px 15px 0 0 !important;
    order: -1 !important;
  }
  
  .seminar-info {
    padding: 20px 0 0 0 !important;
    border-radius: 0 0 15px 15px !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 22px !important;
  }
  
  .divider-text {
    font-size: 13px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 13px !important;
  }
  
  .section-header {
    gap: 18px !important;
  }
  
  .registration-btn {
    width: 100% !important;
    padding: 15px 20px !important;
  }
}
</style>
