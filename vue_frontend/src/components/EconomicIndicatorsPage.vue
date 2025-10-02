<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      heroImage="/img/Image_fx5.jpg"
      mediaKey="hero_economic_indicators"
      cms-page-key="economic-indicators"
    />
    
    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <div class="page-content">
      <div class="section-header">
        <h2 class="section-title">{{ pageTitle }}</h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">{{ pageSubtitle }}</span>
          <div class="divider-line"></div>
        </div>
      </div>
      
      <div class="indicators-description">
        <div v-if="pageBodyHtml" class="cms-body" v-html="pageBodyHtml"></div>
        <p v-else>経済の動向を把握するために重要な経済指標の一覧となります。</p>
      </div>
      
      <div class="indicators-list">
        <div v-for="(cat, index) in categories" :key="cat.slug || index" class="indicator-item">
          <div class="indicator-term" @click="toggleDefinition(index)">
            <div class="term-line"></div>
            <span class="term-text">{{ cat.name }}</span>
            <span class="toggle-icon" :class="{ open: openItems.includes(index) }">
              <svg width="20" height="20" viewBox="0 0 20 20">
                <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" fill="none"/>
              </svg>
            </span>
          </div>
          <transition name="slide">
            <div v-if="openItems.includes(index)" class="indicator-definition">
              <div class="definition-content">
                <!-- 経済指標データテーブル -->
                <div class="indicator-table-container">
                  <div class="table-wrapper">
                    <table class="indicator-table">
                      <thead>
                        <tr>
                          <th>{{ getTableHeaders(cat).column1 }}</th>
                          <th>{{ getTableHeaders(cat).column2 }}</th>
                          <th>{{ getTableHeaders(cat).column3 }}</th>
                        </tr>
                      </thead>
                                              <tbody>
                          <tr v-for="(row, rowIndex) in getIndicatorRows(cat)" :key="rowIndex">
                            <td>
                              <a v-if="row.itemLink" :href="row.itemLink" target="_blank" class="table-link">{{ row.item || 'データなし' }}</a>
                              <span v-else>{{ row.item || 'データなし' }}</span>
                            </td>
                            <td>
                              <a v-if="row.latestLink" :href="row.latestLink" target="_blank" class="table-link">{{ row.latest || 'データなし' }}</a>
                              <span v-else>{{ row.latest || 'データなし' }}</span>
                            </td>
                            <td>{{ row.previous || 'データなし' }}</td>
                          </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </transition>
        </div>
      </div>
      

    </div>

    <!-- Contact CTA Section -->
    <ContactSection />

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
import apiClient from '@/services/apiClient.js'
import { usePageText } from '@/composables/usePageText'

