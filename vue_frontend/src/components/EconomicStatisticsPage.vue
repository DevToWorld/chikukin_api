<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      heroImage="/img/Image_fx6.jpg"
      mediaKey="hero_economic_statistics"
      cms-page-key="economic-statistics"
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
        <button class="filter-download-btn">さらに表示
          <div class="icon-box">
            <svg class="arrow-icon" width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="23" height="23" rx="5" fill="white"/>
              <path d="M17.5302 11.9415L13.0581 16.5412C12.9649 16.6371 12.8384 16.691 12.7066 16.691C12.5747 16.691 12.4482 16.6371 12.355 16.5412C12.2618 16.4453 12.2094 16.3152 12.2094 16.1796C12.2094 16.044 12.2618 15.9139 12.355 15.818L15.9793 12.0909L6.2469 12.0909C6.11511 12.0909 5.98872 12.0371 5.89554 11.9413C5.80235 11.8454 5.75 11.7154 5.75 11.5799C5.75 11.4443 5.80235 11.3143 5.89554 11.2185C5.98872 11.1226 6.11511 11.0688 6.2469 11.0688L15.9793 11.0688L12.355 7.34171C12.2618 7.24581 12.2094 7.11574 12.2094 6.98012C12.2094 6.84449 12.2618 6.71443 12.355 6.61853C12.4482 6.52263 12.5747 6.46875 12.7066 6.46875C12.8384 6.46875 12.9649 6.52263 13.0581 6.61853L17.5302 11.2183C17.5764 11.2657 17.613 11.3221 17.638 11.3841C17.6631 11.4462 17.6759 11.5127 17.6759 11.5799C17.6759 11.647 17.6631 11.7135 17.638 11.7756C17.613 11.8376 17.5764 11.894 17.5302 11.9415Z" fill="#1A1A1A"/>
            </svg>
          </div>
        </button>

        <!-- Divider Line -->
        <div class="filter-divider"></div>

        <!-- Category Filter -->
        <div class="category-filter">
          <select 
            v-model="selectedCategory"
            @change="selectCategory(selectedCategory)"
            class="category-select"
          >
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
        <div
          class="featured-content"
          @click="goToStatisticsDetail(featuredPublication.id)"
        >
          <div class="featured-image" :class="{ blurred: isRestricted(featuredPublication) }">
            <img :src="featuredPublication.image || '/img/image-1.png'" :alt="featuredPublication.title" />
            <MembershipBadge
              v-if="isRestricted(featuredPublication) && getItemLevel(featuredPublication) && getItemLevel(featuredPublication) !== 'free'"
              :level="getItemLevel(featuredPublication)"
              class="publication-badge"
            />
          </div>
          <div class="featured-info">
            <div class="featured-meta">
              <span class="featured-year">{{ formatDate(featuredPublication.publication_date) }}</span>
              <span class="featured-category">{{ getCategoryName(featuredPublication.category) }}</span>
            </div>
            <div class="featured-details">
               <div class="content-title">{{ featuredPublication.title }}</div>
               <div class="content-text">{{ featuredPublication.description }}</div>
             </div>

            <button class="download-btn" @click.stop="handleDownloadOrNavigate(featuredPublication)">{{ getButtonLabel(featuredPublication) }}
              <div class="icon-box">
                <div class="pdf-icon-wrapper">
                  <img class="pdf-icon" :src="getButtonLabel(featuredPublication) === 'PDFダウンロード' ? '/img/pdfaicon.png' : '/img/arrow-icon.svg'" :alt="getButtonLabel(featuredPublication) === 'PDFダウンロード' ? 'PDF' : '詳細'" width="24" height="24" />
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
            @click="goToStatisticsDetail(publication.id)"
          >
            <div class="publication-image" :class="{ blurred: isRestricted(publication) }">
              <img :src="publication.image || '/img/image-1.png'" :alt="publication.title" />
              <MembershipBadge
                v-if="isRestricted(publication) && getItemLevel(publication) && getItemLevel(publication) !== 'free'"
                :level="getItemLevel(publication)"
                class="publication-badge"
              />
            </div>
            <div class="publication-info">
              <div class="publication-meta">
                <span class="featured-category">{{ getCategoryName(publication.category) }}</span>
                <span class="featured-year">{{ formatDate(publication.publication_date) }}</span>
              </div>
              <h3 class="publication-title">{{ publication.title }}</h3>
              <button class="publication-download" @click.stop="handleDownloadOrNavigate(publication)">{{ getButtonLabel(publication) }}
                <div class="icon-box">
                <div class="pdf-icon-wrapper">
                  <img class="pdf-icon" :src="getButtonLabel(publication) === 'PDFダウンロード' ? '/img/pdfaicon.png' : '/img/arrow-icon.svg'" :alt="getButtonLabel(publication) === 'PDFダウンロード' ? 'PDF' : '詳細'" width="24" height="24" />
                </div>
              </div>
            </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="loading" class="loading">
        読み込み中...
      </div>
    </div>

    <!-- Action Button Section -->
    <ActionButton 
      :primaryText="ctaPrimaryText"
      :secondaryText="ctaSecondaryText"
      @primary-click="handleContactClick"
      @secondary-click="handleJoinClick"
    />

    <!-- Contact CTA Section -->
    <ContactSection cms-page-key="economic-statistics" />

    <!-- Access Section -->
    <AccessSection cms-page-key="economic-statistics" />

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
import ActionButton from "./ActionButton.vue";
import MembershipBadge from './MembershipBadge.vue'
import { frame132131753022Data } from "../data.js";
import apiClient from '../services/apiClient.js';
import { usePageText } from '@/composables/usePageText'
import { navigateToPublication, navigateToStatistics } from '../utils/navigation.js';
import CmsBlock from './CmsBlock.vue'

