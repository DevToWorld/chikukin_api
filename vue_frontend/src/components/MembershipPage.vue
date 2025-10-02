<template>
  <div class="membership-page">
    <!-- Navigation -->
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      :cms-page-key="cmsKey"
      heroImage="/img/Image_fx7.jpg"
      mediaKey="hero_membership"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <!-- CMS Preview Body under hero when preview/edit flags present -->
    <section class="introduction-section" v-if="isEditPreview">
      <div class="container">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>
    </section>

    <!-- Introduction Section -->
    <section class="introduction-section" v-if="!isEditPreview">
      <div class="container">
        <div class="intro-content">
          <h2 class="intro-title">
            <CmsText :pageKey="cmsKey" fieldKey="intro_title" tag="span" :fallback="'ご入会に際しまして'" />
          </h2>
          <p class="intro-text">
            <CmsText :pageKey="cmsKey" fieldKey="intro_text" tag="span" :fallback="'ちくぎん地域経済研究所では各種サービスを気軽にご利用いただけるよう、会員制度「ちくぎん地域経済クラブ」を設けております。会員の皆さまには地域経済の情報誌「ちくぎん地域経済レポート」をお届けするとともに、企業経営に関する各種サービスの提供や講演会・セミナーの案内など、御社のビジネスをバックアップします。'" />
          </p>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" v-if="!isEditPreview">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText :pageKey="cmsKey" fieldKey="services_title" tag="span" :fallback="'主な会員が受けられるサービス内容'" />
          </h2>
          <div class="section-decoration">
            <div class="decoration-line"></div>
            <span class="decoration-text">
              <CmsText :pageKey="cmsKey" fieldKey="services_label" tag="span" :fallback="'service'" />
            </span>
            <div class="decoration-line"></div>
          </div>
        </div>

        <div class="services-content">
          <!-- Premium Services (hidden on standard-only view) -->
          <div class="service-category" v-if="!isStandardView">
            <h3 class="category-title">
              <CmsText :pageKey="cmsKey" fieldKey="premium_category_title" tag="span" :fallback="'プレミアサービス'" />
            </h3>
            <div class="service-grid">
              <div class="service-card">
                <img :src="getImage('premium_service1_image', 'https://api.builder.io/api/v1/image/assets/TEMP/ce302535d023990b1eca162e1afd7a0f8380e1dc?width=800')" alt="日経トップリーダー" class="service-image" />
                <div class="service-overlay">
                  <span class="service-tag">
                    <CmsText :pageKey="cmsKey" fieldKey="premium_service1_tag" tag="span" :fallback="'consultation'" />
                  </span>
                  <h4 class="service-name">
                    <CmsText :pageKey="cmsKey" fieldKey="premium_service1_name" tag="span" :fallback="'日経トップリーダー'" />
                  </h4>
                </div>
              </div>
            </div>
          </div>

          <div class="section-divider"></div>

          <!-- Standard & Premium Services (or Standard only on standard view) -->
          <div class="service-category">
            <h3 class="category-title">
              <CmsText :pageKey="cmsKey" :fieldKey="isStandardView ? 'standard_category_title' : 'standard_premium_category_title'" tag="span" :fallback="isStandardView ? 'スタンダードサービス' : 'スタンダード　&　プレミアサービス'" />
            </h3>
            <div class="service-grid">
              <div class="service-card">
                <img :src="getImage('standard_service1_image', 'https://api.builder.io/api/v1/image/assets/TEMP/bd8bbc9de4c9de4eb59eee7bc0cdac4b87af7876?width=800')" alt="ビジネスセミナー" class="service-image" />
                <div class="service-overlay">
                  <span class="service-tag">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service1_tag" tag="span" :fallback="'consultation'" />
                  </span>
                  <h4 class="service-name">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service1_name" tag="span" :fallback="'ビジネスセミナー'" />
                  </h4>
                </div>
              </div>
              <div class="service-card">
                <img :src="getImage('standard_service2_image', 'https://api.builder.io/api/v1/image/assets/TEMP/960b36fb39767fc67e6b4a40be6051bee73138a8?width=800')" alt="マッチング" class="service-image" />
                <div class="service-overlay">
                  <span class="service-tag">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service2_tag" tag="span" :fallback="'problem'" />
                  </span>
                  <h4 class="service-name">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service2_name" tag="span" :fallback="'マッチング'" />
                  </h4>
                </div>
              </div>
              <div class="service-card">
                <img :src="getImage('standard_service3_image', 'https://api.builder.io/api/v1/image/assets/TEMP/33c870bec0d0bf29bf0782915d3a6f8fe1f96623?width=800')" alt="経済統計指標DL" class="service-image" />
                <div class="service-overlay">
                  <span class="service-tag">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service3_tag" tag="span" :fallback="'problem'" />
                  </span>
                  <h4 class="service-name">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service3_name" tag="span" :fallback="'経済統計指標DL'" />
                  </h4>
                </div>
              </div>
              <div class="service-card">
                <img :src="getImage('standard_service4_image', 'https://api.builder.io/api/v1/image/assets/TEMP/3a52b313c631d4d2f82f6039afa3a0b86b3c4f1e?width=800')" alt="地域経済統計レポート閲覧" class="service-image" />
                <div class="service-overlay">
                  <span class="service-tag">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service4_tag" tag="span" :fallback="'consultation'" />
                  </span>
                  <h4 class="service-name">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service4_name" tag="span" :fallback="'地域経済統計レポート閲覧'" />
                  </h4>
                </div>
              </div>
              <div class="service-card">
                <img :src="getImage('standard_service5_image', 'https://api.builder.io/api/v1/image/assets/TEMP/4096c08b8615d6b9ef2adeb87f0b1f03ccb58429?width=800')" alt="経営相談" class="service-image" />
                <div class="service-overlay">
                  <span class="service-tag">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service5_tag" tag="span" :fallback="'problem'" />
                  </span>
                  <h4 class="service-name">
                    <CmsText :pageKey="cmsKey" fieldKey="standard_service5_name" tag="span" :fallback="'経営相談'" />
                  </h4>
                </div>
              </div>
            </div>
          </div>

          <div class="section-divider"></div>

          <!-- Membership Information -->
          <div class="membership-info">
          <h3 class="membership-info-title">
            <CmsText :pageKey="cmsKey" fieldKey="membership_info_title" tag="span" :fallback="'会員サービスについて'" />
          </h3>
            <div class="membership-table">
              <div class="table-row table-row-top">
                <div class="table-label">
                  <CmsText :pageKey="cmsKey" fieldKey="membership_benefits_label" tag="span" :fallback="'会員のメリット'" />
                </div>
                <div class="table-content">
                  <CmsText :pageKey="cmsKey" fieldKey="membership_benefits_text" tag="div" :fallback="'ちくぎん地域経済研究所では、会員の皆さまに、企業経営に必要な各種情報、人材育成のための各種サービスを提供するとともに、経営相談に応じています。会員の皆さまは、各種サービスを無料または会員価格でご利用になれます。'" />
                </div>
              </div>
              <div class="table-row">
                <div class="table-label">
                  <CmsText :pageKey="cmsKey" fieldKey="membership_fee_label" tag="span" :fallback="'月会費'" />
                </div>
                <div class="table-content">
                  <CmsText :pageKey="cmsKey" fieldKey="membership_fee_text" tag="div" :fallback="'・スタンダード1,000 円（税別）・プレミアム3,000円（税別）'" />
                </div>
              </div>
            </div>
            <p class="membership-description">
              さらに、プレミアムネット会員サービスでは、ビジネスチャンスが増える事業所紹介ページ作成、ビジネスツールと情報を一箇所に集めた「プレミアムサイト CRI+」がご利用いただけます。まずはお気軽にお問い合わせください。
            </p>
          </div>

          <!-- Button -->
          <button class="filter-download-btn" @click="goToPremiumMembership">プレミアム会員の特典詳細
          <div class="icon-box">
            <svg class="arrow-icon" width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="23" height="23" rx="5" fill="white"/>
              <path d="M17.5302 11.9415L13.0581 16.5412C12.9649 16.6371 12.8384 16.691 12.7066 16.691C12.5747 16.691 12.4482 16.6371 12.355 16.5412C12.2618 16.4453 12.2094 16.3152 12.2094 16.1796C12.2094 16.044 12.2618 15.9139 12.355 15.818L15.9793 12.0909L6.2469 12.0909C6.11511 12.0909 5.98872 12.0371 5.89554 11.9413C5.80235 11.8454 5.75 11.7154 5.75 11.5799C5.75 11.4443 5.80235 11.3143 5.89554 11.2185C5.98872 11.1226 6.11511 11.0688 6.2469 11.0688L15.9793 11.0688L12.355 7.34171C12.2618 7.24581 12.2094 7.11574 12.2094 6.98012C12.2094 6.84449 12.2618 6.71443 12.355 6.61853C12.4482 6.52263 12.5747 6.46875 12.7066 6.46875C12.8384 6.46875 12.9649 6.52263 13.0581 6.61853L17.5302 11.2183C17.5764 11.2657 17.613 11.3221 17.638 11.3841C17.6631 11.4462 17.6759 11.5127 17.6759 11.5799C17.6759 11.647 17.6631 11.7135 17.638 11.7756C17.613 11.8376 17.5764 11.894 17.5302 11.9415Z" fill="#1A1A1A"/>
            </svg>
          </div>
        </button>

        </div>
      </div>
    </section>

    <!-- Flow Section -->
    <section class="flow-section" v-if="!isEditPreview">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText :pageKey="cmsKey" fieldKey="flow_title" tag="span" :fallback="'入会までの流れ'" />
          </h2>
          <div class="section-decoration">
            <div class="decoration-line"></div>
            <span class="decoration-text">
              <CmsText :pageKey="cmsKey" fieldKey="flow_label" tag="span" :fallback="'flow of joining'" />
            </span>
            <div class="decoration-line"></div>
          </div>
        </div>

        <div class="flow-content">
          <div class="flow-steps">
            <div class="flow-step">
              <div class="step-number">STEP1</div>
              <div class="step-content">
                <h4 class="step-title">
                  <CmsText :pageKey="cmsKey" fieldKey="step1_title" tag="span" :fallback="'当事務所、もしくは最寄りの筑邦銀行の窓口でお申し込み'" />
                </h4>
                <p class="step-description">
                  <CmsText :pageKey="cmsKey" fieldKey="step1_desc" tag="span" :fallback="'入会申込書に必要事項をご記入いただきましたら、当研究所・筑邦銀行の窓口にて直接ご提出ください。<br>・筑邦銀行とのお取引がない方もお申し込みいただけます。'" />
                </p>
              </div>
            </div>
            <div class="flow-step">
              <div class="step-number">STEP2</div>
              <div class="step-content">
                <h4 class="step-title">
                  <CmsText :pageKey="cmsKey" fieldKey="step2_title" tag="span" :fallback="'入会に際しての簡単な審査'" />
                </h4>
                <p class="step-description">
                  <CmsText :pageKey="cmsKey" fieldKey="step2_desc" tag="span" :fallback="'※審査完了後、当事務所よりメールで審査結果が届きます。'" />
                </p>
              </div>
            </div>
            <div class="flow-step">
              <div class="step-number">STEP3</div>
              <div class="step-content">
                <h4 class="step-title">
                  <CmsText :pageKey="cmsKey" fieldKey="step3_title" tag="span" :fallback="'審査完了後、当事務所指定の銀行口座へ月会費のお支払い'" />
                </h4>
              </div>
            </div>
            <div class="flow-step">
              <div class="step-number">STEP4</div>
              <div class="step-content">
                <h4 class="step-title">
                  <CmsText :pageKey="cmsKey" fieldKey="step4_title" tag="span" :fallback="'支払い完了後、当事務所での確認作業が完了し次第、当事務所より、お客様の会員IDとパスワードを発行し、メールにてご連絡させていただきます。'" />
                </h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <ActionButton 
      :primary-text="ctaPrimaryText"
      :secondary-text="ctaSecondaryText"
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
import Navigation from "./Navigation.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import AccessSection from "./AccessSection.vue";
import ContactSection from "./ContactSection.vue";
import Footer from "./Footer.vue";
import Group27 from "./Group27";
import FixedSideButtons from "./FixedSideButtons.vue";
import ActionButton from "./ActionButton.vue";
import HeroSection from "./HeroSection.vue";
import { frame132131753022Data } from "../data";
import { usePageText } from '@/composables/usePageText'
import CmsBlock from './CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'

