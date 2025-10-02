<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle || '用語集'"
      :subtitle="pageSubtitle || 'glossary'"
      cms-page-key="glossary"
      heroImage="/img/hero-image.png"
      mediaKey="hero_glossary"
    />
    
    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <div class="page-content">
      <div class="content-header">
        <h2 class="page-title">{{ pageTitle }}</h2>
        <div class="title-decoration">
          <div class="line-left"></div>
          <span class="title-english">{{ pageSubtitle }}</span>
          <div class="line-right"></div>
        </div>
      </div>
      
      <!-- CMS Preview Body under hero when preview/edit flags present -->
      <div class="content-container" v-if="isEditPreview">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>
      
      <div class="glossary-description" v-if="!isEditPreview">
        <CmsText
          pageKey="glossary"
          fieldKey="intro"
          tag="p"
          type="html"
          :fallback="'経済をより深く知るために必要な用語集となります。'"
          class="intro-text"
        />
      </div>
      
      <!-- PDF Download Button -->
      <div class="download-section">
        <button class="download-btn" @click="downloadPDF">
          <div class="text-44 valign-text-middle inter-bold-white-15px"><CmsText pageKey="glossary" fieldKey="download_pdf" tag="span" :fallback="'PDFダウンロード'" /></div>
          <div class="pdf-icon-wrapper">
            <img class="pdf-icon" src="/img/pdfaicon.png" alt="PDF" width="24" height="24" />
          </div>
        </button>
      </div>
      
      <div class="glossary-list" v-if="!isEditPreview">
        <div v-for="(item, index) in paginatedGlossary" :key="index" class="glossary-item">
          <div class="glossary-term" @click="toggleDefinition(index)">
            <div class="term-line"></div>
            <span class="term-text">{{ item.term }}</span>
            <span class="toggle-icon" :class="{ open: openItems.includes(index) }">
              <svg width="20" height="20" viewBox="0 0 20 20">
                <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" fill="none"/>
              </svg>
            </span>
          </div>
          <transition name="slide">
            <div v-if="openItems.includes(index)" class="glossary-definition">
              <div class="definition-content">
                <div class="definition-text" v-html="item.definition"></div>
              </div>
            </div>
          </transition>
        </div>
      </div>
      
      <!-- Pagination (dynamic) -->
      <div class="pagination" v-if="!isEditPreview && totalPages > 1">
        <button class="pagination-btn" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">‹</button>
        <template v-for="(p, i) in pagesToShow">
          <span v-if="p === '…'" class="pagination-dots" :key="`dots-${i}`">…</span>
          <button v-else class="pagination-btn" :class="{ active: currentPage === p }" @click="goToPage(p)" :key="`page-${p}`">{{ p }}</button>
        </template>
        <button class="pagination-btn" @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">›</button>
        <button class="pagination-btn next-btn" @click="goToPage(totalPages)" :disabled="currentPage === totalPages">最後</button>
      </div>
    </div>

    <!-- Contact CTA Section -->
    <ContactSection cms-page-key="glossary" />

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer Navigation -->
    <Footer v-bind="frame132131753022Props" />

    <!-- Fixed Side Buttons -->
    <FixedSideButtons position="bottom" />
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";
import Footer from "./Footer.vue";
import Group27 from "./Group27.vue";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import ContactSection from "./ContactSection.vue";
import AccessSection from "./AccessSection.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import { frame132131753022Data } from "../data.js";
import { usePageText } from '@/composables/usePageText'
import CmsText from '@/components/CmsText.vue'
import CmsBlock from '@/components/CmsBlock.vue'