export default {
  name: "EconomicStatisticsPage",
  components: {
    Navigation,
    Footer,
    Group27,
    HeroSection,
    Breadcrumbs,
    ContactSection,
    AccessSection,
    FixedSideButtons,
    ActionButton,
    MembershipBadge,
    CmsBlock
  },
  data() {
    return {
      pageKey: 'economic-statistics',
      frame132131753022Props: frame132131753022Data,
      loading: true,
      selectedYear: 'all',
      selectedCategory: 'all',
      error: null,
      currentPage: 1,
      totalPages: 1,
      years: [],
      categories: [ { id: 'all', name: '全て' } ],
      featuredPublication: null,
      publications: [],
      // レガシー: 全体ブラーの既定値（個別判定に移行）
      shouldBlur: true
    };
  },
  async mounted() {
    // Load structured texts for this page (title, lead, CTA etc.)
    try {
      this._pageText = usePageText(this.pageKey)
      await this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
    // 認証状態からブラー制御を再判定
    await this.updateBlurFromAuth()
    await Promise.all([
      this.loadFeaturedReport(),
      this.loadCategories(),
      this.loadYears(),
      this.loadPublications()
    ]);
  },
  created() {
    try {
      const user = JSON.parse(localStorage.getItem('memberUser') || 'null')
      const t = user?.membership_type
      this.shouldBlur = !(t === 'standard' || t === 'premium')
    } catch(e) { this.shouldBlur = true }
  },
  computed: {
    // Access a ref to establish reactivity
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() {
      // fallback to hardcoded title if no CMS override
      return this._pageText?.getText('page_title', '経済・調査統計') || '経済・調査統計'
    },
    pageSubtitle() {
      return this._pageText?.getText('page_subtitle', 'economic statistics') || 'economic statistics'
    },
    ctaPrimaryText() {
      return this._pageText?.getText('cta_primary', 'お問い合わせはこちら') || 'お問い合わせはこちら'
    },
    ctaSecondaryText() {
      return this._pageText?.getText('cta_secondary', '入会はこちら') || '入会はこちら'
    },
    filteredPublications() {
      const filtered = this.publications.filter(pub => {
        const yearMatch = this.selectedYear === 'all' || pub.year === this.selectedYear;
        const categoryMatch = this.selectedCategory === 'all' || pub.category === this.selectedCategory;
        return yearMatch && categoryMatch;
      });
      // 4列×3行=12個のレイアウトに合わせて最初の12個を返す
      return filtered.slice(0, 12);
    }
  },
  methods: {
    handleContactClick() {
      const link = this._pageText?.getLink('cta_primary', '/contact') || '/contact'
      this.$router.push(link)
    },
    handleJoinClick() {
      const link = this._pageText?.getLink('cta_secondary', '/membership/apply') || '/membership/apply'
      this.$router.push(link)
    },
    async updateBlurFromAuth() {
      try {
        // まずlocalStorageで判定
        const userRaw = localStorage.getItem('memberUser')
        if (userRaw) {
          const u = JSON.parse(userRaw)
          const t = u?.membership_type
          this.shouldBlur = !(t === 'standard' || t === 'premium')
          return
        }
        // トークンがあればプロフィールを取得して保存
        const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken')
        if (token) {
          const res = await apiClient.get('/api/member/my-profile')
          const m = (res && res.success) ? res.data : null
          if (m && m.membership_type) {
            localStorage.setItem('memberUser', JSON.stringify(m))
            this.shouldBlur = !(m.membership_type === 'standard' || m.membership_type === 'premium')
            return
          }
        }
        // 取得できない場合はぼかしを維持
        this.shouldBlur = true
      } catch (e) {
        this.shouldBlur = true
      }
    },
    // 現在の閲覧者の会員レベル取得（'premium' | 'standard' | null）
    viewerMembershipLevel() {
      try {
        const raw = localStorage.getItem('memberUser')
        if (!raw) return null
        const u = JSON.parse(raw)
        const t = (u?.membership_type || '').toLowerCase()
        if (t === 'premium' || t === 'standard') return t
        return null
      } catch (e) { return null }
    },
    // 現在の閲覧者が有料会員か判定
    viewerHasPaidMembership() {
      const lvl = this.viewerMembershipLevel()
      return lvl === 'standard' || lvl === 'premium'
    },

    // 個別アイテムの制限判定：会員種別まで考慮
    isRestricted(item) {
      if (!item) return false
      const required = (item.membership_level || item.membershipLevel || '').toLowerCase()
      // 明示的に無料 or 非会員公開
      if (required === 'free' || item.members_only === false) return false
      // 必要レベルが未指定で members_only の場合 → 有料会員なら可
      if (!!item.members_only && !required) return !this.viewerHasPaidMembership()
      // 標準限定 → 標準/プレミアムは閲覧可
      const viewer = this.viewerMembershipLevel()
      if (required === 'standard') return !(viewer === 'standard' || viewer === 'premium')
      // プレミアム限定 → プレミアムのみ可
      if (required === 'premium') return viewer !== 'premium'
      // その他は保守的に制限とみなす
      return !!item.members_only && !this.viewerHasPaidMembership()
    },
    // ダウンロード可否
    canAccess(item) {
      return !this.isRestricted(item)
    },
    // ボタン文言（アクセス不可時は会員種別を表示）
    getButtonLabel(item) {
      if (this.canAccess(item)) return 'PDFダウンロード'
      const level = this.getItemLevel(item)
      if (level === 'premium') return 'プレミアム会員限定'
      if (level === 'standard') return 'スタンダード会員限定'
      return '会員限定'
    },
    // 表示用の会員レベル（standard|premium|free|null）
    getItemLevel(item) {
      if (!item) return null
      const level = (item.membership_level || item.membershipLevel || '').toLowerCase()
      if (level === 'standard' || level === 'premium' || level === 'free') return level
      return null
    },

    async loadPublications() {
      this.loading = true;
      try {
        const params = {
          page: this.currentPage,
          per_page: 12
        };
        
        if (this.selectedCategory && this.selectedCategory !== 'all') {
          params.category = this.selectedCategory;
        }

        if (this.selectedYear && this.selectedYear !== 'all') {
          params.year = this.selectedYear;
        }
        
        const response = await apiClient.getEconomicReports(params);
        
        if (response.success && response.data) {
          this.publications = response.data.reports.map(item => this.formatReportItem(item));
          this.totalPages = response.data.pagination.total_pages;
        } else {
          // フォールバック: 既存データを使用
          console.log('APIからデータを取得できませんでした。フォールバックデータを使用します。');
        }
      } catch (err) {
        console.error('経済統計の読み込みに失敗しました:', err);
        this.error = '経済統計の読み込みに失敗しました。';
        // フォールバック: 既存データを使用
      } finally {
        this.loading = false;
      }
    },

    async loadFeaturedReport() {
      try {
        const response = await apiClient.getFeaturedEconomicReport();
        
        if (response.success && response.data) {
          this.featuredPublication = this.formatReportItem(response.data);
        }
      } catch (err) {
        console.error('特集レポートの読み込みに失敗しました:', err);
        // フォールバック: 既存データを使用
      }
    },

    async loadCategories() {
      try {
        const response = await apiClient.getEconomicReportCategories();
        
        if (response.success && response.data) {
          this.categories = [
            { id: 'all', name: '全て' },
            ...response.data
          ];
        }
      } catch (err) {
        console.error('カテゴリの読み込みに失敗しました:', err);
        // フォールバック: 既存データを使用
      }
    },

    async loadYears() {
      try {
        const response = await apiClient.getEconomicReportYears();
        
        if (response.success && response.data) {
          this.years = response.data;
        }
      } catch (err) {
        console.error('年の読み込みに失敗しました:', err);
        // フォールバック: 既存データを使用
      }
    },
    
    formatReportItem(item) {
      return {
        id: item.id,
        title: item.title,
        year: item.year,
        category: item.category,
        image: item.cover_image_url || item.cover_image || '/img/image-1.png',
        description: item.description,
        author: item.author,
        publisher: item.publisher,
        pages: item.pages,
        is_downloadable: item.is_downloadable,
        // 真偽値に正規化（undefined/null対策）
        members_only: !!item.members_only,
        membership_level: item.membership_level || item.membershipLevel || null,
        publication_date: item.publication_date,
        keywords: item.keywords
      };
    },

    // 無料公開ならダウンロード、会員限定なら詳細/ログインへ
    handleDownloadOrNavigate(item) {
      if (!item) return
      if (this.isRestricted(item)) {
        this.goToStatisticsDetail(item.id)
      } else {
        this.downloadPublication(item.id)
      }
    },
    
    async downloadPublication(publicationId) {
      try {
        const response = await apiClient.downloadEconomicReport(publicationId);
        
        if (response.success && response.data) {
          if (response.data.file_url) {
            // ダウンロードURLにリダイレクト
            window.open(response.data.file_url, '_blank');
          } else {
            throw new Error('ダウンロードURLが取得できませんでした');
          }
        } else if (response.requires_login) {
          // ログインが必要な場合
          alert('このレポートは会員限定です。ログインしてください。');
          this.$router.push('/member-login');
        } else {
          throw new Error(response.message || 'ダウンロードに失敗しました');
        }
      } catch (err) {
        console.error('ダウンロードに失敗しました:', err);
        alert('ダウンロードに失敗しました。しばらくしてから再度お試しください。');
      }
    },
    selectYear(year) {
      this.selectedYear = year;
    },
    selectCategory(categoryId) {
      this.selectedCategory = categoryId;
    },
    getCategoryName(categoryId) {
      const category = this.categories.find(cat => cat.id === categoryId);
      return category ? category.name : '経済統計';
    },
    formatDate(dateString) {
      if (!dateString) return ''
      const d = new Date(dateString)
      if (isNaN(d.getTime())) return dateString
      const mm = String(d.getMonth() + 1).padStart(2, '0')
      const dd = String(d.getDate()).padStart(2, '0')
      return `${d.getFullYear()}/${mm}/${dd}`
    },
    goToPublicationDetail(publicationId) {
      this.$router.push(navigateToPublication(publicationId));
    },
    goToStatisticsDetail(statisticsId) {
      this.$router.push(navigateToStatistics(statisticsId));
    },
    goToContact() {
      this.$router.push('/contact');
    },
    goToRegister() {
      this.$router.push('/application-form');
    },
    handleContactClick() {
      this.$router.push('/contact');
    },
    handleJoinClick() {
      this.$router.push('/membership/apply');
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
  margin: 0 auto 0;
}

.filter-download-btn:hover {
  opacity: 0.8;
}

/* Filter Divider */
.filter-divider {
  width: 100%;
  height: 1px;
  background-color: #E0E0E0;
  margin: 10px 0;
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
  justify-content: flex-end;
  width: 100%;
  max-width: 1500px;
  margin: 0 auto;
}

.category-select {
  background: #F6D5D8;
  border: none;
  padding: 15px 20px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 0.9rem;
  color: #1A1A1A;
  font-family: 'Inter', sans-serif;
  font-weight: 500;
  min-width: 300px;
  outline: none;
}

.category-select:hover {
  background: #da5761;
  color: white;
}

.category-select:focus {
  box-shadow: none;
}

.category-select option {
  background: white;
  color: #1A1A1A;
  padding: 10px;
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
  gap: 20px;
  width: 100%;
  border-top: 0.5px dashed #B0B0B0;
  border-bottom: 0.5px dashed #B0B0B0;
  padding: 20px 0;
}

.content-title {
  font-family: 'Inter', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: #1A1A1A;
  line-height: 1.4;
}

.content-text {
  font-family: 'Inter', sans-serif;
  font-size: 16px;
  font-weight: 400;
  color: #666;
  line-height: 1.6;
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
  position: relative;
}

.publication-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.publication-image.blurred img, .featured-image.blurred img { filter: blur(6px); }

/* Membership badge positioning */
.publication-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 2;
}

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



/* Responsive Design */
@media (max-width: 1200px) {
  .category-filter {
    justify-content: center !important;
  }
  
  .category-select {
    width: 100% !important;
    max-width: 100% !important;
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
  
  .content-title {
    text-align: left !important;
  }
  
  .content-text {
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
  
  .content-title {
    font-size: 18px !important;
  }
  
  .content-text {
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
  
  .content-title {
    font-size: 17px !important;
  }
  
  .content-text {
    font-size: 17px !important;
  }
  
  .publication-title {
    font-size: 17px !important;
  }
  
  .year-filter {
    justify-content: center !important;
  }
  
  .category-select {
    min-width: 100% !important;
    padding: 12px 18px !important;
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
  
  .content-title {
    font-size: 16px !important;
  }
  
  .content-text {
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
  
  .category-select {
    min-width: 100% !important;
    padding: 10px 15px !important;
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
  
  .content-title {
    font-size: 13px !important;
    width: 100% !important;
  }
  
  .content-text {
    font-size: 13px !important;
    width: 100% !important;
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
  
  .category-select {
    min-width: 100% !important;
    padding: 8px 12px !important;
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