export default {
  name: "MembershipPage",
  components: {
    Navigation,
    Breadcrumbs,
    AccessSection,
    ContactSection,
    Footer,
    Group27,
    FixedSideButtons,
    ActionButton,
    HeroSection,
    CmsBlock,
    CmsText,
  },
  data() {
    return {
      frame132131753022Props: frame132131753022Data,
    };
  },
  computed: {
    isStandardView() {
      return this.cmsKey === 'standard-membership'
    },
    cmsKey() {
      const name = this.$route && this.$route.name
      const path = (this.$route && this.$route.path || '').toLowerCase()
      if (name === 'standardMembership' || path.includes('/membership/standard')) return 'standard-membership'
      return 'membership'
    },
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', this.cmsKey === 'standard-membership' ? 'スタンダード会員' : '入会案内') || (this.cmsKey === 'standard-membership' ? 'スタンダード会員' : '入会案内') },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', this.cmsKey === 'standard-membership' ? 'standard membership' : 'membership') || (this.cmsKey === 'standard-membership' ? 'standard membership' : 'membership') },
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
    try {
      this._pageText = usePageText(this.cmsKey)
      this._pageText.load({ force: true })
    } catch(e) { /* noop */ }
    // Ensure media registry for responsive images and per-page overrides
    import('@/composables/usePageMedia').then(mod => {
      try {
        const { usePageMedia } = mod
        this._pageMedia = usePageMedia()
        this._pageMedia.ensure(this.cmsKey)
        this._media = this._pageMedia._media
        // Watch for registry updates to reflect changes immediately (incl. mobile)
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
      } catch(_) {}
    })
  },
  watch: {
    cmsKey(newKey, oldKey) {
      if (newKey && newKey !== oldKey) {
        try {
          this._pageText = usePageText(newKey)
          this._pageText.load({ force: true })
        } catch(e) { /* noop */ }
      }
    }
  },
  methods: {
    getImage(key, fallback = '') {
      // 1) Page-managed images first (cache-busted, ensures admin updates reflect instantly)
      try {
        const page = this._pageText?.page?.value
        const images = page?.content?.images || {}
        if (Object.prototype.hasOwnProperty.call(images, key)) {
          const v = images[key]
          if (typeof v === 'string' && v) return v
          if (v && typeof v === 'object' && v.url) {
            let url = v.url
            try {
              if (url.startsWith('/storage/') && v.uploaded_at) {
                const ver = Date.parse(v.uploaded_at) || Date.now()
                url += (url.includes('?') ? '&' : '?') + '_t=' + encodeURIComponent(String(ver))
              }
            } catch (_) {}
            return url
          }
        }
      } catch (_) {}
      // 2) Per-page media registry (supports responsive variants, e.g., _mobile)
      try {
        if (this._pageMedia) {
          const v = this._pageMedia.getResponsiveSlot(key, key, fallback)
          if (v) return v
        }
        if (this._media) {
          if (this._media.getResponsiveImage) return this._media.getResponsiveImage(key, fallback) || fallback
          if (this._media.getImage) return this._media.getImage(key, fallback) || fallback
        }
      } catch (_) {}
      return fallback
    },
    handleContactClick() {
      const link = this._pageText?.getLink('cta_primary', '/contact') || '/contact'
      this.$router.push(link);
    },
    handleJoinClick() {
      const link = this._pageText?.getLink('cta_secondary', '/membership/apply') || '/membership/apply'
      this.$router.push(link);
    },
    goToPremiumMembership() {
      this.$router.push('/premium-membership');
    }
  }
};
</script>

