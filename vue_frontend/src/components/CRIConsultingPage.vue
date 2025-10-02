<template>
  <div class="cri-consulting-page">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      cms-page-key="cri-consulting"
      heroImage="/img/Image_fx9.jpg"
      mediaKey="hero_consulting"
    />
    
    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />
    
    <!-- CMS Preview Body under hero when preview/edit flags present -->
    <div class="main-content" v-if="isEditPreview">
      <div class="content-container">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>
    </div>
    
    <!-- What is CRI Consulting Section -->
    <div class="what-is-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="cri-consulting" fieldKey="what_title" tag="span" :fallback="'CRI 経営コンサルティングとは？'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text"><CmsText pageKey="cri-consulting" fieldKey="what_subtitle" tag="span" :fallback="'consulting'" /></span>
          <div class="divider-line"></div>
        </div>
      </div>
      
      <div class="content-container">
        <img :src="slotImage('what_image', 'https://api.builder.io/api/v1/image/assets/TEMP/f016525f0cc061901e592a57545785e894630484?width=940')" alt="Consulting Image" class="content-image" />
        <div class="content-text">
          <h3 class="content-subtitle"><CmsText pageKey="cri-consulting" fieldKey="what_content_subtitle" tag="span" :fallback="`What's CRI Management Consulting`" /></h3>
          <h4 class="content-heading"><CmsText pageKey="cri-consulting" fieldKey="what_content_heading" tag="span" :fallback="'CRI経営コンサルティングとは、事業でお悩みの皆様に対し、アドバイス、サポートするサービスです'" /></h4>
          <div class="content-description">
            <CmsText pageKey="cri-consulting" fieldKey="what_content_body" tag="div" type="html" :fallback="`日常の企業活動の中で、まず自社が抱える様々な問題や課題・顧客ニーズなどに気付き、それらを整理して対応策を講じることができれば、改善に向けて新たなスタートを切ったと言えましょう。<br>しかし、「何か変だ。」と思いながらもとりあえず一日の業務を無事に終えたことに安心していては、身近に存在する重要なことに気付くのが遅れることもあります。`" />
          </div>
        </div>
      </div>
    </div>
    
    <!-- Main Duties Section -->
    <div class="main-duties-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title"><CmsText pageKey="cri-consulting" fieldKey="duties_title" tag="span" :fallback="'CRI経営コンサルティングの主な業務'" /></h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text"><CmsText pageKey="cri-consulting" fieldKey="duties_subtitle" tag="span" :fallback="'main duties'" /></span>
          <div class="divider-line"></div>
        </div>
        <p class="section-subtitle"><CmsText pageKey="cri-consulting" fieldKey="duties_intro" tag="span" type="html" :fallback="'事業にまつわる様々をサポート行います。<br>当研究所のネットワークを活かし、御社の実情に合う専門家もご紹介できます。'" /></p>
      </div>
      
      <div class="duties-container">
        <div class="duties-content">
          <h3 class="duties-title"><CmsText pageKey="cri-consulting" fieldKey="duties_label" tag="span" :fallback="'MAIN DUTIES'" /></h3>
          <h4 class="duties-heading"><CmsText pageKey="cri-consulting" fieldKey="duties_heading" tag="span" :fallback="'事業にまつわる様々な悩みを一緒に解決します'" /></h4>
          <div class="duties-list">
            <CmsText pageKey="cri-consulting" fieldKey="duties_list" tag="div" type="html" :fallback="`<div>・経営戦略策定のサポート</div><div>・ビジネスマッチィングの支援</div><div>・事業継承の支援（後継者育成支援）</div><div>・社員研修、経営計画策定のための人材育成</div><div>・税務、財務、事務など各業務の課題解決力の育成</div><div>・事務省力化のサポート</div><div>・人事制度の策定</div><div>・ワンストップのよろず差相談</div>`" />
          </div>
        </div>
        <img :src="slotImage('duties_image', 'https://api.builder.io/api/v1/image/assets/TEMP/25a28ba9ea089f902a21c4d02c416034111f837a?width=1304')" alt="Main Duties Image" class="duties-image" />
      </div>
      
      <p class="duties-description">
        当研究所だけで対応できないことは、CRIのもつネットワークを活用し、相談者の実情に合う専門家をご紹介いたします。その上で一緒に課題解決に向けて動いていきます。専門家が加わることで、状況に応じたよりよい解決方法を導き出すことができます。
      </p>
    </div>

    <!-- Contact CTA Section -->
    <ContactSection />
    
    <!-- Support Content and Costs Section -->
    <div class="support-section">
      <div class="section-header">
        <h2 class="section-title"><CmsText pageKey="cri-consulting" fieldKey="support_title" tag="span" :fallback="'サポート内容と費用に関して'" /></h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text"><CmsText pageKey="cri-consulting" fieldKey="support_subtitle_en" tag="span" :fallback="'contents and costs'" /></span>
          <div class="divider-line"></div>
        </div>
        <p class="section-subtitle"><CmsText pageKey="cri-consulting" fieldKey="support_intro" tag="span" type="html" :fallback="'経営改善とは会社の中の「人」が変わることです。<br>ご相談と問題点・課題の整理は無料です。まずは、お気軽にご相談ください。'" /></p>
      </div>
      
      <div class="support-content">
        <div class="support-category">
          <h3 class="category-title"><CmsText pageKey="cri-consulting" fieldKey="support_free_title" tag="span" :fallback="'無料 -FREE-'" /></h3>
          <div class="service-grid">
            <div class="service-card">
              <img :src="slotImage('support_free_consultation', 'https://api.builder.io/api/v1/image/assets/TEMP/33cee256926cd28b9a1a674d35fd9166a56602c3?width=590')" alt="Consultation" class="service-image" />
              <div class="service-info">
                <span class="service-label">consultation</span>
                <h4 class="service-title">ご相談</h4>
              </div>
            </div>
            <div class="service-card">
              <img :src="slotImage('support_free_problem', 'https://api.builder.io/api/v1/image/assets/TEMP/31768edf86418d36cb69d49cceebe7329e674e2b?width=590')" alt="Problem Analysis" class="service-image" />
              <div class="service-info">
                <span class="service-label">problem</span>
                <h4 class="service-title">問題点・課題の整理</h4>
              </div>
            </div>
          </div>
        </div>
        
        <div class="support-divider"></div>
        
        <div class="support-category">
          <h3 class="category-title"><CmsText pageKey="cri-consulting" fieldKey="support_paid_title" tag="span" :fallback="'有料 -PAID-'" /></h3>
          <div class="service-grid">
            <div class="service-card">
              <img :src="slotImage('support_paid_strategy', 'https://api.builder.io/api/v1/image/assets/TEMP/3ae2d367ee53d3693e55ab25762bbc22b1e79db3?width=590')" alt="Strategy Planning" class="service-image" />
              <div class="service-info">
                <span class="service-label">consultation</span>
                <h4 class="service-title">対応策の立案</h4>
              </div>
            </div>
            <div class="service-card">
              <img :src="slotImage('support_paid_improvement', 'https://api.builder.io/api/v1/image/assets/TEMP/314b38f7051166e9dfefd653b917881f2e188fb0?width=590')" alt="Improvement Activities" class="service-image" />
              <div class="service-info">
                <span class="service-label">problem</span>
                <h4 class="service-title">改善に向けた活動</h4>
              </div>
            </div>
            <div class="service-card">
              <img :src="slotImage('support_paid_analysis', 'https://api.builder.io/api/v1/image/assets/TEMP/e92448f8521df04fdda36093ecdc836ecfdcbdbb?width=590')" alt="Analysis" class="service-image" />
              <div class="service-info">
                <span class="service-label">problem</span>
                <h4 class="service-title">分析（新たな課題認識）</h4>
              </div>
            </div>
            <div class="service-card">
              <img :src="slotImage('support_paid_newbiz', 'https://api.builder.io/api/v1/image/assets/TEMP/ccca71dcedbebcbeb9188be2f5b67ca86225155f?width=590')" alt="New Business Activities" class="service-image" />
              <div class="service-info">
                <span class="service-label">problem</span>
                <h4 class="service-title">新たな事業活動</h4>
              </div>
            </div>
          </div>
        </div>
        
        <div class="support-divider"></div>
        
        <div class="support-description">
          <p>「経営改善」とは、会社（ハード）が変わるのではなく、会社の中にいる「人」（ソフト）が変わることです。 ちくぎん地域経済研究所は、事業のお悩み解決のために、あなたにぴったりの解決策をご提案いたします。<br>どうぞお気軽にご相談ください。</p>
          <button class="contact-btn" @click="goToContact">
            <span>お問い合わせはこちら</span>
            <svg width="18" height="19" viewBox="0 0 18 19" fill="none">
              <rect y="0.5" width="18" height="18" rx="5" fill="white"/>
              <path d="M13.2193 10.2752L9.7194 13.875C9.64643 13.95 9.54746 13.9922 9.44427 13.9922C9.34107 13.9922 9.24211 13.95 9.16914 13.875C9.09617 13.7999 9.05517 13.6981 9.05517 13.592C9.05517 13.4858 9.09617 13.3841 9.16914 13.309L12.0055 10.3922L4.38888 10.3922C4.28574 10.3922 4.18683 10.35 4.1139 10.275C4.04097 10.2 4 10.0983 4 9.99219C4 9.88611 4.04097 9.78437 4.1139 9.70936C4.18683 9.63435 4.28574 9.59221 4.38888 9.59221L12.0055 9.59221L9.16914 6.67537C9.09617 6.60032 9.05517 6.49853 9.05517 6.39239C9.05517 6.28625 9.09617 6.18446 9.16914 6.1094C9.24211 6.03435 9.34107 5.99219 9.44427 5.99219C9.54746 5.99219 9.64643 6.03435 9.7194 6.1094L13.2193 9.7092C13.2554 9.74635 13.2841 9.79046 13.3037 9.83902C13.3233 9.88758 13.3333 9.93962 13.3333 9.99219C13.3333 10.0448 13.3233 10.0968 13.3037 10.1454C13.2841 10.1939 13.2554 10.238 13.2193 10.2752Z" fill="#1A1A1A"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Achievements Section -->
    <div class="achievements-section">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="cri-consulting" fieldKey="achievements_title" tag="span" :fallback="'CRI経営コンサルティングの実績紹介'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="cri-consulting" fieldKey="achievements_subtitle" tag="span" :fallback="'achievements'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      
      <div class="achievements-content">
        <div class="achievements-grid">
          <div class="achievement-card">
            <img :src="slotImage('achievements_item1_image', 'https://api.builder.io/api/v1/image/assets/TEMP/ec6122758f12e1bfbe99ee61905a31ea4d49a78c?width=574')" alt="Achievement 1" class="achievement-image" />
            <div class="achievement-info">
              <div class="achievement-meta">
                <span class="achievement-date">2025.6.27</span>
                <span class="achievement-category">カテゴリー</span>
              </div>
              <h4 class="achievement-title"><CmsText pageKey="cri-consulting" fieldKey="achievements_item1_title" tag="span" :fallback="'分析（新たな課題認識）'" /></h4>
              <p class="achievement-description"><CmsText pageKey="cri-consulting" fieldKey="achievements_item1_desc" tag="div" type="html" :fallback="'有限会社AliveCast 中村理氏による…'" /></p>
            </div>
          </div>
          
          <div class="achievement-card">
            <img :src="slotImage('achievements_item2_image', 'https://api.builder.io/api/v1/image/assets/TEMP/95f39033c1a29bc732f48805d8362c58b24c766c?width=574')" alt="Achievement 2" class="achievement-image" />
            <div class="achievement-info">
              <div class="achievement-meta">
              <span class="achievement-date"><CmsText pageKey="cri-consulting" fieldKey="achievements_item2_date" tag="span" :fallback="'2025.6.27'" /></span>
              <span class="achievement-category"><CmsText pageKey="cri-consulting" fieldKey="achievements_item2_category" tag="span" :fallback="'カテゴリー'" /></span>
              </div>
              <h4 class="achievement-title"><CmsText pageKey="cri-consulting" fieldKey="achievements_item2_title" tag="span" :fallback="'分析（新たな課題認識）'" /></h4>
              <p class="achievement-description"><CmsText pageKey="cri-consulting" fieldKey="achievements_item2_desc" tag="div" type="html" :fallback="'在上海中国ビジネスウォッチャー…'" /></p>
            </div>
          </div>
          
          <div class="achievement-card">
            <img :src="slotImage('achievements_item3_image', 'https://api.builder.io/api/v1/image/assets/TEMP/9c17e76e3ac6cf114e40472b8f2ce93b5bfa00f3?width=574')" alt="Achievement 3" class="achievement-image" />
            <div class="achievement-info">
              <div class="achievement-meta">
              <span class="achievement-date"><CmsText pageKey="cri-consulting" fieldKey="achievements_item3_date" tag="span" :fallback="'2025.6.27'" /></span>
              <span class="achievement-category"><CmsText pageKey="cri-consulting" fieldKey="achievements_item3_category" tag="span" :fallback="'カテゴリー'" /></span>
              </div>
              <h4 class="achievement-title"><CmsText pageKey="cri-consulting" fieldKey="achievements_item3_title" tag="span" :fallback="'分析（新たな課題認識）'" /></h4>
              <p class="achievement-description"><CmsText pageKey="cri-consulting" fieldKey="achievements_item3_desc" tag="div" type="html" :fallback="'Excel 2010 講座を開催…'" /></p>
            </div>
          </div>
          
          <div class="achievement-card">
            <img :src="slotImage('achievements_item4_image', 'https://api.builder.io/api/v1/image/assets/TEMP/5dabcf000de42e14f9010cb47622d830cacdf698?width=574')" alt="Achievement 4" class="achievement-image" />
            <div class="achievement-info">
              <div class="achievement-meta">
              <span class="achievement-date"><CmsText pageKey="cri-consulting" fieldKey="achievements_item4_date" tag="span" :fallback="'2025.6.27'" /></span>
              <span class="achievement-category"><CmsText pageKey="cri-consulting" fieldKey="achievements_item4_category" tag="span" :fallback="'カテゴリー'" /></span>
              </div>
              <h4 class="achievement-title"><CmsText pageKey="cri-consulting" fieldKey="achievements_item4_title" tag="span" :fallback="'分析（新たな課題認識）'" /></h4>
              <p class="achievement-description"><CmsText pageKey="cri-consulting" fieldKey="achievements_item4_desc" tag="div" type="html" :fallback="'上海経済事情視察ツアーを実施…'" /></p>
            </div>
          </div>
        </div>
        
        <div class="achievements-divider"></div>
        
        <p class="achievements-note">
          <CmsText pageKey="cri-consulting" fieldKey="achievements_note" tag="span" :fallback="'このほか「Hot Information」…'" />
        </p>
      </div>
    </div>
    
    <!-- Action Button Section -->
    <ActionButton 
      :primary-text="_pageText?.getText('cta_primary', 'お問い合わせはこちら') || 'お問い合わせはこちら'"
      :secondary-text="_pageText?.getText('cta_secondary', '入会はこちら') || '入会はこちら'"
      @primary-click="handleContactClick"
      @secondary-click="handleJoinClick"
    />
    
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
import ContactSection from "./ContactSection.vue";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import ActionButton from "./ActionButton.vue";
import { usePageText } from '@/composables/usePageText'
import { usePageMedia } from '@/composables/usePageMedia'
import CmsBlock from '@/components/CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'

