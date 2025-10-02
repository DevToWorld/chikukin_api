<template>
  <div class="terms-of-service-page">
    <!-- Navigation -->
    <navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      cms-page-key="terms"
      heroImage="/img/Image_fx8.jpg"
      mediaKey="hero_terms"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <!-- Main Content -->
    <div class="main-content">
      <div class="section-header">
        <h2 class="main-title">
          <CmsText pageKey="terms" fieldKey="page_title" tag="span" :fallback="pageTitle" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="terms" fieldKey="page_subtitle" tag="span" :fallback="pageSubtitle" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>

      <!-- Full HTML render is only for CMS preview; otherwise keep section layout -->
      <div class="content-container" v-if="hasHtml && isEditPreview">
        <div class="cms-body" v-html="_pageText?.getHtml('body', '')"></div>
      </div>

      <div class="content-container" v-if="!hasHtml">
        <!-- Introduction -->
        <div class="intro-section">
          <CmsText
            pageKey="terms"
            fieldKey="intro"
            tag="p"
            type="html"
            class="intro-text"
            :fallback="`このウェブサイトhttp://www.chikugin-ri.co.jp/（以下｢当ウェブサイト｣といいます）は、株式会社 ちくぎん地域経済研究所（以下｢当研究所｣といいます）が運営しています。<br>ご利用にあたっては、以下の注意事項等をお読みになり、ご了解いただいたうえでご利用ください。なお、当研究所は当ウェブサイトの利用条件を予告なしに変更することがありますので、最新の利用条件をご確認ください。`"
          />
        </div>

        <!-- Terms of Service Sections -->
        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="copyright_title" tag="span" :fallback="'著作権等について'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="copyright_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="'当ウェブサイトに掲載された全ての内容（情報、商標、デザイン等）の著作権その他の知的財産権は、当研究所又は権限ある第三者に帰属します。したがって、それらを無断で使用、複製、改変することを禁じます。'"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="link_title" tag="span" :fallback="'リンクについて'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="link_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="'当ウェブサイトから、リンクやバナーによって他のウェブサイトへ移動できる場合があります。移動した先のウェブサイトは当研究所が運営するものではありません。したがって、その内容等について当研究所は責任を負いかねますのでご了承ください。'"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="disclaimer_title" tag="span" :fallback="'免責事項'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="disclaimer_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="`当ウェブサイトに掲載された情報、また、当ウェブサイトを利用することで生じたいかなるトラブル、損失、損害に対して、当研究所は責任を負いません。.6%の割合による遅延損害金を支払うものとします。<br>当ウェブサイトは、予告なしに運営の中断や、内容の変更を行うことがあります。これによって生じたいかなるトラブル、損失、損害に対しても、当研究所は責任を負いません。`"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="security_title" tag="span" :fallback="'セキュリティについて'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="security_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="`当ウェブサイトでは、不正アクセス、紛失、破壊、改ざん、漏えい等のトラブルを防止するために、セキュリティを高めるための取組みを継続的に実施していますが、かかる事故が一切発生しないことを保証するものではありません。<br>なお、当ウェブサイトからダウンロードする情報に含まれる可能性のあるコンピュータウィルス等への予防措置は利用者ご自身の責任でお取りください。`"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="cookie_title" tag="span" :fallback="'クッキー(Cookie)について'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="cookie_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="`当ウェブサイトでは、お客さまへの適切な情報の提供や、セキュリティの確保を目的に、クッキー(Cookie)を使用することがあります。<br>クッキー(Cookie)とは、お客さまがウェブサイトにアクセスする際に、ウェブサイトを通じてお客さまの端末に一定のテキストファイル(情報)を格納し、次回お客さまが同一のウェブサイトにアクセスする際にお客さまを識別できるようにする技術のことです。なお、ブラウザの設定によりクッキー(Cookie)の受入れを停止することもできます。`"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="environment_title" tag="span" :fallback="'ご利用環境について'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="environment_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="'このサイトはInternet Explorer7.0以上ならびにFirefox4.0以上のバージョンでのご利用を推奨しております。'"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="prohibited_title" tag="span" :fallback="'禁止される行為'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="prohibited_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="'当研究所は、利用者に通知することなく、本サービスの内容を変更しまたは本サービスの提供を中止することができるものとし、これによって利用者に生じた損害について一切の責任を負いません。'"
          />
        </div>

        <div class="policy-section">
          <h3 class="section-title">
            <CmsText pageKey="terms" fieldKey="article8_title" tag="span" :fallback="'第8条（利用規約の変更）'" />
          </h3>
          <CmsText
            pageKey="terms"
            fieldKey="article8_body"
            tag="p"
            class="section-content"
            type="html"
            :fallback="`(1) 営利の目的で（但し、当研究所との取引を除く）、当ウェブサイト又は当ウェブサイト上の情報を使用又は複製すること<br>(2) 当ウェブサイト上の情報に何らかの改変を加えること<br>(3) 当研究所の事前の同意を得ることなく当ウェブサイトへのリンクを行うこと<br>(4) 当ウェブサイトのうち、一般からのアクセスが意図されていない部分にアクセスすること<br>(5) 当ウェブサイトのコードを複製すること<br>(6) システムの脆弱性を試すこと又はセキュリティもしくは認証システムを破ること<br>(7) 有害なコンピュータプログラム等を送信または書き込むこと<br>(8) 当研究所または第三者を誹謗中傷し、またはその名誉・信用を害すること<br>(9) 法令に違反する行為を行うこと<br>(10) その他当研究所が不適切と認める行為を行うこと`"
          />
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
import vector7 from "../../public/img/vector-7.svg";
import { frame132131753022Data } from "../data";
import { usePageText } from '@/composables/usePageText'
import CmsText from '@/components/CmsText.vue'