<style scoped>

.membership-page {
  background-color: #ECECEC;
  min-height: 100vh;
}

/* Hero Section - 削除済み（HeroSection.vueコンポーネントを使用） */

/* Introduction Section */
.introduction-section {
  padding: 70px 50px 50px 50px;
}

.container {
  max-width: 2000px;
  margin: 0 auto;
}

.intro-content {
  background: #FFFFFF;
  border-radius: 20px;
  padding: 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
}

.intro-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
  line-height: 1.5;
  text-align: center;
}

.intro-text {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
  text-align: left;
}

/* Services Section */
.services-section {
  padding: 50px;
}

.section-header {
  text-align: center;
  margin-bottom: 40px;
}

.section-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 36px;
  font-weight: 700;
  line-height: normal;
  letter-spacing: -0.72px;
  margin-bottom: 29px;
}

.section-decoration {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
}

.decoration-line {
  width: 69px;
  height: 2px;
  background: #DA5761;
}

.decoration-text {
  color: #DA5761;
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
  letter-spacing: 0em;
}

.services-content {
  background: #FFFFFF;
  border-radius: 20px;
  padding: 50px;
}

.service-category {
  margin-bottom: 50px;
}

.category-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 24px;
  font-weight: 700;
  line-height: normal;
  margin-bottom: 20px;
}

