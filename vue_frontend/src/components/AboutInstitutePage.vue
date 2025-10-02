<template>
  <div class="about-institute-page">
    <navigation />

    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      cms-page-key="about-institute"
      title-field-key="page_title"
      subtitle-field-key="page_subtitle"
      :heroImage="media('hero', '/img/Image_fx10.jpg', 'hero_about_institute')"
      mediaKey="hero_about_institute"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <!-- CMS Preview Body under hero when preview/edit flags present -->
    <div class="main-content" v-if="isEditPreview">
      <div class="content-container">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>
    </div>
    <!-- About Section -->
    <div class="about-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="about-institute" fieldKey="about_title" tag="span" :fallback="'ちくぎん地域経済研究所について'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text"><CmsText pageKey="about-institute" fieldKey="about_subtitle" tag="span" :fallback="'FOR YOU'" /></span>
          <div class="divider-line"></div>
        </div>
      </div>
      
      <div class="about-content">
        <img class="about-image" :src="img('about_image', 'https://api.builder.io/api/v1/image/assets/TEMP/ec1204f76835f4d00fb62a46530330165ae1b65e?width=1340')" alt="会社画像" />
        <div class="about-text">
          <div class="about-headline">
            <CmsText 
              pageKey="about-institute" 
              fieldKey="main_headline" 
              tag="h3" 
              type="html"
              class="main-headline"
              :fallback="mainHeadlineText"
            />
          </div>
          <div class="about-description">
            <CmsText pageKey="about-institute" fieldKey="about_body" tag="div" type="html" :fallback="`当研究所は、産・官・学・金(金融)のネットワークによる様々な分野の調査研究を通じ、企業活動などをサポートします。<br><br>経済・社会・産業動向などに関する調査研究及び企業経営や県民の生活のお役に立つ情報をご提供するとともに、各種経済・文化団体の事務局活動等を通じて、地域社会に貢献することを目指しております。<br>今後とも地元企業や地域の皆様をはじめ行政等との連携を緊密にし、様々な事業展開を図り地域の発展に寄与してまいりたいと考えております。`" />
          </div>
        </div>
      </div>
    </div>

    <!-- Service Overview Section -->
    <div class="service-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title"><CmsText pageKey="about-institute" fieldKey="service_title" tag="span" :fallback="'サービス概要'" /></h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text"><CmsText pageKey="about-institute" fieldKey="service_subtitle" tag="span" :fallback="'service'" /></span>
          <div class="divider-line"></div>
        </div>
      </div>
      
      <div class="service-cards">
        <div class="service-card" id="service-development">
          <img class="service-image" :src="img('service1_image', 'https://api.builder.io/api/v1/image/assets/TEMP/a3c7e188d6e1f00b84c70555aa606fd2ebe0cc5b?width=870')" alt="開発・研究" />
          <div class="service-content">
            <h3 class="service-title"><CmsText pageKey="about-institute" fieldKey="service1_title" tag="span" :fallback="'開発・研究'" /></h3>
            <p class="service-description"><CmsText pageKey="about-institute" fieldKey="service1_desc" tag="span" :fallback="'ちくぎん地域経済研究所では、国・地方自治体・大学・企業などからのご要望を受け、様々な情報を収集・調査・分析をします。有意義な情報を分かりやすく発信していきます。'" /></p>
              <div class="service-details">
                <CmsText pageKey="about-institute" fieldKey="service1_list" tag="div" type="html" :fallback="'・主な定期刊行物<br>・ちくぎん地域経済レポート（四半期毎）<br>・Hot Information（1ヶ月に2回）<br>・メールマガジン（毎週2回）'" />
              </div>
          </div>
        </div>
        
        <div class="service-card" id="service-human-resources">
          <img class="service-image" :src="img('service2_image', 'https://api.builder.io/api/v1/image/assets/TEMP/f92b01ad91c11f2d63f1c851a4991b7316f2365d?width=870')" alt="人材開発、セミナー" />
          <div class="service-content">
            <h3 class="service-title"><CmsText pageKey="about-institute" fieldKey="service2_title" tag="span" :fallback="'人材開発、セミナー'" /></h3>
            <p class="service-description"><CmsText pageKey="about-institute" fieldKey="service2_desc" tag="span" :fallback="'企業幹部、従業員または後継者(次世代育成)にマッチする効果的な研修を行います。ご要望に合わせて各種講演会、セミナー等の企画・運営を行うほか、様々なニーズに合わせて、外部専門家等の紹介・斡旋をいたします。'" /></p>
            <div class="service-details">
              <CmsText 
                pageKey="about-institute" 
                fieldKey="service2_list" 
                tag="div" 
                type="html"
                :fallback="'・人材紹介、斡旋<br>・外部専門家の紹介、斡旋<br>・教育・研修<br>・社員研修<br>・講演会<br>・セミナー'" 
              />
            </div>
          </div>
        </div>
        
        <div class="service-card" id="service-management-support">
          <img class="service-image" :src="img('service3_image', 'https://api.builder.io/api/v1/image/assets/TEMP/9de269531d1458bd155965ea0ad95d1a7f468fe4?width=870')" alt="経営支援" />
          <div class="service-content">
            <h3 class="service-title"><CmsText pageKey="about-institute" fieldKey="service3_title" tag="span" :fallback="'経営支援(経営サポート)'" /></h3>
            <p class="service-description"><CmsText pageKey="about-institute" fieldKey="service3_desc" tag="span" :fallback="'企業の皆様の経営課題解決に向けたお手伝いを行います。社内のバックオフィス業務構築などのお悩み事がありましたらご相談ください。'" /></p>
            <div class="service-details">
              <CmsText 
                pageKey="about-institute" 
                fieldKey="service3_list" 
                tag="div" 
                type="html"
                :fallback="'・コンサルティング<br>・経営課題解決サポート<br>・ビジネスマッチング<br>・国内外企業の紹介<br>・新ビジネスモデルの発展<br>・業務提携等の支援'" 
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Button Section -->
    <ActionButton v-if="!isEditPreview"
      :primary-text="ctaPrimaryText"
      :secondary-text="ctaSecondaryText"
      @primary-click="handleContactClick"
      @secondary-click="handleJoinClick"
    />

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
import Navigation from "./Navigation";
import ContactSection from "./ContactSection.vue";
import Footer from "./Footer";
import Group27 from "./Group27";
import AccessSection from "./AccessSection";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import ActionButton from "./ActionButton.vue";
import vector7 from "../../public/img/vector-7.svg";
import { frame132131753022Data } from "../data";
import { usePageText } from '@/composables/usePageText'
import CmsText from '@/components/CmsText.vue'
import CmsBlock from '@/components/CmsBlock.vue'
import { resolveMediaUrl } from '@/utils/url.js'