export default {
  name: "GlossaryPage",
  components: {
    Navigation,
    Footer,
    Group27,
    HeroSection,
    Breadcrumbs,
    ContactSection,
    AccessSection,
    FixedSideButtons,
    CmsText,
    CmsBlock,
  },
  data() {
    return {
      frame132131753022Props: frame132131753022Data,
      searchQuery: '',
      openItems: [],
      currentPage: 1,
      itemsPerPage: 15,
      glossary: [
        {
          category: 'economic',
          term: 'CPI',
          definition: 'CPIとは、Consumer Price Index（消費者物価指数）の略称で、一般的な消費者が購入する商品やサービスの価格の変動を測る指標です。この指数は、食品や衣類、住宅、交通費など、日常生活に必要な一連の商品とサービスの価格を追跡し、それらの価格が時間とともにどのように変化するかを示します。<br><br>CPIは、インフレーション（物価上昇）やデフレーション（物価下降）の測定に用いられ、経済の健全性を評価するための重要な指標の一つです。政府や中央銀行は、CPIを参考にして金融政策を決定し、たとえばインフレが高い場合には金利を上げるなどの措置を取ることがあります。<br><br>また、CPIは給与や年金などの生活給付金の調整にも使われることがあり、物価の変動に合わせてこれらの支払い額が増減することがあります。消費者にとっては、物価の変動を理解し、自身の購買力がどのように影響を受けているかを知るための重要な指標となります。',
        },
        {
          category: 'economic',
          term: 'TIBOR',
          definition: 'TIBORとは、Tokyo Interbank Offered Rateの略称で、全国銀行協会が算出する「東京銀行間取引金利」のことを指します。<br><br>具体的には、主要な銀行が他の銀行に資金を貸し出す際に提示する金利の平均値を指します。日本円TIBORとユーロ円TIBORの2種類があり、期間別（1週間、1か月、3か月、6か月、12か月）に算出され、毎営業日午前11時頃に公表されます。<br><br>TIBORは、多くのローンや金融商品の金利設定のベースとして使用されます。例えば、変動金利型住宅ローンの金利や、企業の借入金利などに利用されることがあります。しかし、2012年のLIBOR不正操作問題を受けて、TIBORを含む銀行間取引金利の信頼性や透明性に疑問が投げかけられ、金利指標の改革や代替指標の検討が進められています。その一環で、ユーロ円TIBORは2024年12月末をもって恒久的に公表が停止されています。<br><br>投資家にとっては、TIBORの動向が金融市場全体の金利水準や、金利に連動する金融商品の価格に影響を与えるため、重要な指標の一つとなっています。ただし、近年では代替指標への移行も検討されているため、今後の動向に注意が必要です。',
        },
        {
          category: 'financial',
          term: '消費者信頼感指数',
          definition: '消費者が将来の経済状況や自身の収入について抱いている期待感や不安感を数値化した指標です。消費者がどの程度消費を控えるか、あるいは積極的に消費を行うかを予測する上で重要な指標となります。',
        },
        {
          category: 'financial',
          term: 'GDPデフレーター',
          definition: '名目GDPを実質GDPで割って算出される物価指数です。GDPに含まれる全ての財・サービスの価格変動を総合的に測定し、経済全体の物価水準の変化を示す指標です。',
        },
        {
          category: 'business',
          term: '短期金融資産',
          definition: '1年以内に現金化できる金融資産のことです。現金、預金、短期有価証券、売掛金などが含まれ、企業の流動性や短期的な支払い能力を示す指標となります。',
        },
        {
          category: 'business',
          term: 'M&A',
          definition: '企業の合併（Merger）と買収（Acquisition）を総称した用語です。企業の成長戦略や事業再編の手段として活用されます。',
        },
        {
          category: 'statistics',
          term: '標準偏差',
          definition: 'データのばらつきを示す統計指標です。平均値からの偏差の二乗平均の平方根で計算され、リスク分析などに使用されます。',
        },
        {
          category: 'economic',
          term: '公定歩合',
          definition: '中央銀行が民間銀行に資金を貸し出す際の基準金利です。金融政策の重要な手段として、市場金利や経済活動に大きな影響を与えます。',
        },
        {
          category: 'economic',
          term: '実質金利',
          definition: '名目金利からインフレ率を差し引いた金利です。実際の購買力の変化を反映し、投資判断や経済分析において重要な指標となります。',
        },
        {
          category: 'financial',
          term: '地方債',
          definition: '地方公共団体が発行する債券です。道路や学校などの公共事業の財源として活用され、国債と同様に安全な投資対象として知られています。',
        }
      ]
    };
  },
  computed: {
    // Page text helpers
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '用語集') || '用語集' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'Glossary') || 'Glossary' },
    isEditPreview() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsPreview') || params.has('cmsEdit')
      } catch (_) { return false }
    },

    // Filtering
    filteredGlossary() {
      let filtered = this.glossary
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(item =>
          (item.term || '').toLowerCase().includes(query) ||
          (item.definition || '').toLowerCase().includes(query)
        )
      }
      return filtered
    },

    // Pagination
    totalPages() {
      const len = this.filteredGlossary.length
      return Math.max(1, Math.ceil(len / this.itemsPerPage))
    },
    paginatedGlossary() {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredGlossary.slice(start, end)
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
  mounted() {
    try {
      this._pageText = usePageText('glossary')
      this._pageText.load({ force: true }).then(() => {
        try {
          const items = this._pageText?.page?.value?.content?.items
          if (Array.isArray(items) && items.length) {
            // Validate/normalize; only override when at least one valid item exists
            const normalized = items
              .map(it => it || {})
              .map(it => ({
                term: typeof it.term === 'string' ? it.term : (typeof it.title === 'string' ? it.title : ''),
                definition: typeof it.definition === 'string' ? it.definition : (typeof it.content === 'string' ? it.content : ''),
                category: typeof it.category === 'string' ? it.category : ''
              }))
              .filter(it => it.term && it.definition)

            if (normalized.length) {
              this.glossary = normalized
            }
          }
        } catch (_) {}
      })
    } catch(e) { /* noop */ }
  },
  methods: {
    goToPage(p) {
      if (p >= 1 && p <= this.totalPages) this.currentPage = p
    },
    toggleDefinition(index) {
      const itemIndex = this.openItems.indexOf(index);
      if (itemIndex > -1) {
        this.openItems.splice(itemIndex, 1);
      } else {
        this.openItems.push(index);
      }
    },
    
    downloadPDF() {
      // 用語集のPDFファイルURL（実際のファイルパスに変更してください）
      const pdfUrl = '/pdf/glossary.pdf';
      window.open(pdfUrl, '_blank');
    }
  }
};
</script>