export default {
  name: "EconomicIndicatorsPage",
  components: {
    Navigation,
    Footer,
    Group27,
    HeroSection,
    Breadcrumbs,
    ContactSection,
    AccessSection,
    FixedSideButtons
  },
  data() {
    return {
      pageKey: 'economic-indicators',
      frame132131753022Props: frame132131753022Data,
      pageBodyHtml: '',
      openItems: [],
      categories: [],
      indicatorsByCategory: {},
      indicators: [
        {
          category: 'economic',
          term: '景気',
        },
        {
          category: 'economic',
          term: '個人消費',
        },
        {
          category: 'economic',
          term: '投資',
        },
        {
          category: 'financial',
          term: '貿易',
        },
        {
          category: 'financial',
          term: '生産',
        },
        {
          category: 'economic',
          term: '雇用・所得',
        },
        {
          category: 'economic',
          term: '金融',
        },
        {
          category: 'economic',
          term: '海外指標',
        }
      ]
    };
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '経済指標一覧') || '経済指標一覧' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'economic indicators') || 'economic indicators' },
  },
  async mounted() {
    // ページ文言（タイトル等）をCMSから読込
    try {
      this._pageText = usePageText(this.pageKey)
      await this._pageText.load({ force: true })
    } catch (e) { /* noop */ }

    // CMSからHTMLが設定されていれば優先表示 + カテゴリ/指標の読込
    try {
      const [pageRes, catRes, indRes] = await Promise.all([
        apiClient.getPageContent('economic-indicators'),
        apiClient.getIndicatorCategories(),
        apiClient.getIndicators()
      ])
      const html = pageRes?.data?.page?.content?.html
      if (typeof html === 'string' && html.trim()) {
        this.pageBodyHtml = html
      }
      if (catRes?.success) {
        this.categories = Array.isArray(catRes.data) ? catRes.data : []
      }
      if (indRes?.success) {
        const grouped = {}
        ;(indRes.data || []).forEach(i => {
          const key = i.category || 'others'
          if (!grouped[key]) grouped[key] = []
          grouped[key].push(i)
        })
        this.indicatorsByCategory = grouped
      }
    } catch (e) { /* noop */ }
  },
  methods: {
    toggleDefinition(index) {
      const itemIndex = this.openItems.indexOf(index);
      if (itemIndex > -1) {
        this.openItems.splice(itemIndex, 1);
      } else {
        this.openItems.push(index);
      }
    },
    


    getTableHeaders(termOrCat) {
      return {
        column1: '経済指標',
        column2: 'リンク先',
        column3: '公表日'
      };
    },
    getIndicatorRows(cat) {
      const list = this.indicatorsByCategory[cat?.slug] || []
      return list.map(ind => ({
        item: ind.name,
        latest: ind.source || '-',
        previous: (ind.publication_schedule && String(ind.publication_schedule).trim())
          ? ind.publication_schedule
          : (ind.publication_date ? new Date(ind.publication_date).toLocaleDateString('ja-JP') : '-'),
        itemLink: ind.link_url || '',
        latestLink: ind.link_url || ''
      }))
    },
    // 既存の静的データは下に残します（未取得時のフォールバック用）
    getIndicatorData(term) {
      // 各経済指標に対応する11行3列のデータを返す
      const dataMap = {
        '景気': [
          { item: 'GDP速報', latest: '内閣府', previous: '四半期毎の翌々月(中旬)', itemLink: 'https://www.esri.cao.go.jp/jp/sna/data/data_list/sokuhou/gaiyou/gaiyou_top.html', latestLink: 'https://www.esri.cao.go.jp/' },
          { item: '景気動向指数(全国)', latest: '内閣府', previous: '翌々月の上旬', itemLink: 'https://www.esri.cao.go.jp/jp/stat/di/di.html', latestLink: 'https://www.esri.cao.go.jp/' },
          { item: '景気動向指数(福岡県)', latest: '福岡県', previous: '翌々月の31日', itemLink: 'https://www.pref.fukuoka.lg.jp/contents/keiki.html', latestLink: 'https://www.pref.fukuoka.lg.jp/' },
          { item: '日銀短観(全国)', latest: '日本銀行', previous: '四半期毎の翌月1日', itemLink: 'https://www.boj.or.jp/statistics/dl/tankan/index.htm/', latestLink: 'https://www.boj.or.jp/' },
          { item: '日銀短観(九州)', latest: '日本銀行 福岡支店', previous: '四半期毎の翌月1日', itemLink: 'https://www.boj.or.jp/statistics/dl/tankan/index.htm/', latestLink: 'https://www.boj.or.jp/fukuoka/' },
          { item: '景気ウォッチャー調査(全国、九州)', latest: '	内閣府', previous: '翌月の6営業日', itemLink: 'https://www5.cao.go.jp/keizai3/watcher/index.html', latestLink: 'https://www.esri.cao.go.jp/' },
          { item: '福岡県内の経済情勢報告', latest: '福岡財務支局', previous: '四半期毎の翌月25日～31日', itemLink: 'https://www.mof.go.jp/fukuoka/', latestLink: 'https://www.mof.go.jp/fukuoka/' },
          { item: '福岡市の地場企業経営動向調査', latest: '福岡商工会議所', previous: '四半期毎の翌月OR翌々月', itemLink: 'https://www.fukunet.or.jp/', latestLink: 'https://www.fukunet.or.jp/' },
          { item: '北九州市の地場企業経営動向調査', latest: '北九州商工会議所', previous: '四半期毎の翌月', itemLink: 'https://www.kitaq.or.jp/', latestLink: 'https://www.kitaq.or.jp/' },
          { item: '久留米市の地場企業景況調査', latest: '久留米商工会議所', previous: '四半期毎の翌々々月の上旬', itemLink: 'https://www.kurume-cci.or.jp/', latestLink: 'https://www.kurume-cci.or.jp/' },
          { item: '福岡県内の経済情勢報告', latest: '福岡財務支局', previous: '四半期毎の翌月25日～31日', itemLink: 'https://www.mof.go.jp/fukuoka/', latestLink: 'https://www.mof.go.jp/fukuoka/' }
        ],
        '個人消費': [
          { item: '商業販売額速報(全国)', latest: '経済産業省', previous: '翌月の下旬(29～31日)', itemLink: 'https://www.meti.go.jp/statistics/tyo/syougyo/index.html', latestLink: 'https://www.meti.go.jp/' },
          { item: '大型小売店業態別販売額(全国)', latest: '経済産業省', previous: '翌月の下旬(29～31日)', itemLink: 'https://www.meti.go.jp/statistics/tyo/syougyo/index.html', latestLink: 'https://www.meti.go.jp/' },
          { item: '大型小売店業態別販売額(九州、福岡県)', latest: '	九州経済産業局', previous: '翌々月の第2営業日～第4営業日', itemLink: 'https://www.kyushu.meti.go.jp/statistics/index.html', latestLink: 'https://www.kyushu.meti.go.jp/' },
          { item: 'コンビニエンスストア・専門量販店販売額(九州)', latest: '	九州経済産業局', previous: '翌々月の第2営業日～第4営業日', itemLink: 'https://www.kyushu.meti.go.jp/statistics/index.html', latestLink: 'https://www.kyushu.meti.go.jp/' },
          { item: '消費動向調査(全国)', latest: '内閣府', previous: '翌月の中旬', itemLink: 'https://www.esri.cao.go.jp/jp/stat/shouhi/index.html', latestLink: 'https://www.esri.cao.go.jp/' },
          { item: '消費活動指数', latest: '日本銀行', previous: '翌月の第5営業日', itemLink: 'https://www.boj.or.jp/statistics/dl/boj_fx/index.htm/', latestLink: 'https://www.boj.or.jp/' }
        ],
        '投資': [
          { item: '建築着工統計(全国、福岡県)', latest: '国土交通省', previous: '翌々月の31日', itemLink: 'https://www.mlit.go.jp/toukei/toukei.html', latestLink: 'https://www.mlit.go.jp/' },
          { item: '公共工事請負金額(福岡県)', latest: '福岡県', previous: '翌々月の31日前後', itemLink: 'https://www.pref.fukuoka.lg.jp/contents/construction.html', latestLink: 'https://www.pref.fukuoka.lg.jp/' },
          { item: '機械受注統計(全国)', latest: '内閣府', previous: '翌々月の10日前後', itemLink: 'https://www.esri.cao.go.jp/jp/stat/di/di.html', latestLink: 'https://www.esri.cao.go.jp/' },
          { item: '設備投資計画調査(全国、九州)', latest: '日本政策投資銀行', previous: '翌年の8月', itemLink: 'https://www.dbj.jp/', latestLink: 'https://www.dbj.jp/' }
        ],
        '貿易': [
          { item: '貿易統計(九州、福岡県)', latest: '門司税関', previous: '翌月の下旬', itemLink: 'https://www.customs.go.jp/toukei/info/index.htm', latestLink: 'https://www.customs.go.jp/' }
        ],
        '生産': [
          { item: '鉱工業生産指数(全国)', latest: '経済産業省', previous: '翌月の31日前後', itemLink: 'https://www.meti.go.jp/statistics/tyo/iip/index.html', latestLink: 'https://www.meti.go.jp/' },
          { item: '鉱工業生産指数(九州)', latest: '九州経済産業局', previous: '翌々月第2週目OR第3週目', itemLink: 'https://www.kyushu.meti.go.jp/statistics/index.html', latestLink: 'https://www.kyushu.meti.go.jp/' },
          { item: '鉱工業生産指数(福岡県)', latest: '福岡県', previous: '翌々月の31日前後', itemLink: 'https://www.pref.fukuoka.lg.jp/contents/keiki.html', latestLink: 'https://www.pref.fukuoka.lg.jp/' }
        ],
        '雇用・所得': [
          { item: '完全失業率(全国)', latest: '総務省', previous: '翌月の31日前後', itemLink: 'https://www.stat.go.jp/data/roudou/index.html', latestLink: 'https://www.stat.go.jp/' },
          { item: '有効求人倍率(全国、福岡県)', latest: '厚生労働省', previous: '翌月の31日前後', itemLink: 'https://www.mhlw.go.jp/toukei/list/114-1.html', latestLink: 'https://www.mhlw.go.jp/' },
          { item: '毎月勤労統計調査地方調査(福岡県の所得・雇用実態)', latest: '福岡県', previous: '	翌々月の下旬(20日～31日)', itemLink: 'https://www.pref.fukuoka.lg.jp/contents/roudou.html', latestLink: 'https://www.pref.fukuoka.lg.jp/' }
        ],
        '金融': [
          { item: '企業倒産状況(全国、福岡県)', latest: '東京商工リサーチ', previous: '翌々月の下旬(20日～31日)', itemLink: 'https://www.tsr-net.co.jp/news/statistics/', latestLink: 'https://www.tsr-net.co.jp/' },
          { item: '日経平均株価', latest: '	東京商工リサーチ', previous: '翌月の6営業日', itemLink: 'https://www.nikkei.com/markets/kabu/', latestLink: 'https://www.nikkei.com/' }
        ],
        '海外指標': [
          { item: '米ドル対円相場', latest: ' ', previous: '翌月の1日', itemLink: 'https://www.boj.or.jp/statistics/dl/boj_fx/index.htm/', latestLink: 'https://www.boj.or.jp/' },
          { item: 'ユーロ対円相場', latest: ' ', previous: '翌月の1日', itemLink: 'https://www.boj.or.jp/statistics/dl/boj_fx/index.htm/', latestLink: 'https://www.boj.or.jp/' }
        ]
      };
      
      return dataMap[term] || [
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' },
        { item: 'データなし', latest: '-', previous: '-' }
      ];
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

.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 40px;
}

.section-title {
  color: #1A1A1A;
  font-size: 36px;
  font-weight: 700;
  margin: 0;
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

/* Description */
.indicators-description {
  text-align: center;
  margin-bottom: 40px;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  justify-content: center;
  align-items: center;
}

.indicators-description p {
  color: #666;
  font-size: 1.1rem;
  line-height: 1.6;
  margin: 0;
  text-align: center;
  width: 100%;
}

/* Indicators Items */
.indicators-list {
  max-width: 1500px;
  margin: 0 auto;
}

.indicator-item {
  background: white;
  border-radius: 10px;
  margin-bottom: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  overflow: hidden;
}

.indicator-term {
  display: flex;
  align-items: center;
  padding: 20px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.indicator-term:hover {
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

.indicator-definition {
  display: flex;
  align-items: flex-start;
  padding: 20px;
  border-top: 1px solid #dee2e6;
}

.definition-content {
  flex: 1;
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

/* テーブルスタイル */
.indicator-table-container {
  padding: 20px;
  background: #f8f9fa;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.table-wrapper {
  overflow-x: auto;
}

.indicator-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 6px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.indicator-table th {
  background: #DA5761;
  color: white !important;
  padding: 12px 8px;
  text-align: center;
  font-weight: 600;
  font-size: 0.9rem;
  border: 1px solid #c44569;
}

.indicator-table td {
  padding: 10px 8px;
  text-align: left;
  border: 1px solid #e9ecef;
  font-size: 0.85rem;
  line-height: 1.4;
}

.indicator-table tr:nth-child(even) {
  background: #f8f9fa;
}

.indicator-table tr:hover {
  background: #e9ecef;
}

.indicator-table th:first-child,
.indicator-table td:first-child {
  text-align: left;
  font-weight: 500;
  color: #1A1A1A;
}

.indicator-table td:nth-child(2) {
  text-align: left;
  font-weight: 600;
  color: #DA5761;
}

.indicator-table td:nth-child(3) {
  text-align: left;
  font-weight: 400;
  color: #1A1A1A;
}

.table-link {
  color: #DA5761;
  text-decoration: underline;
  font-weight: 600;
  transition: color 0.3s ease;
}

.table-link:hover {
  color: #B23A3A;
  text-decoration: none;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .page-content {
    padding: 40px 30px;
  }
}

@media (max-width: 1150px) {
  .section-header {
    gap: 25px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
}

@media (max-width: 900px) {
  .page-content {
    padding: 30px 20px;
  }
  
  .section-header {
    gap: 22px !important;
  }
  
  .indicator-item {
    padding: 0;
  }
  
  .indicator-term {
    padding: 15px;
  }
  
  .term-text {
    font-size: 17px !important;
  }
  
  .indicator-table th,
  .indicator-table td {
    font-size: 17px !important;
  }
  
  .section-title {
    font-size: 29px !important;
  }
  
  .indicators-description p {
    font-size: 17px !important;
  }
}

@media (max-width: 768px) {
  .page-content {
    padding: 40px 15px;
  }
  
  .section-header {
    gap: 20px !important;
  }
  
  .indicator-term {
    padding: 15px;
  }
  
  .term-text {
    font-size: 16px !important;
  }
  
  .term-line {
    width: 5px;
    height: 35px;
    margin-right: 10px;
  }
  
  .indicator-table-container {
    padding: 15px;
    margin-top: 15px;
  }
  
  .indicator-table th,
  .indicator-table td {
    padding: 8px 4px;
    font-size: 16px !important;
  }
  
  .section-title {
    font-size: 27px !important;
  }
  
  .indicators-description p {
    font-size: 16px !important;
  }
}

@media (max-width: 480px) {
  .page-content {
    padding: 20px 15px;
  }

  .indicators-description p {
  font-size: 13px !important;
}

.indicators-description {
  margin-bottom: 20px;
}
  
  .indicator-item {
    padding: 0;
  }
  
  .indicator-term {
    padding: 10px;
  }

  .indicator-definition {
  padding: 10px;
}
  
  .term-text {
    font-size: 13px !important;
  }
  
  .indicator-table-container {
    padding: 10px;
    margin-top: 0px;
  }
  
  .indicator-table th,
  .indicator-table td {
    padding: 6px 3px;
    font-size: 13px !important;
  }
  
  .term-line {
    width: 4px;
    height: 30px;
    margin-right: 8px;
  }

  .section-header {
    gap: 18px !important;
    margin-bottom: 20px;
  }

  .section-title {
    font-size: 22px !important;
  }

  .divider-text {
    font-size: 13px !important;
  }
}




</style>
