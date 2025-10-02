<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      heroImage="/img/Image_fx5.jpg"
      mediaKey="hero_publications"
      cms-page-key="publications"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="breadcrumbs" />

    <!-- Publication Detail Section -->
    <section class="publication-detail-section" v-if="publication">
      <div class="section-header">
        <h2 class="section-title">{{ pageTitle }}</h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">{{ pageSubtitle }}</span>
          <div class="divider-line"></div>
        </div>
      </div>

      <!-- Publication Details Card -->
      <div class="publication-detail-card">
          <div class="publication-content">
                         <div class="publication-info">
              <div class="publication-details">
                             <div class="detail-row">
                  <span class="detail-label">タイトル</span>
                  <span class="detail-value">{{ publication.title || '刊行物レポート' }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">カテゴリー</span>
                  <span class="detail-value">{{ getCategoryText(publication.category) || '研究レポート' }}</span>
                </div>
                
                <div class="detail-row">
                  <span class="detail-label">詳細</span>
                  <span class="detail-value">{{ publication.description || '詳細情報がここに表示されます。' }}</span>
                </div>
                
                <div class="detail-row" v-if="publication.author">
                  <span class="detail-label">著者・編集者</span>
                  <span class="detail-value">{{ publication.author }}</span>
                </div>
                
                <div class="detail-row" v-if="publication.pages">
                  <span class="detail-label">ページ数</span>
                  <span class="detail-value">{{ publication.pages }}ページ</span>
                </div>
               
               
             </div>
           </div>
           
           <div class="publication-image">
             <img :src="publication.image_url || '/img/image-1.png'" :alt="publication.title" />
           </div>
         </div>



        <!-- Download/Login Button -->
        <div class="login-section">
          <!-- Show notice for members-only when not permitted (includes not logged in) -->
          <p v-if="shouldShowMembersNotice" class="members-only-notice">この刊行物は会員限定です。ダウンロードするにはログインが必要です。</p>
          <!-- Download button when allowed -->
          <button v-if="canDownloadByAccess" class="login-btn" @click="publication?.id && downloadPublication(publication.id)">
            <div class="text-44 valign-text-middle inter-bold-white-15px">PDFダウンロード</div>
            <div class="pdf-icon-wrapper">
              <img class="pdf-icon" src="/img/pdfaicon.png" alt="PDF" width="24" height="24" />
            </div>
          </button>
          <!-- Login button when not permitted -->
          <button v-else class="login-btn" @click="handlePrimaryAction">
            <div class="text-44 valign-text-middle inter-bold-white-15px">ログインする</div>
            <div class="pdf-icon-wrapper">
              <img class="pdf-icon" src="/img/login-arrow-icon.svg" alt="ログイン" width="24" height="24" />
            </div>
          </button>
        </div>

      </div>
      </section>

<!-- Action Button Section -->
<ActionButton 
  :primaryText="ctaPrimaryText"
  :secondaryText="ctaSecondaryText"
  max-width="1500px"
  @primary-click="handleContactClick"
  @secondary-click="handleJoinClick"
/>

    <!-- Contact Section -->
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
import Navigation from "./Navigation";
import Footer from "./Footer";
import Group27 from "./Group27";
import AccessSection from "./AccessSection.vue";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import ContactSection from "./ContactSection.vue";
import ActionButton from "./ActionButton.vue";
import { frame132131753022Data } from "../data";
import apiClient from '../services/apiClient.js';
import { useMemberAuth } from '@/composables/useMemberAuth'
import { usePageText } from '@/composables/usePageText'

export default {
  name: "PublicationDetailPage",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ContactSection,
    ActionButton
  },
  data() {
    return {
      pageKey: 'publications',
      frame132131753022Props: frame132131753022Data,
      publication: null,
      loading: true,
      error: null
    };
  },
  async mounted() {
    await this.loadPublication();
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load()
    } catch(e) { /* noop */ }
  },
  computed: {
    // Auth/compliance computed based on member auth composable
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '刊行物') || '刊行物' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'publications') || 'publications' },
    ctaPrimaryText() { return this._pageText?.getText('cta_primary', 'お問い合わせはこちら') || 'お問い合わせはこちら' },
    ctaSecondaryText() { return this._pageText?.getText('cta_secondary', 'メンバー登録はこちら') || 'メンバー登録はこちら' },
    breadcrumbs() {
      return [
        { text: '刊行物', link: '/publications' },
        { text: this.publication?.title || '刊行物詳細' }
      ]
    },
    // 必要会員レベル（members_only=true かつ level未指定はstandardとみなす）
    requiredLevel() {
      if (!this.publication) return 'free'
      const lvlRaw = this.publication.membership_level || this.publication.membershipLevel
      const lvl = String(lvlRaw || '').trim().toLowerCase()
      // DBに membership_level が入っている場合はそれを優先（source of truth）
      if (lvl) return lvl
      // 旧データ互換: members_only=true の場合は標準会員以上とみなす
      const mo = (this.publication.members_only ?? this.publication.membersOnly)
      return mo ? 'standard' : 'free'
    },
    // 会員レベルに基づくダウンロード可否
    // ・free: だれでも可
    // ・standard/premium: 現在の会員レベルが必要レベル以上の場合のみ可
    canDownloadByAccess() {
      try {
        if (!this.publication) return false
        const level = String(this.requiredLevel || '').toLowerCase()
        if (level === 'free') return true
        const { isLoggedIn, canAccessContent } = useMemberAuth()
        return isLoggedIn() && canAccessContent(level)
      } catch (_) { return false }
    },
    detailButtonText() {
      return this.canDownloadByAccess ? 'PDFダウンロード' : 'ログインする'
    },
    shouldShowMembersNotice() {
      try {
        if (!this.publication) return false
        const level = String(this.requiredLevel || '').toLowerCase()
        if (level === 'free') return false
        const { isLoggedIn, canAccessContent } = useMemberAuth()
        return !(isLoggedIn() && canAccessContent(level))
      } catch (_) { return true }
    }
  },
  methods: {
    async loadPublication() {
      try {
        this.loading = true;
        const publicationId = this.$route.params.id;
        
        const response = await apiClient.getPublication(publicationId);
        
        if (response.success && response.data && response.data.publication) {
          this.publication = this.formatPublicationData(response.data.publication, { can_download: response.data.can_download });
        } else {
          // Fallback to mock data
          this.loadFallbackData();
        }
      } catch (error) {
        console.error('Error loading publication:', error);
        this.loadFallbackData();
      } finally {
        this.loading = false;
      }
    },
    async downloadPublication(publicationId) {
      try {
        const response = await apiClient.downloadPublication(publicationId)
        if (response?.success && response?.data?.download_url) {
          window.open(response.data.download_url, '_blank')
          // ログイン済みならダウンロード履歴に記録
          try { await apiClient.logMemberAccess({ content_type: 'publication', content_id: publicationId, access_type: 'download' }) } catch(e) { /* noop */ }
        } else {
          throw new Error('ダウンロードURLが取得できませんでした')
        }
      } catch (error) {
        console.error('ダウンロードに失敗しました:', error)
        alert('ダウンロードに失敗しました。しばらくしてから再度お試しください。')
      }
    },
    
    loadFallbackData() {
      // Mock data for demonstration
             this.publication = {
         id: this.$route.params.id,
         title: '経営戦略に関する書籍',
         category: 'research',
         description: '経営戦略に関する詳細な分析と考察をまとめた書籍です。最新の経営理論と実践的な事例を交えて解説しています。',
         author: '田中太郎',
         pages: 250,
         membersOnly: true,
         image_url: '/img/image-1.png',
         isDownloadable: true
       };
    },
    
    formatPublicationData(publicationData, meta = {}) {
      return {
        ...publicationData,
        isDownloadable: publicationData.is_downloadable || false,
        membersOnly: publicationData.members_only || false,
        membershipLevel: publicationData.membership_level || (publicationData.members_only ? 'standard' : 'free'),
        canDownload: typeof meta.can_download !== 'undefined'
          ? !!meta.can_download
          : !!(publicationData.is_downloadable && publicationData.file_url)
      };
    },
    
    formatDetailDate(dateString) {
      if (!dateString) return '';
      return dateString;
    },
    
    getCategoryText(category) {
      const categories = {
        'research': '研究レポート',
        'quarterly': '季報',
        'special': '特別レポート',
        'survey': '調査資料'
      };
      return categories[category] || 'その他';
    },
    
    handlePrimaryAction() {
      if (!this.canDownloadByAccess) {
        const redirect = encodeURIComponent(this.$route.fullPath)
        try {
          const { isLoggedIn } = useMemberAuth()
          if (!isLoggedIn()) {
            this.$router.push(`/member-login?redirect=${redirect}`)
          } else {
            this.$router.push('/membership')
          }
        } catch(_) { this.$router.push(`/member-login?redirect=${redirect}`) }
        return
      }
      if (this.publication?.id) {
        this.downloadPublication(this.publication.id)
      }
    },
    
    goToContact() {
      this.$router.push('/contact');
    },
    
    goToMember() {
      this.$router.push('/membership');
    },
    handleContactClick() {
      const link = this._pageText?.getLink('cta_primary', '/contact') || '/contact'
      this.$router.push(link);
    },
    handleJoinClick() {
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

/* Hero Section and Breadcrumb styles are now handled by components */

/* Publication Detail Section */
.publication-detail-section {
  padding: 70px 50px 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
  background: #ECECEC;
}

.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
}

.section-title {
  color: #1A1A1A;
  font-size: 36px;
  font-weight: 700;
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

/* Publication Detail Card */
.publication-detail-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 1500px;
  padding: 50px;
}

.publication-content {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  gap: 50px;
  height: auto;
}

.publication-image {
  width: 40%;
  height: auto;
  overflow: hidden;
  flex-shrink: 0;
  align-self: stretch;
}

.publication-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  min-height: 400px;
}

