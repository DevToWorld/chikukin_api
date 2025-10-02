<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <div class="hero-section" :style="heroBackgroundStyle">
      <div class="hero-overlay">
        <div class="hero-content">
          <h1 class="hero-title">
            <CmsText pageKey="about" fieldKey="page_title" tag="span" :fallback="pageTitle || '会社概要'" />
          </h1>
          <p class="hero-subtitle">
            <CmsText pageKey="about" fieldKey="page_subtitle" tag="span" :fallback="pageSubtitle || ''" />
          </p>
        </div>
      </div>
    </div>

    <!-- CMS Preview Body: render full HTML under hero when preview/edit flags present -->
    <section class="section" v-if="isEditPreview">
      <div class="container">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>
    </section>

    <!-- Our Mission Section (hidden during preview/edit) -->
    <section class="section mission-section">
      <div class="container" v-if="!isEditPreview">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText pageKey="about" fieldKey="philosophy_title" tag="span" :fallback="'経営理念'" />
          </h2>
          <p class="section-subtitle">
            <CmsText pageKey="about" fieldKey="philosophy_subtitle" tag="span" :fallback="'OUR MISSION'" />
          </p>
        </div>
        
        <div class="mission-content">
          <div class="mission-image">
            <img :src="getImageUrl('content') || '/img/image-1.png'" alt="ネットワークイメージ" />
          </div>
          <div class="mission-text">
            <h3>
              <CmsText pageKey="about" fieldKey="mission_title" tag="span" :fallback="missionTitle || '産学官金のネットワークを活かした地域創生'" />
            </h3>
            <p>
              <CmsText pageKey="about" fieldKey="mission_text" tag="span" :fallback="missionText || '私たちは、産・官・学・金（金融機関）のネットワークを活用し、地域経済の持続的な発展と地域企業の成長を支援することを使命としています。1991年の設立以来、福岡県久留米市を拠点に、九州地域の経済発展に貢献してまいりました。'" />
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Message Section -->
    <section class="section message-section" v-if="!isEditPreview">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText pageKey="about" fieldKey="message_title" tag="span" :fallback="'ご挨拶'" />
          </h2>
          <p class="section-subtitle">
            <CmsText pageKey="about" fieldKey="message_subtitle" tag="span" :fallback="'MESSAGE'" />
          </p>
        </div>
        
        <div class="message-content">
          <div class="message-text">
            <CmsText
              pageKey="about"
              fieldKey="message_body"
              tag="div"
              type="html"
              :fallback="defaultAboutMessage"
            />
            <CmsText
              pageKey="about"
              fieldKey="message_signature"
              tag="div"
              class="message-signature"
              :fallback="'株式会社ちくぎん地域経済研究所 代表取締役社長'"
            />
          </div>
          <div class="message-image">
            <img :src="getImageUrl('message') || '/img/image-2.png'" alt="代表挨拶" />
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" v-if="!isEditPreview">
      <div class="container">
        <div class="cta-content">
          <h2>
            <CmsText pageKey="about" fieldKey="cta_block_title" tag="span" :fallback="'株式会社ちくぎん地域経済研究所'" />
          </h2>
          <p>
            <CmsText pageKey="about" fieldKey="cta_block_body" tag="span" :fallback="'地域の未来を共に考え、企業の成長をサポートします。'" />
          </p>
          <button class="cta-button" @click="scrollToContact">
            <CmsText pageKey="about" fieldKey="cta_block_button" tag="span" :fallback="'お問い合わせはこちら'" />
          </button>
        </div>
      </div>
    </section>

    <!-- Company Info Section -->
    <section class="section company-section" v-if="!isEditPreview">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText pageKey="about" fieldKey="company_title" tag="span" :fallback="'会社概要'" />
          </h2>
          <p class="section-subtitle">
            <CmsText pageKey="about" fieldKey="company_subtitle" tag="span" :fallback="'COMPANY PROFILE'" />
          </p>
        </div>
        
        <div class="company-table">
          <table>
            <tr>
              <th><CmsText pageKey="about" fieldKey="company_label_name" tag="span" :fallback="'会社名'" /></th>
              <td><CmsText pageKey="about" fieldKey="company_value_name" tag="span" :fallback="'株式会社ちくぎん地域経済研究所'" /></td>
            </tr>
            <tr>
              <th><CmsText pageKey="about" fieldKey="company_label_established" tag="span" :fallback="'設立'" /></th>
              <td><CmsText pageKey="about" fieldKey="company_value_established" tag="span" :fallback="'平成3年4月1日（1991年）'" /></td>
            </tr>
            <tr>
              <th><CmsText pageKey="about" fieldKey="company_label_capital" tag="span" :fallback="'資本金'" /></th>
              <td><CmsText pageKey="about" fieldKey="company_value_capital" tag="span" :fallback="'30,000千円'" /></td>
            </tr>
            <tr>
              <th><CmsText pageKey="about" fieldKey="company_label_address" tag="span" :fallback="'所在地'" /></th>
              <td><CmsText pageKey="about" fieldKey="company_value_address" tag="div" type="html" :fallback="'〒839-0864 福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階'" /></td>
            </tr>
            <tr>
              <th><CmsText pageKey="about" fieldKey="company_label_tel" tag="span" :fallback="'電話番号'" /></th>
              <td><CmsText pageKey="about" fieldKey="company_value_tel" tag="span" :fallback="'TEL：0942-46-5081'" /></td>
            </tr>
            <tr>
              <th><CmsText pageKey="about" fieldKey="company_label_business_hours" tag="span" :fallback="'営業時間'" /></th>
              <td><CmsText pageKey="about" fieldKey="company_value_business_hours" tag="span" :fallback="'平日 9:00～17:00'" /></td>
            </tr>
          </table>
        </div>
      </div>
    </section>

    <!-- History Section -->
    <section class="section history-section" v-if="!isEditPreview">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText pageKey="about" fieldKey="history_title" tag="span" :fallback="'沿革'" />
          </h2>
          <p class="section-subtitle">
            <CmsText pageKey="about" fieldKey="history_subtitle" tag="span" :fallback="'HISTORY'" />
          </p>
        </div>
        
        <div class="history-timeline">
          <div class="timeline-item" v-for="(item, idx) in visibleHistory" :key="idx">
            <div class="timeline-year">{{ item.year }}</div>
            <div class="timeline-content">
              <h4>{{ item.title }}</h4>
              <p v-for="(t, i) in item.texts" :key="i">{{ t }}</p>
            </div>
          </div>
        </div>

        <div class="history-more" v-if="historyItems.length > visibleHistoryCount">
          <button class="more-button" @click="showMoreHistory">
            <CmsText pageKey="about" fieldKey="history_more" tag="span" :fallback="'さらに表示'" />
          </button>
        </div>
      </div>
    </section>

    <!-- Staff Section -->
    <section class="section staff-section" v-if="!isEditPreview">
      <div class="container">
        <div class="section-header">
          <h2 class="section-title">
            <CmsText pageKey="company-profile" fieldKey="staff_title" tag="span" :fallback="'所属紹介'" />
          </h2>
          <p class="section-subtitle">
            <CmsText pageKey="company-profile" fieldKey="staff_subtitle" tag="span" :fallback="'OUR TEAM'" />
          </p>
        </div>
        
        <div class="staff-grid">
          <div class="staff-card">
            <div class="staff-image">
              <img src="/img/image.png" alt="スタッフ1" />
            </div>
            <div class="staff-info">
              <h4><CmsText pageKey="company-profile" fieldKey="staff_kuga_position" tag="span" :fallback="'代表取締役社長'" /></h4>
              <p><CmsText pageKey="company-profile" fieldKey="staff_kuga_name" tag="span" :fallback="'空閑 重信'" /></p>
            </div>
          </div>
          <div class="staff-card">
            <div class="staff-image">
              <img src="/img/image-1.png" alt="スタッフ2" />
            </div>
            <div class="staff-info">
              <h4><CmsText pageKey="company-profile" fieldKey="staff_mizokami_position" tag="span" :fallback="'取締役企画部長　兼調査部長'" /></h4>
              <p><CmsText pageKey="company-profile" fieldKey="staff_mizokami_name" tag="span" :fallback="'溝上 浩文'" /></p>
            </div>
          </div>
          <div class="staff-card">
            <div class="staff-image">
              <img src="/img/image-2.png" alt="スタッフ3" />
            </div>
            <div class="staff-info">
              <h4><CmsText pageKey="company-profile" fieldKey="staff_morita_position" tag="span" :fallback="'企画部　部長代理'" /></h4>
              <p><CmsText pageKey="company-profile" fieldKey="staff_morita_name" tag="span" :fallback="'森田 祥子'" /></p>
            </div>
          </div>
          <div class="staff-card">
            <div class="staff-image">
              <img src="/img/image.png" alt="スタッフ4" />
            </div>
            <div class="staff-info">
              <h4><CmsText pageKey="company-profile" fieldKey="staff_takada_position" tag="span" :fallback="'調査部　主任'" /></h4>
              <p><CmsText pageKey="company-profile" fieldKey="staff_takada_name" tag="span" :fallback="'髙田 友里恵'" /></p>
            </div>
          </div>
          <div class="staff-card">
            <div class="staff-image">
              <img src="/img/image-1.png" alt="スタッフ5" />
            </div>
            <div class="staff-info">
              <h4><CmsText pageKey="company-profile" fieldKey="staff_nakamura_position" tag="span" :fallback="''" /></h4>
              <p><CmsText pageKey="company-profile" fieldKey="staff_nakamura_name" tag="span" :fallback="'中村 公栄'" /></p>
            </div>
          </div>
        </div>

        <div class="staff-more">
          <button class="more-button">
            <CmsText pageKey="about" fieldKey="staff_more" tag="span" :fallback="'続きを見る'" />
          </button>
        </div>
      </div>
    </section>

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer Navigation -->
    <Footer v-bind="frame132131753022Props" />
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";
import Footer from "./Footer.vue";
import Group27 from "./Group27.vue";
import AccessSection from "./AccessSection.vue";
import { frame132131753022Data } from "../data.js";
import CmsBlock from './CmsBlock.vue'
import axios from 'axios';
import { getApiUrl } from '@/config/api';
import { usePageText } from '@/composables/usePageText'
import CmsText from '@/components/CmsText.vue'

