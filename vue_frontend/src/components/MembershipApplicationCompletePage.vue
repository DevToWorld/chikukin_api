<template>
    <div class="contact-form-page">
      <Navigation />
      
      <!-- Hero Section -->
      <HeroSection 
        title="入会申し込み"
        subtitle="membership"
        heroImage="https://api.builder.io/api/v1/image/assets/TEMP/53cc5489ed3a3ad5de725cbc506b45ae898146f0?width=2880"
      />
  
      <!-- Breadcrumbs -->
      <Breadcrumbs :breadcrumbs="['入会申し込み', '完了']" />
  
      <!-- Form Section -->
      <section class="form-section">
        <div class="form-container">
          <div class="form-header">
            <h1 class="form-title">入会申し込み完了</h1>
            <div class="form-divider">
              <div class="divider-line"></div>
              <span class="divider-text">membership</span>
              <div class="divider-line"></div>
            </div>
            <div class="form-steps">
              <span class="step-inactive">①入会情報の入力</span>
              <span class="step-inactive">　- ②記入内容のご確認　</span>
              <span class="step-active">- ③完了</span>
            </div>
          </div>
  
          <div class="contact-form">
            <!-- Completion Message -->
            <div class="completion-message">
              <p class="completion-text">
                入会申し込みが完了しました。<br>
                申し込み内容の詳細は、後日メールにてご連絡いたします。<br>
                ご不明な点がございましたら、お気軽にお問い合わせください。
              </p>
            </div>
            <!-- Back to top (home) button -->
            <ActionButton
              :primaryText="'トップに戻る'"
              :showSecondary="false"
              :maxWidth="'1014px'"
              @primary-click="$router.push('/')"
            />
          </div>
        </div>
      </section>
  
      <!-- Access Section -->
      <AccessSection />
  
      <!-- Footer Navigation -->
      <Footer v-bind="frame132131753022Props" />
    </div>
  </template>
  
  <script>
  import Navigation from "./Navigation.vue";
  import Footer from "./Footer.vue";
  import Group27 from "./Group27.vue";
  import AccessSection from './AccessSection.vue';
  import HeroSection from './HeroSection.vue';
  import Breadcrumbs from './Breadcrumbs.vue';
