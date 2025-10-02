<template>
  <div class="privacy-policy-page">
    <!-- Navigation -->
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      cms-page-key="privacy"
      heroImage="/img/Image_fx8.jpg"
      mediaKey="hero_privacy"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <!-- Main Content -->
    <div class="main-content">
      <div class="section-header">
        <h2 class="main-title">
          <CmsText pageKey="privacy" fieldKey="page_title" tag="span" :fallback="pageTitle" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="privacy" fieldKey="page_subtitle" tag="span" :fallback="pageSubtitle" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>

      <!-- Render sanitized full HTML only in CMS preview/edit mode -->
      <div class="content-container" v-if="hasHtml && isEditPreview">
        <div class="cms-body" v-html="_pageText?.getHtml('body', '')"></div>
      </div>

      <!-- Structured content (texts) in normal viewing or when no full HTML provided -->
      <div class="content-container" v-if="!isEditPreview || !hasHtml">
        <!-- Introduction -->
        <div class="intro-section">
          <p class="intro-text">
            <CmsText pageKey="privacy" fieldKey="intro" tag="span" :fallback="'当研究所では以下のプライバシーポリシーに基づき、お客様より提供を受けた個人情報を厳正な管理の元に保管し、当研究所の管理行においてのみ利用します。当研究所のサービスを利用した場合、当研究所のキャンペーンやアンケート等に応募した場合には、プライバシーポリシーに同意したものとみなされますので、本プライバシーポリシーの内容をご理解いただくようお願いいたします。'" />
          </p>
        </div>

        <!-- Privacy Policy Sections -->
        <div class="policy-section">
          <h3 class="section-title"><CmsText pageKey="privacy" fieldKey="collection_title" tag="span" :fallback="'個人情報の収集'" /></h3>
          <p class="section-content">
            <CmsText pageKey="privacy" fieldKey="collection_body" tag="span" :fallback="'当研究所では、当研究所の各種サービスをご利用いただくにあたって、個人の情報をお伺いする場合があります。お伺いする情報としては、会社名、役職、氏名、Ｅメールアドレス、電話番号などの個人情報が主なものとなります。また、それ以外にもアンケート調査のため、質問させていただくこともございます。これらの情報は、すべて下記の収集目的に従って、適法かつ公正な手段により収集されます。'" />
          </p>
        </div>

        <div class="policy-section">
          <h3 class="section-title"><CmsText pageKey="privacy" fieldKey="purpose_title" tag="span" :fallback="'個人情報の利用目的'" /></h3>
          <p class="section-content"><CmsText pageKey="privacy" fieldKey="purpose_intro" tag="span" :fallback="'当研究所が個人情報を収集または利用する目的は以下のとおりです。'" /></p>
          <div class="list-content">
            <CmsText pageKey="privacy" fieldKey="purpose_list" tag="div" type="html" :fallback="'サービスの提供、キャンペーンなどを行うため<br>サービスに関する情報またはキャンペーン情報を提供するため<br>お客様から寄せられたご意見、ご要望にお答えするため<br>サービスの開発・改善を目的とした調査・検討を行うため<br>サービスに関する統計的資料を作成するため'" />
          </div>
        </div>

        <div class="policy-section">
          <h3 class="section-title"><CmsText pageKey="privacy" fieldKey="disclosure_title" tag="span" :fallback="'個人情報の第三者提供'" /></h3>
          <div class="list-content">
            <CmsText pageKey="privacy" fieldKey="disclosure_list" tag="div" type="html" :fallback="'法令により情報の開示が求められる場合<br>人の生命、身体または財産の保護のために必要があると当研究所が判断した場合<br>国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力することその他公共の利益のために特に必要があると当研究所が判断した場合<br>お客様または当研究所の権利の確保のために必要であると当研究所が判断した場合<br>業務遂行に必要な限度で個人情報の取扱いを委託する場合'" />
          </div>
        </div>

        <div class="policy-section">
          <h3 class="section-title"><CmsText pageKey="privacy" fieldKey="correction_title" tag="span" :fallback="'個人情報の開示・訂正・削除'" /></h3>
          <p class="section-content">
            <CmsText pageKey="privacy" fieldKey="correction_body" tag="div" type="html" :fallback="'お客様は、当研究所所定の手続きにより、以下の請求を行うことができます。(1) 当研究所の保有する自己の個人情報が誤った情報でないことを確認すること (2) 当研究所の保有する自己の個人情報が誤った情報である場合に、それを訂正または削除すること<br>当研究所は、前項(２)の個人情報の訂正または削除の可否を決定した場合には、遅滞なく、当該お客様に通知します。<br>これらの請求を希望される場合には、お問い合わせフォームよりご連絡ください。'" />
          </p>
        </div>

        <div class="policy-section">
          <h3 class="section-title"><CmsText pageKey="privacy" fieldKey="disclaimer_title" tag="span" :fallback="'免責事項'" /></h3>
          <p class="section-content">
            <CmsText pageKey="privacy" fieldKey="disclaimer_body1" tag="span" :fallback="'当研究所では、お客様の個人情報を厳格に管理し、改ざん、漏えいや不正アクセス等の危険性に対して予防策を実施します。 個人情報の登録が発生するWebページではデータ送信の際にSSL(Secure Socket Layer)暗号化技術を使用しております。'" />
          </p>
          <p class="section-content">
            <CmsText pageKey="privacy" fieldKey="disclaimer_body2" tag="span" :fallback="'ただし、各種申込フォームにお客様が入力されたメールアドレスが間違っている場合は責任を負いかねますのでご理解下さい。'" />
          </p>
          <p class="section-content">
            <CmsText pageKey="privacy" fieldKey="disclaimer_body3" tag="span" :fallback="'※各種申込フォームでは内容をお客様に確認していただくために、登録されたメールアドレスに申込情報を自動的に配信する仕組みになっています。そのため間違ったメールアドレスであっても、そのメールアドレスに申込情報が自動的に配信されます。'" />
          </p>
        </div>

        <div class="policy-section">
          <h3 class="section-title"><CmsText pageKey="privacy" fieldKey="changes_title" tag="span" :fallback="'プライバシーポリシーの変更'" /></h3>
          <p class="section-content">
            <CmsText pageKey="privacy" fieldKey="changes_body" tag="span" :fallback="'当研究所サイトを利用された場合、お客様はプライバシーポリシーに同意して頂いたものと判断いたします。諸事情により、上記のプライバシーポリシーを部分的に変更、修正、 追加、削除させて頂くことがございます。加えて、プライバシーポリシーについては、変更等のご連絡をお客様にご連絡することはありません。随時、このページにてご確認ください。'" />
          </p>
        </div>
      </div>
    </div>

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
import AccessSection from "./AccessSection";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import CmsBlock from './CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'
import vector7 from "../../public/img/vector-7.svg";
import { frame132131753022Data } from "../data";
import { usePageText } from '@/composables/usePageText'