import Group27 from "./Group27";
import { homePageData, frame132131753022Data } from "../data";

export default {
  name: "CriConsultingPage",
  components: {
    Navigation,
    Footer,
    ContactSection,
    AccessSection,
    Group27,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ActionButton,
    CmsText,
    CmsBlock
  },
  data() {
    return {
      pageKey: 'cri-consulting',
      frame132131753022Props: homePageData.frame132131753022Props,
    };
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', 'CRI 経営コンサルティング') || 'CRI 経営コンサルティング' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'consulting') || 'consulting' },
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
    this.adjustRectangleHeight();
    window.addEventListener('resize', this.adjustRectangleHeight);
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }

    // Setup per-page media mapping + watch for updates
    try {
      const pm = usePageMedia()
      this._pageMedia = pm
      pm.ensure(this.pageKey).then(() => {
        try {
          const m = pm._media
          const readVersion = () => {
            const imgs = m && m.images
            const val = imgs && (imgs.value !== undefined ? imgs.value : imgs)
            try { return val ? JSON.stringify(val) : '' } catch(_) { return val ? Object.keys(val).join('|') : '' }
          }
          this.$watch(readVersion, () => { this.$forceUpdate() })
          const readLoaded = () => {
            const ld = m && m.loaded
            return ld && (ld.value !== undefined ? ld.value : ld)
          }
          this.$watch(readLoaded, () => { this.$forceUpdate() })
        } catch(_) {}
      })
    } catch(_) { /* noop */ }

    // Reflect admin edits to PageContent immediately (images/texts) without full reload
    try {
      this.__onStorage = (ev) => {
        const k = ev && ev.key ? String(ev.key) : ''
        if (k === 'page_content_cache:' + this.pageKey) {
          try { this._pageText && this._pageText.load && this._pageText.load({ force: true }) } catch(_) {}
          // force UI refresh to re-resolve slotImage()
          try { this.$forceUpdate() } catch(_) {}
        }
      }
      window.addEventListener('storage', this.__onStorage)
      this.__onVis = () => { if (document.visibilityState === 'visible') { try { this._pageText.load({ force: true }) } catch(_) {} } }
      document.addEventListener('visibilitychange', this.__onVis)
    } catch(_) {}
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.adjustRectangleHeight);
    try { if (this.__onStorage) window.removeEventListener('storage', this.__onStorage) } catch(_) {}
    try { if (this.__onVis) document.removeEventListener('visibilitychange', this.__onVis) } catch(_) {}
  },
  methods: {
    slotImage(slotKey, fallback = '') {
      // 方針A: ページ管理の content.images を最優先（モバイルでも確実に反映）
      try {
        const page = this._pageText?.page?.value
        const v = page?.content?.images?.[slotKey]
        if (typeof v === 'string' && v) return v
        if (v && typeof v === 'object' && v.url) {
          let u = v.url
          try {
            if (u.startsWith('/storage/') && v.uploaded_at) {
              const ver = Date.parse(v.uploaded_at) || Date.now()
              u += (u.includes('?') ? '&' : '?') + '_t=' + encodeURIComponent(String(ver))
            }
          } catch(_) {}
          return u
        }
      } catch (_) {}
      // 次にレジストリ（ページマッピング）: viewportに応じたキーも解決
      try {
        if (this._pageMedia) {
          const v = this._pageMedia.getResponsiveSlot(slotKey, slotKey, fallback)
          if (v && v !== fallback) return v
        }
      } catch(_) {}
      return fallback
    },
    // Equal-height for paired images/text is handled with CSS Grid
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
    handleContactClick() {
      this.$router.push('/contact');
    },
    handleJoinClick() {
      this.$router.push('/application-form');
    },
    goToContact() {
      this.$router.push('/contact');
    }
  }
};
</script>