.service-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 10px;
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

.service-overlay {
  position: absolute;
  bottom: 0;
  right: 0;
  background: #FFF;
  padding: 13px;
  border-radius: 10px 0 0 0;
  width: 85%;
}

.service-tag {
  color: #DA5761;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 400;
  display: block;
  margin-bottom: 5px;
}

.service-name {
  color: #3F3F3F;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 700;
  margin: 0;
}



.section-divider {
  width: 100%;
  height: 1px;
  background: #CFCFCF;
  margin: 50px 0;
}

/* Membership Info */
.membership-info {
  background: #ECECEC;
  border-radius: 20px;
  padding: 30px;
}

.membership-info-title {
  color: #1A1A1A;
  font-family: var(--font-family-inter);
  font-size: 24px;
  font-weight: 700;
  line-height: normal;
  margin-bottom: 20px;
}

.membership-table {
  border-top: 0.5px dashed #B0B0B0;
  margin-bottom: 20px;
}

.table-row {
  display: flex;
  align-items: center;
  gap: 30px;
  padding: 15px 0;
  border-bottom: 0.5px dashed #B0B0B0;
}

.table-row-top {
  align-items: flex-start;
}

.table-label {
  background: #727272;
  color: #FFFFFF;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
  padding: 5px 10px;
  border-radius: 5px;
  width: 200px;
  text-align: center;
  flex-shrink: 0;
}