export default {
  name: "AboutInstitutePage",
  components: {
    Navigation,
    ContactSection,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ActionButton,
    CmsText,
    CmsBlock
  },
  data() {
    return {
      pageKey: 'about-institute',
      vector7: vector7,
      frame132131753022Props: frame132131753022Data,
      mainHeadlineText: '産・官・学・金<span class="small-text">(金融機関)</span><br>をつなぐ架け橋へ',
      _pageMedia: null,
      _media: null,
    };
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', 'ちくぎん地域経済研究所について') || 'ちくぎん地域経済研究所について' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'for you') || 'for you' },
    ctaPrimaryText() { return this._pageText?.getText('cta_primary', 'お問い合わせはこちら') || 'お問い合わせはこちら' },
    ctaSecondaryText() { return this._pageText?.getText('cta_secondary', '入会はこちら') || '入会はこちら' },
    isEditPreview() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsPreview') || params.has('cmsEdit')
      } catch (_) { return false }
    },
  },
  mounted() {
    // Load CMS page text once
    try {
      this._pageText = usePageText(this.pageKey)
      const opts = { force: true }
      try {
        const token = localStorage.getItem('admin_token')
        if (token && token.length > 0) opts.preferAdmin = true
      } catch (_) {}
      this._pageText.load(opts)
    } catch (_) {}

    // Media registry (page-scoped + global) for responsive assets
    import('@/composables/usePageMedia').then(mod => {
      try {
        const { usePageMedia } = mod
        this._pageMedia = usePageMedia()
        this._pageMedia.ensure(this.pageKey)
        this._media = this._pageMedia._media
        // Re-render when media payload updates
        try {
          const readImages = () => {
            const imgs = this._media && this._media.images
            const val = imgs && (imgs.value !== undefined ? imgs.value : imgs)
            try { return val ? JSON.stringify(val) : '' } catch (_) { return val ? Object.keys(val).join('|') : '' }
          }
          this.$watch(readImages, () => { this.$forceUpdate() })
          const readLoaded = () => {
            const ld = this._media && this._media.loaded
            return ld && (ld.value !== undefined ? ld.value : ld)
          }
          this.$watch(readLoaded, () => { this.$forceUpdate() })
        } catch (_) {}
      } catch (_) {}
    })

    // Layout adjustments
    this.adjustRectangleHeight();
    this.adjustMainHeadline();
    // Ensure parentheses text like （金融機関） is reduced in size
    // note: small text is handled via inline span in mainHeadlineText
    window.addEventListener('resize', this.adjustRectangleHeight);
    window.addEventListener('resize', this.adjustMainHeadline);
    // Reflect admin edits immediately: listen for localStorage cache updates and reload PageContent
    try {
      this.__onStorage = (ev) => {
        const k = ev && ev.key ? String(ev.key) : ''
        if (k === 'page_content_cache:' + this.pageKey) {
          try { this._pageText && this._pageText.load && this._pageText.load({ force: true }) } catch(_) {}
        }
      }
      window.addEventListener('storage', this.__onStorage)
      // Also refresh when tab becomes visible again
      this.__onVis = () => { if (document.visibilityState === 'visible') { try { this._pageText.load({ force: true }) } catch(_) {} } }
      document.addEventListener('visibilitychange', this.__onVis)
    } catch(_) {}
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.adjustRectangleHeight);
    window.removeEventListener('resize', this.adjustMainHeadline);
    // no observer to clean up
    try { if (this.__onStorage) window.removeEventListener('storage', this.__onStorage) } catch(_) {}
    try { if (this.__onVis) document.removeEventListener('visibilitychange', this.__onVis) } catch(_) {}
  },
  methods: {
    media(key, fallback = '', mediaKey = '') {
      try {
        const page = this._pageText && this._pageText.page && this._pageText.page.value
        const imgs = page && page.content && page.content.images
        if (imgs && Object.prototype.hasOwnProperty.call(imgs, key)) {
          const v = imgs[key]
          let url = (v && typeof v === 'object') ? (v.url || '') : (typeof v === 'string' ? v : '')
          try {
            const meta = (v && typeof v === 'object') ? v : null
            const ver = meta && meta.uploaded_at ? (Date.parse(meta.uploaded_at) || Date.now()) : null
            if (ver && typeof url === 'string' && url.startsWith('/storage/')) {
              url += (url.includes('?') ? '&' : '?') + '_t=' + encodeURIComponent(String(ver))
            }
          } catch (_) {}
          if (typeof url === 'string' && url.length) {
            return resolveMediaUrl(url)
          }
        }
      } catch (_) {}

      try {
        if (this._pageMedia) {
          const slot = this._pageMedia.getResponsiveSlot(key, mediaKey || key, fallback)
          if (slot && typeof slot === 'string' && slot.length) return slot
        }
        if (this._media) {
          const lookupKey = mediaKey || key
          if (this._media.getResponsiveImage) {
            const img = this._media.getResponsiveImage(lookupKey, fallback)
            if (img && typeof img === 'string' && img.length) return img
          }
          if (this._media.getImage) {
            const img = this._media.getImage(lookupKey, fallback)
            if (img && typeof img === 'string' && img.length) return img
          }
        }
      } catch (_) {}
      return fallback
    },
    img(key, fallback = '') {
      return this.media(key, fallback)
    },
    adjustRectangleHeight() {
      this.$nextTick(() => {
        const frame1321317466 = this.$el.querySelector('.frame-1321317466');
        const rectangle3 = this.$el.querySelector('.rectangle-3');
        
        if (frame1321317466 && rectangle3) {
          const frameHeight = frame1321317466.offsetHeight;
          rectangle3.style.height = frameHeight + 'px';
        }
      });
    },
    // Equal-height is achieved via CSS Grid; no JS height sync needed
    adjustMainHeadline() {
      this.$nextTick(() => {
        if (window.innerWidth <= 1150) {
          // 1150px以下では改行を削除
          this.mainHeadlineText = '産・官・学・金<span class="small-text">(金融機関)</span>をつなぐ架け橋へ';
        } else {
          // 1150px超では改行を追加
          this.mainHeadlineText = '産・官・学・金<span class="small-text">(金融機関)</span><br>をつなぐ架け橋へ';
        }
      });
    },
    // no DOM post-processing; rely on explicit <span class="small-text"> in text
    handleContactClick() {
      this.$router.push('/contact');
    },
    handleJoinClick() {
      this.$router.push('/application-form');
    }
  }
};
</script>