import { frame132131753022Data } from "../data.js";
import ActionButton from './ActionButton.vue'
  
  export default {
    name: 'MembershipApplicationCompletePage',
    components: {
      Navigation,
      Footer,
      Group27,
      AccessSection,
      HeroSection,
      Breadcrumbs,
      ActionButton
    },
    data() {
      return {
        applicationNumber: null,
        frame132131753022Props: frame132131753022Data
      };
    },
    mounted() {
      // URLパラメータから申し込み番号を取得
      const params = new URLSearchParams(this.$route.query);
      this.applicationNumber = params.get('applicationNumber');
    }
  };
  </script>
  
  <style scoped>
  .contact-form-page {
    background-color: #ECECEC;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, Roboto, Helvetica, sans-serif;
  }
  
  /* Navigation */
  .navigation {
    align-items: center;
    background-color: #ffffff;
    box-shadow: 0px 3px 20px #00000026;
    display: flex;
    justify-content: space-between;
    padding: 10px 20px;
    position: relative;
    width: 100%;
    z-index: 9;
  }
  
  .logo-section {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  
  .nav-controls {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: auto;
    min-width: 0;
  }
  
  .sub-nav {
    display: flex;
    align-items: center;
    gap: 15px;
    justify-content: flex-start;
  }
  
  .hamburger-menu {
    background: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 4px;
    width: 26px;
    height: 24px;
    justify-content: center;
    align-items: center;
  }
  
  .hamburger-line {
    width: 18px;
    height: 2px;
    background-color: #1A1A1A;
    transition: all 0.3s ease;
  }
  
  .hamburger-menu:hover .hamburger-line {
    background-color: #DA5761;
  }
  
  /* サブナビのレスポンシブ対応 */
  @media (max-width: 1150px) {
    .sub-nav {
      display: none;
    }
  }
  
  /* Form Section */
  .form-section {
    padding: 70px 50px 70px;
  }
  
  .form-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
    max-width: 1440px;
    margin: 0 auto;
  }
  
  .form-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 29px;
  }
  
  .form-title {
    font-size: 36px;
    font-weight: 700;
    color: #1A1A1A;
    letter-spacing: -0.72px;
    text-align: center;
    margin: 0;
  }
  
  .form-divider {
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
  
  .form-steps {
    text-align: center;
    font-size: 18px;
    letter-spacing: -0.36px;
    max-width: 1014px;
  }
  
  .step-active {
    font-weight: 700;
    color: #1A1A1A;
  }
  
  .step-inactive {
    font-weight: 400;
    color: #727272;
  }
  
  /* Form Fields */
  .contact-form {
    padding: 50px;
    border-radius: 20px;
    background: #FFF;
  }
  
  .completion-message {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
  }
  
  .completion-text {
    font-size: 20px;
    font-weight: 400;
    color: #1A1A1A;
    line-height: 1.8;
    margin: 0;
  }
  
  /* Footer Navigation */
  
  .footer-navigation {
    background: #CFCFCF;
    padding: 50px 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 50px;
  }
  
  .footer-links {
    display: flex;
    align-items: flex-start;
    gap: 60px;
  }
  
  .footer-column {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
    width: 171px;
  }
  
  .footer-item {
    display: flex;
    height: 18px;
    align-items: center;
    gap: 10px;
    align-self: stretch;
  }
  
  .footer-sub-item {
    display: flex;
    height: 15px;
    padding-left: 20px;
    align-items: center;
    gap: 10px;
    align-self: stretch;
  }
  
  .arrow-icon {
    width: 10px;
    height: 17px;
    fill: #DA5761;
  }
  
  .dash-icon {
    width: 2px;
    height: 10px;
    transform: rotate(90deg);
    fill: #DA5761;
  }
  
  /* Responsive Design */
  @media (max-width: 1150px) {
    .form-section {
      padding: 50px 30px 40px !important;
    }
    
    .contact-form {
      padding: 30px 20px !important;
    }
    
    .form-title {
      font-size: 32px !important;
    }
  
    .divider-text {
      font-size: 18px !important;
    }
  
    .form-steps {
      font-size: 18px !important;
    }
  
    .completion-text {
      font-size: 18px !important;
    }
  
    .form-header {
      gap: 25px !important;
    }
  
    .form-container {
      gap: 35px !important;
    }
  
    .terms-content {
      padding: 35px !important;
      gap: 25px !important;
    }
  
    .terms-section {
      gap: 25px !important;
    }
  }
  
  @media (max-width: 900px) {
    .form-section {
      padding: 30px 20px 40px !important;
    }
    
    .contact-form {
      padding: 30px 20px !important;
    }
    
    .form-title {
      font-size: 29px !important;
    }
  
    .divider-text {
      font-size: 17px !important;
    }
  
    .form-steps {
      font-size: 17px !important;
    }
  
    .completion-text {
      font-size: 17px !important;
    }
  
    .form-header {
      gap: 22px !important;
    }
  
    .form-container {
      gap: 30px !important;
    }
  
    .terms-content {
      padding: 30px !important;
      gap: 25px !important;
    }
  
    .terms-section {
      gap: 25px !important;
    }
  }
  
  @media (max-width: 768px) {
    .form-section {
      padding: 30px 20px 40px !important;
    }
    
    .contact-form {
      padding: 30px 20px !important;
    }
    
    .form-title {
      font-size: 27px !important;
    }
  
    .divider-text {
      font-size: 16px !important;
    }
  
    .form-steps {
      font-size: 16px !important;
    }
  
    .completion-text {
      font-size: 16px !important;
    }
  
    .form-header {
      gap: 20px !important;
    }
  
    .form-container {
      gap: 25px !important;
    }
  
    .terms-content {
      padding: 25px !important;
      gap: 20px !important;
    }
  
    .terms-section {
      gap: 20px !important;
    }
  }
  
  @media (max-width: 480px) {
    .form-section {
      padding: 20px 15px 30px !important;
    }
    
    .contact-form {
      padding: 20px 15px !important;
    }
    
    .form-title {
      font-size: 22px !important;
    }
  
    .divider-text {
      font-size: 13px !important;
    }
  
    .form-steps {
      font-size: 13px !important;
    }
  
    .completion-text {
      font-size: 13px !important;
    }
  
    .form-header {
      gap: 18px !important;
    }
  
    .form-container {
      gap: 20px !important;
    }
  
    .terms-content {
      padding: 15px !important;
      gap: 15px !important;
      border-radius: 15px !important;
    }
  
    .terms-section {
      gap: 15px !important;
    }
  
    .divider-line {
      width: 50px !important;
    }
  
    .form-divider {
      gap: 10px !important;
    }
  }
  </style>
  