.table-content {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
  flex: 1;
}

.membership-description {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: normal;
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
  margin: 50px auto 0px;
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


.membership-action-btn {
  display: flex;
  width: 300px;
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
  margin: 0 auto 30px;
}

.membership-action-btn:hover {
  background: var(--color-secondary);
}



/* Flow Section */
.flow-section {
  padding: 50px;
}

.flow-content {
  background: #FFFFFF;
  border-radius: 20px;
  padding: 50px;
}

.flow-steps {
  border-top: 0.5px dashed #DA5761;
}

.flow-step {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px 0;
  border-bottom: 0.5px dashed #DA5761;
  min-height: 125px;
}

.step-number {
  color: #DA5761;
  font-family: var(--font-family-inter);
  font-size: 36px;
  font-weight: 700;
  line-height: 1.5;
  width: 150px;
  flex-shrink: 0;
}

.step-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.step-title {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
}

.step-description {
  color: #3F3F3F;
  font-family: var(--font-family-inter);
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
}



/* Company Banner Section */
.company-banner-section {
  height: 351px;
  background: #FF6B6B;
  position: relative;
  overflow: hidden;
}

.company-banner-section::before {
  content: '';
  position: absolute;
  top: -225px;
  left: 0;
  width: 100%;
  height: 706px;
  background: url('https://api.builder.io/api/v1/image/assets/TEMP/42f6878f33350b96e3bbff6f048c352a254e8e2e?width=2882') center/cover no-repeat;
}

.company-banner-section::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(77, 77, 77, 0.70);
}