export default {
  name: "TermsOfServicePage",
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
  mounted() {
    this.adjustRectangleHeight();
    window.addEventListener('resize', this.adjustRectangleHeight);
    try {
      this._pageText = usePageText('terms')
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.adjustRectangleHeight);
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '利用規約') || '利用規約' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'terms of service') || 'terms of service' },
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
  methods: {
    adjustRectangleHeight() {
      this.$nextTick(() => {
        const frame1321317466 = this.$el.querySelector('.frame-1321317466');
        const rectangle3 = this.$el.querySelector('.rectangle-3');
        
        if (frame1321317466 && rectangle3) {
          const frameHeight = frame1321317466.offsetHeight;
          rectangle3.style.height = frameHeight + 'px';
        }
      });
    }
  }
};
</script>

<style scoped>
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
.terms-of-service-page {
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

.section-title {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 36px;
  font-weight: 700;
  color: #1A1A1A;
  letter-spacing: -0.72px;
  text-align: left;
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
  white-space: nowrap;
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

/* CMS body safe defaults (prevent layout break) */
.cms-body {
  width: 100%;
  max-width: 2000px;
  background: #FFF;
  border-radius: 20px;
  padding: 30px;
  overflow-wrap: anywhere;
}
.cms-body h1, .cms-body h2, .cms-body h3, .cms-body h4, .cms-body h5, .cms-body h6 {
  margin: 0.6em 0 0.4em;
  line-height: 1.3;
}
.cms-body p { margin: 0.6em 0; line-height: 1.6; }
.cms-body ul, .cms-body ol { padding-left: 1.4em; margin: 0.6em 0; }
.cms-body img { max-width: 100%; height: auto; }
.cms-body table { width: 100%; border-collapse: collapse; margin: 0.8em 0; }
.cms-body th, .cms-body td { border: 1px solid #E5E5E5; padding: 8px 10px; text-align: left; vertical-align: top; }



/* Footer Navigation */



/* Responsive Design */
@media (max-width: 1150px) {
  .frame-1321317467-1 {
    flex-direction: column !important;
    gap: 20px !important;
  }
  
  .rectangle-3 {
    width: 100% !important;
    max-width: none !important;
    order: 2 !important;
  }
  
  .frame-1321317466 {
    order: 1 !important;
  }
  
  
  .main-content {
    padding: 50px 30px !important;
  }
  
  .content-container {
    padding: 30px 20px !important;
  }
  
  .section-header {
    gap: 25px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
  
  .transaction-law-table {
    padding: 50px 30px !important;
  }
  
  .table-label {
    font-size: 18px !important;
  }
  
  .table-value {
    font-size: 18px !important;
  }
  
  .intro-text {
    font-size: 18px !important;
  }
  
  .section-content {
    font-size: 18px !important;
  }
  
  .list-content {
    font-size: 18px !important;
  }
  
  .company-address {
    font-size: 18px !important;
  }
  
  .copyright {
    font-size: 18px !important;
  }
  
  .footer-section {
    padding: 50px 30px !important;
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
  
  .transaction-law-table {
    padding: 45px 25px !important;
  }
  
  .table-label {
    font-size: 17px !important;
  }
  
  .table-value {
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
  
  .company-address {
    font-size: 17px !important;
  }
  
  .copyright {
    font-size: 17px !important;
  }
  
  .footer-section {
    padding: 45px 25px !important;
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
  
  .transaction-law-table {
    padding: 30px 20px !important;
  }
  
  .table-row {
    flex-direction: column !important;
    gap: 10px !important;
  }
  
  .table-label {
    min-width: auto !important;
    font-size: 16px !important;
  }
  
  .table-value {
    max-width: none !important;
    font-size: 16px !important;
  }
  
  .table-value-with-button {
    flex-direction: column !important;
    gap: 15px !important;
    width: 100% !important;
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

  
  
  .company-address {
    font-size: 16px !important;
  }
  
  .copyright {
    font-size: 16px !important;
  }

  .footer-section {
    padding: 30px 20px !important;
  }

  .footer-navigation {
    flex-direction: column !important;
    gap: 30px !important;
  }

  .footer-column {
    width: 100% !important;
  }

  .company-logo-section {
    flex-direction: column !important;
    text-align: center !important;
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
  
  .transaction-law-table {
    padding: 20px 15px !important;
  }
  
  .table-label {
    font-size: 13px !important;
  }
  
  .table-value {
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
  
  .company-address {
    font-size: 13px !important;
  }
  
  .copyright {
    font-size: 13px !important;
  }
  
  .footer-section {
    padding: 20px 15px !important;
  }
}

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
  .inline-editable {
    font-size: 18px !important;
  }
}

@media (max-width: 768px) {
  .inline-editable {
    font-size: 16px !important;
  }
}

@media (max-width: 480px) {
  .inline-editable {
    font-size: 14px !important;
  }
}
</style>
