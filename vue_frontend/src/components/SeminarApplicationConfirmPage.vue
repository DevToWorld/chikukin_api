<template>
  <div class="contact-form-page">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="セミナー申し込み"
      subtitle="seminar application"
      heroImage="/img/Image_fx6.jpg"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー', '申し込み', '確認']" />

    <!-- Form Section -->
    <section class="form-section">
      <div class="form-container">
        <div class="form-header">
          <h1 class="form-title">セミナー申し込み</h1>
          <div class="form-divider">
            <div class="divider-line"></div>
            <span class="divider-text">seminar application</span>
            <div class="divider-line"></div>
          </div>
          <div class="form-steps">
            <span class="step-inactive">①お客様情報の入力</span>
            <span class="step-active">　- ②記入内容のご確認　</span>
            <span class="step-inactive">- ③完了</span>
          </div>
        </div>

        <div class="contact-form">
          <!-- Subject -->
          <div class="form-field">
            <label class="field-label">会員タイプ</label>
            <div class="field-input">
              <div class="confirm-value">{{ getSubjectText(formData.subject) }}</div>
            </div>
          </div>

          <!-- Name -->
          <div class="form-field">
            <label class="field-label">お名前</label>
            <div class="field-input">
              <div class="confirm-value">{{ formData.lastName }} {{ formData.firstName }}</div>
            </div>
          </div>

          <!-- Furigana -->
          <div class="form-field">
            <label class="field-label">ふりがな（全角ひらがな）</label>
            <div class="field-input">
              <div class="confirm-value">{{ formData.lastNameKana }} {{ formData.firstNameKana }}</div>
            </div>
          </div>

          <!-- Company Name -->
          <div class="form-field">
            <label class="field-label">会社名</label>
            <div class="field-input">
              <div class="confirm-value">{{ formData.companyName || '未入力' }}</div>
            </div>
          </div>

          <!-- Position -->
          <div class="form-field">
            <label class="field-label">役職</label>
            <div class="field-input">
              <div class="confirm-value">{{ formData.position || '未入力' }}</div>
            </div>
          </div>

          <!-- Phone -->
          <div class="form-field">
            <label class="field-label">電話番号</label>
            <div class="field-input">
              <div class="confirm-value">{{ formData.phone || '未入力' }}</div>
            </div>
          </div>

          <!-- Email -->
          <div class="form-field">
            <label class="field-label">メールアドレス</label>
            <div class="field-input">
              <div class="confirm-value">{{ formData.email }}</div>
            </div>
          </div>

          <!-- Inquiry Content -->
          <div class="form-field">
            <label class="field-label">参加希望内容・特記事項</label>
            <div class="field-input">
              <div class="confirm-value content-value">{{ formData.content }}</div>
            </div>
          </div>

          

                     <!-- Action Buttons -->
           <div class="action-section">
             <div class="action-buttons">
               <button type="button" class="back-btn" @click="goBack">
                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                   <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                   <path d="M5.9698 12.4415L10.4419 17.0412C10.5351 17.1371 10.6616 17.191 10.7934 17.191C10.9253 17.191 11.0518 17.1371 11.145 17.0412C11.2382 16.9453 11.2906 16.8152 11.2906 16.6796C11.2906 16.544 11.2382 16.4139 11.145 16.318L7.5207 12.5909L17.2531 12.5909C17.3849 12.5909 17.5113 12.5371 17.6045 12.4413C17.6977 12.3454 17.75 12.2154 17.75 12.0799C17.75 11.9443 17.6977 11.8143 17.6045 11.7185C17.5113 11.6226 17.3849 11.5688 17.2531 11.5688L7.5207 11.5688L11.145 7.84171C11.2382 7.74581 11.2906 7.61574 11.2906 7.48012C11.2906 7.34449 11.2382 7.21443 11.145 7.11853C11.0518 7.02263 10.9253 6.96875 10.7934 6.96875C10.6616 6.96875 10.5351 7.02263 10.4419 7.11853L5.9698 11.7183C5.9236 11.7657 5.887 11.8221 5.862 11.8841C5.8369 11.9462 5.8241 12.0127 5.8241 12.0799C5.8241 12.147 5.8369 12.2135 5.862 12.2756C5.887 12.3376 5.9236 12.394 5.9698 12.4415Z" fill="#1A1A1A"/>
                 </svg>
                 戻る
               </button>
               <button type="button" class="submit-btn" @click="submitForm">
                 送信する
                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                   <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                   <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#1A1A1A"/>
                 </svg>
               </button>
             </div>
           </div>
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
import apiClient from '../services/apiClient.js';
import Navigation from "./Navigation.vue";
import Footer from "./Footer.vue";
import Group27 from "./Group27.vue";
import AccessSection from './AccessSection.vue';
import HeroSection from './HeroSection.vue';
import Breadcrumbs from './Breadcrumbs.vue';
import { frame132131753022Data } from "../data.js";