.publication-info {
  width: 60%;
  flex: 1;
  height: 100%;
}

.publication-details {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.detail-row {
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
  align-items: flex-start;
  border-bottom: 0.5px dashed #D0D0D0;
  padding-bottom: 15px;
}

 .detail-row:first-child {
   border-top: 0.5px dashed #D0D0D0;
   padding-top: 15px;
 }

 .detail-row:last-child {
   padding-bottom: 0;
   margin-bottom: 0;
 }

 .detail-label {
  width: 200px;
  font-weight: normal;
  color: white;
  font-size: 0.9rem;
  background-color: #727272;
  padding: 5px 0;
  border-radius: 5px;
  text-align: center;
  margin-bottom: 5px;
}

.detail-value {
  width: 100%;
  color: #666;
  font-size: 0.9rem;
  line-height: 1.5;
  padding-left: 10px;
}

/* Login Section */
.login-section {
  text-align: center;
  margin-top: 30px;
  background: #f6f7f8;
  border-radius: 10px;
  padding: 24px 16px;
}

.login-btn {
  align-items: center;
  background-color: #DA5761;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  border: none;
  cursor: pointer;
  color: white;
  font-family: var(--font-family-inter);
  font-size: 1.1rem;
  font-weight: bold;
  transition: all 0.3s;
  margin: 0 auto;
}

.login-btn:hover {
  opacity: 0.8;
}

.text-44 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: 0px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
  display: flex;
  align-items: center;
  color: var(--white);
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 700;
}

