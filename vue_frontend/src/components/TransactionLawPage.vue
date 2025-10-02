<template>
  <div class="transaction-law-page">
    <!-- Navigation -->
    <navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      cms-page-key="transaction-law"
      heroImage="/img/Image_fx8.jpg"
      mediaKey="hero_transaction_law"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <!-- Main Content -->
    <div class="main-content">
      <div class="content-header">
        <h2 class="page-title">
          <CmsText pageKey="transaction-law" fieldKey="page_title" tag="span" :fallback="pageTitle" />
        </h2>
        <div class="title-decoration">
          <div class="decoration-line"></div>
          <span class="decoration-text">
            <CmsText pageKey="transaction-law" fieldKey="page_subtitle" tag="span" :fallback="pageSubtitle" />
          </span>
          <div class="decoration-line"></div>
        </div>
      </div>
      <!-- Full HTML render only in CMS preview; keep static table otherwise -->
      <div class="content-container" v-if="hasHtml && isEditPreview">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>

      <div v-if="!isEditPreview" class="transaction-law-table">
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="seller_label" tag="span" :fallback="'販売業者'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="seller_value" tag="span" :fallback="'株式会社 ちくぎん地域経済研究所'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="rep_label" tag="span" :fallback="'代表者名'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="rep_value" tag="span" :fallback="'代表取締役社長　空閑 重信'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="addr_label" tag="span" :fallback="'住所'" /></div>
          <div class="table-value">
            <CmsText pageKey="transaction-law" fieldKey="addr_value" tag="div" type="html" :fallback="'〒839-0864<br>福岡県久留米市百年公園1番1号<br>久留米リサーチセンタービル6階'" />
          </div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="tel_label" tag="span" :fallback="'電話番号'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="tel_value" tag="span" :fallback="'0942-46-5081 (平日9:00～17:00)'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="fax_label" tag="span" :fallback="'FAX番号'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="fax_value" tag="span" :fallback="'0942-38-7631'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="mail_label" tag="span" :fallback="'メール'" /></div>
          <div class="table-value-with-button">
            <span><CmsText pageKey="transaction-law" fieldKey="mail_value" tag="span" :fallback="'info@chikugin-ri.co.jp'" /></span>
            <button class="contact-button" @click="goToContact">
              <CmsText pageKey="transaction-law" fieldKey="contact_cta" tag="span" :fallback="'お問い合わせはこちら'" />
              <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect y="0.5" width="18" height="18" rx="5" fill="white"/>
                <path d="M13.7193 9.84548L10.2194 13.4453C10.1464 13.5203 10.0475 13.5625 9.94427 13.5625C9.84107 13.5625 9.74211 13.5203 9.66914 13.4453C9.59617 13.3702 9.55517 13.2684 9.55517 13.1623C9.55517 13.0562 9.59617 12.9544 9.66914 12.8793L12.5055 9.96248L4.88888 9.96248C4.78574 9.96248 4.68683 9.92034 4.6139 9.84533C4.54097 9.77032 4.5 9.66858 4.5 9.5625C4.5 9.45642 4.54097 9.35468 4.6139 9.27967C4.68683 9.20466 4.78574 9.16252 4.88888 9.16252L12.5055 9.16252L9.66914 6.24568C9.59617 6.17063 9.55517 6.06884 9.55517 5.9627C9.55517 5.85656 9.59617 5.75477 9.66914 5.67972C9.74211 5.60466 9.84107 5.5625 9.94427 5.5625C10.0475 5.5625 10.1464 5.60466 10.2194 5.67972L13.7193 9.27952C13.7554 9.31666 13.7841 9.36078 13.8037 9.40933C13.8233 9.45789 13.8333 9.50994 13.8333 9.5625C13.8333 9.61506 13.8233 9.66711 13.8037 9.71567C13.7841 9.76422 13.7554 9.80834 13.7193 9.84548Z" fill="#DA5761"/>
              </svg>
            </button>
          </div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="fee_label" tag="span" :fallback="'料金'" /></div>
          <div class="table-value">
            <div class="fee-description">
              <CmsText pageKey="transaction-law" fieldKey="fee_desc" tag="div" type="html" :fallback="'会費は下記に定める金額とします。<br>（法人会員については個人の登録１名あたりの金額）'" />
            </div>
            <div class="fee-section-title"><CmsText pageKey="transaction-law" fieldKey="fee_section_title" tag="span" :fallback="'■ ちくぎん地域経済クラブ'" /></div>
            <div class="fee-table">
              <div class="fee-row">
                <div class="fee-type"><CmsText pageKey="transaction-law" fieldKey="fee_standard_label" tag="span" :fallback="'スタンダード会員'" /></div>
                <div class="fee-amount"><CmsText pageKey="transaction-law" fieldKey="fee_standard_amount" tag="span" :fallback="'月額 1,000円（消費税別）'" /></div>
              </div>
              <div class="fee-row">
                <div class="fee-type"><CmsText pageKey="transaction-law" fieldKey="fee_premium_label" tag="span" :fallback="'プレミアムネット会員'" /></div>
                <div class="fee-amount"><CmsText pageKey="transaction-law" fieldKey="fee_premium_amount" tag="span" :fallback="'月額 3,000円（消費税別）'" /></div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="payment_label" tag="span" :fallback="'支払い時期および方法'" /></div>
          <div class="table-value">
            <CmsText pageKey="transaction-law" fieldKey="payment_body" tag="div" type="html" :fallback="'会費の納入は入会月の翌月から口座振替によって、毎月20日（休日の場合は翌営業日）に会員種別ごとの月額会費を納入することとします。<br>口座振替を利用しない場合は、毎年4月20日（休日の場合は翌営業日）までに年会費（毎年4月1日～翌年3月31日分）を一括納入することとします。'" />
          </div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="otherfees_label" tag="span" :fallback="'その他料金'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="otherfees_value" tag="span" :fallback="'口座振込：振込手数料'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="service_time_label" tag="span" :fallback="'提供時間'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="service_time_value" tag="span" :fallback="'所定の手続きの終了後、直ちにご利用いただけます。'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="cancel_label" tag="span" :fallback="'退会について'" /></div>
          <div class="table-value"><CmsText pageKey="transaction-law" fieldKey="cancel_value" tag="span" :fallback="'ご利用の停止、退会については、退会希望月の前月末までに、お電話または筑邦銀行窓口に直接お申し出ください。'" /></div>
        </div>
        
        <div class="table-row">
          <div class="table-label"><CmsText pageKey="transaction-law" fieldKey="refund_label" tag="span" :fallback="'返金について'" /></div>
          <div class="table-value">
            <CmsText pageKey="transaction-law" fieldKey="refund_body" tag="div" type="html" :fallback="'退会または除名された会員がすでに納入した会費は返還しないものとします。<br>ただし、会員規約の変更に伴い退会する会員で、退会月の翌月以降の先払分会費がある場合は、当該会費を返還することとします。'" />
          </div>
        </div>

      <!-- Member Terms Section -->
        <div class="member-terms-section">
          <div class="terms-notice">
            <CmsText pageKey="transaction-law" fieldKey="terms_note" tag="span" :fallback="'会員サービスについては、会員規約も合わせてご確認ください。'" />
          </div>
        
        <div class="terms-content">
          <div class="terms-article">
            <h3 class="terms-title"><CmsText pageKey="transaction-law" fieldKey="article1_title" tag="span" :fallback="'第1条(目的）'" /></h3>
            <p class="terms-text">
              <CmsText pageKey="transaction-law" fieldKey="article1_body" tag="span" :fallback="'「ちくぎん地域経済クラブ」（以下、「本会」という）は、株式会社ちくぎん地域経済研究所（以下、「当社」という）が運営するサービスであり、産・官・学・金（金融機関）のネットワーク構築や会員相互の交流等を通じて、企業経営等に役立つ様々な情報や機会提供により、会員企業等がともに発展し、ひいては地域の振興・発展に寄与することを目的とします。'" />
            </p>
          </div>
          
          <div class="terms-article">
            <h3 class="terms-title"><CmsText pageKey="transaction-law" fieldKey="article2_title" tag="span" :fallback="'第2条(会員）'" /></h3>
            <p class="terms-text">
              <CmsText pageKey="transaction-law" fieldKey="article2_body" tag="div" type="html" :fallback="'本規約を了承のうえ当社所定の形式により入会の手続きをされた法人およびそれに準ずる団体、個人事業主または個人のうち、当社が会員入会を承認した方を本会の会員とします（以下、会員入会を承認した法人およびそれに準ずる団体または個人事業主の方を「法人会員」、会員入会を承認した個人の方を「個人会員」という）。なお、法人会員は、複数口の入会が可能です。<br>会員は、会員資格を第三者に利用させたり、貸与、譲渡、売買、質入等をすることはできないものとします。'" />
            </p>
          </div>
          
          <div class="terms-article">
            <h3 class="terms-title"><CmsText pageKey="transaction-law" fieldKey="article3_title" tag="span" :fallback="'第3条(会員種別および会員サービス）'" /></h3>
            <p class="terms-text">
              <CmsText pageKey="transaction-law" fieldKey="article3_body" tag="span" :fallback="'本会の会員は、スタンダード会員とプレミアムネット会員の2種類とし、その会員種別に応じた次のサービス（以下、「会員サービス」という）を利用できるものとします。'" />
            </p>
            
            <div class="service-section">
              <h4 class="service-title">【スタンダード会員】</h4>
              <div class="service-item">
                <span class="service-number">①</span>
                <span class="service-text">機関誌「ちくぎん地域経済レポート」等、当社が発行する刊行物並びにダイレクトメール、E-Mail等による経済、産業、企業動向等、企業経営に役立つ情報サービス</span>
              </div>
              <div class="service-item">
                <span class="service-number">②</span>
                <span class="service-text">各種の経営相談に対する課題解決に向けた提案（相談の内容によっては有料）</span>
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
                <span class="service-text">プレミアムネット会員専用インターネットサイト（販路拡大等を目的としたビジネスマッチングサービスを含む）による企業経営に役立つ各種ビジネス情報の提供</span>
              </div>
              <div class="service-item">
                <span class="service-number">⑥</span>
                <span class="service-text">会員企業PR情報掲載サービス</span>
              </div>
              <div class="service-item">
                <span class="service-number">⑦</span>
                <span class="service-text">日経BP発刊「日経トップリーダー」の送付。同社主催の「日経トップリーダー 経営セミナー」の案内</span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="pdf-download" @click="downloadPDF">
          <span>会員規約をPDFでダウンロード</span>
          <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 2.8125C12.5895 2.8125 10.2332 3.52728 8.22899 4.86646C6.22477 6.20564 4.66267 8.10907 3.74022 10.336C2.81778 12.563 2.57643 15.0135 3.04668 17.3777C3.51694 19.7418 4.67769 21.9134 6.38214 23.6179C8.08659 25.3223 10.2582 26.4831 12.6223 26.9533C14.9865 27.4236 17.437 27.1822 19.664 26.2598C21.8909 25.3373 23.7944 23.7752 25.1335 21.771C26.4727 19.7668 27.1875 17.4105 27.1875 15C27.1841 11.7687 25.899 8.67076 23.6141 6.3859C21.3292 4.10104 18.2313 2.81591 15 2.8125ZM18.4758 15.6633L13.7883 20.3508C13.7012 20.4379 13.5978 20.507 13.484 20.5541C13.3702 20.6013 13.2482 20.6255 13.125 20.6255C13.0018 20.6255 12.8798 20.6013 12.766 20.5541C12.6522 20.507 12.5488 20.4379 12.4617 20.3508C12.3746 20.2637 12.3055 20.1603 12.2584 20.0465C12.2112 19.9327 12.187 19.8107 12.187 19.6875C12.187 19.5643 12.2112 19.4423 12.2584 19.3285C12.3055 19.2147 12.3746 19.1113 12.4617 19.0242L16.4871 15L12.4617 10.9758C12.2858 10.7999 12.187 10.5613 12.187 10.3125C12.187 10.0637 12.2858 9.82513 12.4617 9.64922C12.6376 9.47331 12.8762 9.37448 13.125 9.37448C13.3738 9.37448 13.6124 9.47331 13.7883 9.64922L18.4758 14.3367C18.563 14.4238 18.6321 14.5272 18.6793 14.641C18.7265 14.7548 18.7507 14.8768 18.7507 15C18.7507 15.1232 18.7265 15.2452 18.6793 15.359C18.6321 15.4728 18.563 15.5762 18.4758 15.6633Z" fill="#DA5761"/>
          </svg>
          </div>
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
import AccessSection from './AccessSection.vue';
import Navigation from "./Navigation";
import Footer from "./Footer";
import Group27 from "./Group27";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import { usePageText } from '@/composables/usePageText'
import FixedSideButtons from "./FixedSideButtons.vue";
import CmsBlock from './CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'