export default {
  name: "AboutPage",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    CmsBlock,
    CmsText
  },
  data() {
    return {
      pageKey: 'about',
      pageData: null,
      loading: true,
      error: null,
      // 沿革の「さらに表示」用: 初期は5件表示
      visibleHistoryCount: 5,
      frame132131753022Props: frame132131753022Data,
      defaultAboutMessage: `
        <p>変化の激しい経済環境の中で、企業が持続的な成長を遂げるためには、正確な情報と深い洞察に基づく戦略的な意思決定が不可欠です。</p>
        <p>私たちちくぎん地域経済研究所は、長年培ってきた調査研究の知見と幅広いネットワークを活用し、地域の皆様の課題解決と成長を全力でサポートしてまいります。</p>
        <p>産・官・学・金の連携を深め、地域経済の活性化に貢献することで、九州地域の明るい未来を共に創造していきたいと考えております。</p>
        <div class="message-signature">
          <p>株式会社ちくぎん地域経済研究所</p>
          <p>代表取締役社長</p>
        </div>
      `,
    };
  },
  computed: {
    pageTitle() {
      const fallback = this.pageData?.title || '会社概要'
      return this._pageText?.getText('page_title', fallback) || fallback;
    },
    pageSubtitle() {
      return this._pageText?.getText('page_subtitle', 'ABOUT US') || 'ABOUT US'
    },
    missionTitle() {
      const fallback = this.pageData?.content?.missionTitle || ''
      return this._pageText?.getText('mission_title', fallback) || fallback
    },
    missionText() {
      const fallback = this.pageData?.content?.missionText || ''
      return this._pageText?.getText('mission_body', fallback) || fallback
    },
    heroBackgroundStyle() {
      const heroImage = this.getImageUrl('hero');
      if (heroImage) {
        return {
          background: `linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('${heroImage}') center/cover`
        };
      }
      return {
        background: `linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/img/hero-image.png') center/cover`
      };
    },
    // 沿革データ（CMSにあれば優先）
    historyItems() {
      const fromCms = this.pageData?.content?.history
      if (Array.isArray(fromCms) && fromCms.length) {
        return fromCms.map((it, idx) => ({
          id: it.id || idx,
          year: String(it.year || it.date || ''),
          title: it.title || it.heading || '',
          texts: Array.isArray(it.texts)
            ? it.texts
            : (it.description ? [it.description] : [])
        }))
      }
      // 既存ダミー + 追加で動作確認
      return [
        { year: '1988', title: '調査研究開始', texts: ['筑邦銀行にて地域経済に関する調査研究を開始'] },
        { year: '2010', title: '経営戦略支援', texts: ['企業への経営戦略支援、事業承継サポート、M&Aアドバイザリーを本格化'] },
        { year: '2011', title: 'コンサルティング事業拡大', texts: ['福岡県、佐賀県、長崎県、大分県、熊本県、宮崎県、鹿児島県での調査・研究、コンサルティング事業を展開。', '地域密着型の総合的なサービス提供を開始。'] },
        { year: '2014', title: '新サービス開始', texts: ['データ分析支援サービスの提供を開始'] },
        { year: '2017', title: '地域連携強化', texts: ['地方自治体との共同研究プロジェクトを開始'] },
        { year: '2019', title: 'DX推進', texts: ['リサーチ業務のデジタル化を推進'] },
        { year: '2022', title: '拠点拡充', texts: ['九州内での連携拠点を拡充'] },
      ]
    },
    visibleHistory() {
      return this.historyItems.slice(0, this.visibleHistoryCount)
    },
    isEditPreview() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsPreview') || params.has('cmsEdit')
      } catch (_) { return false }
    }
  },
  async mounted() {
    try {
      const response = await axios.get(getApiUrl('/api/pages/about'));
      this.pageData = response.data;
      this.loading = false;
    } catch (err) {
      console.error('Failed to fetch page data:', err);
      this.error = 'ページデータの取得に失敗しました';
      this.loading = false;
    }
    try {
      this._pageText = usePageText(this.pageKey)
      this._pageText.load()
    } catch(e) { /* noop */ }
  },
  methods: {
    scrollToContact() {
      // お問い合わせページに遷移
      this.$router.push('/contact');
    },
    showMoreHistory() {
      const next = this.visibleHistoryCount + 5
      this.visibleHistoryCount = Math.min(next, this.historyItems.length)
    },
    getImageUrl(type) {
      // 1) ページ管理の画像を最優先
      try {
        const imgs = this._pageText?.page?.value?.content?.images
        const v = imgs && imgs[type]
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

      // 2) 旧APIのpageDataフォールバック
      if (this.pageData?.content?.images?.[type]?.url) {
        const u = this.pageData.content.images[type].url
        if (!u) return null
        if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('//')) return u
        if (u.startsWith('/storage/') || u.startsWith('storage/')) return u.startsWith('/storage/') ? u : `/${u}`
        if (u.startsWith('/img/') || u.startsWith('/images/') || u.startsWith('/assets/') || u.startsWith('/favicon')) return u
        if (u.startsWith('/')) return u
        return `/storage/${u.replace(/^public\//,'')}`
      }
      return null;
    }
  }
};
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-container {
  min-height: 100vh;
  background-color: #ffffff;
}

