<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle || 'お知らせ'"
      :subtitle="pageSubtitle || 'information'"
      heroImage="/img/Image_fx3.jpg"
      mediaKey="hero_news"
      cms-page-key="news"
    />
    
    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <div class="page-content">
      <div class="content-header">
        <h2 class="page-title">
          <CmsText pageKey="news" fieldKey="page_title" tag="span" :fallback="pageTitle || 'お知らせ'" />
        </h2>
        <div class="title-decoration">
          <div class="line-left"></div>
          <span class="title-english">
            <CmsText pageKey="news" fieldKey="page_subtitle" tag="span" :fallback="pageSubtitle || 'information'" />
          </span>
          <div class="line-right"></div>
        </div>
      </div>

      <!-- CMS Body (optional) -->
      <!-- CMS Body removed -->

      <div class="news-container">
        <div class="news-categories">
          <button 
            :class="['category-btn', { active: selectedCategory === 'all' }]" 
            @click="filterCategory('all')"
          >
            全て
          </button>
          <button 
            :class="['category-btn', { active: selectedCategory === 'research' }]" 
            @click="filterCategory('research')"
          >
            ちくぎん地域経済レポート
          </button>
          <button 
            :class="['category-btn', { active: selectedCategory === 'quarterly' }]" 
            @click="filterCategory('quarterly')"
          >
            ちくぎん地域経済レポート
          </button>
          <button 
            :class="['category-btn', { active: selectedCategory === 'special' }]" 
            @click="filterCategory('special')"
          >
            ちくぎん地域経済レポート
          </button>
          <button 
            :class="['category-btn', { active: selectedCategory === 'free' }]" 
            @click="filterCategory('free')"
          >
            ちくぎん地域経済レポート
          </button>
        </div>
        
        <!-- モバイル用プルダウンメニュー -->
        <div class="news-categories-mobile">
          <select 
            v-model="selectedCategory" 
            @change="filterCategory(selectedCategory)"
            class="category-select"
          >
            <option value="all">全て</option>
            <option value="research">ちくぎん地域経済レポート</option>
            <option value="quarterly">ちくぎん地域経済レポート</option>
            <option value="special">ちくぎん地域経済レポート</option>
            <option value="free">ちくぎん地域経済レポート</option>
          </select>
        </div>

        <div class="news-list" v-if="!loading">
        <article 
          v-for="(item, index) in filteredNews" 
          :key="item.id"
          class="news-item"
          @click="goToNewsDetail(item.id)"
          style="cursor: pointer;"
        >
                     <div class="news-meta">
             <span class="news-date">{{ formatDate(item.date) }}</span>
             <span :class="['news-category', getCategoryClass(item.category)]">
               {{ getCategoryLabel(item.category) }}
             </span>
             <h3 class="news-title">{{ item.title }}</h3>
           </div>
                       <div class="news-content">
              <div class="news-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="11" fill="#DA5761"/>
                  <path d="M9 7L15 12L9 17" stroke="white" stroke-width="2" fill="none"/>
                </svg>
              </div>
            </div>
        </article>
      </div>

               <div v-if="loading" class="loading">
           読み込み中...
         </div>

          <!-- Pagination (dynamic) -->
          <div class="pagination" v-if="totalPages > 1">
            <button class="pagination-btn" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">‹</button>
            <template v-for="(p, i) in pagesToShow">
              <span v-if="p === '…'" class="pagination-dots" :key="`dots-${i}`">…</span>
              <button v-else class="pagination-btn" :class="{ active: currentPage === p }" @click="changePage(p)" :key="`page-${p}`">{{ p }}</button>
            </template>
            <button class="pagination-btn" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">›</button>
            <button class="pagination-btn next-btn" @click="changePage(totalPages)" :disabled="currentPage === totalPages">最後</button>
          </div>
       </div>
     </div>

    <!-- Contact CTA Section -->
    <ContactSection cms-page-key="news" />

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
import apiClient from '../services/apiClient.js';
import { frame132131753022Data } from "../data.js";
import CmsBlock from './CmsBlock.vue'
import { usePageText } from '@/composables/usePageText'
import CmsText from '@/components/CmsText.vue'