<style scoped>
.cri-consulting-page {
  background-color: #ECECEC;
  min-height: 100vh;
  width: 100%;
}



/* Section Headers */
.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 40px;
}

.section-title {
  color: #1A1A1A;
  text-align: center;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 36px;
  font-weight: 700;
  line-height: normal;
  letter-spacing: -0.72px;
  margin: 0;
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
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
}

.section-subtitle {
  color: #1A1A1A;
  text-align: center;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  letter-spacing: -0.36px;
  max-width: 1014px;
}

/* What is CRI Section */
.what-is-section {
  width: 100%;
  padding: 70px 50px 50px;
}

.content-container {
  background-color: #FFF;
  border-radius: 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: stretch;
  max-width: 2000px;
  margin: 0 auto;
}

.content-image {
  width: 100%;
  max-height: 550px;
  border-radius: 20px 0 0 20px;
  object-fit: cover;
  object-position: center;
  display: block;
}

.content-text {
  width: 100%;
  max-height: 550px;
  padding: 50px;
  background: #FFF;
  border-radius: 0 20px 20px 0;
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.content-subtitle {
  color: #DA5761;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 64px;
  font-weight: 700;
  line-height: 110%;
  margin: 0;
}

.content-heading {
  color: #1A1A1A;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 28px;
  font-weight: 700;
  line-height: 150%;
  margin: 0;
}

.content-description {
  color: #3F3F3F;
  font-family: Noto Sans JP, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  margin: 0;
}

/* Main Duties Section */
.main-duties-section {
  width: 100%;
  padding: 50px 50px 70px;
}

.duties-container {
  background: #FFF;
  border-radius: 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: stretch;
  max-width: 2000px;
  margin: 0 auto;
}

.duties-content {
  width: 100%;
  max-height: 550px;
  padding: 50px;
  background: #FFF;
  border-radius: 20px 0 0 20px;
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.duties-title {
  color: #DA5761;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 64px;
  font-weight: 700;
  line-height: 150%;
  margin: 0;
}

.duties-heading {
  color: #1A1A1A;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 28px;
  font-weight: 700;
  line-height: 150%;
  margin: 0;
}

.duties-list {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.duties-list div {
  color: #3F3F3F;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
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

/* Override inline-editable font size for duties-list to match other content */
.duties-list .inline-editable {
  font-size: 18px;
  color: #3F3F3F;
}

.duties-image {
  width: 100%;
  max-height: 550px;
  border-radius: 0 20px 20px 0;
  object-fit: cover;
  object-position: center;
  display: block;
}

.duties-description {
  color: #1A1A1A;
  text-align: center;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  letter-spacing: -0.36px;
  max-width: 1140px;
  margin: 0 auto;
  margin-top: 50px;
}

/* Support Section */
.support-section {
  width: 100%;
  padding: 80px 50px 50px;
}

.support-content {
  padding: 50px;
  border-radius: 20px;
  background: #FFF;
  max-width: 2000px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 50px;
}

.support-category {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.category-title {
  color: #1A1A1A;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 24px;
  font-weight: 700;
  line-height: normal;
  margin: 0;
}

.service-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.service-card {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  background: #FFF;
}

.service-image {
  width: 100%;
  height: auto;
  object-fit: cover;
  border-radius: 20px;
  aspect-ratio: 16 / 9;
}

.service-info {
  position: absolute;
  bottom: 0;
  right: 0;
  background: #FFF;
  padding: 13px;
  border-radius: 10px 0 0 0;
  width: 85%;
}

.service-label {
  color: #DA5761;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 400;
  display: block;
  margin-bottom: 5px;
}

.service-title {
  color: #3F3F3F;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  margin: 0;
}

.support-divider {
  width: 100%;
  height: 1px;
  background: #CFCFCF;
}

.support-description {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 50px;
}

.support-description p {
  color: #1A1A1A;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  letter-spacing: -0.36px;
  text-align: center;
  margin: 0;
}

.contact-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 380px;
  padding: 10px 0;
  border-radius: 10px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.contact-btn span {
  color: #FFF;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.contact-btn:hover {
  opacity: 0.8;
}

/* Achievements Section */
.achievements-section {
  width: 100%;
  padding: 50px;
}

.achievements-content {
  padding: 50px;
  border-radius: 20px;
  background: #FFF;
  max-width: 2000px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 50px;
}

.achievements-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}

.achievement-card {
  background: #FFF;
  border-radius: 10px;
  overflow: hidden;
}

.achievement-image {
  width: 100%;
  height: auto;
  object-fit: cover;
  border-radius: 10px;
  aspect-ratio: 16 / 9;
}

.achievement-info {
  padding: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.achievement-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.achievement-date {
  color: #3F3F3F;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 400;
}

.achievement-category {
  color: #FFF;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 13px;
  font-weight: 400;
  padding: 5px 38px 6px;
  border-radius: 100px;
  background: #DA5761;
}

.achievement-title {
  color: #1A1A1A;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  line-height: normal;
  margin: 0;
}

.achievement-description {
  color: #3F3F3F;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  margin: 0;
}

.achievements-divider {
  width: 100%;
  height: 1px;
  background: #CFCFCF;
}

.achievements-note {
  color: #1A1A1A;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  letter-spacing: -0.36px;
  text-align: center;
  margin: 0;
}

/* Company Intro Section */
.company-intro-section {
  width: 100%;
  height: 351px;
  background: #FF6B6B;
  position: relative;
  background-image: url('https://api.builder.io/api/v1/image/assets/TEMP/42f6878f33350b96e3bbff6f048c352a254e8e2e?width=2882');
  background-size: cover;
  background-position: center;
}

.company-intro-overlay {
  background: rgba(77, 77, 77, 0.70);
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 50px;
}

.company-intro-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
  max-width: 600px;
  text-align: center;
}

.company-intro-title {
  color: #FFF;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 40px;
  font-weight: 700;
  line-height: 150%;
  margin: 0;
}

.company-intro-subtitle {
  color: #FFF;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  line-height: 150%;
  margin: 0;
}

.company-intro-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 380px;
  padding: 10px 0;
  border-radius: 10px;
  background: #DA5761;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.company-intro-btn span {
  color: #FFF;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}



@media (max-width: 1850px) {
  .content-image {
    max-height: 650px;
  }

  .content-text {
    max-height: 650px;
  }
}

@media (max-width: 1600px) {
  .content-image {
    max-height: 700px;
  }

  .content-text {
    max-height: 700px;
  }
}

@media (max-width: 1480px) {
    .duties-image {
    max-height: 580px;
  }
}


/* Responsive Design */
@media (max-width: 1280px) {
  .what-is-section {
    padding: 50px 30px !important;
  }
  
  .main-duties-section {
    padding: 50px 30px !important;
  }
  
  .support-section {
    padding: 50px 30px !important;
  }
  
  .achievements-section {
    padding: 50px 30px !important;
  }
  
  .section-header {
    gap: 25px !important;
    margin-bottom: 25px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .section-subtitle {
    font-size: 17px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
  
  .content-subtitle,
  .duties-title {
    font-size: 48px !important;
  }
  
  .content-heading,
  .duties-heading {
    font-size: 25px !important;
  }
  
  .content-description,
  .duties-description,
  .support-description p,
  .achievements-note,
  .duties-list div {
    font-size: 17px !important;
  }
  
  .duties-description {
    margin-top: 40px !important;
  }
  
  .category-title {
    font-size: 22px !important;
  }
  
  .achievement-title {
    font-size: 18px !important;
  }
  
  .achievement-description {
    font-size: 17px !important;
  }
  
  .frame-1321317467-1 {
    flex-direction: column;
    gap: 20px;
  }
  
  .rectangle-3 {
    width: 100%;
    max-width: none;
    order: 2;
  }
  
  .frame-1321317466 {
    order: 1;
  }
  
  .service-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .achievements-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Responsive font sizes for inline-editable */
@media (max-width: 1150px) {
  .inline-editable {
    font-size: 20px !important;
  }
  
  .duties-list .inline-editable {
    font-size: 18px !important;
  }
}

@media (max-width: 1150px) {
  .what-is-section {
    padding: 50px 30px !important;
  }
  
  .main-duties-section {
    padding: 50px 30px !important;
  }
  
  .support-section {
    padding: 50px 30px !important;
  }
  
  .achievements-section {
    padding: 50px 30px !important;
  }
  
  .section-header {
    gap: 25px !important;
    margin-bottom: 25px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .section-subtitle {
    font-size: 17px !important;
  }
  
  .content-subtitle,
  .duties-title {
    font-size: 48px !important;
  }
  
  .content-heading,
  .duties-heading {
    font-size: 25px !important;
  }
  
  .content-description,
  .duties-description,
  .support-description p,
  .achievements-note,
  .duties-list div {
    font-size: 17px !important;
  }
  
  .duties-description {
    margin-top: 40px !important;
  }
  
  .duties-list .inline-editable {
    font-size: 17px !important;
  }
  
  .service-title {
    font-size: 17px !important;
  }
  
  .category-title {
    font-size: 22px !important;
  }
  
  .achievement-title {
    font-size: 18px !important;
  }
  
  .achievement-description {
    font-size: 17px !important;
  }
  
  .frame-1321317467-1 {
    flex-direction: column;
    gap: 20px;
  }
  
  .rectangle-3 {
    width: 100%;
    max-width: none;
    order: 2;
  }
  
  .frame-1321317466 {
    order: 1;
  }

  .duties-content {
    gap: 25px !important;
  }
}

@media (max-width: 900px) {
  .cri-consulting-page {
    overflow-x: hidden;
  }
  
  .what-is-section {
    padding: 30px 20px !important;
  }
  
  .main-duties-section {
    padding: 30px 20px !important;
  }
  
  .support-section {
    padding: 30px 20px !important;
  }
  
  .achievements-section {
    padding: 30px 20px !important;
  }
  
  .section-header {
    gap: 22px !important;
    margin-bottom: 22px !important;
  }
  
  .section-title {
    font-size: 29px !important;
  }
  
  .section-subtitle {
    font-size: 16px !important;
  }
  
  .divider-text {
    font-size: 17px !important;
  }
  
  .content-subtitle,
  .duties-title {
    font-size: 42px !important;
  }
  
  .content-heading,
  .duties-heading {
    font-size: 23px !important;
  }
  
  .content-description,
  .duties-description,
  .support-description p,
  .achievements-note,
  .duties-list div {
    font-size: 16px !important;
  }
  
  .duties-description {
    margin-top: 30px !important;
  }
  
  .duties-list .inline-editable {
    font-size: 16px !important;
  }
  
  .service-title {
    font-size: 16px !important;
  }
  
  .category-title {
    font-size: 20px !important;
  }
  
  .achievement-title {
    font-size: 17px !important;
  }
  
  .achievement-description {
    font-size: 16px !important;
  }
  
  .content-container,
  .duties-container {
    display: flex !important;
    flex-direction: column;
  }
  
  .content-image,
  .duties-image {
    width: 100%;
    border-radius: 20px 20px 0 0;
  }
  
  .duties-container {
    display: flex !important;
    flex-direction: column-reverse;
  }
  
  .duties-content {
    width: 100% !important;
    border-radius: 0 0 20px 20px;
  }
  
  .duties-image {
    height: 300px !important;
  }
  
  .content-image {
    height: 300px !important;
  }
  
  .content-text {
    width: 100% !important;
    border-radius: 0 0 20px 20px !important;
  }
  
  .service-grid,
  .achievements-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .access-map {
    width: 100%;
  }
}

@media (max-width: 768px) {
  .what-is-section {
    padding: 30px 20px !important;
  }
  
  .main-duties-section {
    padding: 30px 20px !important;
  }
  
  .support-section {
    padding: 30px 20px !important;
  }
  
  .achievements-section {
    padding: 30px 20px !important;
  }
  
  .section-header {
    gap: 20px !important;
    margin-bottom: 20px !important;
  }
  
  .section-title {
    font-size: 27px !important;
  }
  
  .section-subtitle {
    font-size: 15px !important;
  }
  
  .divider-text {
    font-size: 16px !important;
  }
  
  .content-subtitle,
  .duties-title {
    font-size: 38px !important;
  }
  
  .content-heading,
  .duties-heading {
    font-size: 22px !important;
  }
  
  .content-description,
  .duties-description,
  .support-description p,
  .achievements-note,
  .duties-list div {
    font-size: 15px !important;
  }
  
  .duties-description {
    margin-top: 25px !important;
  }
  
  .duties-list .inline-editable {
    font-size: 15px !important;
  }
  
  .service-title {
    font-size: 15px !important;
  }
  
  .category-title {
    font-size: 19px !important;
  }
  
  .achievement-title {
    font-size: 16px !important;
  }
  
  .achievement-description {
    font-size: 15px !important;
  }
  
  .service-grid,
  .achievements-grid {
    grid-template-columns: 1fr;
  }
  
  .cta-btn {
    width: 100%;
    padding: 15px 20px;
  }
  
  .content-text,
  .duties-content {
    padding: 30px 20px;
  }

  .content-text,
  .duties-content {
    gap: 20px;
  }
  
  .support-content,
  .achievements-content {
    padding: 30px 20px;
    gap: 30px;
  }
  
  .content-image {
    height: 300px !important;
  }
  
  .duties-image {
    height: 300px !important;
  }
}

@media (max-width: 480px) {
  .what-is-section {
    padding: 20px 15px !important;
  }
  
  .main-duties-section {
    padding: 20px 15px !important;
  }
  
  .support-section {
    padding: 20px 15px !important;
  }
  
  .achievements-section {
    padding: 20px 15px !important;
  }
  
  .section-header {
    gap: 18px !important;
    margin-bottom: 18px !important;
  }
  
  .section-title {
    font-size: 22px !important;
  }
  
  .section-subtitle {
    font-size: 13px !important;
  }
  
  .divider-text {
    font-size: 13px !important;
  }
  
  .content-subtitle,
  .duties-title {
    font-size: 25px !important;
  }
  
  .content-heading,
  .duties-heading {
    font-size: 18px !important;
  }
  
  .content-description,
  .duties-description,
  .support-description p,
  .achievements-note,
  .duties-list div {
    font-size: 13px !important;
  }
  
  .duties-description {
    margin-top: 20px !important;
  }
  
  .duties-list .inline-editable {
    font-size: 13px !important;
  }
  
  .service-title {
    font-size: 13px !important;
  }
  
  .category-title {
    font-size: 18px !important;
  }
  
  .achievement-title {
    font-size: 15px !important;
  }
  
  .achievement-description {
    font-size: 13px !important;
  }
  
  .content-text,
  .duties-content {
    padding: 20px 15px;
  }
  
  .support-content,
  .achievements-content {
    padding: 20px 15px;
    gap: 30px;
  }
  
  .content-image {
    height: 200px !important;
  }
  
  .duties-image {
    height: 200px !important;
  }
  
  .service-grid,
  .achievements-grid {
    gap: 15px;
  }
  
  .contact-btn {
    width: 100%;
    max-width: 300px;
  }
}
</style>