.banner-content {
  position: relative;
  z-index: 2;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 420px;
}

.banner-text {
  text-align: center;
  color: #FFFFFF;
}

.banner-subtitle {
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 8px;
}

.banner-title {
  font-family: var(--font-family-inter);
  font-size: 40px;
  font-weight: 700;
  line-height: 1.5;
  margin-bottom: 43px;
}

.banner-description {
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
  line-height: 1.5;
  margin-bottom: 33px;
}

.banner-button {
  background: #DA5761;
  border: none;
  border-radius: 10px;
  color: #FFFFFF;
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 700;
  line-height: 1.5;
  padding: 10px 20px;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

/* Responsive Design */
@media (max-width: 1280px) {
  .service-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
}

@media (max-width: 1150px) {
  .introduction-section {
    padding: 50px 30px !important;
  }
  
  .services-section {
    padding: 50px 30px !important;
  }
  
  .flow-section {
    padding: 50px 30px !important;
  }
  
  .intro-content {
    padding: 30px 20px !important;
    gap: 35px !important;
  }
  
  .services-content {
    padding: 30px 20px !important;
  }
  
  .flow-content {
    padding: 30px 20px !important;
  }
  
  .section-title {
    font-size: 32px !important;
    margin-bottom: 25px !important;
  }
  
  .section-header {
    margin-bottom: 25px !important;
  }
  
  .category-title {
    font-size: 22px !important;
  }
  
  .membership-info-title {
    font-size: 22px !important;
  }
  
  .decoration-text {
    font-size: 18px !important;
  }
  
  .intro-title {
    font-size: 18px !important;
  }
  
  .intro-text {
    font-size: 18px !important;
  }
  
  .service-name {
    font-size: 18px !important;
  }
  
  .step-title {
    font-size: 18px !important;
  }
  
  .step-description {
    font-size: 18px !important;
  }
  
  .table-label {
    font-size: 18px !important;
  }
  
  .table-content {
    font-size: 18px !important;
  }
  
  .membership-description {
    font-size: 18px !important;
  }
  
  .step-number {
    font-size: 32px !important;
  }
  
  .service-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
  
  .flow-step {
    min-height: 110px !important;
  }
}

@media (max-width: 900px) {
  .introduction-section {
    padding: 30px 20px !important;
  }
  
  .services-section {
    padding: 30px 20px !important;
  }
  
  .flow-section {
    padding: 30px 20px !important;
  }
  
  .intro-content {
    padding: 35px !important;
    gap: 30px !important;
  }
  
  .services-content {
    padding: 30px 20px !important;
  }
  
  .flow-content {
    padding: 30px 20px !important;
  }
  
  .section-title {
    font-size: 29px !important;
    margin-bottom: 22px !important;
  }
  
  .section-header {
    margin-bottom: 22px !important;
  }
  
  .category-title {
    font-size: 20px !important;
  }
  
  .membership-info-title {
    font-size: 20px !important;
  }
  
  .decoration-text {
    font-size: 17px !important;
  }
  
  .intro-title {
    font-size: 17px !important;
  }
  
  .intro-text {
    font-size: 17px !important;
  }
  
  .service-name {
    font-size: 17px !important;
  }
  
  .step-title {
    font-size: 17px !important;
  }
  
  .step-description {
    font-size: 17px !important;
  }
  
  .table-label {
    font-size: 17px !important;
  }
  
  .table-content {
    font-size: 17px !important;
  }
  
  .membership-description {
    font-size: 17px !important;
  }
  
  .step-number {
    font-size: 29px !important;
  }
  
  .flow-step {
    min-height: 100px !important;
  }
  
  .section-divider {
    margin: 40px 0 !important;
  }
}

@media (max-width: 768px) {
  .introduction-section {
    padding: 30px 20px !important;
  }
  
  .services-section {
    padding: 30px 20px !important;
  }
  
  .flow-section {
    padding: 30px 20px !important;
  }
  
  .intro-content {
    padding: 30px !important;
    gap: 25px !important;
  }
  
  .services-content {
    padding: 30px 20px !important;
  }
  
  .flow-content {
    padding: 30px 20px !important;
  }
  
  .section-title {
    font-size: 27px !important;
    margin-bottom: 20px !important;
  }
  
  .section-header {
    margin-bottom: 20px !important;
  }
  
  .category-title {
    font-size: 19px !important;
  }
  
  .membership-info-title {
    font-size: 19px !important;
  }
  
  .decoration-text {
    font-size: 16px !important;
  }
  
  .intro-title {
    font-size: 16px !important;
  }
  
  .intro-text {
    font-size: 16px !important;
  }
  
  .service-name {
    font-size: 16px !important;
  }
  
  .step-title {
    font-size: 16px !important;
  }
  
  .step-description {
    font-size: 16px !important;
  }
  
  .table-label {
    font-size: 16px !important;
  }
  
  .table-content {
    font-size: 16px !important;
  }
  
  .membership-description {
    font-size: 16px !important;
  }
  
  .step-number {
    font-size: 27px !important;
  }
  
  .service-grid {
    grid-template-columns: 1fr !important;
  }
  
  .flow-step {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 15px !important;
    min-height: auto !important;
    padding: 20px 0 !important;
  }
  
  .step-number {
    width: auto !important;
  }
  
  .section-divider {
    margin: 30px 0 !important;
  }
  
  .membership-info {
    padding: 25px !important;
  }
  
  .filter-download-btn {
    margin: 40px auto 0px !important;
    padding: 12px 40px !important;
    font-size: 14px !important;
  }
  
  .table-row,
  .table-row-top {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 15px !important;
  }
  
  .table-label {
    width: 150px !important;
    text-align: left !important;
  }
  
  .table-content {
    width: 100% !important;
  }
}

@media (max-width: 480px) {
  .introduction-section {
    padding: 20px 15px !important;
  }
  
  .services-section {
    padding: 20px 15px !important;
  }
  
  .flow-section {
    padding: 20px 15px !important;
  }
  
  .intro-content {
    padding: 20px !important;
    gap: 20px !important;
  }
  
  .services-content {
    padding: 20px 15px !important;
  }
  
  .flow-content {
    padding: 20px 15px !important;
  }
  
  .section-title {
    font-size: 22px !important;
    margin-bottom: 18px !important;
  }
  
  .section-header {
    margin-bottom: 18px !important;
  }
  
  .category-title {
    font-size: 18px !important;
  }
  
  .membership-info-title {
    font-size: 18px !important;
  }
  
  .decoration-text {
    font-size: 13px !important;
  }
  
  .intro-title {
    font-size: 13px !important;
  }
  
  .intro-text {
    font-size: 13px !important;
  }
  
  .service-name {
    font-size: 13px !important;
  }
  
  .step-title {
    font-size: 13px !important;
  }
  
  .step-description {
    font-size: 13px !important;
  }
  
  .table-label {
    font-size: 13px !important;
  }
  
  .table-content {
    font-size: 13px !important;
  }
  
  .membership-description {
    font-size: 13px !important;
  }
  
  .step-number {
    font-size: 22px !important;
  }
  
  .flow-step {
    padding: 15px 0 !important;
    gap: 12px !important;
  }
  
  .section-divider {
    margin: 20px 0 !important;
  }
  
  .membership-info {
    padding: 15px !important;
  }
  
  .filter-download-btn {
    margin: 30px auto 0px !important;
    padding: 10px 30px !important;
    font-size: 12px !important;
  }
  
  .decoration-line {
    width: 50px !important;
  }
  
  .section-decoration {
    gap: 10px !important;
  }
  
  .service-overlay {
    padding: 10px !important;
    height: 60px !important;
  }
  
  .table-row {
    gap: 20px !important;
    padding: 12px 0 !important;
  }
  
  .table-label {
    width: 150px !important;
    padding: 4px 8px !important;
  }
}
</style>
