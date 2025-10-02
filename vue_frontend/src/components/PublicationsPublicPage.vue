<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      heroImage="/img/hero-image.png"
      mediaKey="hero_publications"
      cms-page-key="publications"
    />
    
    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <div class="page-content">
      <!-- Publications Header -->
      <div class="publications-header">
        <h2 class="page-title">{{ pageTitle }}</h2>
        <div class="title-decoration">
          <div class="line-left"></div>
          <span class="title-english">{{ pageSubtitle }}</span>
          <div class="line-right"></div>
        </div>
      </div>

      <!-- CMS Body (optional) -->
      <!-- CMS Body removed -->

      <!-- Filter Container -->
      <div class="filter-container">
        <!-- Year Filter -->
        <div class="year-filter">
          <button 
            v-for="year in years" 
            :key="year"
            :class="['year-btn', { active: selectedYear === year }]"
            @click="selectYear(year)"
          >
            {{ year }}年
          </button>
        </div>

        <!-- Download Button -->
        <button class="filter-download-btn"><CmsText pageKey="publications" fieldKey="show_more" tag="span" :fallback="'さらに表示'" />
          <div class="icon-box">
            <svg class="arrow-icon" width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="23" height="23" rx="5" fill="white"/>
              <path d="M17.5302 11.9415L13.0581 16.5412C12.9649 16.6371 12.8384 16.691 12.7066 16.691C12.5747 16.691 12.4482 16.6371 12.355 16.5412C12.2618 16.4453 12.2094 16.3152 12.2094 16.1796C12.2094 16.044 12.2618 15.9139 12.355 15.818L15.9793 12.0909L6.2469 12.0909C6.11511 12.0909 5.98872 12.0371 5.89554 11.9413C5.80235 11.8454 5.75 11.7154 5.75 11.5799C5.75 11.4443 5.80235 11.3143 5.89554 11.2185C5.98872 11.1226 6.11511 11.0688 6.2469 11.0688L15.9793 11.0688L12.355 7.34171C12.2618 7.24581 12.2094 7.11574 12.2094 6.98012C12.2094 6.84449 12.2618 6.71443 12.355 6.61853C12.4482 6.52263 12.5747 6.46875 12.7066 6.46875C12.8384 6.46875 12.9649 6.52263 13.0581 6.61853L17.5302 11.2183C17.5764 11.2657 17.613 11.3221 17.638 11.3841C17.6631 11.4462 17.6759 11.5127 17.6759 11.5799C17.6759 11.647 17.6631 11.7135 17.638 11.7756C17.613 11.8376 17.5764 11.894 17.5302 11.9415Z" fill="#1A1A1A"/>
            </svg>
          </div>
        </button>

        <!-- Category Filter -->
        <div class="category-filter">
          <button 
            v-for="category in categories" 
            :key="category.id"
            :class="['category-btn', { active: selectedCategory === category.id }]"
            @click="selectCategory(category.id)"
          >
            {{ category.name }}
          </button>
        </div>

        <!-- Category Filter Mobile -->
        <div class="category-filter-mobile">
          <select 
            v-model="selectedCategory" 
            @change="selectCategory(selectedCategory)"
            class="category-select-mobile"
          >
            <option value=""><CmsText pageKey="publications" fieldKey="all_categories" tag="span" :fallback="'すべてのカテゴリ'" /></option>
            <option 
              v-for="category in categories" 
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>

      <!-- Featured Publication -->
      <div class="featured-publication" v-if="featuredPublication">
        <div class="featured-content" @click="goToPublicationDetail(featuredPublication.id)">
          <div class="featured-image" :class="{ blurred: isRestricted(featuredPublication) }">
            <img src="/img/image-1.png" alt="一般向け刊行物" />
          </div>
            <div class="featured-info">
            <div class="featured-meta">
              <span class="featured-year">{{ formatDate(featuredPublication.publication_date) }}</span>
              <span 
                class="featured-category clickable"
                role="button"
                @click.stop="selectCategory(featuredPublication.category)"
                :title="`カテゴリで絞り込み: ${getCategoryName(featuredPublication.category)}`"
              >
                {{ getCategoryName(featuredPublication.category) }}
              </span>
            </div>
            <div class="featured-details">
              <div class="past-info-row">
                <div class="info-label info-label-author">
                  <span class="label-text">著者</span>
                </div>
                <div class="info-value">{{ featuredPublication.author }}</div>
              </div>
              <div class="past-info-row">
                <div class="info-label info-label-publisher">
                  <span class="label-text">出版社</span>
                </div>
                <div class="info-value">{{ featuredPublication.publisher }}</div>
              </div>
              <div class="past-info-row">
                <div class="info-label info-label-description">
                  <span class="label-text">概要</span>
                </div>
                <div class="info-value">{{ featuredPublication.description }}</div>
              </div>
              <div class="past-info-row">
                <div class="info-label info-label-keywords">
                  <span class="label-text">キーワード</span>
                </div>
                <div class="info-value">{{ featuredPublication.keywords }}</div>
              </div>
            </div>

            <button class="download-btn" @click.stop="goToRegister"><CmsText pageKey="publications" fieldKey="join_to_download" tag="span" :fallback="'入会してダウンロード'" />
              <div class="icon-box">
                <div class="pdf-icon-wrapper">
                  <img class="pdf-icon" src="/img/arrow-icon.svg" alt="入会" width="24" height="24" />
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Publications Grid -->
      <div class="publications-container" v-if="!loading">
        <div class="publications-grid">
          <div 
            v-for="publication in filteredPublications" 
            :key="publication.id"
            class="publication-card"
            @click="goToPublicationDetail(publication.id)"
          >
            <div class="publication-image" :class="{ blurred: isRestricted(publication) }">
              <img :src="publication.image || '/img/image-1.png'" :alt="publication.title" />
            </div>
            <div class="publication-info">
              <div class="publication-meta">
                <span 
                  class="featured-category clickable"
                  role="button"
                  @click.stop="selectCategory(publication.category)"
                  :title="`カテゴリで絞り込み: ${getCategoryName(publication.category)}`"
                >
                  {{ getCategoryName(publication.category) }}
                </span>
                <span class="featured-year">{{ formatDate(publication.publication_date) }}</span>
              </div>
              <h3 class="publication-title">{{ publication.title }}</h3>
              <button class="publication-download"><CmsText pageKey="publications" fieldKey="join_to_download" tag="span" :fallback="'入会してダウンロード'" />
              <div class="icon-box">
                <div class="pdf-icon-wrapper">
                  <img class="pdf-icon" src="/img/arrow-icon.svg" alt="入会" width="24" height="24" />
                </div>
              </div>
            </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="loading" class="loading">
        <CmsText pageKey="publications" fieldKey="loading" tag="span" :fallback="'読み込み中...'" />
      </div>
    </div>

    <!-- button Section -->
    <div class="button-section">
      <div class="button-container">
        <button class="cta-button primary" @click="goToContact">
          <span class="button-text">{{ ctaPrimaryText }}</span>
          <div class="arrow-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="24" height="24" rx="4" fill="#FFFFFF"/>
              <path d="M9.5 6L15.5 12L9.5 18" stroke="#DA5761" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </button>
        <button class="cta-button secondary" @click="goToRegister">
          <span class="button-text">{{ ctaSecondaryText }}</span>
          <div class="arrow-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="24" height="24" rx="4" fill="#FFFFFF"/>
              <path d="M9.5 6L15.5 12L9.5 18" stroke="#9C3940" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </button>
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
import CmsBlock from './CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'
import apiClient from '../services/apiClient.js';
import mockServer from '@/mockServer';
import { usePageText } from '@/composables/usePageText'