<style scoped>
.about-institute-page {
  background-color: #ECECEC;
  font-family: 'Inter', Helvetica, sans-serif;
  position: relative;
  width: 100%;
  overflow-x: auto;
}







/* Section Styling */
.about-section, .service-section {
  padding: 70px 50px 50px 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
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

/* About Section */
.about-section {
  background: #ECECEC;
}

.about-content {
  /* Use grid so both columns share the same row height */
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: stretch;
  width: 100%;
  max-width: 2000px;
  height: auto;
}

.about-image {
  width: 100%;
  max-height: 600px;
  border-radius: 20px 0 0 20px;
  object-fit: cover;
  object-position: center;
  display: block;
}

.about-text {
  width: 100%;
  max-height: 600px;
  padding: 50px;
  background: white;
  border-radius: 0 20px 20px 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 40px;
}

.about-headline {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.main-headline {
  color: #DA5761;
  font-size: 55px;
  font-weight: 700;
  line-height: 1.5;
}

/* より細かいレスポンシブ対応 */
@media (max-width: 1600px) {
  .main-headline {
    font-size: 52px !important;
  }
}

@media (max-width: 1500px) {
  .main-headline {
    font-size: 51px !important;
  }
}

@media (max-width: 1400px) {
  .main-headline {
    font-size: 48px !important;
  }
}

@media (max-width: 1300px) {
  .main-headline {
    font-size: 42px !important;
  }
}

@media (max-width: 1200px) {
  .main-headline {
    font-size: 40px !important;
  }
}

@media (max-width: 1100px) {
  .main-headline {
    font-size: 40px !important;
  }
}

@media (max-width: 1000px) {
  .main-headline {
    font-size: 40px !important;
  }
}

@media (max-width: 900px) {
  .main-headline {
    font-size: 38px !important;
  }
}

@media (max-width: 800px) {
  .main-headline {
    font-size: 35px !important;
  }
}

@media (max-width: 768px) {
  .main-headline {
    font-size: 32px !important;
  }
}

@media (max-width: 700px) {
  .main-headline {
    font-size: 30px !important;
  }
}

@media (max-width: 600px) {
  .main-headline {
    font-size: 28px !important;
  }
}

@media (max-width: 500px) {
  .main-headline {
    font-size: 26px !important;
  }
}

@media (max-width: 480px) {
  .main-headline {
    font-size: 24px !important;
  }
}

@media (max-width: 400px) {
  .main-headline {
    font-size: 22px !important;
  }
}

@media (max-width: 360px) {
  .main-headline {
    font-size: 20px !important;
  }
}

.about-description {
  color: #3F3F3F;
  font-family: 'Noto Sans JP', sans-serif;
  font-size: 20px;
  font-weight: 400;
  line-height: 1.2;
}

/* Service Section */
.service-section {
  background: #ECECEC;
  width: 100%;
}

.service-cards {
  display: flex;
  flex-direction: column;
  gap: 40px;
  width: 100%;
  max-width: 2000px;
}

.service-card {
  /* Grid ensures the image column matches the text column height */
  display: grid;
  grid-template-columns: 40% 60%;
  align-items: stretch;
  width: 100%;
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  scroll-margin-top: 100px; /* アンカーリンク用のスクロールマージン */
}

.service-image {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
  object-position: center;
  display: block;
}

.service-content {
  width: 100%;
  max-height: 400px;
  padding: 40px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.service-title {
  color: #1A1A1A;
  font-size: 24px;
  font-weight: 700;
  line-height: 1.5;
}

.service-description {
  color: #3F3F3F;
  font-size: 20px;
  font-weight: 400;
  line-height: 1.6;
}

.service-details {
  color: #3F3F3F;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.8;
}



/* Responsive Design */
@media (max-width: 1150px) {
  /* セクションのパディング調整 */
  .about-section,
  .service-section {
    padding: 50px 30px !important;
  }

  /* レイアウトの縦並び化 */
  .about-content {
    display: flex !important;
    flex-direction: column !important;
    gap: 0 !important;
  }
  
  /* 要素の幅調整 */
  .about-image,
  .about-text {
    width: 100% !important;
  }
  
  /* 画像の高さ固定 */
  .about-image {
    height: 300px !important;
    border-radius: 20px 20px 0 0 !important;
  }
  
  /* テキストの角丸とパディング調整 */
  .about-text {
    border-radius: 0 0 20px 20px !important;
    padding: 50px !important;
  }

  /* サービスカードの縦並び化 */
  .service-card {
    display: flex !important;
    flex-direction: column !important;
  }
  
  .service-image,
  .service-content {
    width: 100% !important;
  }
  
  .service-image {
    border-radius: 20px 20px 0 0 !important;
    height: 300px !important;
  }
  
  .service-content {
    border-radius: 0 0 20px 20px !important;
  }

  /* フォントサイズ調整 */
  .section-title {
    font-size: 32px !important;
  }

  .divider-text {
    font-size: 18px !important;
  }

  .main-headline {
    white-space: normal !important;
    word-break: break-word !important;
    overflow-wrap: break-word !important;
  }
  
  .about-description {
    font-size: 18px !important;
  }
  
  .service-title {
    font-size: 22px !important;
  }
  
  .service-description {
    font-size: 18px !important;
  }

  .service-details {
    font-size: 18px !important;
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
  .about-institute-page {
    overflow-x: hidden !important;
  }
  
  .about-section,
  .service-section {
    width: 100% !important;
    overflow: hidden !important;
    min-width: auto !important;
  }
  
  .about-content {
    width: 100% !important;
    max-width: 100% !important;
  }
  
  .service-cards {
    width: 100% !important;
    max-width: 100% !important;
  }

  .section-header {
    gap: 22px !important;
  }

  .section-title {
    font-size: 29px !important;
  }

  .divider-text {
    font-size: 17px !important;
  }

  .about-description {
    font-size: 17px !important;
  }
  
  .service-title {
    font-size: 20px !important;
  }
  
  .service-description {
    font-size: 17px !important;
  }

  .service-details {
    font-size: 17px !important;
  }
}

@media (max-width: 768px) {
  /* 完全な縦並び化 */
  .about-content {
    flex-direction: column !important;
    width: 100% !important;
    max-width: 100% !important;
  }
  
  /* セクション幅の最適化 */
  .about-section,
  .service-section {
    width: 100% !important;
    overflow: hidden !important;
  }

  /* セクションヘッダーの調整 */
  .section-header {
    gap: 20px !important;
  }

  /* フォントサイズ調整 */
  .section-title {
    font-size: 27px !important;
  }

  .divider-text {
    font-size: 16px !important;
  }

  .about-description {
    font-size: 16px !important;
  }
  
  .service-title {
    font-size: 19px !important;
  }
  
  .service-description {
    font-size: 16px !important;
  }

  .service-details {
    font-size: 16px !important;
  }
}

@media (max-width: 1050px) {
.service-content {
    max-height: 450px !important;
  }
}

@media (max-width: 480px) {
  /* セクションの調整 */
  .about-section,
  .service-section {
    width: 100% !important;
    overflow: hidden !important;
    padding: 30px 20px !important;
    gap: 20px !important;
  }
  
  /* コンテンツの調整 */
  .about-content {
    width: 100% !important;
    max-width: 100% !important;
  }

  .service-content {
    padding: 30px 20px !important;
  }
  
  .about-text {
    padding: 30px 20px !important;
  }
  
  /* テキストの調整 */
  .about-text {
    padding: 20px 15px !important;
    gap: 20px !important;
  }

  /* フォントサイズ調整 */
  
  .about-description {
    font-size: 13px !important;
  }
  
  .service-title {
    font-size: 16px !important;
  }
  
  .service-description {
    font-size: 13px !important;
  }
  
  .service-details {
    font-size: 13px !important;
  }

  /* 画像の調整 */
  .about-image {
    height: 200px !important;
  }
  
  .service-image {
    height: 200px !important;
  }

  /* セクションヘッダーの調整 */
  .section-header {
    gap: 18px !important;
    margin-bottom: 20px !important;
  }

  .section-title {
    font-size: 22px !important;
  }

  .divider-text {
    font-size: 13px !important;
  }
}

/* 小さなテキスト用のスタイル */
.small-text {
  font-size: 0.33em;
  vertical-align: baseline;
}
</style>