export default {
  name: "PrivacyPolicyPage",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    CmsBlock,
    CmsText,
  },
  data() {
    return {
      vector7: vector7,
      frame132131753022Props: frame132131753022Data,
    };
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', 'プライバシーポリシー') || 'プライバシーポリシー' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'privacy policy') || 'privacy policy' },
    isEditPreview() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsPreview') || params.has('cmsEdit')
      } catch (_) { return false }
    },
    hasHtml() {
      try {
        const page = this._pageText?.page?.value
        const html = page?.content?.html
        return typeof html === 'string' && html.trim().length > 0
      } catch(_) { return false }
    }
  },
  mounted() {
    try {
      this._pageText = usePageText('privacy')
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
  },

};
</script>

<style scoped>
.privacy-policy-page {
  background: #ECECEC;
  min-height: 100vh;
  width: 100%;
}





/* Main Content */
.main-content {
  width: 100%;
  padding: 70px 50px;
  background: #ECECEC;
}

.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 40px;
}

.main-title {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 36px;
  font-weight: 700;
  color: #1A1A1A;
  letter-spacing: -0.72px;
  text-align: center;
  margin: 0;
}

.section-title {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 24px;
  font-weight: 700;
  color: #1A1A1A;
  margin: 0;
}

.section-divider {
  display: flex;
  align-items: center;
  gap: 15px;
  width: auto;
  min-width: 306px;
}

.divider-line {
  width: 80px;
  height: 2px;
  background: #DA5761;
  flex-shrink: 0;
}

.divider-text {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: #DA5761;
}

.content-container {
  padding: 50px;
  border-radius: 20px;
  background: white;
  display: flex;
  flex-direction: column;
  gap: 50px;
  width: 100%;
  max-width: 2000px;
  margin: 0 auto;
}


