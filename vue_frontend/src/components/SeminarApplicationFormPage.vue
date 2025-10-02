<template>
  <div class="contact-form-page">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      heroImage="/img/Image_fx6.jpg"
      mediaKey="hero_seminar"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー', '申し込み', '入力']" />

    <!-- Form Section -->
    <section class="form-section">
      <div class="form-container">
        <div class="form-header">
          <h1 class="form-title">{{ formTitle }}</h1>
          <div class="form-divider">
            <div class="divider-line"></div>
            <span class="divider-text">{{ pageSubtitle }}</span>
            <div class="divider-line"></div>
          </div>
           <div class="form-steps">
             <span class="step-active">①お客様情報の入力</span>
             <span class="step-inactive">　- ②記入内容のご確認　</span>
             <span class="step-inactive">- ③完了</span>
           </div>
        </div>

        <form class="contact-form" @submit.prevent="submitForm">
          <!-- Subject -->
          <div class="form-field">
             <label class="field-label">会員タイプ<span class="required-mark">*</span></label>
            <div class="field-input">
               <select v-model="formData.subject" class="select-field">
                 <option value="">選択してください</option>
                 <option value="standard">スタンダード会員</option>
                 <option value="premium">プレミアムネット会員</option>
                 <option value="inquiry">会員に関するお問い合わせ</option>
                 <option value="other">その他</option>
               </select>
              <svg class="select-arrow" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.0312 10.0306L12.5312 17.5306C12.4616 17.6004 12.3788 17.6557 12.2878 17.6934C12.1967 17.7312 12.0992 17.7506 12.0006 17.7506C11.902 17.7506 11.8044 17.7312 11.7134 17.6934C11.6223 17.6557 11.5396 17.6004 11.47 17.5306L3.96997 10.0306C3.86496 9.92573 3.79343 9.79204 3.76444 9.64648C3.73546 9.50092 3.75031 9.35002 3.80712 9.21291C3.86394 9.07579 3.96016 8.95861 4.0836 8.87621C4.20705 8.79381 4.35217 8.74988 4.50059 8.75H19.5006C19.649 8.74988 19.7941 8.79381 19.9176 8.87621C20.041 8.95861 20.1372 9.07579 20.1941 9.21291C20.2509 9.35002 20.2657 9.50092 20.2367 9.64648C20.2078 9.79204 20.1362 9.92573 20.0312 10.0306Z" fill="#727272"/>
              </svg>
            </div>
          </div>

          <!-- Name -->
          <div class="form-field">
            <label class="field-label">お名前<span class="required-mark">*</span></label>
            <div class="field-input">
              <div class="name-inputs">
                <input v-model="formData.lastName" type="text" placeholder="性" class="text-input" />
                <input v-model="formData.firstName" type="text" placeholder="名" class="text-input" />
              </div>
            </div>
          </div>

          <!-- Furigana -->
          <div class="form-field">
            <label class="field-label">ふりがな（全角ひらがな）</label>
            <div class="field-input">
              <div class="name-inputs">
                <input v-model="formData.lastNameKana" type="text" placeholder="せい" class="text-input" />
                <input v-model="formData.firstNameKana" type="text" placeholder="めい" class="text-input" />
              </div>
            </div>
          </div>

          <!-- Company Name -->
          <div class="form-field">
            <label class="field-label">会社名</label>
            <div class="field-input">
              <input v-model="formData.companyName" type="text" class="text-input single" />
            </div>
          </div>

          <!-- Position -->
          <div class="form-field">
            <label class="field-label">役職</label>
            <div class="field-input">
              <input v-model="formData.position" type="text" class="text-input single" />
            </div>
          </div>

          <!-- Phone -->
          <div class="form-field">
            <label class="field-label">電話番号</label>
            <div class="field-input">
              <input v-model="formData.phone" type="tel" class="text-input single" />
            </div>
          </div>

          <!-- Email -->
          <div class="form-field">
            <label class="field-label">メールアドレス<span class="required-mark">*</span></label>
            <div class="field-input">
              <input v-model="formData.email" type="email" class="text-input single" />
            </div>
          </div>

           <!-- Inquiry Content -->
           <div class="form-field">
             <label class="field-label">入会希望内容・特記事項<span class="required-mark">*</span></label>
             <div class="field-input">
               <textarea v-model="formData.content" class="textarea-input" placeholder="入会希望内容や特記事項をご記入ください"></textarea>
             </div>
           </div>

          <!-- Terms Section -->
          <div class="terms-section">
            <div class="terms-content">
              <div class="terms-article">
                <h3 class="terms-title">第1条(目的）</h3>
                <p class="terms-text">「ちくさん地域経済クラブ」（以下、「本会」という）は、株式会社ちくさん地域経済研究所（以下、「当社」という）が運営するサービスであり、産・食・学・金（金機関）のネットワーク菜や会員相互の交流等を通じて、企業務営等に役立つ様々な情報や機会提供により、会員企業等がともに発展し、ひいては地域の振興・発展に買することを目的とします。</p>
              </div>

              <div class="terms-article">
                <h3 class="terms-title">第2条(会員）</h3>
                <p class="terms-text">本規約を了承のうえ当社所定の形式により入会の手続きをされた法人およびそれに準ずる団体、個人事業主または個人のうち、当社が会員入会を承認した方を本会の会員とします（以下、会員入会を承認した法人およびそれに準ずる団体または個人事業主の方を「法人会員」、会員入会を承認した個人を「個人会員」という）。なお、法人会員は、複数口の入会が可能です。会員は、会員資格を第三者に利用させたり、貸与、譲渡、売買、質入等をすることはできないものとします。</p>
              </div>

              <div class="terms-article">
                <h3 class="terms-title">第3条(会員種別および会員サービス）</h3>
                <p class="terms-text">本会の会員は、スタンダード会員とプレミアムネット会員の2種類とし、その会員種別に応じた次のサービス（以下、「会員サービス」という）を利用できるものとします。</p>

                <h4 class="service-title">【スタンダード会員】</h4>
                <div class="service-item">
                  <span class="service-number">①</span>
                  <span class="service-text">機関誌「ちくぎん地域経済レポート」等、当社が発行する刊行物並びにダイレクトメール、E-Mail等による経済、産業、企業動向等、企業経営に役立つ情報サービス</span>
                </div>
                <div class="service-item">
                  <span class="service-number">②</span>
                  <span class="service-text">各種の糸営相談に対する課題解決に向けた提案（相談の内容によっては有料）</span>
                </div>
                <div class="service-item">
                  <span class="service-number">③</span>
                  <span class="service-text">当社主催の各種セミナー、企画、イベント等の割引料金による案内</span>
                </div>
                <div class="service-item">
                  <span class="service-number">④</span>
                  <span class="service-text">当社が運営するインターネットサイト（スタンダード会員サイト）の利用</span>
                </div>

                <h4 class="service-title">【プレミアムネット会員】</h4>
                <p class="service-subtitle">スタンダード会員が利用できる上記①から④までのサービスに加えて</p>
                <div class="service-item">
                  <span class="service-number">⑤</span>
                  <span class="service-text">ブレミアムネット会員専用インターネットサイト（販路拡大等を目的としたビジネスマッチングサービスを含む）による企業経営に役当つ各種ビジネス情報の提供</span>
                </div>
                <div class="service-item">
                  <span class="service-number">⑥</span>
                  <span class="service-text">会員企業PR情報掲歳サービス</span>
                </div>
                <div class="service-item">
                  <span class="service-number">⑦</span>
                  <span class="service-text">日経BP発刊「日経トップリーダー」の送付。同社主催の「日経トップリーダー 経営セミナー」の案内</span>
                </div>
              </div>
          </div>

            <div class="terms-download">
              <a href="/pdf/chikugin_kiyaku2.pdf" class="download-link" target="_blank">
                会員規約をPDFでダウンロード
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15 2.8125C12.5895 2.8125 10.2332 3.52728 8.22899 4.86646C6.22477 6.20564 4.66267 8.10907 3.74022 10.336C2.81778 12.563 2.57643 15.0135 3.04668 17.3777C3.51694 19.7418 4.67769 21.9134 6.38214 23.6179C8.08659 25.3223 10.2582 26.4831 12.6223 26.9533C14.9865 27.4236 17.437 27.1822 19.664 26.2598C21.8909 25.3373 23.7944 23.7752 25.1335 21.771C26.4727 19.7668 27.1875 17.4105 27.1875 15C27.1841 11.7687 25.899 8.67076 23.6141 6.3859C21.3292 4.10104 18.2313 2.81591 15 2.8125ZM18.4758 15.6633L13.7883 20.3508C13.7012 20.4379 13.5978 20.507 13.484 20.5541C13.3702 20.6013 13.2482 20.6255 13.125 20.6255C13.0018 20.6255 12.8798 20.6013 12.766 20.5541C12.6522 20.507 12.5488 20.4379 12.4617 20.3508C12.3746 20.2637 12.3055 20.1603 12.2584 20.0465C12.2112 19.9327 12.187 19.8107 12.187 19.6875C12.187 19.5643 12.2112 19.4423 12.2584 19.3285C12.3055 19.2147 12.3746 19.1113 12.4617 19.0242L16.4871 15L12.4617 10.9758C12.2858 10.7999 12.187 10.5613 12.187 10.3125C12.187 10.0637 12.2858 9.82513 12.4617 9.64922C12.6376 9.47331 12.8762 9.37448 13.125 9.37448C13.3738 9.37448 13.6124 9.47331 13.7883 9.64922L18.4758 14.3367C18.563 14.4238 18.6321 14.5272 18.6793 14.641C18.7265 14.7548 18.7507 14.8768 18.7507 15C18.7507 15.1232 18.7265 15.2452 18.6793 15.359C18.6321 15.4728 18.563 15.5762 18.4758 15.6633Z" fill="#DA5761"/>
                </svg>
              </a>
          </div>

            <div class="privacy-section">
              <p class="privacy-text">弊社のプライバシーポリシー（個人情報保護方針）に同意をされる場合は、下記のチェックボックスにチェックを入れてください。</p>
              <div class="privacy-checkbox">
                <input type="checkbox" id="privacy-agree" v-model="formData.privacyAgreement" />
                <label for="privacy-agree">個人情報保護方針に同意します<span class="required-mark">*</span></label>
          </div>
          </div>

            <div class="submit-section">
              <button type="submit" class="submit-btn" :disabled="!canSubmit">
                確認する
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                  <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#1A1A1A"/>
                </svg>
              </button>
          </div>
          </div>
        </form>
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
import { usePageText } from '@/composables/usePageText'

export default {
  name: 'SeminarApplicationFormPage',
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
      pageKey: 'membership',
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
  computed: {
    canSubmit() {
      return this.formData.subject &&
             this.formData.lastName &&
             this.formData.firstName &&
             this.formData.email &&
             this.formData.content &&
             this.formData.privacyAgreement &&
             !this.isSubmitting;
    },
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '入会申し込み') || '入会申し込み' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'membership') || 'membership' },
    formTitle() { return this._pageText?.getText('form_title', this.pageTitle) || this.pageTitle },
  },
  mounted() {
    // URLパラメータからフォームデータを復元
    const params = new URLSearchParams(this.$route.query);
    if (params.get('formData')) {
      try {
        this.formData = JSON.parse(decodeURIComponent(params.get('formData')));
      } catch (e) {
        console.error('フォームデータの復元に失敗しました:', e);
      }
    }
    // Load page texts
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load()
    } catch(e) { /* noop */ }
  },
  methods: {
    async submitForm() {
      if (!this.canSubmit) return;
      
      // 確認ページに遷移（フォームデータをURLパラメータで渡す）
      const formDataParam = encodeURIComponent(JSON.stringify(this.formData));
      this.$router.push(`/membership/apply/confirm?formData=${formDataParam}`);
    },
    
    resetForm() {
      this.formData = {
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
      };
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

/* Form Fields */
.contact-form {
  padding: 50px;
  border-radius: 20px;
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

.contact-form {
    padding: 30px 20px !important;
  }

/* Form Section */
.form-section {
  padding: 70px 50px 50px;
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

.required-mark {
  color: #DA5761;
  margin-left: 4px;
}

.field-input {
  width: 920px;
  position: relative;
}

.select-field {
  width: 100%;
  padding: 15px;
  border-radius: 10px;
  background: #FFF;
  border: 1px solid #E0E0E0;
  font-size: 18px;
  color: #727272;
  appearance: none;
}

.select-arrow {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.name-inputs {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.text-input {
  padding: 15px;
  border-radius: 10px;
  background: #FFF;
  border: 1px solid #E0E0E0;
  font-size: 18px;
  color: #1A1A1A;
}

.text-input::placeholder {
  color: #CFCFCF;
}

.text-input.single {
  width: 100%;
  height: 57px;
}

.textarea-input {
  width: 100%;
  height: 250px;
  padding: 15px;
  border-radius: 10px;
  background: #FFF;
  border: 1px solid #E0E0E0;
  font-size: 18px;
  color: #1A1A1A;
  resize: vertical;
  font-family: inherit;
}

.textarea-input::placeholder {
  color: #CFCFCF;
}

/* Terms Section */
.terms-section {
  padding-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
}

.terms-content {
  padding: 30px;
  border-radius: 20px;
  background: #CFCFCF;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 30px;
  max-height: 400px;
  overflow-y: auto;
}

.terms-article {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.terms-title {
  font-size: 18px;
  font-weight: 700;
  color: #1A1A1A;
  margin: 0;
}

.terms-text {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  line-height: 1.6;
  margin: 0;
}

.service-title {
  font-size: 18px;
  font-weight: 700;
  color: #1A1A1A;
  margin: 10px 0 0 0;
}

.service-subtitle {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  margin: 5px 0;
}

.service-item {
  display: flex;
  align-items: flex-start;
  gap: 5px;
  margin-top: 5px;
}

.service-number {
  font-size: 14px;
  font-weight: 400;
  color: #1A1A1A;
  min-width: 21px;
  flex-shrink: 0;
}

.service-text {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  flex: 1;
  line-height: 1.6;
}

.terms-download {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  justify-content: flex-start;
  width: 100%;
}

.download-link {
  display: flex;
  align-items: center;
  gap: 5px;
  padding-bottom: 5px;
  border-bottom: 1px solid #DA5761;
  color: #3F3F3F;
  text-decoration: none;
  font-size: 18px;
  font-weight: 400;
}

.privacy-section {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  gap: 10px;
  width: 100%;
}

.privacy-text {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  text-align: left;
  margin: 0;
}

.privacy-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
}

.privacy-checkbox input[type="checkbox"] {
  width: 16px;
  height: 16px;
}

.privacy-checkbox label {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
}

.submit-section {
  padding-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
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
  
  .field-input {
    width: 70% !important;
  }
  
  .field-label {
    min-width: 200px !important;
    font-size: 18px !important;
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

  .select-field,
  .text-input,
  .textarea-input {
    font-size: 18px !important;
  }

  .terms-title,
  .service-title {
    font-size: 18px !important;
  }

  .terms-text,
  .service-text,
  .service-subtitle {
    font-size: 18px !important;
  }

  .download-link {
    font-size: 18px !important;
  }

  .privacy-text,
  .privacy-checkbox label {
    font-size: 18px !important;
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

  .terms-content {
    padding: 35px !important;
    gap: 25px !important;
  }

  .terms-section {
    gap: 25px !important;
  }

  /* フォームフィールドの調整 */
  .form-field {
    padding: 22px 0 !important;
  }

  /* 名前入力フィールドの調整 */
  .name-inputs {
    gap: 12px !important;
  }

  /* セレクトフィールドの調整 */
  .select-field {
    padding: 16px !important;
  }

  /* テキストエリアの調整 */
  .textarea-input {
    padding: 16px !important;
  }
}

@media (max-width: 900px) {
  .form-section {
    padding: 30px 20px 40px !important;
  }
  
  .field-input {
    width: 100% !important;
  }
  
  .field-label {
    min-width: 180px !important;
    font-size: 17px !important;
  }

  .field-input {
    width: 65% !important;
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

  .select-field,
  .text-input,
  .textarea-input {
    font-size: 17px !important;
  }

  .terms-title,
  .service-title {
    font-size: 17px !important;
  }

  .terms-text,
  .service-text,
  .service-subtitle {
    font-size: 17px !important;
  }

  .download-link {
    font-size: 17px !important;
  }

  .privacy-text,
  .privacy-checkbox label {
    font-size: 17px !important;
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

  .terms-content {
    padding: 30px !important;
    gap: 25px !important;
  }

  .terms-section {
    gap: 25px !important;
  }

  /* フォームフィールドの調整 */
  .form-field {
    padding: 20px 0 !important;
  }

  /* 名前入力フィールドの調整 */
  .name-inputs {
    gap: 10px !important;
  }

  /* セレクトフィールドの調整 */
  .select-field {
    padding: 15px !important;
  }

  /* テキストエリアの調整 */
  .textarea-input {
    padding: 15px !important;
  }
}

@media (max-width: 768px) {
  .form-section {
    padding: 30px 20px 40px !important;
  }
  
  .form-field {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 15px !important;
  }
  
  .field-label {
    min-width: auto !important;
    font-size: 16px !important;
  }
  
  .field-input {
    width: 100% !important;
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

  .select-field,
  .text-input,
  .textarea-input {
    font-size: 16px !important;
  }

  .terms-title,
  .service-title {
    font-size: 16px !important;
  }

  .terms-text,
  .service-text,
  .service-subtitle {
    font-size: 16px !important;
  }

  .download-link {
    font-size: 16px !important;
  }

  .privacy-text,
  .privacy-checkbox label {
    font-size: 16px !important;
  }

  .submit-btn {
    font-size: 17px !important;
    width: 100% !important;
    padding: 15px 60px !important;
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

  .name-inputs {
    width: 100% !important;
  }

  .text-input.single {
    height: 50px !important;
  }

  .textarea-input {
    height: 200px !important;
  }

  /* フォームフィールドのレイアウト調整 */
  .form-field {
    padding: 18px 0 !important;
  }

  /* 名前入力フィールドの調整 */
  .name-inputs {
    gap: 8px !important;
  }

  /* セレクトフィールドの調整 */
  .select-field {
    padding: 14px !important;
  }

  /* テキストエリアの調整 */
  .textarea-input {
    padding: 14px !important;
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

  .select-field,
  .text-input,
  .textarea-input {
    font-size: 13px !important;
    padding: 12px !important;
  }

  .terms-title,
  .service-title {
    font-size: 13px !important;
  }

  .terms-text,
  .service-text,
  .service-subtitle {
    font-size: 13px !important;
  }

  .download-link {
    font-size: 13px !important;
  }

  .privacy-text,
  .privacy-checkbox label {
    font-size: 13px !important;
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

  .terms-content {
    padding: 15px !important;
    gap: 15px !important;
    border-radius: 15px !important;
  }

  .terms-section {
    gap: 15px !important;
  }

  .name-inputs {
    width: 100% !important;
  }

  .text-input.single {
    height: 45px !important;
  }

  .textarea-input {
    height: 150px !important;
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

  /* 名前入力フィールドの調整 */
  .name-inputs {
    gap: 6px !important;
  }

  /* セレクトフィールドの調整 */
  .select-field {
    padding: 10px !important;
  }

  /* テキストエリアの調整 */
  .textarea-input {
    padding: 10px !important;
  }

  /* チェックボックスの調整 */
  .privacy-checkbox input[type="checkbox"] {
    width: 14px !important;
    height: 14px !important;
  }

  /* ダウンロードリンクの調整 */
  .download-link svg {
    width: 25px !important;
    height: 25px !important;
  }

  .contact-form {
    padding: 20px 15px !important;
  }
}
</style>