/* Members-only notice */
.members-only-notice {
  color: #666;
  font-size: 1rem;
  font-weight: 500;
  margin: 0 0 12px;
}

.loading {
  text-align: center;
  padding: 60px;
  color: #666;
  font-size: 1.1rem;
}

/* Contact Banner styles are now handled by ContactSection component */

/* Access Section styles are now handled by AccessSection component */

/* Floating Action Buttons styles are now handled by FixedSideButtons component */

/* Footer Navigation */

/* Responsive Design */
@media (max-width: 1150px) {
  .publication-detail-section {
    padding: 50px 30px !important;
  }
  
  .publication-detail-card {
    padding: 40px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
  
  .detail-label {
    font-size: 18px !important;
  }
  
  .detail-value {
    font-size: 18px !important;
  }
  
  .publication-content {
    flex-direction: column !important;
    gap: 30px !important;
  }
  
  .publication-image {
    width: 100% !important;
    height: 300px !important;
    border-radius: 20px 20px 0 0 !important;
    order: -1 !important;
  }
  
  .publication-info {
    width: 100% !important;
    order: 2 !important;
  }
}

/* 900px以下（タブレット） */
@media (max-width: 900px) {
  .publication-image {
    height: 280px !important;
    order: -1 !important;
  }
}


@media (max-width: 900px) {
  .publication-detail-section {
    padding: 30px 20px !important;
  }
  
  .publication-detail-card {
    padding: 35px !important;
  }
  
  .section-title {
    font-size: 29px !important;
  }
  
  .divider-text {
    font-size: 17px !important;
  }
  
  .detail-label {
    font-size: 17px !important;
  }
  
  .detail-value {
    font-size: 17px !important;
  }
}

@media (max-width: 768px) {
  .publication-detail-section {
    padding: 30px 20px !important;
  }
  
  .publication-detail-card {
    padding: 30px !important;
  }
  
  .section-title {
    font-size: 27px !important;
  }
  
  .divider-text {
    font-size: 16px !important;
  }
  
  .detail-label {
    font-size: 16px !important;
  }
  
  .detail-value {
    font-size: 16px !important;
  }
  
  .publication-content {
    flex-direction: column;
  }
  
  .publication-image {
    width: 100%;
    height: 300px;
  }
  
  .publication-image img {
    height: 250px !important;
    order: -1 !important;
  }
  
  .publication-info {
    width: 100%;
  }
  
  .detail-label {
    width: 100%;
  }
  
  .button-section {
    padding: 50px 20px;
  }
  
  .cta-button {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .publication-detail-section {
    padding: 20px 15px !important;
  }
  
  .publication-detail-card {
    padding: 20px !important;
  }
  
  .section-title {
    font-size: 22px !important;
  }
  
  .divider-text {
    font-size: 13px !important;
  }
  
  .detail-label {
    font-size: 13px !important;
  }
  
  .detail-value {
    font-size: 13px !important;
  }
  
  .publication-image {
    height: 200px !important;
    border-radius: 15px 15px 0 0 !important;
    order: -1 !important;
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

.login-btn .pdf-icon {
  color: #DA5761;
}

.pdf-icon {
  width: 23px;
  height: 23px;
}
</style>
