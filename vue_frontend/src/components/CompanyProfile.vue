<template>
  <div class="company-profile">
    <navigation />

    <!-- Hero Section -->
    <HeroSection 
      :title="pageTitle"
      :subtitle="pageSubtitle"
      :heroImage="media('hero_company_profile', '/img/Image_fx10.jpg')"
      cmsPageKey="company-profile"
      titleFieldKey="page_title"
      subtitleFieldKey="page_subtitle"
      mediaKey="hero_company_profile"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="[pageTitle]" />

    <!-- CMS Preview Body under hero when preview/edit flags present -->
    <div class="main-content" v-if="isEditPreview">
      <div class="content-container">
        <div class="cms-body" v-html="_pageText?.getHtml('body','')"></div>
      </div>
    </div>

    <!-- In-page Navigation -->
    <nav class="inpage-nav" aria-label="セクション内リンク" v-if="!isEditPreview">
      <ul class="inpage-list">
        <li class="inpage-item">
          <a href="#philosophy" @click.prevent="scrollTo('philosophy')">
            <CmsText pageKey="company-profile" fieldKey="nav_philosophy" tag="span" :fallback="'経営理念'" />
          </a>
        </li>
        <li class="inpage-item">
          <a href="#message" @click.prevent="scrollTo('message')">
            <CmsText pageKey="company-profile" fieldKey="nav_message" tag="span" :fallback="'ご挨拶'" />
          </a>
        </li>
        <li class="inpage-item">
          <a href="#profile" @click.prevent="scrollTo('profile')">
            <CmsText pageKey="company-profile" fieldKey="nav_profile" tag="span" :fallback="'企業概要'" />
          </a>
        </li>
        <li class="inpage-item">
          <a href="#history" @click.prevent="scrollTo('history')">
            <CmsText pageKey="company-profile" fieldKey="nav_history" tag="span" :fallback="'沿革'" />
          </a>
        </li>
        <li class="inpage-item">
          <a href="#staff" @click.prevent="scrollTo('staff')">
            <CmsText pageKey="company-profile" fieldKey="nav_staff" tag="span" :fallback="'所員紹介'" />
          </a>
        </li>
        <li class="inpage-item">
          <a href="#financial-reports" @click.prevent="scrollTo('financial-reports')">
            <CmsText pageKey="company-profile" fieldKey="nav_financial" tag="span" :fallback="'決算報告'" />
          </a>
        </li>
        <li class="inpage-item">
          <a href="#access" @click.prevent="scrollTo('access')">
            <CmsText pageKey="company-profile" fieldKey="nav_access" tag="span" :fallback="'アクセス'" />
          </a>
        </li>
      </ul>
    </nav>

    <!-- CMS Body (optional full HTML override) -->
    <!-- CMS Body removed -->

    <!-- Philosophy Section -->
    <section id="philosophy" class="philosophy-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="company-profile" fieldKey="philosophy_title" tag="span" :fallback="'経営理念'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="company-profile" fieldKey="philosophy_subtitle" tag="span" :fallback="'philosophy'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      <div class="philosophy-content">
        <img class="philosophy-image" :src="media('company_profile_philosophy', 'https://api.builder.io/api/v1/image/assets/TEMP/01501a28725d762f4b766643e9bcd235f2e43e2e?width=1340')" alt="Philosophy" />
        <div class="philosophy-text">
          <CmsText
            pageKey="company-profile"
            fieldKey="mission_label"
            tag="div"
            class="mission-title"
            :fallback="'OUR MISSION'"
          />
          <div class="mission-content">
            <CmsText
              pageKey="company-profile"
              fieldKey="mission_title"
              tag="h3"
              class="mission-heading"
              :fallback="'産官学金のネットワーク活用による地域貢献'"
            />
            <CmsText
              pageKey="company-profile"
              fieldKey="mission_body"
              tag="div"
              type="html"
              class="mission-description"
              :fallback="defaultMissionBody"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Message Section -->
    <section id="message" class="message-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="company-profile" fieldKey="message_title" tag="span" :fallback="'ご挨拶'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="company-profile" fieldKey="message_subtitle" tag="span" :fallback="'message'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      <div class="message-content">
        <div class="message-text">
          <div class="message-title">
            <CmsText pageKey="company-profile" fieldKey="message_label" tag="div" :fallback="'MESSAGE'" />
          </div>
          <div class="message-body">
            <CmsText
              pageKey="company-profile"
              fieldKey="message_body"
              tag="div"
              type="html"
              :fallback="defaultMessageBody"
            />
          </div>
          <div class="message-signature">
            <CmsText pageKey="company-profile" fieldKey="message_signature" tag="div" :fallback="'株式会社 ちくぎん地域経済研究所代表取締役社長 空閑 重信'" />
          </div>
        </div>
        <img class="message-image" :src="media('company_profile_message', 'https://api.builder.io/api/v1/image/assets/TEMP/20aa75cfa1be4c2096a1f47bf126cf240173b231?width=1340')" alt="Message" />
      </div>
    </section>

    <!-- Contact CTA Section -->
    <ContactSection cms-page-key="company-profile" v-if="!isEditPreview" />

    <!-- Company Profile Section -->
    <section id="profile" class="company-profile-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="company-profile" fieldKey="profile_title" tag="span" :fallback="'会社概要'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="company-profile" fieldKey="profile_subtitle" tag="span" :fallback="'company profile'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      <div class="profile-table">
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_company_name_label" tag="span" :fallback="'会社名'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_company_name_value" tag="span" :fallback="'株式会社 ちくぎん地域経済研究所'" />
          </div>
        </div>
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_established_label" tag="span" :fallback="'設立'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_established_value" tag="span" :fallback="'平成23年7月1日'" />
          </div>
        </div>
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_address_label" tag="span" :fallback="'住所'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_address_value" tag="div" type="html" :fallback="'〒839-0864<br>福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階'" />
          </div>
        </div>
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_representative_label" tag="span" :fallback="'代表者'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_representative_value" tag="span" :fallback="'代表取締役社長　空閑 重信'" />
          </div>
        </div>
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_capital_label" tag="span" :fallback="'資本金'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_capital_value" tag="span" :fallback="'3,000万円'" />
          </div>
        </div>
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_shareholders_label" tag="span" :fallback="'株主'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_shareholders_value" tag="span" :fallback="'筑邦銀行　および筑邦銀行グループ会社 （ちくぎんリース(株)、昭光(株)、筑邦信用保証(株)）'" />
          </div>
        </div>
        <div class="profile-row">
          <div class="profile-label">
            <CmsText pageKey="company-profile" fieldKey="profile_organization_label" tag="span" :fallback="'組織体制'" />
          </div>
          <div class="profile-value">
            <CmsText pageKey="company-profile" fieldKey="profile_organization_value" tag="span" :fallback="'企画部、調査部'" />
          </div>
        </div>
      </div>
    </section>    

    <!-- History Section -->
    <section id="history" class="history-section" v-if="!isEditPreview">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="company-profile" fieldKey="history_title" tag="span" :fallback="'沿革'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="company-profile" fieldKey="history_subtitle" tag="span" :fallback="'history'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      <div v-if="historyList && historyList.length" class="history-content">
        <div 
          v-for="(h, idx) in historyList" 
          :key="idx" 
          class="history-item"
        >
          <div class="history-year">{{ h.year || '' }}</div>
          <div class="history-details">
            <div class="history-date">{{ h.date || '' }}</div>
            <div class="history-description" v-html="h.body || h.title || ''"></div>
          </div>
        </div>
      </div>
      <!-- CMSデータがない場合はフォールバックを表示 -->
      <div v-else class="history-content">
        <div class="history-item">
          <div class="history-year">1988</div>
          <div class="history-details">
            <div class="history-date">昭和63年1月30日</div>
            <div class="history-description">ちくぎんコンピュータサービス（株）設立</div>
          </div>
        </div>
        <div class="history-item">
          <div class="history-year">2010</div>
          <div class="history-details">
            <div class="history-date">平成22年6月29日</div>
            <div class="history-description">ちくぎんコンピュータサービス（株）の定款変更により、経営コンサルティング業務・経済調査等業務を追加。</div>
          </div>
        </div>
        <div class="history-item">
          <div class="history-year">2011</div>
          <div class="history-details">
            <div class="history-date">平成23年7月1日</div>
            <div class="history-description">社名変更　（株）ちくぎん地域経済研究所として新たにスタート。<br>主たる業務は調査・研究、人材開発、IT関連サービス、経営支援（経営サポート）、コンシェルジュサービス。</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Staff Section -->
    <section id="staff" class="staff-section">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="company-profile" fieldKey="staff_title" tag="span" :fallback="'所員紹介'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="company-profile" fieldKey="staff_subtitle" tag="span" :fallback="'MEMBER'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      <div class="staff-carousel">
        <div class="carousel-container">
          <div class="staff-members">
            <div class="staff-member">
              <img class="staff-photo" :src="media('company_profile_staff_morita', 'https://api.builder.io/api/v1/image/assets/TEMP/013d1cd8a9cd502c97404091dee8168d1aa93903?width=452')" alt="森田 祥子" />
              <div class="staff-info">
                <CmsText pageKey="company-profile" fieldKey="staff_morita_position" tag="div" class="staff-position" :fallback="'企画部　部長代理'" />
                <div class="staff-name">
                  <CmsText pageKey="company-profile" fieldKey="staff_morita_name" tag="span" :fallback="'森田 祥子'" />
                  <span class="staff-reading"><CmsText pageKey="company-profile" fieldKey="staff_morita_reading" tag="span" :fallback="'もりた さちこ'" /></span>
                </div>
                <CmsText pageKey="company-profile" fieldKey="staff_morita_note" tag="div" class="staff-note" :fallback="'（アジア福岡パートナーズへ出向）'" allowEmpty />
              </div>
            </div>
            <div class="staff-member">
              <img class="staff-photo" :src="media('company_profile_staff_mizokami', 'https://api.builder.io/api/v1/image/assets/TEMP/3eb35c11c5738cb9283fd65048f0db5c42dd1080?width=451')" alt="溝上 浩文" />
              <div class="staff-info">
                <CmsText pageKey="company-profile" fieldKey="staff_mizokami_position" tag="div" class="staff-position" :fallback="'取締役企画部長　兼調査部長'" />
                <div class="staff-name">
                  <CmsText pageKey="company-profile" fieldKey="staff_mizokami_name" tag="span" :fallback="'溝上 浩文'" />
                  <span class="staff-reading"><CmsText pageKey="company-profile" fieldKey="staff_mizokami_reading" tag="span" :fallback="'みぞかみ ひろふみ'" /></span>
                </div>
              </div>
            </div>
            <div class="staff-member">
              <img class="staff-photo" :src="media('company_profile_staff_kuga', 'https://api.builder.io/api/v1/image/assets/TEMP/ce433d9c00a0ce68895c315df3a3c49aa626deff?width=451')" alt="空閑 重信" />
              <div class="staff-info">
                <CmsText pageKey="company-profile" fieldKey="staff_kuga_position" tag="div" class="staff-position" :fallback="'代表取締役社長'" />
                <div class="staff-name">
                  <CmsText pageKey="company-profile" fieldKey="staff_kuga_name" tag="span" :fallback="'空閑 重信'" />
                  <span class="staff-reading"><CmsText pageKey="company-profile" fieldKey="staff_kuga_reading" tag="span" :fallback="'くが しげのぶ'" /></span>
                </div>
              </div>
            </div>
            <div class="staff-member">
              <img class="staff-photo" :src="media('company_profile_staff_takada', 'https://api.builder.io/api/v1/image/assets/TEMP/b21372a6aca15dfc189c6953aeb23f36f5d5e20b?width=451')" alt="髙田 友里恵" />
              <div class="staff-info">
                <CmsText pageKey="company-profile" fieldKey="staff_takada_position" tag="div" class="staff-position" :fallback="'調査部　主任'" />
                <div class="staff-name">
                  <CmsText pageKey="company-profile" fieldKey="staff_takada_name" tag="span" :fallback="'髙田 友里恵'" />
                  <span class="staff-reading"><CmsText pageKey="company-profile" fieldKey="staff_takada_reading" tag="span" :fallback="'たかだ ゆりえ'" /></span>
                </div>
              </div>
            </div>
            <div class="staff-member">
              <img class="staff-photo" :src="media('company_profile_staff_nakamura', 'https://api.builder.io/api/v1/image/assets/TEMP/497e67c9baa8add863ab6c5cc32439cf23eea4c3?width=451')" alt="中村 公栄" />
              <div class="staff-info">
                <CmsText pageKey="company-profile" fieldKey="staff_nakamura_position" tag="div" class="staff-position" :fallback="''" allowEmpty />
                <div class="staff-name">
                  <CmsText pageKey="company-profile" fieldKey="staff_nakamura_name" tag="span" :fallback="'中村 公栄'" />
                  <span class="staff-reading"><CmsText pageKey="company-profile" fieldKey="staff_nakamura_reading" tag="span" :fallback="'なかむら きえみ'" /></span>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-controls">
            <button class="carousel-prev">
              <svg width="16" height="16" viewBox="0 0 16 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.544218 24.0321L12.8448 43.1583C13.1914 43.6972 13.6615 44 14.1517 44C14.6419 44 15.112 43.6972 15.4587 43.1583C15.8053 42.6193 16 41.8883 16 41.1261C16 40.3639 15.8053 39.6329 15.4587 39.094L4.46349 22.0024L15.4556 4.90604C15.6272 4.63918 15.7633 4.32236 15.8562 3.97368C15.9491 3.62501 15.9969 3.2513 15.9969 2.87389C15.9969 2.49649 15.9491 2.12278 15.8562 1.7741C15.7633 1.42542 15.6272 1.10861 15.4556 0.841744C15.2839 0.574878 15.0802 0.363189 14.8559 0.218762C14.6317 0.0743358 14.3914 -2.81188e-09 14.1486 0C13.9059 2.81189e-09 13.6656 0.0743357 13.4413 0.218762C13.2171 0.363189 13.0133 0.574878 12.8417 0.841744L0.541144 19.9678C0.369335 20.2347 0.233092 20.5517 0.14023 20.9005C0.047369 21.2494 -0.000284195 21.6234 9.53674e-07 22.001C0.000287056 22.3786 0.0485067 22.7524 0.141896 23.1009C0.235286 23.4495 0.372005 23.7659 0.544218 24.0321Z" fill="#1A1A1A"/>
              </svg>
            </button>
            <div class="carousel-indicators">
              <span 
                v-for="(indicator, index) in carouselIndicators" 
                :key="index"
                class="indicator"
                :class="{ active: index === currentCarouselIndex }"
                @click="scrollToCarouselIndex(index)"
              ></span>
            </div>
            <button class="carousel-next">
              <svg width="16" height="16" viewBox="0 0 16 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.4558 24.0321L3.15522 43.1583C2.8086 43.6972 2.33848 44 1.84828 44C1.35809 44 0.88797 43.6972 0.54135 43.1583C0.194729 42.6193 3.65224e-09 41.8883 0 41.1261C-3.65224e-09 40.3639 0.194729 39.6329 0.54135 39.094L11.5365 22.0024L0.544425 4.90604C0.372796 4.63918 0.236652 4.32236 0.143767 3.97368C0.0508821 3.62501 0.00307463 3.2513 0.00307462 2.87389C0.00307462 2.49649 0.0508821 2.12278 0.143767 1.7741C0.236652 1.42542 0.372796 1.10861 0.544425 0.841744C0.716055 0.574878 0.919808 0.363189 1.14405 0.218762C1.3683 0.0743358 1.60864 -2.81188e-09 1.85136 0C2.09408 2.81189e-09 2.33442 0.0743357 2.55867 0.218762C2.78291 0.363189 2.98667 0.574878 3.15829 0.841744L15.4589 19.9678C15.6307 20.2347 15.7669 20.5517 15.8598 20.9005C15.9526 21.2494 16.0003 21.6234 16 22.001C15.9997 22.3786 15.9515 22.7524 15.8581 23.1009C15.7647 23.4495 15.628 23.7659 15.4558 24.0321Z" fill="#1A1A1A"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Financial Reports Section -->
    <section id="financial-reports" class="financial-reports-section">
      <div class="section-header">
        <h2 class="section-title">
          <CmsText pageKey="company-profile" fieldKey="financial_reports_title" tag="span" :fallback="'決算報告'" />
        </h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">
            <CmsText pageKey="company-profile" fieldKey="financial_reports_subtitle" tag="span" :fallback="'financial reports'" />
          </span>
          <div class="divider-line"></div>
        </div>
      </div>
      <div class="financial-reports-content">
        <div v-if="loadingReports" class="loading-message">
          決算報告を読み込み中...
        </div>
        <div v-else-if="displayedReports.length === 0" class="no-reports-message">
          決算報告はありません
        </div>
        <div v-else class="report-content">
          <div class="report-card">
            <div 
              v-for="(report, rIdx) in displayedReports" 
              :key="report.id || rIdx" 
              class="report-year-section"
            >
              <h2 class="year-title">{{ report.fiscal_year }}</h2>
              <div class="report-info">
                <div class="report-date">{{ report.date_label }}</div>
                <div class="report-links">
                  <div 
                    v-for="(item, index) in (report.items || [])" 
                    :key="index" 
                    class="link-text"
                  >
                    <a v-if="item.url" :href="item.url" target="_blank" rel="noopener noreferrer">{{ item.label || item.url }}</a>
                    <span v-else>{{ item.label }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </section>

        <!-- Access Section -->
    <div id="access"></div>
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
import ContactSection from "./ContactSection.vue";
import Footer from "./Footer";
import Group27 from "./Group27";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import { usePageText } from '@/composables/usePageText'
import { usePageMedia } from '@/composables/usePageMedia'
import { resolveMediaUrl } from '@/utils/url.js'
import CmsBlock from '@/components/CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'

import vector7 from "../../public/img/vector-7.svg";
import { frame132131753022Data } from "../data";

export default {
  name: "CompanyProfile",
  components: {
    Navigation,
    ContactSection,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    CmsText,
    CmsBlock,
  },
  data() {
    return {
      pageKey: 'company-profile',
      vector7: vector7,
      frame132131753022Props: frame132131753022Data,
      financialReports: [],
      loadingReports: false,
      carouselIndicators: [],
      currentCarouselIndex: 0,
      defaultMessageBody: `
        <p>皆さま方には、平素より筑邦銀行グループをご利用お引き立ていただき誠にありがとうございます。</p>
        <p>私どもは「地域社会へのご奉仕」という基本理念のもと、総合金融サービスの向上・充実に努めてまいりました。こうした中で、地元のさらなる発展に貢献するため、「(株)ちくぎん地域経済研究所」を設立いたしました。</p>
        <p>当研究所では、産・官・学・金（金融機関）のネットワークの構築などにより、今後一層発展の可能性を秘めたバイオ・アグリ・医療・介護をはじめ様々な分野の調査研究をより専門的に行うことといたします。</p>
        <p>また、こうした研究成果や様々なネットワークを活用し経営コンサルティング機能を十分に発揮することなどにより、多様な企業の生産・販売活動や医療・介護活動などのサポートを行ってまいります。</p>
        <p>以上のような活動を通じ、皆さま方と緊密な連携のもと、ヒト・モノ・カネ・情報を最大限に活かし、地域の振興・発展にお役にたてるよう努めてまいる所存です。</p>
        <p>当研究所へのご支援、ご愛顧をよろしくお願い申し上げます。</p>
      `,
      defaultMissionBody: `
        <ul>
          <li>地域に根差した経済・産業の調査・研究</li>
          <li>地域経済を担う企業・医療・農業・学術研究活動のサポート</li>
          <li>未来を支える「人」づくり</li>
        </ul>
      `,
      aboutInstituteDescription: `
        <p>株式会社ちくぎん地域経済研究所は、筑邦銀行グループの一員として、地域社会の発展に貢献することを使命としています。</p>
        <p>私たちは産・官・学・金（金融機関）のネットワークを構築し、バイオ・アグリ・医療・介護をはじめとする様々な分野の調査研究を専門的に行っています。</p>
        <p>また、研究成果やネットワークを活用した経営コンサルティング機能により、多様な企業の生産・販売活動や医療・介護活動をサポートしています。</p>
        <p>ヒト・モノ・カネ・情報を最大限に活かし、地域の振興・発展に寄与することを目指しています。</p>
      `,
    };
  },
  computed: {
    _pageRef() { return this._pageText?.page?.value },
    pageTitle() { return this._pageText?.getText('page_title', '会社概要') || '会社概要' },
    pageSubtitle() { return this._pageText?.getText('page_subtitle', 'About Us') || 'About Us' },
    isEditPreview() {
      try {
        const hash = window.location.hash || ''
        const qs = hash.includes('?') ? hash.split('?')[1] : (window.location.search || '').slice(1)
        const params = new URLSearchParams(qs)
        return params.has('cmsPreview') || params.has('cmsEdit')
      } catch (_) { return false }
    },
    displayedReports() {
      try {
        const c = this._pageText?.page?.value?.content
        const arr = Array.isArray(c?.financial_reports) ? c.financial_reports : []
        if (arr.length) {
          return arr.map((r, i) => ({
            id: r.id || i,
            fiscal_year: r.fiscal_year || '',
            date_label: r.date_label || '',
            items: Array.isArray(r.items) ? r.items.map(it => ({
              label: typeof it?.label === 'string' ? it.label : (typeof it === 'string' ? it : ''),
              url: typeof it?.url === 'string' ? it.url : ''
            })) : []
          }))
        }
      } catch(_) {}
      // Fallback to local static structure if CMS has no data
      return (this.financialReports || []).map((fr, i) => ({
        id: fr.id || i,
        fiscal_year: fr.fiscal_year || '',
        date_label: fr.report_info || '',
        items: Array.isArray(fr.report_links) ? fr.report_links.map(s => ({ label: s, url: '' })) : []
      }))
    },
    historyList() {
      try {
        const c = this._pageText?.page?.value?.content
        return Array.isArray(c?.history) ? c.history : []
      } catch(_) { return [] }
    },
    historyAbsent() {
      try {
        const c = this._pageText?.page?.value?.content
        return !(c && Object.prototype.hasOwnProperty.call(c, 'history'))
      } catch (_) { return true }
    }
  },
  mounted() {
    // CSS Grid handles equal heights; keep rectangle sync only
    this.adjustRectangleHeight();
    window.addEventListener('resize', this.adjustRectangleHeight);
    this.setupCarouselScroll();
    try {
      this._pageText = usePageText(this.pageKey)
      const opts = { force: false }
      // 管理者ログイン時は管理APIを優先（未公開の最新も即時反映）
      try {
        const token = localStorage.getItem('admin_token')
        if (token && token.length > 0) opts.preferAdmin = true
      } catch (_) {}
      this._pageText.load(opts)
    } catch(e) { /* noop */ }
    this.loadFinancialReports();
    // lazy media registry (for staff/philosophy/message images)
    import('@/composables/usePageMedia').then(mod => {
      try {
        const { usePageMedia } = mod
        this._pageMedia = usePageMedia()
        this._pageMedia.ensure(this.pageKey)
        // also keep direct media ref for watchers
        this._media = this._pageMedia._media
        // Reactivity bridge: re-render when media images map changes
        try {
          const readVersion = () => {
            const imgs = this._media && this._media.images
            const val = imgs && (imgs.value !== undefined ? imgs.value : imgs)
            try { return val ? JSON.stringify(val) : '' } catch(_) { return val ? Object.keys(val).join('|') : '' }
          }
          this.$watch(readVersion, () => { this.$forceUpdate() })
          const readLoaded = () => {
            const ld = this._media && this._media.loaded
            return ld && (ld.value !== undefined ? ld.value : ld)
          }
          this.$watch(readLoaded, () => { this.$forceUpdate() })
        } catch (_) { /* noop */ }
      } catch(e) { /* noop */ }
    })
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.adjustRectangleHeight);
  },
  methods: {
    scrollTo(id) {
      try {
        const el = this.$el.querySelector(`#${id}`)
        if (el && typeof el.scrollIntoView === 'function') {
          el.scrollIntoView({ behavior: 'smooth', block: 'start' })
        } else if (el) {
          window.location.hash = id
        }
      } catch (_) {}
    },
    media(key, fallback = '') {
      try {
        // A-方針: ページ管理の content.images を最優先（文言と同じ経路で即時反映）
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
          } catch(_) {}
          if (typeof url === 'string' && url.length) return resolveMediaUrl(url)
        }

        if (this._pageMedia) {
          // slot = key, default mediaKey = key
          return this._pageMedia.getResponsiveSlot(key, key, fallback) || fallback
        }
        if (this._media) {
          if (this._media.getResponsiveImage) return this._media.getResponsiveImage(key, fallback) || fallback
          if (this._media.getImage) return this._media.getImage(key, fallback) || fallback
        }
      } catch(e) {}
      return fallback
    },
    calculateCarouselIndicators() {
      const screenWidth = window.innerWidth;
      const totalStaffCount = 5; // 実際のstaff-memberの数
      
      if (screenWidth > 1400) {
        // 1400px以上は全員表示で横スクロールなし
        this.carouselIndicators = [];
      } else if (screenWidth > 900) {
        // 900px〜1400pxは4人表示で1人ずつスクロール
        const visibleCount = 4;
        const scrollableCount = totalStaffCount - visibleCount + 1; // 5 - 4 + 1 = 2
        this.carouselIndicators = Array(scrollableCount).fill(null);
      } else if (screenWidth > 600) {
        // 600px〜900pxは3人表示で1人ずつスクロール
        const visibleCount = 3;
        const scrollableCount = totalStaffCount - visibleCount + 1; // 5 - 3 + 1 = 3
        this.carouselIndicators = Array(scrollableCount).fill(null);
      } else {
        // 600px以下は2人表示で1人ずつスクロール
        const visibleCount = 2;
        const scrollableCount = totalStaffCount - visibleCount + 1; // 5 - 2 + 1 = 4
        this.carouselIndicators = Array(scrollableCount).fill(null);
      }
    },
    scrollToCarouselIndex(index) {
      const staffMembers = document.querySelector('.staff-members');
      if (!staffMembers) return;

      const screenWidth = window.innerWidth;
      if (screenWidth <= 1400) {
        let scrollAmount;
        if (screenWidth > 900) {
          // 900px〜1400px: 4人表示なので1人分の幅
          scrollAmount = (staffMembers.offsetWidth - 30) / 4 + 10;
        } else if (screenWidth > 600) {
          // 600px〜900px: 3人表示なので1人分の幅
          scrollAmount = (staffMembers.offsetWidth - 20) / 3 + 10;
        } else {
          // 600px以下: 2人表示なので1人分の幅
          scrollAmount = (staffMembers.offsetWidth - 10) / 2 + 10;
        }
        
        staffMembers.scrollTo({
          left: index * scrollAmount,
          behavior: 'smooth'
        });
        
        this.currentCarouselIndex = index;
      }
    },
    setupCarouselScroll() {
      this.$nextTick(() => {
        const prevButton = document.querySelector('.carousel-prev');
        const nextButton = document.querySelector('.carousel-next');
        const staffMembers = document.querySelector('.staff-members');
        
        if (prevButton && nextButton && staffMembers) {
          // インジケーター数を計算
          this.calculateCarouselIndicators();
          
          const getScrollAmount = () => {
            const screenWidth = window.innerWidth;
            if (screenWidth > 900) {
              // 900px〜1400px: 4人表示なので1人分の幅
              return (staffMembers.offsetWidth - 30) / 4 + 10; // 4等分 + gap
            } else if (screenWidth > 600) {
              // 600px〜900px: 3人表示なので1人分の幅
              return (staffMembers.offsetWidth - 20) / 3 + 10; // 3等分 + gap
            } else {
              // 600px以下: 2人表示なので1人分の幅
              return (staffMembers.offsetWidth - 10) / 2 + 10; // 2等分 + gap
            }
          };

          const updateIndicators = () => {
            const screenWidth = window.innerWidth;
            if (screenWidth <= 1400) {
              const scrollLeft = staffMembers.scrollLeft;
              const scrollAmount = getScrollAmount();
              const currentIndex = Math.round(scrollLeft / scrollAmount);
              this.currentCarouselIndex = Math.min(currentIndex, this.carouselIndicators.length - 1);
            }
          };
          
          prevButton.addEventListener('click', () => {
            const screenWidth = window.innerWidth;
            if (screenWidth <= 1400 && this.currentCarouselIndex > 0) {
              this.currentCarouselIndex--;
              const scrollAmount = getScrollAmount();
              staffMembers.scrollTo({
                left: this.currentCarouselIndex * scrollAmount,
                behavior: 'smooth'
              });
            }
          });
          
          nextButton.addEventListener('click', () => {
            const screenWidth = window.innerWidth;
            if (screenWidth <= 1400 && this.currentCarouselIndex < this.carouselIndicators.length - 1) {
              this.currentCarouselIndex++;
              const scrollAmount = getScrollAmount();
              staffMembers.scrollTo({
                left: this.currentCarouselIndex * scrollAmount,
                behavior: 'smooth'
              });
            }
          });

          // スクロール時のインジケーター更新
          staffMembers.addEventListener('scroll', updateIndicators);
          
          // リサイズ時のインジケーター再計算
          window.addEventListener('resize', () => {
            this.calculateCarouselIndicators();
            this.currentCarouselIndex = 0;
          });
        }
      });
    },
    async loadFinancialReports() {
      this.loadingReports = true;
      try {
        // 決算報告データを表示
        this.financialReports = [
          {
            id: 1,
            fiscal_year: '2025年3月期',
            report_type: '年次決算',
            report_date: '2025-05-12',
            revenue: null,
            net_income: null,
            file_path: null,
            report_info: '決算情報（2025年5月12日）',
            report_links: [
              '決算情報（2025年5月12日）',
              '決算要旨（PDF ： 1.28MB／全31ページ）',
              '決算説明会 第Ⅰ部（決算報告）資料（PDF ： 384KB／全22ページ）',
              '決算説明会 第Ⅱ部（社長メッセージ）スピーチ（PDF ： 620KB／全11ページ）',
              'メディア向け決算説明会の模様をご覧いただけます'
            ]
          },
          {
            id: 2,
            fiscal_year: '2024年3月期',
            report_type: '年次決算',
            report_date: '2024-05-12',
            revenue: null,
            net_income: null,
            file_path: null,
            report_info: '決算情報（2024年5月12日）',
            report_links: [
              '決算情報（2024年5月12日）',
              '決算要旨（PDF ： 1.25MB／全30ページ）',
              '決算説明会 第Ⅰ部（決算報告）資料（PDF ： 380KB／全21ページ）',
              '決算説明会 第Ⅱ部（社長メッセージ）スピーチ（PDF ： 615KB／全10ページ）',
              'メディア向け決算説明会の模様をご覧いただけます'
            ]
          },
          {
            id: 3,
            fiscal_year: '2023年3月期',
            report_type: '年次決算',
            report_date: '2023-05-12',
            revenue: null,
            net_income: null,
            file_path: null,
            report_info: '決算情報（2023年5月12日）',
            report_links: [
              '決算情報（2023年5月12日）',
              '決算要旨（PDF ： 1.22MB／全29ページ）',
              '決算説明会 第Ⅰ部（決算報告）資料（PDF ： 375KB／全20ページ）',
              '決算説明会 第Ⅱ部（社長メッセージ）スピーチ（PDF ： 610KB／全9ページ）',
              'メディア向け決算説明会の模様をご覧いただけます'
            ]
          },
          {
            id: 4,
            fiscal_year: '2022年3月期',
            report_type: '年次決算',
            report_date: '2022-05-12',
            revenue: null,
            net_income: null,
            file_path: null,
            report_info: '決算情報（2022年5月12日）',
            report_links: [
              '決算情報（2022年5月12日）',
              '決算要旨（PDF ： 1.20MB／全28ページ）',
              '決算説明会 第Ⅰ部（決算報告）資料（PDF ： 370KB／全19ページ）',
              '決算説明会 第Ⅱ部（社長メッセージ）スピーチ（PDF ： 605KB／全8ページ）',
              'メディア向け決算説明会の模様をご覧いただけます'
            ]
          }
        ];
      } catch (error) {
        console.error('Error loading financial reports:', error);
        this.financialReports = [];
      } finally {
        this.loadingReports = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('ja-JP', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatCurrency(amount) {
      if (!amount) return '';
      return new Intl.NumberFormat('ja-JP', {
        style: 'currency',
        currency: 'JPY',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(amount);
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
  }
};
</script>

<style scoped>
.company-profile {
  background-color: #ECECEC;
  font-family: 'Inter', Helvetica, sans-serif;
  position: relative;
  width: 100%;
  overflow-x: auto;
}

/* In-page nav */
.inpage-nav {
  padding: 16px 20px 0;
}

.inpage-list {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0; /* gaps come from separators */
  flex-wrap: wrap;
  list-style: none;
  padding: 0;
  margin: 0 auto;
  max-width: 1200px;
}

.inpage-item {
  display: inline-flex;
  align-items: center;
  font-weight: 700;
  color: #6B7280; /* gray-500 */
}

.inpage-item a {
  color: inherit;
  text-decoration: none;
  font-size: 16px;
  padding: 6px 8px;
  line-height: 1.6;
}

.inpage-item a:hover { color: #DA5761; }

/* angled slash separator (after each item except last) */
.inpage-item:not(:last-child)::after {
  content: '/';
  color: #B0B0B0;
  display: inline-block;
  margin: 0 14px;
  transform: skewX(-10deg);
}

/* Responsive behavior: turn into horizontal scroll with snap on small screens */
@media (max-width: 900px) {
  .inpage-list {
    justify-content: flex-start;
    flex-wrap: nowrap;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x proximity;
    padding-bottom: 8px;
    gap: 0;
  }
  .inpage-item {
    flex: 0 0 auto;
    scroll-snap-align: center;
  }
  /* keep separators smaller on mobile */
  .inpage-item:not(:last-child)::after {
    margin: 0 10px;
    opacity: 0.7;
  }
}





/* Section Styling */
section {
  padding: 70px 50px 50px;
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

/* Philosophy Section */
.philosophy-section {
  background: #ECECEC;
  width: 100%;
  overflow: hidden;
  padding: 70px 50px 50px;
}

.philosophy-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: stretch;
  width: 100%;
  max-width: 2000px;
}

.philosophy-image {
  width: 100%;
  max-height: 400px;
  display: block;
  border-radius: 20px 0 0 20px;
  object-fit: cover;
  object-position: center;
}

.philosophy-text {
  width: 100%;
  max-height: 400px;
  padding: 50px;
  background: white;
  border-radius: 0 20px 20px 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 40px;
}

.mission-title {
  color: #DA5761;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 64px;
  font-weight: 700;
  line-height: 110%;
  margin: 0;
}

.mission-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.mission-heading {
  color: #1A1A1A;
  font-size: 28px;
  font-weight: 700;
  line-height: 1.5;
}

.mission-description {
  color: #3F3F3F;
  font-family: 'Noto Sans JP', sans-serif;
  font-size: 20px;
  font-weight: 400;
  line-height: 1.2;
}

.mission-line {
  display: inline-block;
  text-indent: -1em;
  padding-left: 1em;
}

/* Message Section */
.message-section {
  background: #ECECEC;
  width: 100%;
  overflow: hidden;
  padding: 70px 50px 50px;
}

.message-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: stretch;
  width: 100%;
  max-width: 2000px;
}

.message-text {
  width: 100%;
  max-height: 700px;
  padding: 50px;
  background: white;
  border-radius: 20px 0 0 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-end;
  gap: 40px;
}

.message-title {
  color: #DA5761;
  font-size: 64px;
  font-weight: 700;
  line-height: 1.5;
  align-self: stretch;
}

.message-body {
  color: #3F3F3F;
  font-family: 'Noto Sans JP', sans-serif;
  font-size: 20px;
  font-weight: 400;
  line-height: 1.2;
  align-self: stretch;
}

.message-body p {
  margin-bottom: 1em;
}

.message-signature {
  color: #3F3F3F;
  font-family: 'Noto Sans JP', sans-serif;
  font-size: 20px;
  font-weight: 400;
}

.message-image {
  width: 100%;
  max-height: 700px;
  display: block;
  border-radius: 0 20px 20px 0;
  object-fit: cover;
  object-position: center;
}

/* Company Profile Section */
.company-profile-section {
  background: #ECECEC;
  padding: 70px 50px 50px;
}

.profile-table {
  background: white;
  border-radius: 20px;
  padding: 50px;
  width: 100%;
  max-width: 2000px;
  display: flex;
  flex-direction: column;
}

.profile-row {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  height: auto;
  border-bottom: 0.5px dashed #DA5761;
  padding: 30px 0;
}

.profile-row:first-child {
  border-top: 0.5px dashed #DA5761;
}

.profile-label {
  color: #3F3F3F;
  font-size: 18px;
  font-weight: 700;
  line-height: 1.5;
  width: 180px;
  flex-shrink: 0;
}

.profile-value {
  color: #3F3F3F;
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
}

/* History Section */
.history-section {
  background: #ECECEC;
  padding: 70px 50px 50px;
}

.history-content {
  background: white;
  border-radius: 20px;
  padding: 50px;
  width: 100%;
  max-width: 2000px;
  display: flex;
  flex-direction: column;
}

.history-item {
  display: flex;
  padding: 30px 0;
  justify-content: flex-start;
  align-items: center;
  border-bottom: 0.5px dashed #DA5761;
}

.history-item:first-child {
  border-top: 0.5px dashed #DA5761;
}

.history-year {
  color: #DA5761;
  font-size: 60px;
  font-weight: 700;
  line-height: 1.5;
  width: 250px;
  flex-shrink: 0;
}

.history-details {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.history-date {
  color: #1A1A1A;
  font-size: 20px;
  font-weight: 700;
  line-height: 1.5;
}

.history-description {
  color: #3F3F3F;
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
}

.detail-button {
  background: #1A1A1A;
  border-radius: 10px;
  padding: 10px 0;
  width: 300px;
  height: 43px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  color: white;
  font-size: 15px;
  font-weight: 700;
  line-height: 1.5;
  margin: 0 auto;
  margin-top: 50px;
}

/* Staff Section */
.staff-section {
  background: #ECECEC;
  padding: 70px 50px 50px;
}

/* Financial Reports Section */
.financial-reports-section {
  background: #ECECEC;
  padding: 70px 50px 50px;
}

.financial-reports-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.loading-message,
.no-reports-message {
  text-align: center;
  color: #3F3F3F;
  font-size: 18px;
  padding: 40px 0;
}

.report-content {
  display: flex;
  padding: 50px;
  flex-direction: column;
  align-items: center;
  gap: 50px;
  width: 100%;
  max-width: 2000px;
  border-radius: 20px;
  background: #FFF;
}

.report-card {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
}

.report-year-section {
  width: 100%;
  padding: 30px 0;
  border-bottom: 0.5px dashed #B0B0B0;
}

.report-year-section:first-child {
  border-top: 0.5px dashed #B0B0B0;
}

.year-title {
  font-size: 24px;
  color: #1A1A1A;
  font-weight: bold;
  margin-bottom: 15px;
}

.report-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 28px;
}

.report-date {
  color: #666;
  font-family: Inter;
  font-size: 0.95rem;
  font-weight: 400;
  line-height: normal;
}

.report-links {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.link-text {
  color: #0066cc;
  font-family: Inter;
  font-size: 20px;
  font-weight: 400;
  line-height: normal;
  text-indent: -1em;
  padding-left: 1em;
}

.link-text::before {
  content: "・";
  margin-right: 4px;
}


.show-more-btn {
  display: flex;
  width: 300px;
  padding: 10px 0;
  justify-content: center;
  align-items: center;
  gap: 8px;
  background: #1A1A1A;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  color: #ffffff;
  transition: all 0.3s ease;
  margin: 0 auto;
}

.show-more-btn span {
  color: #ffffff;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 150%;
}

.show-more-btn:hover {
  opacity: 0.8;
}


.staff-carousel {
  background: white;
  border-radius: 20px;
  padding: 50px 40px;
  width: 100%;
  max-width: 2000px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
}

.carousel-prev, .carousel-next {
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  transition: all 0.2s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.carousel-prev:hover, .carousel-next:hover {
  background: #e9ecef;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.carousel-prev:active, .carousel-next:active {
  transform: translateY(0);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.staff-carousel {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.carousel-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 20px;
  width: 100%;
}

.carousel-controls {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  max-width: 400px;
  margin-top: 10px;
}

.carousel-indicators {
  display: flex;
  align-items: center;
  gap: 8px;
}

.indicator {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #d1d5db;
  cursor: pointer;
  transition: all 0.2s ease;
}

.indicator.active {
  background: #3b82f6;
  transform: scale(1.2);
}

.indicator:hover {
  background: #9ca3af;
}

.staff-members {
  display: flex;
  gap: 10px;
  overflow-x: auto;
  width: 100%;
  scroll-behavior: smooth;
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none; /* IE and Edge */
}

@media (min-width: 1401px) {
  .staff-members {
    overflow-x: hidden;
  }
  
  .carousel-controls {
    display: none;
  }
}

.staff-members::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Opera */
}

.staff-member {
  width: calc((100% - 40px) / 5);
  display: flex;
  flex-direction: column;
  gap: 20px;
  flex-shrink: 0;
}

.staff-photo {
  width: 100%;
  aspect-ratio: 226 / 300;
  object-fit: cover;
}

.staff-info {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.staff-position {
  color: #1A1A1A;
  font-size: 12px;
  font-weight: 400;
  line-height: 1.5;
}

.staff-name {
  color: #1A1A1A;
  font-size: 20px;
  font-weight: 400;
  line-height: 1.5;
}

.staff-reading {
  font-size: 12px;
  font-weight: 400;
}

.staff-note {
  color: #1A1A1A;
  font-size: 12px;
  font-weight: 400;
  line-height: 1.5;
}

/* Access Section */

.access-map {
  width: 692px;
  height: 398px;
  border-radius: 10px;
}

.info-heading {
  color: #DA5761;
  font-size: 20px;
  font-weight: 700;
  line-height: 2;
  margin-bottom: 10px;
}

.info-item {
  color: #1A1A1A;
  font-size: 16px;
  font-weight: 400;
  line-height: 2;
  margin-bottom: 5px;
}

/* Footer Navigation */

.footer-navigation {
  background: #CFCFCF;
  padding: 50px 100px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 50px;
}

.footer-links {
  display: flex;
  align-items: flex-start;
  gap: 60px;
}

.footer-column {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 20px;
  width: 171px;
}

.footer-item {
  display: flex;
  height: 18px;
  align-items: center;
  gap: 10px;
  align-self: stretch;
}

.footer-sub-item {
  display: flex;
  height: 15px;
  padding-left: 20px;
  align-items: center;
  gap: 10px;
  align-self: stretch;
}

.arrow-icon {
  width: 10px;
  height: 17px;
  fill: #DA5761;
}

.dash-icon {
  width: 2px;
  height: 10px;
  transform: rotate(90deg);
  fill: #DA5761;
}

.footer-item span,
.footer-sub-item span {
  color: #1A1A1A;
  font-size: 16px;
  font-weight: 400;
  line-height: 2;
}

.footer-divider {
  width: 1240px;
  height: 1px;
  border: none;
  background: #B2B2B2;
}

.footer-bottom {
  width: 716px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.footer-company {
  display: flex;
  align-items: center;
  gap: 20px;
  justify-content: center;
}

.footer-logo {
  width: 54px;
  height: 42px;
}

.footer-company-text {
  display: flex;
  flex-direction: column;
  gap: -5px;
}

.footer-company-name {
  color: #1A1A1A;
  font-size: 24px;
  font-weight: 700;
  line-height: 1.5;
}

.footer-group-name {
  color: #1A1A1A;
  font-size: 15px;
  font-weight: 700;
  line-height: 1.5;
}

.footer-contact {
  color: #1A1A1A;
  text-align: center;
  font-size: 20px;
  font-weight: 400;
  line-height: 1.2;
}

.footer-copyright {
  color: #1A1A1A;
  font-size: 15px;
  font-weight: 500;
  line-height: 1.5;
  text-align: center;
  margin-top: 20px;
}



/* Responsive Design */
@media (max-width: 1400px) {
  .staff-member {
    width: calc((100% - 30px) / 4);
    min-width: calc((100% - 30px) / 4);
  }

  .message-text {
    max-height: 900px;
  }

  .message-image {
    max-height: 900px;
  }
}

@media (max-width: 900px) {
  .staff-member {
    width: calc((100% - 20px) / 3) !important;
    min-width: calc((100% - 20px) / 3) !important;
  }
}

@media (max-width: 1150px) {
  .frame-1321317467-1 {
    flex-direction: column;
    gap: 20px;
  }
}

@media (max-width: 1050px) {
  .staff-name {
    display: flex;
    flex-direction: column;
    gap: 0px;
  }

  .staff-reading {
    align-self: flex-start;
  }
}

@media (max-width: 1150px) {
  .rectangle-3 {
    width: 100%;
    max-width: none;
    order: 2;
  }
  
  .frame-1321317466 {
    order: 1;
  }

  .philosophy-section,
  .message-section,
  .company-profile-section,
  .history-section,
  .staff-section,
  .financial-reports-section {
    padding: 50px 30px !important;
  }

  .philosophy-text,
  .message-text,
  .profile-table,
  .history-content,
  .staff-carousel {
    padding: 50px;
  }
  
  .philosophy-content,
  .message-content {
    display: flex;
    flex-direction: column;
    gap: 0;
  }
  
  .philosophy-image,
  .philosophy-text,
  .message-text,
  .message-image {
    width: 100%;
  }
  
  .philosophy-image {
    border-radius: 20px 20px 0 0;
    height: 300px !important;
  }
  
  .philosophy-text {
    border-radius: 0 0 20px 20px;
    padding: 50px;
  }
  
  .message-text {
    border-radius: 0 0 20px 20px;
    padding: 50px;
    order: 2;
  }
  
  .message-image {
    border-radius: 20px 20px 0 0;
    height: 300px !important;
    order: 1;
  }

  .section-title {
    font-size: 32px !important;
  }

  .divider-text {
    font-size: 18px !important;
  }

  .mission-title,
  .message-title {
    font-size: 48px !important;
  }
  
  .mission-description,
  .message-body,
  .message-signature,
  .profile-value,
  .history-description {
    font-size: 18px !important;
  }

  .mission-heading,
  .history-date,
  .staff-name {
    font-size: 22px !important;
  }
  
  .history-item,
  .profile-row {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
    padding: 10px 0;
  }
  
  .history-year {
    width: auto;
    font-size: 48px !important;
  }
  
  .history-details {
    width: 100%;
  }
}

@media (max-width: 1150px) {
  .section-header {
    gap: 25px !important;
    margin-bottom: 25px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
}

@media (max-width: 900px) {
  .staff-member {
    width: calc((100% - 20px) / 3) !important;
    min-width: calc((100% - 20px) / 3) !important;
  }

  .staff-name {
    display: flex;
    flex-direction: row;
    gap: 5px;
  }

  .staff-reading {
    align-self: center;
  }

  .company-profile {
    overflow-x: hidden;
  }
  
  .navigation-header,
  .hero-section,
  .breadcrumbs,
  section,
  .contact-cta-section,
  .footer-navigation {
    min-width: auto;
    width: 100%;
  }
  
  .philosophy-section,
  .message-section {
    width: 100%;
    overflow: hidden;
  }
  
  .philosophy-content,
  .message-content {
    width: 100%;
    max-width: 100%;
  }

  .report-content {
    padding: 30px 20px !important;
  }

  .report-year-section {
    padding: 20px !important;
  }
  
  .year-title {
    font-size: 22px !important;
  }
  
  .report-date {
    font-size: 16px !important;
  }

  .link-text {
    font-size: 17px !important;
  }

  .section-header {
    gap: 22px !important;
    margin-bottom: 22px !important;
  }

  .section-title {
    font-size: 29px !important;
  }

  .divider-text {
    font-size: 17px !important;
  }

  .mission-title,
  .message-title {
    font-size: 42px !important;
  }

  .mission-description,
  .message-body,
  .message-signature,
  .profile-value,
  .history-description {
    font-size: 17px !important;
  }

  .mission-heading,
  .history-date,
  .staff-name {
    font-size: 20px !important;
  }

  .history-year {
    font-size: 42px !important;
  }

  .staff-members {
    display: flex;
    gap: 10px;
    width: 100%;
    overflow-x: auto;
    scroll-behavior: smooth;
  }

  .philosophy-section,
  .message-section,
  .company-profile-section,
  .about-institute-section,
  .history-section,
  .staff-section,
  .financial-reports-section {
    padding: 30px 20px !important;
    gap: 30px;
  }
  
  .philosophy-text,
  .message-text {
    padding: 30px 20px;
  }
  
  .profile-table,
  .history-content,
  .staff-carousel {
    padding: 30px 20px;
  }

  .staff-member {
    width: 200px;
    min-width: 200px;
  }

  .carousel-controls {
    max-width: 300px;
  }

  .carousel-prev,
  .carousel-next {
    width: 35px;
    height: 35px;
  }

  .indicator {
    width: 6px;
    height: 6px;
  }
}

@media (max-width: 768px) {
  .staff-name {
    display: flex;
    flex-direction: column;
    gap: 0px;
  }

  .staff-reading {
    align-self: flex-start;
  }
  
  .staff-members {
    display: flex;
    gap: 10px;
    width: 100%;
    overflow-x: auto;
    scroll-behavior: smooth;
  }

  .philosophy-content,
  .message-content {
    flex-direction: column;
    width: 100%;
    max-width: 100%;
  }
  
  .philosophy-image,
  .philosophy-text,
  .message-text,
  .message-image {
    width: 100%;
  }
  
  .philosophy-section,
  .message-section {
    width: 100%;
    overflow: hidden;
  }

  .philosophy-section,
  .message-section,
  .company-profile-section,
  .about-institute-section,
  .history-section,
  .staff-section,
  .financial-reports-section {
    padding: 30px 20px !important;
    gap: 30px;
  }
  
  .philosophy-text,
  .message-text {
    padding: 30px 20px;
  }
  
  .profile-table,
  .history-content,
  .staff-carousel {
    padding: 30px 20px;
  }

  .section-header {
    gap: 20px !important;
    margin-bottom: 20px !important;
  }

  .section-title {
    font-size: 27px !important;
  }

  .divider-text {
    font-size: 16px !important;
  }

  .link-text {
    font-size: 16px !important;
  }

  .year-title {
    font-size: 19px !important;
  }

  .report-content {
    padding: 30px 20px !important;
  }

  .mission-title,
  .message-title {
    font-size: 38px !important;
  }

  .mission-description,
  .message-body,
  .message-signature,
  .profile-value,
  .history-description {
    font-size: 16px !important;
  }

  .mission-heading,
  .history-date,
  .staff-name {
    font-size: 19px !important;
  }

  .history-year {
    font-size: 38px !important;
  }
  
  .footer-links {
    flex-wrap: wrap;
    gap: 30px;
  }
}

@media (max-width: 700px) {
  .staff-note {
    font-size: 10px !important;
  }
}

@media (max-width: 600px) {
  .staff-member {
    width: calc((100% - 10px) / 2) !important;
    min-width: calc((100% - 10px) / 2) !important;
  }
}

@media (max-width: 480px) {
  .philosophy-section,
  .message-section {
    width: 100%;
    overflow: hidden;
  }

  .philosophy-section,
  .message-section,
  .company-profile-section,
  .history-section,
  .staff-section,
  .financial-reports-section {
    padding: 20px 15px !important;
    gap: 20px;
  }
  
  .philosophy-content,
  .message-content {
    width: 100%;
    max-width: 100%;
  }
  
  .philosophy-text,
  .message-text {
    padding: 20px 15px;
    gap: 20px;
  }
  
  .profile-table,
  .history-content,
  .staff-carousel {
    padding: 20px 15px;
  }

  .history-year {
    font-size: 35px !important;
  }
  
  .mission-title,
  .message-title {
    font-size: 25px !important;
  }

  .mission-description,
  .message-body,
  .message-signature,
  .profile-value,
  .history-description {
    font-size: 13px !important;
  }

  .mission-heading,
  .history-date,
  .staff-name {
    font-size: 18px !important;
  }

  .staff-position,
  .staff-note,
  .staff-reading {
    font-size: 10px !important;
  }

  .staff-info {
    gap: 10px !important;
  }

  .staff-name {
    display: flex;
    flex-direction: column;
    gap: 0px;
  }

  .staff-reading {
    align-self: flex-start;
  }

  .philosophy-image {
    height: 200px !important;
  }

  .message-image {
    height: 200px !important;
    order: 1;
  }

  .detail-button {
    margin-top: 30px !important;
  }

  .section-header {
    gap: 18px !important;
    margin-bottom: 18px !important;
  }

  .section-title {
    font-size: 22px !important;
  }

  .divider-text {
    font-size: 13px !important;
  }

  .link-text {
    font-size: 13px !important;
  }

  .year-title {
    font-size: 18px !important;
  }

  .report-content {
    padding: 20px 15px !important;
  }

  .report-year-section {
    padding: 15px 5px !important;
  }
  
  .year-title {
    font-size: 18px !important;
  }
  
  .report-date {
    font-size: 13px !important;
  }

  .year-title {
    font-size: 20px !important;
  }

  .report-content {
    padding: 30px 20px !important;
  }
}

/* Show More Button Responsive Styles */
@media (max-width: 1150px) {
  .show-more-btn span {
    font-size: 18px !important;
  }
}

@media (max-width: 900px) {
  .show-more-btn span {
    font-size: 17px !important;
  }
}

@media (max-width: 768px) {
  .show-more-btn span {
    font-size: 16px !important;
  }
}

@media (max-width: 480px) {
  .show-more-btn {
    width: 250px !important;
  }
  
  .show-more-btn span {
    font-size: 13px !important;
  }
}

@media (max-width: 400px) {
  .show-more-btn {
    width: 100% !important;
  }
}

/* About Institute Section Styles */
.about-institute-section {
  padding: 80px 0;
  background-color: #f8f9fa;
}

.about-institute-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.about-institute-text {
  margin-bottom: 60px;
  text-align: center;
}

.about-institute-text p {
  font-size: 16px;
  line-height: 1.8;
  color: #333;
  margin-bottom: 20px;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
}

.about-institute-features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 40px;
  margin-top: 40px;
}

.feature-item {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  padding: 30px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.feature-icon {
  flex-shrink: 0;
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #dc3545, #c82333);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.feature-icon img {
  width: 30px;
  height: 30px;
  filter: brightness(0) invert(1);
}

.feature-content {
  flex: 1;
}

.feature-title {
  font-size: 20px;
  font-weight: 600;
  color: #333;
  margin-bottom: 12px;
  line-height: 1.4;
}

.feature-description {
  font-size: 14px;
  line-height: 1.6;
  color: #666;
  margin: 0;
}

/* Responsive Design for About Institute Section */
@media (max-width: 768px) {
  .about-institute-section {
    padding: 60px 0;
  }
  
  .about-institute-content {
    padding: 0 15px;
  }
  
  .about-institute-text {
    margin-bottom: 40px;
  }
  
  .about-institute-text p {
    font-size: 15px;
    line-height: 1.7;
  }
  
  .about-institute-features {
    grid-template-columns: 1fr;
    gap: 30px;
    margin-top: 30px;
  }
  
  .feature-item {
    flex-direction: column;
    text-align: center;
    padding: 25px;
    gap: 15px;
  }
  
  .feature-icon {
    width: 50px;
    height: 50px;
    margin: 0 auto;
  }
  
  .feature-icon img {
    width: 25px;
    height: 25px;
  }
  
  .feature-title {
    font-size: 18px;
    margin-bottom: 10px;
  }
  
  .feature-description {
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .about-institute-section {
    padding: 40px 0;
  }
  
  .about-institute-content {
    padding: 0 10px;
  }
  
  .about-institute-text {
    margin-bottom: 30px;
  }
  
  .about-institute-text p {
    font-size: 14px;
    line-height: 1.6;
  }
  
  .about-institute-features {
    gap: 20px;
    margin-top: 20px;
  }
  
  .feature-item {
    padding: 20px;
    gap: 12px;
  }
  
  .feature-icon {
    width: 45px;
    height: 45px;
  }
  
  .feature-icon img {
    width: 22px;
    height: 22px;
  }
  
  .feature-title {
    font-size: 16px;
    margin-bottom: 8px;
  }
  
  .feature-description {
    font-size: 13px;
  }
}

/* Inline-editable elements should match description font size */
.inline-editable {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  color: #3F3F3F;
  line-height: normal;
}

/* Responsive font sizes for inline-editable */
@media (max-width: 1150px) {
  .inline-editable {
    font-size: 18px !important;
  }
}

@media (max-width: 900px) {
  .inline-editable {
    font-size: 17px !important;
  }
}

@media (max-width: 768px) {
  .inline-editable {
    font-size: 16px !important;
  }
}

@media (max-width: 480px) {
  .inline-editable {
    font-size: 13px !important;
  }
}
</style>