/* Hero Section */
.hero-section {
  height: 400px;
  background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
              url('/img/hero-image.png') center/cover;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-overlay {
  text-align: center;
  color: white;
}

.hero-title {
  font-size: 3rem;
  font-weight: bold;
  margin-bottom: 10px;
}

.hero-subtitle {
  font-size: 1.2rem;
  letter-spacing: 2px;
  color: #da5761;
}

/* Section Common Styles */
.section {
  padding: 80px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-title {
  font-size: 2.5rem;
  color: #1A1A1A;
  margin-bottom: 10px;
  font-weight: bold;
}

.section-subtitle {
  color: #da5761;
  font-size: 1rem;
  letter-spacing: 2px;
  font-weight: 500;
  position: relative;
  padding-bottom: 20px;
}

.section-subtitle::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 2px;
  background-color: #da5761;
}

/* Mission Section */
.mission-section {
  background-color: #f8f9fa;
}

.mission-content {
  display: grid;
  grid-template-columns: 45% 1fr;
  align-items: stretch;
  gap: 60px;
}

.mission-image {
  height: 100%;
}

.mission-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
  display: block;
}

.mission-text {
  flex: 1;
}

.mission-text h3 {
  font-size: 1.8rem;
  color: #da5761;
  margin-bottom: 20px;
  font-weight: bold;
}