.intro-section {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.intro-text {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  color: #3F3F3F;
  line-height: normal;
  margin: 0;
}

.policy-section {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.section-title {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 24px;
  font-weight: 700;
  color: #1A1A1A;
  margin: 0;
}

.section-content {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  color: #3F3F3F;
  line-height: normal;
  margin: 0;
}

.list-content {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  color: #3F3F3F;
  line-height: normal;
  margin: 0;
}

/* Inline-editable elements should match description font size */
.inline-editable {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 400;
  color: #3F3F3F;
  line-height: normal;
}

/* CMS body safe defaults (prevent layout break) */
.cms-body {
  width: 100%;
  max-width: 2000px;
  background: #FFF;
  border-radius: 20px;
  padding: 30px;
  overflow-wrap: anywhere;
}
.cms-body /deep/ h1, .cms-body /deep/ h2, .cms-body /deep/ h3, .cms-body /deep/ h4, .cms-body /deep/ h5, .cms-body /deep/ h6 {
  margin: 0.6em 0 0.4em;
  line-height: 1.3;
}
.cms-body /deep/ p { margin: 0.6em 0; line-height: 1.6; }
.cms-body /deep/ ul, .cms-body /deep/ ol { padding-left: 1.4em; margin: 0.6em 0; }
.cms-body /deep/ img { max-width: 100%; height: auto; }
.cms-body /deep/ table { width: 100%; border-collapse: collapse; margin: 0.8em 0; }
.cms-body /deep/ th, .cms-body /deep/ td { border: 1px solid #E5E5E5; padding: 8px 10px; text-align: left; vertical-align: top; }

/* Footer Navigation */



/* Responsive Design */
/* Inline-editable elements should match description font size */
.inline-editable {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 400;
  color: #3F3F3F;
  line-height: normal;
}

/* Responsive font sizes for inline-editable */
@media (max-width: 1150px) {
  .main-title {
    font-size: 32px !important;
  }
  
  .section-title {
    font-size: 22px !important;
  }
  
  .inline-editable {
    font-size: 20px !important;
  }
}

@media (max-width: 900px) {
  .main-content {
    padding: 30px 20px !important;
  }
  
  .content-container {
    padding: 30px 20px !important;
  }
  
  .section-header {
    gap: 22px !important;
  }
  
  .main-title {
    font-size: 29px !important;
  }
  
  .section-title {
    font-size: 20px !important;
  }
  
  .divider-text {
    font-size: 17px !important;
  }
  
  .intro-text {
    font-size: 17px !important;
  }
  
  .intro-text .inline-editable {
    font-size: 17px !important;
  }
  
  .section-content {
    font-size: 17px !important;
  }
  
  .section-content .inline-editable {
    font-size: 17px !important;
  }
  
  .list-content {
    font-size: 17px !important;
  }
  
  .list-content .inline-editable {
    font-size: 17px !important;
  }
  
  .inline-editable {
    font-size: 18px !important;
  }
}

@media (max-width: 768px) {
  .main-content {
    padding: 30px 20px !important;
  }
  
  .content-container {
    padding: 30px 20px !important;
  }
  
  .section-header {
    gap: 20px !important;
  }
  
  .main-title {
    font-size: 27px !important;
  }
  
  .section-title {
    font-size: 19px !important;
  }
  
  .divider-text {
    font-size: 16px !important;
  }
  
  .intro-text {
    font-size: 16px !important;
  }
  
  .intro-text .inline-editable {
    font-size: 16px !important;
  }
  
  .section-content {
    font-size: 16px !important;
  }
  
  .section-content .inline-editable {
    font-size: 16px !important;
  }
  
  .list-content {
    font-size: 16px !important;
  }
  
  .list-content .inline-editable {
    font-size: 16px !important;
  }
  
  .inline-editable {
    font-size: 16px !important;
  }
}

@media (max-width: 480px) {
  .main-content {
    padding: 20px 15px !important;
  }
  
  .content-container {
    padding: 20px 15px !important;
  }
  
  .section-header {
    gap: 18px !important;
  }
  
  .main-title {
    font-size: 22px !important;
  }
  
  .section-title {
    font-size: 18px !important;
  }
  
  .divider-text {
    font-size: 13px !important;
  }
  
  .intro-text {
    font-size: 13px !important;
  }
  
  .intro-text .inline-editable {
    font-size: 13px !important;
  }
  
  .section-content {
    font-size: 13px !important;
  }
  
  .section-content .inline-editable {
    font-size: 13px !important;
  }
  
  .list-content {
    font-size: 13px !important;
  }
  
  .list-content .inline-editable {
    font-size: 13px !important;
  }
  
  .inline-editable {
    font-size: 14px !important;
  }
}

</style>