import vector7 from "../../public/img/vector-7.svg";
import { frame132131753022Data } from "../data";

export default {
  name: "TransactionLawPage",
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
  methods: {
    goToContact() {
      this.$router.push('/contact');
    },
    
    downloadPDF() {
      // 会員規約のPDFファイルURL
      const pdfUrl = '/pdf/chikugin_kiyaku2.pdf';
      window.open(pdfUrl, '_blank');
    }
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '特定商取引法に関する表記') || '特定商取引法に関する表記' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'transaction law') || 'transaction law' },
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
      this._pageText = usePageText('transaction-law')
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
  },
};
</script>

<style scoped>
.transaction-law-page {
  background-color: #ECECEC;
  width: 100%;
  min-height: 100vh;
}





/* Main Content */
.main-content {
  width: 100%;
  padding: 70px 50px 80px 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
}

.content-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
}

.page-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 36px;
  font-weight: 700;
  line-height: normal;
  letter-spacing: -0.72px;
  margin: 0;
}

.title-decoration {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  width: auto;
  min-width: 320px;
  height: 30px;
}

.decoration-line {
  width: 80px;
  height: 2px;
  background: #DA5761;
  flex-shrink: 0;
}

.decoration-text {
  color: #DA5761;
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
}

/* Transaction Law Table */
.transaction-law-table {
  width: 100%;
  max-width: 2000px;
  background: #FFF;
  border-radius: 20px;
  padding: 50px;
}