.mission-text p {
  color: #666;
  line-height: 1.8;
  font-size: 1.1rem;
}

/* Message Section */
.message-section {
  background-color: #ffffff;
}

.message-content {
  display: grid;
  grid-template-columns: 1fr 45%;
  align-items: stretch;
  gap: 60px;
}

.message-text {
  flex: 1;
}

.message-text p {
  color: #666;
  line-height: 1.8;
  font-size: 1.1rem;
  margin-bottom: 20px;
}

.message-signature {
  margin-top: 40px;
  text-align: right;
}

.message-signature p {
  color: #1A1A1A;
  font-weight: bold;
  margin-bottom: 5px;
}

.message-image {
  height: 100%;
}

.message-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
  display: block;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, #da5761 0%, #c44853 100%);
  color: white;
  text-align: center;
  padding: 80px 0;
}

.cta-content h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  font-weight: bold;
}

.cta-content p {
  font-size: 1.1rem;
  margin-bottom: 30px;
}

.cta-button {
  background: white;
  color: #da5761;
  border: none;
  padding: 15px 40px;
  font-size: 1.1rem;
  border-radius: 50px;
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
  font-weight: bold;
}

.cta-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Company Section */
.company-section {
  background-color: #f8f9fa;
}

.company-table {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.company-table table {
  width: 100%;
  border-collapse: collapse;
}

.company-table th {
  background-color: #f8f9fa;
  padding: 20px;
  text-align: left;
  width: 200px;
  border-bottom: 1px solid #dee2e6;
  font-weight: bold;
  color: #1A1A1A;
}

.company-table td {
  padding: 20px;
  border-bottom: 1px solid #dee2e6;
  color: #666;
}

/* History Section */
.history-section {
  background-color: #ffffff;
}

.history-timeline {
  max-width: 800px;
  margin: 0 auto;
}

.timeline-item {
  display: flex;
  margin-bottom: 40px;
  position: relative;
}

.timeline-year {
  flex: 0 0 120px;
  font-size: 1.5rem;
  font-weight: bold;
  color: #da5761;
  text-align: right;
  padding-right: 30px;
}

.timeline-content {
  flex: 1;
  padding-left: 30px;
  border-left: 3px solid #da5761;
  position: relative;
}

.timeline-content::before {
  content: '';
  position: absolute;
  left: -8px;
  top: 0;
  width: 14px;
  height: 14px;
  background-color: #da5761;
  border-radius: 50%;
}

.timeline-content h4 {
  color: #1A1A1A;
  font-size: 1.2rem;
  margin-bottom: 10px;
  font-weight: bold;
}

.timeline-content p {
  color: #666;
  line-height: 1.6;
  margin-bottom: 5px;
}

.history-more {
  text-align: center;
  margin-top: 40px;
}

.more-button {
  background: #da5761;
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 25px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s;
}

.more-button:hover {
  background-color: #c44853;
}

/* Staff Section */
.staff-section {
  background-color: #f8f9fa;
}

.staff-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 30px;
  margin-bottom: 40px;
}