export default {
  name: "PublicationsPublicPage",
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
      pageKey: 'publications',
      frame132131753022Props: frame132131753022Data,
      loading: true,
      selectedYear: 'all',
      selectedCategory: 'all',
      error: null,
      currentPage: 1,
      totalPages: 1,
      years: [2024, 2023, 2022, 2021, 2020, 2019, 2018, 2017, 2016],
      categories: [ { id: 'all', name: '全て' } ],
      featuredPublication: {
        id: 1,
        title: '一般向け刊行物',
        year: 2024,
        category: 'economics',
        author: 'ちくぎん地域経済研究所',
        publisher: '株式会社ちくぎん地域経済研究所',
        description: '2024年度版 一般向け地域経済レポート',
        keywords: '地域経済、一般公開、経済分析について',
        image: '/img/image-1.png'
      },
      publications: [
        {
          id: 2,
          title: '一般向け刊行物（約７）ページ',
          year: 2024,
          category: 'economics',
          image: '/img/image-1.png'
        },
        {
          id: 3,
          title: '一般向け刊行物（約７）ページ',
          year: 2024,
          category: 'quarterly',
          image: '/img/image-1.png'
        },
        {
          id: 4,
          title: '一般向け刊行物（約７）ページ',
          year: 2024,
          category: 'special',
          image: '/img/image-1.png'
        },
        {
          id: 5,
          title: '一般向け刊行物（約７）ページ',
          year: 2024,
          category: 'free',
          image: '/img/image-1.png'
        },
        {
          id: 6,
          title: '一般向け刊行物（約７）ページ',
          year: 2023,
          category: 'economics',
          image: '/img/image-1.png'
        },
        {
          id: 7,
          title: '一般向け刊行物（約７）ページ',
          year: 2023,
          category: 'quarterly',
          image: '/img/image-1.png'
        },
        {
          id: 8,
          title: '一般向け刊行物（約７）ページ',
          year: 2023,
          category: 'special',
          image: '/img/image-1.png'
        },
        {
          id: 9,
          title: '一般向け刊行物（約７）ページ',
          year: 2023,
          category: 'free',
          image: '/img/image-1.png'
        },
        {
          id: 10,
          title: '一般向け刊行物（約７）ページ',
          year: 2022,
          category: 'economics',
          image: '/img/image-1.png'
        },
        {
          id: 11,
          title: '一般向け刊行物（約７）ページ',
          year: 2022,
          category: 'quarterly',
          image: '/img/image-1.png'
        },
        {
          id: 12,
          title: '一般向け刊行物（約７）ページ',
          year: 2022,
          category: 'special',
          image: '/img/image-1.png'
        },
        {
          id: 13,
          title: '一般向け刊行物（約７）ページ',
          year: 2022,
          category: 'free',
          image: '/img/image-1.png'
        },
        {
          id: 14,
          title: '一般向け刊行物（約７）ページ',
          year: 2021,
          category: 'economics',
          image: '/img/image-1.png'
        },
        {
          id: 15,
          title: '一般向け刊行物（約７）ページ',
          year: 2021,
          category: 'quarterly',
          image: '/img/image-1.png'
        },
        {
          id: 16,
          title: '一般向け刊行物（約７）ページ',
          year: 2021,
          category: 'special',
          image: '/img/image-1.png'
        },
        {
          id: 17,
          title: '一般向け刊行物（約７）ページ',
          year: 2021,
          category: 'free',
          image: '/img/image-1.png'
        },
        {
          id: 18,
          title: '一般向け刊行物（約７）ページ',
          year: 2020,
          category: 'economics',
          image: '/img/image-1.png'
        },
        {
          id: 19,
          title: '一般向け刊行物（約７）ページ',
          year: 2020,
          category: 'quarterly',
          image: '/img/image-1.png'
        },
        {
          id: 20,
          title: '一般向け刊行物（約７）ページ',
          year: 2020,
          category: 'special',
          image: '/img/image-1.png'
        },
        {
          id: 21,
          title: '一般向け刊行物（約７）ページ',
          year: 2020,
          category: 'free',
          image: '/img/image-1.png'
        },
        {
          id: 22,
          title: '一般向け刊行物（約７）ページ',
          year: 2019,
          category: 'economics',
          image: '/img/image-1.png'
        },
        {
          id: 23,
          title: '一般向け刊行物（約７）ページ',
          year: 2019,
          category: 'quarterly',
          image: '/img/image-1.png'
        },
        {
          id: 24,
          title: '一般向け刊行物（約７）ページ',
          year: 2019,
          category: 'special',
          image: '/img/image-1.png'
        }
      ]
    };
  },
  created() {
    try {
      const user = JSON.parse(localStorage.getItem('memberUser') || 'null')
      const t = user?.membership_type
      this.shouldBlur = !(t === 'standard' || t === 'premium')
    } catch(e) { this.shouldBlur = true }
  },
  computed: {
    filteredPublications() {
      let filtered = this.publications;
      
      // Year filter
      if (this.selectedYear !== 'all') {
        filtered = filtered.filter(pub => pub.year === this.selectedYear);
      }
      
      // Category filter
      if (this.selectedCategory !== 'all') {
        filtered = filtered.filter(pub => pub.category === this.selectedCategory);
      }
      
      return filtered.slice(0, 12);
    }
  },
  mounted() {
    this.loadCategories();
    this.loadPublications();
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() {
      return this._pageText?.getText('page_title', '刊行物') || '刊行物'
    },
    pageSubtitle() {
      return this._pageText?.getText('page_subtitle', 'publications') || 'publications'
    },
    ctaPrimaryText() {
      return this._pageText?.getText('cta_primary', 'お問い合わせはこちら') || 'お問い合わせはこちら'
    },
    ctaSecondaryText() {
      return this._pageText?.getText('cta_secondary', '入会はこちら') || '入会はこちら'
    }
  },
  methods: {
    async loadCategories() {
      try {
        // 公開APIから取得（管理で設定したカテゴリが反映される）
        const res = await apiClient.getPublicPublicationCategories()
        if (res && res.success && Array.isArray(res.data)) {
          this.categories = [
            { id: 'all', name: '全て' },
            ...res.data.map(c => ({ id: c.slug, name: c.name }))
          ]
          return
        }
        // モックにフォールバック
        try {
          const cats = await mockServer.getPublicationCategories()
          if (cats && cats.length) {
            this.categories = [
              { id: 'all', name: '全て' },
              ...cats.map(c => ({ id: c.slug, name: c.name }))
            ]
            return
          }
        } catch (_) { /* noop */ }
        // 最後のフォールバック
        this.categories = [
          { id: 'all', name: '全て' },
          { id: 'research', name: '調査研究' },
          { id: 'quarterly', name: '定期刊行物' },
          { id: 'special', name: '特別企画' },
          { id: 'statistics', name: '統計資料' }
        ]
      } catch(e) {
        this.categories = [
          { id: 'all', name: '全て' },
          { id: 'research', name: '調査研究' },
          { id: 'quarterly', name: '定期刊行物' },
          { id: 'special', name: '特別企画' },
          { id: 'statistics', name: '統計資料' }
        ]
      }
    },
    formatDate(dateString) {
      if (!dateString) return ''
      const d = new Date(dateString)
      if (isNaN(d.getTime())) return dateString
      const mm = String(d.getMonth() + 1).padStart(2, '0')
      const dd = String(d.getDate()).padStart(2, '0')
      return `${d.getFullYear()}/${mm}/${dd}`
    },
    async loadPublications() {
      this.loading = true;
      try {
        // まずAPIから取得（本番データを優先）
        const params = {
          page: this.currentPage,
          year: this.selectedYear !== 'all' ? this.selectedYear : null,
          category: this.selectedCategory !== 'all' ? this.selectedCategory : null
        }
        
        const response = await apiClient.getPublications(params);
        
        if (response && response.success && response.data && response.data.publications) {
          this.publications = response.data.publications.map(item => this.formatPublicationItem(item));
          this.totalPages = response.data.pagination.total_pages;
          return
        }

        // フォールバック: mockServer
        try {
          const allPublications = await mockServer.getPublications();
          if (allPublications && allPublications.length > 0) {
            // APIが取得できない場合でも、一般向け一覧は「全件表示」。
            // モックの会員レベルは item.membership_level もしくは membershipLevel に入る想定。
            this.publications = allPublications.map(item => ({
              id: item.id,
              title: item.title,
              image: item.cover_image || item.image_url || '/img/-----2-2-4.png',
              description: item.description,
              category: item.category || 'special',
              publication_date: item.publication_date,
              author: item.author || 'ちくぎん地域経済研究所',
              pages: item.pages,
              file_size: item.file_size,
              download_count: item.download_count,
              is_published: item.is_published,
              // モックでも会員レベル情報を保持
              membershipLevel: item.membership_level || item.membershipLevel || 'free'
            }));
            this.totalPages = Math.ceil(this.publications.length / 12);
          }
        } catch (_) { /* noop */ }
      } catch (err) {
        console.error('刊行物の読み込みに失敗しました:', err);
        this.error = '刊行物の読み込みに失敗しました。';
        // フォールバック: 既存データを使用
      } finally {
        this.loading = false;
      }
    },
    
    formatPublicationItem(item) {
      return {
        id: item.id,
        title: item.title,
        year: new Date(item.publication_date).getFullYear(),
        publish_date: item.publication_date,
        publication_date: item.publication_date,
        category: item.category,
        image: item.cover_image_url || item.cover_image || '/img/image-1.png',
        description: item.description,
        author: item.author,
        pages: item.pages,
        is_downloadable: item.is_downloadable,
        members_only: item.members_only,
        // 明示的な会員レベル（未設定は free とみなす）
        membership_level: (item.membership_level || 'free')
      };
    },
    // 一般向け一覧では、会員限定（standard/premium）は常にサムネイルをぼかす
    // 無料（free）はぼかし無し
    isRestricted(item) {
      if (!item) return false
      const level = String(item.membership_level || item.membershipLevel || '').toLowerCase()
      return !!level && level !== 'free'
    },
    
    async downloadPublication(publicationId) {
      try {
        const response = await apiClient.downloadPublication(publicationId);
        if (response.success && response.data.download_url) {
          // ダウンロードリンクを開く
          window.open(response.data.download_url, '_blank');
          // ログイン済みなら履歴に記録（未ログイン時はサイレント）
          try { await apiClient.logMemberAccess({ content_type: 'publication', content_id: publicationId, access_type: 'download' }) } catch(e) { /* noop */ }
        }
      } catch (err) {
        console.error('ダウンロードに失敗しました:', err);
        alert('ダウンロードに失敗しました。');
      }
    },
    async selectYear(year) {
      this.selectedYear = year;
      this.currentPage = 1;
      await this.loadPublications();
    },
    async selectCategory(categoryId) {
      this.selectedCategory = categoryId;
      this.currentPage = 1;
      await this.loadPublications();
    },
    getCategoryName(categoryId) {
      const category = this.categories.find(cat => cat.id === categoryId);
      // カテゴリーが未取得/未登録でも静的文言にならないようにフォールバック
      if (category && category.name) return category.name
      if (typeof categoryId === 'string' && categoryId) return categoryId
      return 'その他'
    },
    goToPublicationDetail(publicationId) {
      this.$router.push(`/publications-public/${publicationId}`);
    },
    goToContact() {
      const link = this._pageText?.getLink('cta_primary', '/contact') || '/contact'
      this.$router.push(link);
    },
    goToRegister() {
      const link = this._pageText?.getLink('cta_secondary', '/register') || '/register'
      this.$router.push(link);
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
  margin: 0 auto;
  padding: 70px 50px 50px;
}

.publications-header {
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
  white-space: nowrap;
}

/* Filter Container */
.filter-container {
  background: white;
  padding: 50px;
  border-radius: 15px;
  margin-bottom: 40px;
  max-width: 2000px;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Year Filter */
.year-filter {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.year-btn {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 0.9rem;
  color: #666;
}

.year-btn.active,
.year-btn:hover {
  background: #da5761;
  color: white;
  border-color: #da5761;
}

/* Filter Download Button */
.filter-download-btn {
  display: flex;
  width: auto;
  padding: 10px 50px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 10px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;
  color: #ffffff;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
  transition: all 0.3s ease;
  margin: 0 auto 30px;
}

.filter-download-btn:hover {
  opacity: 0.8;
}

.icon-box {
  display: flex;
  align-items: center;
  justify-content: center;
}

.arrow-icon {
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Category Filter */
.category-filter {
  display: flex;
  justify-content: center;
  gap: 0;
  border-radius: 8px;
  overflow: hidden;
  width: 100%;
  max-width: 1500px;
  margin: 0 auto;
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
}

.category-btn:last-child {
  border-right: none;
}

/* Category Filter Mobile */
.category-filter-mobile {
  display: none;
  width: 100%;
  margin-bottom: 20px;
}

.category-select-mobile {
  width: 100%;
  padding: 15px 20px;
  background: #F6D5D8;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  color: #1A1A1A;
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 12px center;
  background-repeat: no-repeat;
  background-size: 16px;
  padding-right: 40px;
}

.category-select-mobile:focus {
  outline: none;
  box-shadow: 0 0 0 2px #DA5761;
}

.category-btn.active,
.category-btn:hover {
  background: #da5761;
  color: white;
}

/* Featured Publication */
.featured-publication {
  background: white;
  border-radius: 15px;
  padding: 50px;
  margin-bottom: 40px;
  max-width: 2000px;
  margin-left: auto;
  margin-right: auto;
}

.featured-content {
  display: flex;
  width: 100%;
  align-items: stretch;
  gap: 30px;
  cursor: pointer;
}

.featured-image {
  width: 30%;
  flex-shrink: 0;
  border-radius: 10px;
  overflow: hidden;
  position: relative;
  min-height: 300px;
}

.featured-image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
}

.featured-info {
  display: flex;
  flex: 1;
  flex-direction: column;
  flex-shrink: 0;
}

.featured-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.featured-year {
  color:#3F3F3F;
  font-size: 0.8rem;
  font-weight: 500;
}

.featured-category {
  background: #F6D5D8;
  color: #1A1A1A;
  padding: 10px 15px;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
  width: auto;
  align-self: flex-start;
}

.featured-details {
  display: flex;
  flex-direction: column;
  gap: 0;
  width: 100%;
}

.past-info-row {
  display: flex;
  padding: 15px 0;
  align-items: center;
  gap: 50px;
  border-bottom: 0.5px dashed #B0B0B0;
  width: 100%;
}

.past-info-row:first-child {
  border-top: 0.5px dashed #B0B0B0;
}

.info-label {
  display: flex;
  padding: 10px 30px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 5px;
  background: #727272;
  white-space: nowrap;
  width: 250px;
  color: #FFFFFF;
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 150%;
  margin: 0;
}

.info-value {
  color: var(--color-secondary);
  font-family: 'Inter', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
}

.download-btn {
  display: flex;
  width: auto;
  padding: 10px 50px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 10px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;

  color: #ffffff;
  margin-top: 20px;
  margin-left: auto;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
  transition: all 0.3s ease;
}

.download-btn:hover {
  opacity: 0.8;
}

/* Publications Container */
.publications-container {
  background: white;
  padding: 50px;
  border-radius: 15px;
  max-width: 2000px;
  margin-left: auto;
  margin-right: auto;
}

/* Publications Grid */
.publications-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}

/* Add row separators when grid becomes 2 columns */
@media (min-width: 600px) and (max-width: 900px) {
  .publications-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .publication-card:nth-child(odd) {
    border-bottom: 1px solid #E5E5E5;
    padding-bottom: 15px;
    margin-bottom: 0;
    border-radius: 10px 10px 0 0 !important;
  }
  
  .publication-card:nth-child(even) {
    border-bottom: 1px solid #E5E5E5;
    padding-bottom: 15px;
    margin-bottom: 0;
    border-radius: 10px 10px 0 0 !important;
  }
  
  .publication-card:nth-last-child(-n+2) {
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
    border-radius: 10px !important;
  }
}

/* Add row separators when grid becomes 1 column */
@media (max-width: 599px) {
  .publications-grid {
    grid-template-columns: 1fr;
  }
  
  .publication-card:not(:last-child) {
    border-bottom: 1px solid #E5E5E5;
    padding-bottom: 15px;
    margin-bottom: 0;
    border-radius: 10px 10px 0 0 !important;
  }
  
  .publication-card:last-child {
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
    border-radius: 10px !important;
  }
}

.publication-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  transition: all 0.3s;
  cursor: pointer;
  min-height: 300px;
  display: flex;
  flex-direction: column;
}

.publication-card:hover {
  transform: translateY(-5px);

}

.publication-image {
  height: 180px;
  overflow: hidden;
}

.publication-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.publication-image.blurred img, .featured-image.blurred img { filter: blur(6px); }

.publication-info {
  padding: 20px 0 0 0;
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.publication-meta {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 15px;
}

.publication-title {
  font-size: 1rem;
  color: #1A1A1A;
  margin-bottom: 15px;
  font-weight: 500;
  line-height: 1.4;
}

.publication-download {
  display: flex;
  width: 100%;
  padding: 10px 0;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 10px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;
  color: #ffffff;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
  transition: all 0.3s ease;
}

.publication-download:hover {
  opacity: 0.8;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
}

/* Button Section */
.button-section {
  background: #ECECEC;
  padding: 50px 50px 70px 50px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.button-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
  width: 100%;
  max-width: 2000px;
}

.cta-button {
  width: 300px;
  padding: 20px 40px;
  border: none;
  border-radius: 10px;
  font-family: 'Inter', Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 30px;
}

.button-text {
  width: auto;
  text-align: center;
}

.arrow-icon {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cta-button.primary {
  background-color: #DA5761;
  color: #FFFFFF;
  width: 100%;
}

.cta-button.primary:hover {
  background-color: #c44a54;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(218, 87, 97, 0.3);
}

.cta-button.secondary {
  background-color: #9C3940;
  color: #FFFFFF;
  width: 100%;
}

.cta-button.secondary:hover {
  background-color: #8a3238;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(156, 57, 64, 0.3);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .category-filter {
    display: none;
  }
  
  .category-filter-mobile {
    display: block;
    margin-bottom: 40px;
    width: 100%;
  }
}

@media (max-width: 1150px) {
  .page-content {
    padding: 50px 30px !important;
  }
  
  .publications-header {
    gap: 25px !important;
  }
  
  .page-title {
    font-size: 32px !important;
  }
  
  .title-english {
    font-size: 18px !important;
  }
  
  .publications-grid {
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 20px !important;
  }
  
  .featured-publication {
    padding: 30px 20px !important;
  }
  
  .featured-content {
    flex-direction: column !important;
    gap: 20px !important;
  }
  
  .featured-image {
    width: 100% !important;
    height: 300px !important;
  }
  
  .featured-info {
    width: 100% !important;
  }
  
  .featured-details {
    gap: 0 !important;
    text-align: left !important;
  }
  
  .past-info-row {
    flex-direction: column !important;
    gap: 10px !important;
    align-items: flex-start !important;
  }
  
  .info-label {
    width: 100% !important;
    text-align: left !important;
  }
  
  .download-btn {
    width: 100% !important;
    margin-left: 0 !important;
  }
  
  .filter-container {
    padding: 30px 20px !important;
  }
  
  .publications-container {
    padding: 30px 20px !important;
  }
  
  .button-section {
    padding: 30px 20px 50px 20px !important;
  }
  
  .info-label {
    font-size: 18px !important;
    padding: 8px 25px !important;
    width: 220px !important;
  }
  
  .info-value {
    font-size: 18px !important;
  }
  
  .publication-title {
    font-size: 18px !important;
  }
}

@media (max-width: 900px) {
  .page-content {
    padding: 30px 20px !important;
  }
  
  .publications-header {
    gap: 22px !important;
  }
  
  .page-title {
    font-size: 29px !important;
  }
  
  .title-english {
    font-size: 17px !important;
  }
  
  .publications-grid {
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 20px !important;
  }
  
  .featured-publication {
    padding: 30px 20px !important;
  }
  
  .filter-container {
    padding: 30px 20px !important;
  }
  
  .publications-container {
    padding: 30px 20px !important;
  }
  
  .button-section {
    padding: 30px 20px 50px 20px !important;
  }
  
  .info-label {
    font-size: 17px !important;
    padding: 8px 20px !important;
    width: 200px !important;
  }
  
  .info-value {
    font-size: 17px !important;
  }
  
  .publication-title {
    font-size: 17px !important;
  }
  
  .year-filter {
    justify-content: center !important;
  }
  
  .category-filter {
    flex-direction: column !important;
    gap: 10px !important;
  }
  
  .category-btn {
    width: 100% !important;
    border-right: none !important;
    border-bottom: 1px solid #DA5761 !important;
  }
  
  .category-btn:last-child {
    border-bottom: none !important;
  }
}

@media (max-width: 768px) {
  .page-content {
    padding: 30px 20px !important;
  }
  
  .publications-header {
    gap: 20px !important;
  }
  
  .page-title {
    font-size: 27px !important;
  }
  
  .title-english {
    font-size: 16px !important;
  }
  
  .publications-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
    gap: 15px !important;
  }
  
  .featured-publication {
    padding: 30px 20px !important;
  }
  
  .filter-container {
    padding: 30px 20px !important;
  }
  
  .publications-container {
    padding: 30px 20px !important;
  }
  
  .button-section {
    padding: 30px 20px 50px 20px !important;
  }
  
  .info-label {
    font-size: 16px !important;
    padding: 8px 18px !important;
    width: 180px !important;
  }
  
  .info-value {
    font-size: 16px !important;
  }
  
  .publication-title {
    font-size: 16px !important;
  }
  
  .featured-content {
    flex-direction: column !important;
    text-align: center !important;
  }
  
  .featured-image {
    flex: none !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 auto !important;
  }
  
  .category-filter {
    flex-wrap: nowrap !important;
    gap: 0 !important;
  }
  
  .category-btn {
    padding: 8px 10px !important;
    font-size: 0.6rem !important;
    border-right: 1px solid #DA5761 !important;
    border-bottom: none !important;
    border-radius: 0 !important;
    margin-bottom: 0 !important;
    min-width: 0 !important;
    flex: 1 !important;
  }
}

@media (max-width: 480px) {
  .page-content {
    padding: 20px 15px !important;
  }
  
  .publications-header {
    gap: 18px !important;
  }
  
  .page-title {
    font-size: 22px !important;
  }
  
  .title-english {
    font-size: 13px !important;
  }
  
  .publications-grid {
    grid-template-columns: 1fr !important;
    gap: 20px !important;
  }
  
  .featured-publication {
    padding: 20px 15px !important;
  }
  
  .filter-container {
    padding: 20px 15px !important;
  }
  
  .publications-container {
    padding: 20px 15px !important;
  }
  
  .button-section {
    padding: 20px 15px 40px 15px !important;
  }
  
  .info-label {
    font-size: 13px !important;
    padding: 6px 15px !important;
    width: 100% !important;
  }
  
  .info-value {
    font-size: 13px !important;
  }
  
  .publication-title {
    font-size: 13px !important;
  }
  
  .featured-image {
    height: 200px !important;
  }
  
  .featured-image img {
    height: 200px !important;
  }
  
  .publication-image {
    height: 150px !important;
  }
  
  .year-btn {
    padding: 6px 12px !important;
    font-size: 0.8rem !important;
  }
  
  .category-btn {
    padding: 15px 20px !important;
    font-size: 14px !important;
  }
}

/* PDF Icon Wrapper */
.pdf-icon-wrapper {
  width: 24px;
  height: 24px;
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.download-btn .pdf-icon {
  color: #1A1A1A;
}

.publication-download .pdf-icon {
  color: #1A1A1A;
}

.pdf-icon {
  width: 23px;
  height: 23px;
}

/* Footer Navigation */
</style>