.table-row {
  display: flex;
  width: 100%;
  padding: 20px 0;
  border-bottom: 0.5px dashed #DA5761;
  justify-content: flex-start;
  align-items: flex-start;
}

.table-row:first-child {
  border-top: 0.5px dashed #DA5761;
}

.table-label {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 700;
  line-height: 150%;
  width: 250px;
}

.table-value {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
  flex: 1;
  width: 100%;
}

.table-value-with-button {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex: 1;
  width: 100%;
}

.table-value-with-button span {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
}

.contact-button {
  display: flex;
  padding: 10px 54px;
  align-items: center;
  gap: 10px;
  border-radius: 10px;
  background: #DA5761;
  border: none;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  color: #FFF;
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
  cursor: pointer;
}

.contact-button:hover {
  opacity: 0.8;
}

.fee-description {
  margin-bottom: 15px;
}

.fee-section-title {
  margin: 15px 0;
  font-weight: 400;
}

.fee-table {
  display: flex;
  flex-direction: column;
  background: #ECECEC;
  border-radius: 5px;
  overflow: hidden;
}

.fee-row {
  display: flex;
  padding: 15px 30px;
  justify-content: flex-start;
  align-items: center;
  border-bottom: 1px solid #B6B5B5;
}

.fee-row:last-child {
  border-bottom: none;
}