.staff-card {
  text-align: center;
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}

.staff-card:hover {
  transform: translateY(-5px);
}

.staff-image {
  width: 120px;
  height: 120px;
  margin: 0 auto 15px;
  overflow: hidden;
  border-radius: 50%;
}

.staff-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.staff-info h4 {
  color: #1A1A1A;
  font-size: 0.9rem;
  margin-bottom: 5px;
  font-weight: bold;
}

.staff-info p {
  color: #666;
  font-size: 1rem;
}

.staff-more {
  text-align: center;
}

/* Access Section */

.access-map {
  flex: 0 0 45%;
}

.access-map img {
  width: 100%;
  height: auto;
  border-radius: 10px;
}

.access-details h4 {
  color: #da5761;
  font-size: 1.2rem;
  margin-bottom: 10px;
  margin-top: 25px;
  font-weight: bold;
}

.access-details h4:first-child {
  margin-top: 0;
}

.access-details p {
  color: #666;
  line-height: 1.6;
  margin-bottom: 15px;
}

.access-details ul {
  list-style: none;
  padding: 0;
}

.access-details li {
  color: #666;
  line-height: 1.6;
  margin-bottom: 8px;
  padding-left: 10px;
}

.access-details a {
  color: #da5761;
  text-decoration: none;
}

.access-details a:hover {
  text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .section {
    padding: 60px 0;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .mission-content,
  .message-content {
    display: block;
  }
  .mission-image,
  .message-image,
  .access-map {
    flex: none;
  }
  
  .staff-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
  
  .timeline-item {
    flex-direction: column;
  }
  
  .timeline-year {
    text-align: left;
    padding-right: 0;
    padding-bottom: 10px;
  }
  
  .timeline-content {
    padding-left: 0;
    border-left: none;
    border-top: 3px solid #da5761;
    padding-top: 15px;
  }
  
  .timeline-content::before {
    left: 0;
    top: -8px;
  }
}

@media (max-width: 480px) {
  .staff-grid {
    grid-template-columns: 1fr;
  }
  
  .container {
    padding: 0 15px;
  }
}


</style>