<style scoped>

.page-container {
  min-height: 100vh;
  background-color: #ECECEC;
}

/* Page Content */
.page-content {
  width: 100%;
  margin: 0 auto;
  padding: 70px 50px;
}

.content-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 40px;
}

.page-title {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 36px;
  font-weight: 700;
  color: #1A1A1A;
  letter-spacing: -0.72px;
  text-align: center;
  margin: 0;
}

.title-decoration {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
  width: auto;
  min-width: 306px;
}

.line-left, .line-right {
  width: 80px;
  height: 2px;
  background: #DA5761;
  flex-shrink: 0;
}

.title-english {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: #DA5761;
}

/* Description */
.glossary-description {
  text-align: center;
  margin-bottom: 40px;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Download Section */
.download-section {
  display: flex;
  justify-content: center;
  margin-bottom: 40px;
}

.download-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 15px 30px;
  background: #1A1A1A;
  color: white;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: 'Inter', sans-serif;
  font-weight: 600;
  font-size: 15px;
  border: none;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.download-btn:hover {
  background: #333;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.pdf-icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}

.pdf-icon {
  width: 24px;
  height: 24px;
  filter: brightness(0) invert(1);
}

.glossary-description p {
  color: #666;
  font-size: 1.1rem;
  line-height: 1.6;
  margin: 0;
  text-align: center;
  width: 100%;
}

/* Glossary Items */
.glossary-list {
  max-width: 1500px;
  margin: 0 auto;
}

.glossary-item {
  background: white;
  border-radius: 10px;
  margin-bottom: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  overflow: hidden;
}

.glossary-term {
  display: flex;
  align-items: center;
  padding: 20px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.glossary-term:hover {
  background-color: #f8f9fa;
}

.term-line {
  flex: 0 0 5px;
  width: 5px;
  height: 40px;
  background: #da5761;
  margin-right: 15px;
}

.term-text {
  flex: 1;
  font-size: 1rem;
  color: #1A1A1A;
  font-weight: 500;
  font-family: "Noto Sans JP", "Helvetica Neue", Helvetica, Arial, sans-serif;
  line-height: 1.6;
  display: flex;
  align-items: center;
  min-height: 40px;
}

.toggle-icon {
  color: #666;
  transition: transform 0.3s;
}

.toggle-icon.open {
  transform: rotate(180deg);
}

.glossary-definition {
  display: flex;
  align-items: flex-start;
  padding: 20px;
  border-top: 1px solid #dee2e6;
}

.definition-content {
  flex: 1;
  padding-top: 5px;
}

.definition-text {
  color: #666;
  line-height: 1.6;
  margin-bottom: 15px;
  overflow-wrap: anywhere;
}

/* Slide Animation */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  max-height: 0;
  padding-top: 0;
  padding-bottom: 0;
}

.slide-enter-to,
.slide-leave-from {
  opacity: 1;
  max-height: 500px;
}

/* Responsive Design */
@media (max-width: 1150px) {
  .page-content {
    padding: 50px 30px !important;
  }
  
  .page-title {
    font-size: 32px !important;
  }
  
  .page-description {
    font-size: 18px !important;
  }
  
  .glossary-term {
    padding: 25px 30px !important;
    gap: 30px !important;
  }
  
  .term-text {
    font-size: 18px !important;
  }
  
  .term-line {
    width: 8px !important;
    height: 45px !important;
    margin-right: 15px !important;
  }
  
  .pagination-btn {
    width: 38px !important;
    height: 38px !important;
    font-size: 13px !important;
  }
}

@media (max-width: 900px) {
  .page-content {
    padding: 45px 25px !important;
  }
  
  .page-title {
    font-size: 29px !important;
  }
  
  .page-description {
    font-size: 17px !important;
  }
  
  .glossary-term {
    padding: 22px 25px !important;
    gap: 25px !important;
  }
  
  .term-text {
    font-size: 17px !important;
  }
  
  .term-line {
    width: 7px !important;
    height: 42px !important;
    margin-right: 13px !important;
  }
  
  .pagination-btn {
    width: 36px !important;
    height: 36px !important;
    font-size: 12px !important;
  }
}

@media (max-width: 768px) {
  .page-content {
    padding: 40px 15px !important;
  }
  
  .page-title {
    font-size: 27px !important;
  }
  
  .page-description {
    font-size: 16px !important;
  }
  
  .glossary-term {
    padding: 15px !important;
    gap: 20px !important;
  }
  
  .term-text {
    font-size: 16px !important;
  }
  
  .term-line {
    width: 5px !important;
    height: 35px !important;
    margin-right: 10px !important;
  }
  
  .pagination-btn {
    width: 34px !important;
    height: 34px !important;
    font-size: 11px !important;
  }
}

@media (max-width: 480px) {
  .page-content {
    padding: 30px 15px !important;
  }
  
  .page-title {
    font-size: 22px !important;
  }
  
  .page-description {
    font-size: 13px !important;
  }
  
  .glossary-term {
    padding: 12px !important;
    gap: 18px !important;
  }
  
  .term-text {
    font-size: 13px !important;
  }
  
  .term-line {
    width: 4px !important;
    height: 30px !important;
    margin-right: 8px !important;
  }
  
  .pagination-btn {
    width: 32px !important;
    height: 32px !important;
    font-size: 10px !important;
  }
}

/* Footer Navigation */

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
  color: #666;
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
  color: #666;
  font-size: 14px;
  font-weight: 500;
}

.next-btn {
  width: 60px;
}
</style>