export default {
  name: 'SeminarApplicationConfirmPage',
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs
  },
  data() {
    return {
      formData: {
        subject: '',
        lastName: '',
        firstName: '',
        lastNameKana: '',
        firstNameKana: '',
        companyName: '',
        position: '',
        phone: '',
        email: '',
        content: '',
        privacyAgreement: false
      },
      isSubmitting: false,
      submitSuccess: false,
      submitError: null,
      inquiryNumber: null,
      frame132131753022Props: frame132131753022Data
    };
  },
  mounted() {
    // URLパラメータからフォームデータを取得
    const params = new URLSearchParams(this.$route.query);
    if (params.get('formData')) {
      try {
        this.formData = JSON.parse(decodeURIComponent(params.get('formData')));
      } catch (e) {
        console.error('フォームデータの解析に失敗しました:', e);
        const seminarId = this.$route.params.id;
        this.$router.push(`/seminars/${seminarId}/apply`);
      }
    } else {
      // フォームデータがない場合は入力ページにリダイレクト
      const seminarId = this.$route.params.id;
      this.$router.push(`/seminars/${seminarId}/apply`);
    }
  },
  methods: {
    goBack() {
      // フォームデータをURLパラメータで渡して入力ページに戻る
      const formDataParam = encodeURIComponent(JSON.stringify(this.formData));
      const seminarId = this.$route.params.id;
      this.$router.push(`/seminars/${seminarId}/apply?formData=${formDataParam}`);
    },
    getSubjectText(value) {
      const subjects = {
        'standard': 'スタンダード会員',
        'premium': 'プレミアムネット会員',
        'inquiry': '会員に関するお問い合わせ',
        'other': 'その他'
      };
      return subjects[value] || value;
    },
    async submitForm() {
      if (this.isSubmitting) return;

      this.isSubmitting = true;
      this.submitError = null;

      try {
        // subject表示用テキストとtypeコードを分離
        const subjectMap = {
          standard: 'スタンダード会員',
          premium: 'プレミアムネット会員',
          inquiry: '会員に関するお問い合わせ',
          other: 'その他'
        }

        const payload = {
          name: `${this.formData.lastName} ${this.formData.firstName}`.trim(),
          email: this.formData.email,
          phone: this.formData.phone || '',
          company: this.formData.companyName || '',
          subject: subjectMap[this.formData.subject] || this.formData.subject,
          message: this.formData.content,
          inquiry_type: this.formData.subject || null
        }

        const res = await apiClient.submitInquiry(payload)
        if (!res || res.success === false) {
          throw new Error(res?.message || res?.error || '送信に失敗しました')
        }

        // v2: res.data.application_number or fallback to id from either v1/v2
        const applicationNumber = res?.data?.application_number
          || (res?.data?.application_id ? `APP-${String(res.data.application_id).padStart(6, '0')}` : null)
          || (res?.application_id ? `APP-${String(res.application_id).padStart(6, '0')}` : null)
          || `APP-${Date.now()}`

        const seminarId = this.$route.params.id;
        this.$router.push(`/seminars/${seminarId}/apply/complete?applicationNumber=${encodeURIComponent(applicationNumber)}`)
      } catch (err) {
        console.error('セミナー申し込み送信エラー:', err)
        this.submitError = err.message || 'エラーが発生しました。再度お試しください。'
        alert(this.submitError)
      } finally {
        this.isSubmitting = false
      }
    }
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
}

.form-field {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  border-bottom: 0.5px dashed #B0B0B0;
  width: 100%;
}

.form-field:first-child {
  border-top: 0.5px dashed #B0B0B0;
}

.field-label {
  font-size: 18px;
  font-weight: 700;
  color: #3F3F3F;
  line-height: 150%;
  min-width: 320px;
}

.field-input {
  width: 920px;
  position: relative;
}

.confirm-value {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  line-height: 1.6;
  padding: 15px;
  background: #F8F8F8;
  border-radius: 10px;
  border: 1px solid #E0E0E0;
  min-height: 57px;
  display: flex;
  align-items: center;
}