.fee-type {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
  width: 250px;
}

.fee-amount {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
  width: auto;
}

/* Member Terms Section */
.member-terms-section {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.terms-notice {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 700;
  line-height: 150%;
  padding: 5px 0;
  margin-top: 50px;
}

.terms-content {
  padding: 30px;
  background: #ECECEC;
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  gap: 30px;
  width: 100%;
  max-height: 400px;
  overflow-y: auto;
}

.terms-article {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.terms-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 700;
  line-height: normal;
  margin: 0;
}

.terms-text {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  margin: 0;
}

.service-section {
  margin-top: 20px;
}

.service-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 700;
  line-height: normal;
  margin: 15px 0 10px 0;
}

.service-subtitle {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 400;
  line-height: normal;
  margin: 10px 0;
}

.service-item {
  display: flex;
  align-items: flex-start;
  gap: 5px;
  margin-bottom: 10px;
}

.service-number {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 14px;
  font-weight: 400;
  line-height: normal;
  min-width: 21px;
  margin-top: 2px;
}

.service-text {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  flex: 1;
}

.pdf-download {
  display: flex;
    width: fit-content;
  padding: 30px 0 5px 0;
  justify-content: center;
  align-items: center;
  gap: 5px;
  border-bottom: 1px solid #DA5761;
  cursor: pointer;
}