export default {
  name: "NewsPage",
  components: {
    Navigation,
    Footer,
    Group27,
    HeroSection,
    Breadcrumbs,
    ContactSection,
    AccessSection,
    FixedSideButtons,
    CmsBlock,
    CmsText
  },
  data() {
    return {
      pageKey: 'news',
      frame132131753022Props: frame132131753022Data,
      currentPage: 1,
      totalPages: 1,
      selectedCategory: 'all',
      newsItems: [],
      loading: false,
      error: null,
      totalItems: 0
    };
  },
  async mounted() {
    await this.loadNews()
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load()
    } catch(e) { /* noop */ }
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', 'お知らせ') || 'お知らせ' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'information') || 'information' },
    filteredNews() {
      if (this.selectedCategory === 'all') {
        return this.newsItems
      }
      return this.newsItems.filter(item => item.category === this.selectedCategory)
    },
    pagesToShow() {
      const total = this.totalPages || 1
      const current = this.currentPage || 1
      if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1)
      }
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
  methods: {
    async loadNews() {
      this.loading = true
      try {
        // DB（Laravel API）のみから取得（フォールバックなし）
        const params = {
          page: this.currentPage,
          per_page: 10
        }
        if (this.selectedCategory && this.selectedCategory !== 'all') {
          params.category = this.selectedCategory
        }
        // Notice (お知らせ) に一本化
        const response = await apiClient.getNotices(params)
        if (response.success && response.data) {
          const paginator = response.data
          const items = Array.isArray(paginator.data) ? paginator.data : []
          this.newsItems = items.map(item => ({
            id: item.id,
            date: item.published_at || item.created_at,
            category: item.category || 'notice',
            title: item.title,
            description: item.summary || item.content,
            type: 'notice'
          }))
          this.totalPages = paginator.last_page || 1
          this.totalItems = paginator.total || this.newsItems.length
        } else {
          // APIが成功しない/空の場合でもフォールバックはしない
          this.newsItems = []
          this.totalPages = 1
          this.totalItems = 0
        }
      } catch (err) {
        console.error('お知らせの読み込みに失敗しました:', err)
        this.error = 'お知らせの読み込みに失敗しました。'
        // フォールバックは行わない
        this.newsItems = []
        this.totalPages = 1
        this.totalItems = 0
      } finally {
      this.loading = false
    }
  },
  
  // formatNewsItem: Notice統一のため未使用
  
  // フォールバックは使用しない方針
  async changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        await this.loadNews();
      }
    },
    async filterCategory(category) {
      this.selectedCategory = category;
      this.currentPage = 1; // リセット
      await this.loadNews();
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}.${month}.${day}`;
    },
    getCategoryLabel(category) {
      // お知らせ種別・カテゴリに応じて日本語ラベルを表示
      const labels = {
        seminar: 'セミナー',
        publication: '刊行物',
        notice: 'お知らせ',
        // お知らせ（notice）のサブタイプ
        general: 'お知らせ',
        system: 'システム',
        event: 'イベント',
        important: '重要',
        research: '研究',
        quarterly: '四半期経済レポート',
        special: '特集',
        free: '一般公開'
      }
      return labels[category] || category || 'お知らせ'
    },
    getCategoryClass(category) {
      // カテゴリ名をそのままクラスに使用（未定義はnotice）
      return category || 'notice'
    },
    goToNewsDetail(newsId) {
      this.$router.push(`/news/${newsId}`);
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
  box-shadow: none;
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

/* News Container */
.news-container {
  background: white;
  padding: 50px;
  max-width: 1500px;
  margin: 0 auto;
  border-radius: 15px;
}

/* Categories */
.news-categories {
  display: flex;
  justify-content: center;
  gap: 0;
  margin-bottom: 40px;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: none;
  width: 100%;
}

.news-categories-mobile {
  display: none;
  margin-bottom: 40px;
  width: 100%;
}

.category-select {
  width: 100%;
  max-width: 100%;
  padding: 15px 20px;
  border: 2px solid #F6D5D8;
  border-radius: 8px;
  background: #F6D5D8;
  font-size: 16px;
  color: #1A1A1A;
  cursor: pointer;
  outline: none;
  margin: 0;
  display: block;
}

.category-select:focus {
  border-color: #da5761;
  box-shadow: 0 0 0 2px rgba(218, 87, 97, 0.2);
}

.category-select option {
  background: white;
  color: #1A1A1A;
}

.category-btn {
  background: #F6D5D8;
  border: none;
  padding: 15px 20px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 0.8rem;
  color: #666;
  border-right: 1px solid #DA5761;
  white-space: nowrap;
  flex: 1;
  box-sizing: border-box;
  width: 0;
  min-width: 0;
}

.category-btn:last-child {
  border-right: none;
}

.category-btn.active,
.category-btn:hover {
  background: #da5761;
  color: white;
}

/* News List */
.news-list {
  margin-bottom: 40px;
}

.news-item {
  display: flex;
  padding: 20px 20px;
  align-items: center;
  gap: 50px;
  width: 100%;
  background: transparent;
  border: none;
  border-radius: 0;
  overflow: visible;
  transition: none;
  cursor: pointer;
  border-bottom: 0.5px dashed #DA5761;
}

.news-item:first-child {
  border-top: 0.5px dashed #DA5761;
}

.news-item:hover {
  background-color: #FDF6F7;
}

.news-meta {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  gap: 20px;
  border-radius: 5px;
  flex: 1;
}

.news-date {
  color: #1A1A1A;
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
}

.news-category {
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 500;
  color: white;
  /* 背景色が消えてカテゴリが見えなくなっていたため、
     デフォルトの背景色を付与（HomePage.vue と同等の見た目） */
  background: #da5761;
}

.news-category.seminar {
  background: #da5761;
  color: white;
  padding: 5px 25px;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
}

/* 念のため、他カテゴリも同色で明示（API値に依存） */
.news-category.publication,
.news-category.notice,
.news-category.research,
.news-category.quarterly,
.news-category.special,
.news-category.free {
  background: #da5761;
}

.news-title {
  color: var(--color-secondary);
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  margin: 0;
  flex: 1;
}

.news-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.news-content h3 {
  color: var(--color-secondary);
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  flex: 1;
}

.news-arrow {
  color: #da5761;
  margin-left: 15px;
  transition: transform 0.3s;
}

.news-item:hover .news-arrow {
  transform: translateX(5px);
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
}

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
  color: var(--color-secondary);
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
  color: var(--color-secondary);
  font-size: 14px;
  font-weight: 500;
}

.next-btn {
  width: 60px;
}



/* Responsive Design */
@media (max-width: 1200px) {
  .page-content {
    padding: 50px 30px !important;
  }
  
  .news-categories {
    display: none;
  }
  
  .news-categories-mobile {
    display: block;
  }
}

@media (max-width: 1150px) {
  .content-header {
    gap: 25px !important;
  }
  
  .page-title {
    font-size: 32px !important;
  }
  
  .title-english {
    font-size: 18px !important;
  }
}

@media (max-width: 900px) {
  .page-container {
    overflow-x: hidden;
  }

  .news-container {
    padding: 20px 20px !important;
  }
  
  .page-content {
    padding: 30px 20px;
  }
  
  .content-header {
    gap: 22px !important;
  }
  
  .page-title {
    font-size: 29px !important;
  }
  
  .title-english {
    font-size: 17px !important;
  }
  
  .news-categories {
    flex-direction: column;
    gap: 10px;
  }
  
  .category-btn {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #DA5761;
  }
  
  .category-btn:last-child {
    border-bottom: none;
  }
  
  .news-item {
    flex-direction: row;
    gap: 20px;
    align-items: center;
    padding: 30px 0px;
  }
  
  .news-meta {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
    width: 100%;
  }
  
  .news-title {
    font-size: 17px !important;
  }
  
  .news-date {
    font-size: 17px !important;
  }
  
  .news-category {
    font-size: 17px !important;
  }

  .news-categories-mobile {
    margin-bottom: 30px;
  }
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .page-content {
    padding: 30px 20px !important;
  }
  
  .content-header {
    gap: 20px !important;
  }
  
  .page-title {
    font-size: 27px !important;
  }
  
  .title-english {
    font-size: 16px !important;
  }
  
  .news-categories {
    flex-wrap: nowrap;
    gap: 0;
  }
  
  .category-btn {
    padding: 8px 10px;
    font-size: 0.6rem;
    border-right: 1px solid #DA5761;
    border-radius: 0;
    margin-bottom: 0;
    min-width: 0;
    flex: 1;
  }
  
  .news-content {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .news-arrow {
    margin-left: 0;
    align-self: flex-end;
  }
  
  .news-meta {
    flex-wrap: wrap;
    gap: 10px;
  }
  
  .news-title {
    font-size: 16px !important;
  }
  
  .news-date {
    font-size: 16px !important;
  }
  
  .news-category {
    font-size: 16px !important;
  }
  
  .page-title {
    font-size: 27px !important;
  }
  
  .content-header {
    gap: 20px !important;
  }
}

@media (max-width: 480px) {
  .page-content {
    padding: 20px 15px !important;
  }
  
  .news-categories {
    padding: 20px 15px;
  }

  .category-select {
    width: 100%;
    max-width: 100%;
  }

  .news-container {
    padding: 20px 20px !important;
  }
  
  .category-btn {
    padding: 15px 20px;
    font-size: 14px !important;
  }

  .news-content {
    padding: 0;
    gap: 10px;
  }
  
  .news-item {
    padding: 10px 0;
    gap: 10px;
  }
  
  .news-meta {
    padding: 15px 0;
  }
  
  .news-title {
    font-size: 13px !important;
  }
  
  .news-date {
    font-size: 13px !important;
  }
  
  .news-category {
    font-size: 13px !important;
  }
  
  .news-content h3 {
    font-size: 0.9rem;
  }

  .news-categories-mobile {
    margin-bottom: 20px;
  }

  .content-header {
    gap: 18px !important;
    margin-bottom: 20px;
  }

  .page-title {
    font-size: 22px !important;
  }

  .title-english {
  font-size: 13px;
}
}


</style>