.content-value {
  min-height: 250px;
  align-items: flex-start;
  white-space: pre-wrap;
}

/* Action Section */
.action-section {
  padding-top: 50px;
  display: flex;
  justify-content: center;
  width: 100%;
}

.action-buttons {
  display: flex;
  gap: 30px;
  align-items: center;
}



.back-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  width: 200px;
  padding: 20px 40px;
  border-radius: 15px;
  background: #F8F8F8;
  color: #1A1A1A;
  border: 2px solid #E0E0E0;
  cursor: pointer;
  font-size: 20px;
  font-weight: 700;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.back-btn:hover {
  background: #E8E8E8;
  border-color: #D0D0D0;
}

 .submit-btn {
   display: flex;
   align-items: center;
   justify-content: center;
   gap: 20px;
   width: 500px;
   padding: 20px 100px;
   border-radius: 15px;
   background: #1A1A1A;
   color: #FFF;
   border: none;
   cursor: pointer;
   font-size: 20px;
   font-weight: 700;
   box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
   transition: all 0.3s ease;
 }

.submit-btn:hover {
  background: #DA5761;
}

.submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
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

  .field-label {
    font-size: 18px !important;
    min-width: 200px !important;
  }
  
  .field-input {
    width: 70% !important;
  }

  .confirm-value {
    font-size: 18px !important;
  }

  .back-btn {
    font-size: 18px !important;
    width: 180px !important;
    padding: 18px 35px !important;
  }

  .submit-btn {
    font-size: 20px !important;
    width: 450px !important;
    padding: 18px 80px !important;
  }

  .form-header {
    gap: 25px !important;
  }

  .form-container {
    gap: 35px !important;
  }

  .action-section {
    padding-top: 40px !important;
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

  .field-label {
    font-size: 17px !important;
    min-width: 180px !important;
  }
  
  .field-input {
    width: 65% !important;
  }

  .confirm-value {
    font-size: 17px !important;
  }

  .back-btn {
    font-size: 16px !important;
    width: 160px !important;
    padding: 16px 30px !important;
  }

  .submit-btn {
    font-size: 18px !important;
    width: 400px !important;
    padding: 16px 70px !important;
  }

  .form-header {
    gap: 22px !important;
  }

  .form-container {
    gap: 30px !important;
  }

  .action-section {
    padding-top: 35px !important;
  }

    /* フォームフィールドの調整 */
    .form-field {
    padding: 20px 0 !important;
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

  .form-field {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 15px !important;
  }

  .form-field {
    padding: 18px 0 !important;
  }
  
  .field-label {
    min-width: auto !important;
    font-size: 16px !important;
  }
  
  .field-input {
    width: 100% !important;
  }

  .confirm-value {
    font-size: 16px !important;
  }

  .action-buttons {
    flex-direction: column !important;
    gap: 20px !important;
  }
  
  .back-btn {
    width: 100% !important;
    font-size: 16px !important;
    padding: 15px 30px !important;
  }

  .submit-btn {
    width: 100% !important;
    font-size: 17px !important;
    padding: 15px 60px !important;
  }

  .form-header {
    gap: 20px !important;
  }

  .form-container {
    gap: 25px !important;
  }

  .action-section {
    padding-top: 30px !important;
  }
}

@media (max-width: 480px) {
  .form-section {
    padding: 20px 15px 30px !important;
  }

  .form-field {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 12px !important;
    padding: 15px 0 !important;
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

  .field-label {
    font-size: 13px !important;
  }
  
  .field-input {
    width: 100% !important;
  }

  .confirm-value {
    font-size: 13px !important;
    padding: 12px !important;
  }

  .back-btn {
    font-size: 14px !important;
    width: 100% !important;
    padding: 12px 20px !important;
    border-radius: 12px !important;
  }

  .submit-btn {
    font-size: 14px !important;
    width: 100% !important;
    padding: 12px 40px !important;
    border-radius: 12px !important;
  }

  .form-header {
    gap: 18px !important;
  }

  .form-container {
    gap: 20px !important;
  }

  .action-section {
    padding-top: 25px !important;
  }

  .divider-line {
    width: 50px !important;
  }

  .form-divider {
    gap: 10px !important;
  }

    /* フォームフィールドの詳細調整 */
    .form-field {
    padding: 12px 0 !important;
  }
}
</style>