.pdf-download span {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
}

.pdf-download:hover {
  opacity: 0.8;
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

/* Footer Section */
.footer-section {
  width: 100%;
  padding: 50px 100px;
  background: #CFCFCF;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 50px;
}

.footer-content {
  width: 100%;
  max-width: 1240px;
}

.footer-navigation {
  display: flex;
  align-items: flex-start;
  gap: 60px;
  margin-bottom: 50px;
}

.footer-column {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 20px;
  width: 151px;
}

.footer-column:nth-child(2) {
  width: 171px;
}

.footer-column:nth-child(3) {
  width: 210px;
}

.footer-column:nth-child(4) {
  width: 212px;
}

.footer-link-item {
  display: flex;
  height: 18px;
  align-items: center;
  gap: 10px;
  align-self: stretch;
  cursor: pointer;
}

.footer-link-item span {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 16px;
  font-weight: 400;
  line-height: 200%;
}

.footer-link-item:hover span {
  color: #DA5761;
}

.footer-sub-items {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 5px;
  width: 100%;
}

.footer-sub-item {
  display: flex;
  height: 15px;
  padding-left: 20px;
  align-items: center;
  gap: 10px;
  align-self: stretch;
  cursor: pointer;
}

.footer-sub-item span {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 16px;
  font-weight: 400;
  line-height: 200%;
}

.footer-sub-item:hover span {
  color: #DA5761;
}

.footer-service-items {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 5px;
  width: 100%;
  margin-top: 20px;
}

.footer-divider {
  width: 100%;
  height: 1px;
  background: #B2B2B2;
}

.footer-bottom {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.company-logo-section {
  display: flex;
  align-items: center;
  gap: 20px;
}

.company-info-text {
  display: flex;
  flex-direction: column;
  gap: -5px;
}

.company-name {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 24px;
  font-weight: 700;
  line-height: 150%;
}

.company-group {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.company-address {
  color: #1A1A1A;
  text-align: center;
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 400;
  line-height: 120%;
}

.copyright {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 400;
  line-height: 150%;
}



/* Footer Navigation */

/* Responsive Design */
@media (max-width: 1150px) {
  .service-subtitle {
    font-size: 18px !important;
  }
  
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
  
  .hero-title {
    font-size: 32px !important;
  }
  
  .hero-subtitle {
    font-size: 18px !important;
  }
  
  .main-content {
    padding: 50px 30px !important;
  }
  
  .content-header {
    gap: 25px !important;
  }
  
  .page-title {
    font-size: 32px !important;
  }
  
  .decoration-text {
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
  
  .service-title {
    font-size: 18px !important;
  }
  
  .service-text {
    font-size: 18px !important;
  }
  
  .terms-content {
    font-size: 18px !important;
  }
  
  .terms-title {
    font-size: 18px !important;
  }
  
  .terms-text {
    font-size: 18px !important;
  }
  
  .terms-notice {
    font-size: 18px !important;
  }
  
  .fee-description {
    font-size: 18px !important;
  }
  
  .fee-section-title {
    font-size: 18px !important;
  }
  
  .fee-type {
    font-size: 18px !important;
  }
  
  .fee-amount {
    font-size: 18px !important;
  }
  
  .fee-row {
    padding: 12px !important;
  }
  
  .contact-button {
    font-size: 18px !important;
    padding: 15px 30px !important;
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
  .service-subtitle {
    font-size: 17px !important;
  }
  
  .hero-title {
    font-size: 29px !important;
  }
  
  .hero-subtitle {
    font-size: 17px !important;
  }
  
  .main-content {
    padding: 45px 25px !important;
  }
  
  .content-header {
    gap: 22px !important;
  }
  
  .page-title {
    font-size: 29px !important;
  }
  
  .decoration-text {
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
  
  .table-value-with-button {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 15px !important;
  }
  
  .service-title {
    font-size: 17px !important;
  }
  
  .service-text {
    font-size: 17px !important;
  }
  
  .terms-content {
    font-size: 17px !important;
  }
  
  .terms-title {
    font-size: 17px !important;
  }
  
  .terms-text {
    font-size: 17px !important;
  }
  
  .terms-notice {
    font-size: 17px !important;
  }
  
  .fee-description {
    font-size: 17px !important;
  }
  
  .fee-section-title {
    font-size: 17px !important;
  }
  
  .fee-type {
    font-size: 17px !important;
  }
  
  .fee-amount {
    font-size: 17px !important;
  }
  
  .fee-row {
    padding: 11px !important;
  }
  
  .contact-button {
    font-size: 17px !important;
    padding: 14px 28px !important;
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
  .service-subtitle {
    font-size: 16px !important;
  }
  
  .main-content {
    padding: 40px 20px !important;
  }
  
  .content-header {
    gap: 20px !important;
  }
  
  .page-title {
    font-size: 27px !important;
  }
  
  .decoration-text {
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
  
  .service-title {
    font-size: 16px !important;
  }
  
  .service-text {
    font-size: 16px !important;
  }
  
  .terms-content {
    font-size: 16px !important;
  }
  
  .terms-title {
    font-size: 16px !important;
  }
  
  .terms-text {
    font-size: 16px !important;
  }
  
  .terms-notice {
    font-size: 16px !important;
  }
  
  .fee-description {
    font-size: 16px !important;
  }
  
  .fee-section-title {
    font-size: 16px !important;
  }
  
  .fee-type {
    font-size: 16px !important;
  }
  
  .fee-amount {
    font-size: 16px !important;
  }
  
  .fee-row {
    padding: 10px !important;
  }
  
  .contact-button {
    font-size: 16px !important;
    padding: 13px 26px !important;
  }

  .hero-title {
    font-size: 27px !important;
  }
  
  .hero-subtitle {
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
  .service-subtitle {
    font-size: 13px !important;
  }
  
  .hero-title {
    font-size: 22px !important;
  }
  
  .hero-subtitle {
    font-size: 13px !important;
  }
  
  .main-content {
    padding: 30px 15px !important;
  }
  
  .content-header {
    gap: 18px !important;
  }
  
  .page-title {
    font-size: 22px !important;
  }
  
  .decoration-text {
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
  
  .service-title {
    font-size: 13px !important;
  }
  
  .service-text {
    font-size: 13px !important;
  }
  
  .terms-content {
    font-size: 13px !important;
  }
  
  .terms-title {
    font-size: 13px !important;
  }
  
  .terms-text {
    font-size: 13px !important;
  }
  
  .terms-notice {
    font-size: 13px !important;
  }
  
  .fee-description {
    font-size: 13px !important;
  }
  
  .fee-section-title {
    font-size: 13px !important;
  }
  
  .fee-type {
    font-size: 13px !important;
  }
  
  .fee-amount {
    font-size: 13px !important;
  }
  
  .fee-row {
    padding: 8px !important;
  }
  
  .contact-button {
    font-size: 13px !important;
    padding: 12px 24px !important;
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
</style>
