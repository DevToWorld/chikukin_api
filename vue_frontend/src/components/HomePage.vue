<template>
  <div class="home-page">
    <!-- Navigation -->
    <navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      :slides="resolvedHeroSlides"
      :autoPlay="true"
      :autoPlayInterval="6000"
      cmsPageKey="home"
      titleFieldKey="page_title"
      subtitleFieldKey="page_subtitle"
    />

    <!-- CMS Body removed -->

      <div class="frame-1321317457">
          <div class="frame-1321317490" :style="{ 'background-image': 'url(' + frame13213174901 + ')' }" @click="goToSeminar" style="cursor: pointer;">
            <div class="frame-1321317491">
              <div class="text-1 valign-text-middle inter-bold-white-15px">{{ text72 }}</div>
              <div class="seminar valign-text-middle inter-bold-white-24px">{{ seminar }}</div>
            </div>
          </div>
          <div class="frame-1321317491-1" :style="{ 'background-image': 'url(' + frame1321317491 + ')' }" @click="goToPublication" style="cursor: pointer;">
            <div class="frame-1321317491-2">
              <div class="text-1 valign-text-middle inter-bold-white-15px">{{ text69 }}</div>
              <div class="publications valign-text-middle inter-bold-white-24px">{{ publications1 }}</div>
            </div>
          </div>
          <div class="frame-1321317490-1" :style="{ 'background-image': 'url(' + frame13213174902 + ')' }" @click="goToNews" style="cursor: pointer;">
            <div class="frame-1321317491-3">
              <div class="text-2 valign-text-middle inter-bold-white-16px">{{ text70 }}</div>
              <div class="Information valign-text-middle inter-bold-white-24px">{{ Information1 }}</div>
            </div>
          </div>
          <div class="frame-1321317492" :style="{ 'background-image': 'url(' + frame1321317492 + ')' }" @click="goToMembership" style="cursor: pointer;">
            <div class="frame-1321317491-4">
              <div class="text-2 valign-text-middle inter-bold-white-16px">{{ text71 }}</div>
              <div class="membership valign-text-middle inter-bold-white-24px">{{ membership }}</div>
            </div>
          </div>
        </div>
      
      
    
      <div class="overlap-group8">
        <svg class="vector-13" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
          <path d="M-8.02699e-08 120L-3.89972e-06 44.6076C413.287 -99.5267 685.476 162.131 1440 44.6077L1440 120L-8.02699e-08 120Z" fill="#ECECEC"/>
        </svg>
        <div class="main">
          <div class="about">
            <div class="flex-col-1">
              <div class="section-header">
                <h2 class="section-title">
                  <CmsText pageKey="home" fieldKey="about_title" tag="span" :fallback="'ちくぎん地域経済研究所について'" />
                </h2>
                <div class="section-divider">
                  <div class="divider-line"></div>
                  <span class="divider-text">
                    <CmsText pageKey="home" fieldKey="about_subtitle" tag="span" :fallback="'FOR YOU'" />
                  </span>
                  <div class="divider-line"></div>
                </div>
              </div>
              <div class="text-48">
                <CmsText pageKey="home" fieldKey="about_body" tag="div" type="html" :fallback="text113" />
              </div>
            </div>
          </div>
          <div class="frame-1321317462">
            <div class="frame-1321317456">
              <div class="frame-1321317482">
                <card :image="card1Props.image" :frame1321317481Props="card1Props.frame1321317481Props" />
                <card
                  :image="card2Props.image"
                  :className="card2Props.className"
                  :frame1321317481Props="card2Props.frame1321317481Props"
                />
                <card
                  :image="image1"
                  :frame1321317481Props="frame1321317481Props"
                />
              </div>
              <div class="button arrow-dark" @click="$router.push('/aboutus')" style="cursor: pointer;">
                <div class="text-55 valign-text-middle inter-bold-white-15px">
                  <CmsText pageKey="home" fieldKey="cta_secondary" tag="span" :fallback="text120" />
                </div>
                <frame13213176122 />
              </div>
            </div>
          </div>
           
          
          <div class="content-view">
             <div class="frame-1321317480">
               <div class="group-10">
                 <div class="group-20">
                   <img
                     class="vector-6"
                     :src="vector11"
                     alt="Vector 6"
                   />
                   <div class="text-36 valign-text-middle inter-bold-black-24px">{{ text121 }}</div>
                 </div>
                 <div class="ation valign-text-middle inter-bold-black-48px">{{ Information2 }}</div>
               </div>
               <x-button3 class="desktop-button" @click="goToNewsList" />
             </div>
             <div class="frame-1321317467">
              <article 
                  v-for="(item, index) in dynamicNewsItems.slice(0, 5)" 
                  :key="index"
                  class="news-item"
                  @click="goToNewsDetail(index)"
                  style="cursor: pointer;"
                >
                  <div class="news-meta">
                    <div class="news-date-category-row">
                      <span class="news-date">{{ formatDate(item.date) }}</span>
                      <span :class="['news-category', getCategoryClass(item)]">
                        {{ getCategoryLabel(item) }}
                      </span>
                    </div>
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
               <x-button3 class="mobile-button" @click="goToNewsList" />
             </div>
           </div>
        
        <!-- Contact CTA Section -->
        <ContactSection cms-page-key="home" />
        
        <div class="frame-1321317484">
        
        
          <div class="content-view">
          <div class="frame-1321317480">
            <div class="group-10">
              <div class="group-20">
                <img
                  class="vector-6"
                  :src="vector11"
                  alt="Vector 6"
                />
                <div class="text-36 valign-text-middle inter-bold-black-24px">{{ text101 }}</div>
              </div>
              <div class="ation valign-text-middle inter-bold-black-48px">{{ publications2 }}</div>
            </div>
          </div>
          <div class="frame-1321317472">
            <div class="main-publication-wrapper" @click="goToMainPublication">
              <frame1321317474 :x22="dynamicMainPublication.x22" :date="dynamicMainPublication.date" :title="dynamicMainPublication.title" />
            </div>
            <div class="frame-1321317485">
              <div class="frame-1321317487">
                <div class="group-18">
                  <img
                    class="vector-9"
                    :src="vector91"
                    alt="Vector 9"
                    @click="prevPublication"
                    style="cursor: pointer;"
                  />
                  <img
                    class="vector-10"
                    :src="vector101"
                    alt="Vector 10"
                    @click="nextPublication"
                    style="cursor: pointer;"
                  />
                </div>
                <x-button3 class="desktop-publication-button" @click="goToPublicationList" />
              </div>
              <div class="frame-1321317486">
                <div class="publication-item-wrapper" @click="goToPublication(0)">
                  <frame1321317475
                    :x22="dynamicPublications[0] ? dynamicPublications[0].x22 : frame13213174751Props.x22"
                    :hotInformationVol324="dynamicPublications[0] ? dynamicPublications[0].hotInformationVol324 : frame13213174751Props.hotInformationVol324"
                    :date="dynamicPublications[0] ? dynamicPublications[0].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(1)">
                  <frame1321317475
                    :x22="dynamicPublications[1] ? dynamicPublications[1].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[1] ? dynamicPublications[1].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[1] ? dynamicPublications[1].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(2)">
                  <frame1321317475
                    :x22="dynamicPublications[2] ? dynamicPublications[2].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[2] ? dynamicPublications[2].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[2] ? dynamicPublications[2].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(3)">
                  <frame1321317475
                    :x22="dynamicPublications[3] ? dynamicPublications[3].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[3] ? dynamicPublications[3].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[3] ? dynamicPublications[3].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(4)">
                  <frame1321317475
                    :x22="dynamicPublications[4] ? dynamicPublications[4].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[4] ? dynamicPublications[4].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[4] ? dynamicPublications[4].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(5)">
                  <frame1321317475
                    :x22="dynamicPublications[5] ? dynamicPublications[5].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[5] ? dynamicPublications[5].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[5] ? dynamicPublications[5].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(6)">
                  <frame1321317475
                    :x22="dynamicPublications[6] ? dynamicPublications[6].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[6] ? dynamicPublications[6].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[6] ? dynamicPublications[6].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(7)">
                  <frame1321317475
                    :x22="dynamicPublications[7] ? dynamicPublications[7].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[7] ? dynamicPublications[7].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[7] ? dynamicPublications[7].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(8)">
                  <frame1321317475
                    :x22="dynamicPublications[8] ? dynamicPublications[8].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[8] ? dynamicPublications[8].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[8] ? dynamicPublications[8].date : undefined"
                  />
                </div>
                <div class="publication-item-wrapper" @click="goToPublication(9)">
                  <frame1321317475
                    :x22="dynamicPublications[9] ? dynamicPublications[9].x22 : frame13213174752Props.x22"
                    :hotInformationVol324="dynamicPublications[9] ? dynamicPublications[9].hotInformationVol324 : frame13213174752Props.hotInformationVol324"
                    :date="dynamicPublications[9] ? dynamicPublications[9].date : undefined"
                  />
                </div>
              </div>
              <!-- モバイル用のボタン（768px以下で表示） -->
              <div class="mobile-publication-button-wrapper">
                <x-button3 class="mobile-publication-button" @click="goToPublicationList" />
              </div>
            </div>
          </div>
        </div>


        <div class="content-view">
          <div class="frame-1321317480">
            <div class="group-10">
              <div class="group-20">
                <img
                  class="vector-6"
                  :src="vector11"
                  alt="Vector 6"
                />
                <div class="text-36 valign-text-middle inter-bold-black-24px">{{ text104 }}</div>
              </div>
              <div class="ation valign-text-middle inter-bold-black-48px">{{ otherInformation }}</div>
            </div>
          </div>
          <div class="content-view-1">
            <frame1321317474 :x22="frame13213174742Props.x22" :className="frame13213174742Props.className" />
            <div class="frame-1321317473">
              <div class="frame-1321317473-1">
                <div class="overlap-group-container">
                  <div class="overlap-group3-1" @click="goToEconomicIndicators" style="cursor: pointer;">
                    <img class="x2-2-1" :src="x222" alt="2 2" />
                    <div class="overlap-group2">
                      <div class="flex-col-2">
                        <div class="text-4 valign-text-middle inter-semi-bold-ship-gray-12px">{{ text108 }}</div>
                        <div class="viwe-more valign-text-middle inter-normal-ship-gray-10px">{{ viweMore2 }}</div>
                      </div>
                      <div class="caret-right">
                        <img
                          class="vector-14"
                          :src="vector65"
                          alt="Vector"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="overlap-group-1" @click="goToFinancialReport" style="cursor: pointer;">
                    <img class="x2-3" :src="x23" alt="2 3" />
                    <div class="overlap-group-2">
                      <div class="flex-col">
                        <div class="text-4 valign-text-middle inter-semi-bold-ship-gray-12px">{{ text106 }}</div>
                        <div class="viwe-more valign-text-middle inter-normal-ship-gray-10px">{{ viweMore3 }}</div>
                      </div>
                      <div class="caret-right">
                        <img
                          class="vector-14"
                          :src="vector61"
                          alt="Vector"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="overlap-group-1" @click="$router.push('/glossary')" style="cursor: pointer;">
                    <img class="x2-4" :src="x24" alt="2 4" />
                    <div class="overlap-group-2">
                      <div class="flex-col">
                        <div class="text-4 valign-text-middle inter-semi-bold-ship-gray-12px">{{ text107 }}</div>
                        <div class="viwe-more valign-text-middle inter-normal-ship-gray-10px">{{ viweMore4 }}</div>
                      </div>
                      <div class="caret-right">
                        <img
                          class="vector-14"
                          src="/img/vector-61.svg"
                          alt="Vector"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
      </div>
      <svg class="vector-14-1" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M1440 0.0001297L1440 75.3924C1026.71 219.527 754.524 -42.1312 3.89972e-06 75.3923L1.04907e-05 8.77342e-05L1440 0.0001297Z" fill="#ECECEC"/>
      </svg>
      
      <!-- Access Section -->
      <AccessSection />
      
      <!-- Partner Companies Section -->
      <div class="content-view-3">
        <div class="frame-1321317457-1">
          <a href="https://www.chikugin.co.jp/" target="_blank" rel="noopener noreferrer">
            <img class="image-1" :src="image2" alt="筑邦銀行" />
          </a>
          <a href="https://www.chikugin-lease.co.jp/" target="_blank" rel="noopener noreferrer">
            <img class="image-2" :src="image3" alt="ちくぎんリース" />
          </a>
          <a href="https://www.chikugin-ts.co.jp/" target="_blank" rel="noopener noreferrer">
            <img class="image-3" :src="image4" alt="ちくぎんテクノシステムズ" />
          </a>
          <a href="https://afpjp.com/" target="_blank" rel="noopener noreferrer">
            <img class="image-4" :src="image5" alt="アジア福岡パートナーズ" />
          </a>
        </div>
        <img
          class="vector-5-1"
          :src="vector5"
          alt="Vector 5"
        />
      </div>
      
      <!-- Footer Navigation -->
      <Footer v-bind="frame132131753022Props" />
      
      <!-- Fixed Side Buttons -->
      <FixedSideButtons position="bottom" />
    </div>
</template>

<script>
import Navigation from "./Navigation";
import ContactSection from "./ContactSection.vue";
import Card from "./Card";
import Frame1321317481 from "./Frame1321317481";
import Frame13213176122 from "./Frame13213176122";
import XButton3 from "./XButton3";
import XButton4 from "./XButton4";
import Frame1321317474 from "./Frame1321317474";
import Frame1321317475 from "./Frame1321317475";
import Group10 from "./Group10";
import Footer from "./Footer";
import Group27 from "./Group27";
import AccessSection from "./AccessSection.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import { homePageData } from "../data.js";
import mockServer from "@/mockServer";
import apiClient from "@/services/apiClient"; // apiClientをインポート
import vector35 from "../../public/img/vector-35.svg";
import vector152 from "../../public/img/vector-15-2.svg";
import vector63 from "../../public/img/vector-6-3.svg";
import vector11 from "../../public/img/vector-11.svg";
import vector121 from "../../public/img/vector-12-1.svg";
import vector131 from "../../public/img/vector-13.svg";
import vector132 from "../../public/img/vector-13-2.svg";
import vector74 from "../../public/img/vector-74.svg";
import vector6 from "../../public/img/vector-6.svg";
import vector91 from "../../public/img/vector-9-1.svg";
import vector101 from "../../public/img/vector-10-1.svg";
import vector111 from "../../public/img/vector-11-1.svg";
import vector65 from "../../public/img/vector-65.svg";
import vector61 from "../../public/img/vector-61.svg";
import vector5 from "../../public/img/vector-5.svg";
import vector7 from "../../public/img/vector-7.svg";
import CmsBlock from './CmsBlock.vue'
import CmsText from '@/components/CmsText.vue'
import HeroSection from '@/components/HeroSection.vue'
import { usePageText } from '@/composables/usePageText'
export default {
  name: "HomePage",
  components: {
    Navigation,
    CmsBlock,
    CmsText,
    HeroSection,
    ContactSection,
    Card,
    Frame1321317481,
    Frame13213176122,
    XButton3,
    XButton4,
    Frame1321317474,
    Frame1321317475,
    Group10,
    Footer,
    Group27,
    AccessSection,
    FixedSideButtons,
  },
  data() {
    return {
      // Hero slides data
      heroSlides: [
        {
          image: '/img/Image_fx1.jpg',
          title: '産・官・学・金（金融機関）の力で',
          subtitle: '企業活動を支援',
          buttonText: 'お問い合わせはこちら',
          onClick: () => this.goToContact()
        },
        {
          image: '/img/Image_fx6.jpg',
          title: '経営改善に役立つ',
          subtitle: '中小企業セミナー開催',
          buttonText: 'セミナー一覧を見る',
          onClick: () => this.goToSeminar()
        },
        {
          image: '/img/Image_fx3.jpg',
          title: 'ビジネスに役立つ',
          subtitle: '会員サービス',
          buttonText: '入会案内はこちら',
          onClick: () => this.goToMembership()
        }
      ],
      cmsSlides: [],
      // Import data from homePageData
      heroImage: homePageData.heroImage,
      text66: homePageData.text66,
      text67: homePageData.text67,
      text68: homePageData.text68,
      frame13213174901: homePageData.frame13213174901,
      text72: homePageData.text72,
      seminar: homePageData.seminar,
      frame1321317491: homePageData.frame1321317491,
      text69: homePageData.text69,
      publications1: homePageData.publications1,
      frame13213174902: homePageData.frame13213174902,
      text70: homePageData.text70,
      Information1: homePageData.Information1,
      frame1321317492: homePageData.frame1321317492,
      text71: homePageData.text71,
      membership: homePageData.membership,
      text112: homePageData.text112,
      forYou: homePageData.forYou,
      text113: homePageData.text113,
      image1: homePageData.image1,
      text120: homePageData.text120,
      Information2: homePageData.Information2,
      text121: homePageData.text121,
      date1: homePageData.date1,
      hotInformationVol319: homePageData.hotInformationVol319,
      vector3: homePageData.vector3,
      date2: homePageData.date2,
      text123: homePageData.text123,
      date3: homePageData.date3,
      hotInformationVol318: homePageData.hotInformationVol318,
      vector8: homePageData.vector8,
      date4: homePageData.date4,
      hotInformationVol3161: homePageData.hotInformationVol3161,
      vector10: homePageData.vector10,
      date5: homePageData.date5,
      hotInformationVol3162: homePageData.hotInformationVol3162,
      vector12: homePageData.vector12,
      text101: homePageData.text101,
      publications2: homePageData.publications2,
      x221: homePageData.x221,
      date6: homePageData.date6,
      viweMore1: homePageData.viweMore1,
      hotInformationVol324: homePageData.hotInformationVol324,
      text104: homePageData.text104,
      otherInformation: homePageData.otherInformation,
      x222: homePageData.x222,
      text108: homePageData.text108,
      viweMore2: homePageData.viweMore2,
      vector17: homePageData.vector17,
      x23: homePageData.x23,
      text106: homePageData.text106,
      viweMore3: homePageData.viweMore3,
      vector19: homePageData.vector19,
      x24: homePageData.x24,
      text107: homePageData.text107,
      viweMore4: homePageData.viweMore4,
      vector21: homePageData.vector21,


      image2: "/img/bnr_cri200.gif",
      image3: "/img/bnr_c_lease200.gif",
      image4: "/img/bnr_c_ts2.png",
      image5: "/img/bnr_c_partners.png",
      card1Props: homePageData.card1Props,
      card2Props: homePageData.card2Props,
      frame1321317481Props: homePageData.frame1321317481Props,
      frame13213174741Props: homePageData.frame13213174741Props,
      frame13213174751Props: homePageData.frame13213174751Props,
      frame13213174752Props: homePageData.frame13213174752Props,
      frame13213174742Props: homePageData.frame13213174742Props,
      frame132131753022Props: homePageData.frame132131753022Props,
      // Dynamic CMS data for publications
      dynamicMainPublication: homePageData.frame13213174741Props, // デフォルト値
      dynamicPublications: [], // 右側に表示する3件（循環）
      allPublications: [], // 全ての刊行物データ（ID参照用）
      currentIndex: 0, // 右側リストの先頭オフセット（メインは常に最新=0）
      // Dynamic news data
      dynamicNewsItems: [], // CMSから取得したお知らせデータ（カテゴリー情報含む）
      // Vector images for UI elements
      vector35,
      vector152,
      vector63,
      vector11,
      vector121,
      vector131,
      vector132,
      vector74,
      vector6,
      vector91,
      vector101,
      vector111,
      vector132,
      vector121,
      vector65,
      vector61,
      vector5,
      vector7,
    };
  },
  mounted() {
    // トップのお知らせ/お知らせ・セミナー・刊行物などを読み込む
    // モック有効時はモックから、無効時はAPIから取得（内部でエラーハンドリング済み）
    this.loadLatestData().catch(error => {
      console.error('データの読み込みに失敗しました:', error);
    });
    // CMS（PageContent: home）からヒーロースライダー/バナー画像を取得
    this.loadCmsHeroSlides().catch(() => {});
    this.loadCmsHomeImages().catch(() => {});
  },
  methods: {
    async loadCmsHeroSlides() {
      try {
        const cms = usePageText('home')
        await cms.load({ preferAdmin: true })
        const page = cms.page && cms.page.value ? cms.page.value : null
        const images = (page && page.content && page.content.images) ? page.content.images : {}
        const getImg = (key) => {
          const v = images ? images[key] : null
          if (!v) return ''
          if (typeof v === 'string') return v
          if (typeof v === 'object' && v.url) return v.url
          return ''
        }
        const getText = (key, fb='') => (cms.getText ? cms.getText(key, fb, { allowEmpty: true }) : fb)
        const getLink = (key, fb='') => (cms.getLink ? cms.getLink(key, fb, { allowEmpty: true }) : fb)

        const defaults = this.heroSlides || []
        const merged = [1,2,3].map((i, idx) => {
          const key = `hero_slide_${i}`
          const def = defaults[idx] || {}
          const img = getImg(key) || def.image || ''
          const title = getText(`${key}_title`, def.title || '')
          const subtitle = getText(`${key}_subtitle`, def.subtitle || '')
          const buttonText = getText(`${key}_button`, def.buttonText || '')
          const link = getLink(`${key}_link`, '')
          const onClick = def.onClick || (link ? (() => this.$router.push(link)) : undefined)
          return { image: img, title, subtitle, buttonText, onClick, link }
        })
        // If at least one image overridden, use merged
        const hasOverride = merged.some((s, idx) => s.image && s.image !== (defaults[idx] && defaults[idx].image))
        if (hasOverride) this.cmsSlides = merged
      } catch (e) {
        // 非致命: フォールバックに任せる
      }
    },
    async loadCmsHomeImages() {
      try {
        const cms = usePageText('home')
        await cms.load({ preferAdmin: true })
        const page = cms.page && cms.page.value ? cms.page.value : null
        const images = (page && page.content && page.content.images) ? page.content.images : {}
        const getImg = (key, defVal) => {
          const v = images && images[key]
          if (!v) return defVal
          if (typeof v === 'string') return v
          if (typeof v === 'object' && v.url) return v.url
          return defVal
        }
        this.frame13213174901 = getImg('banner_seminar', this.frame13213174901)
        this.frame1321317491  = getImg('banner_publications', this.frame1321317491)
        this.frame13213174902 = getImg('banner_info', this.frame13213174902)
        this.frame1321317492  = getImg('banner_membership', this.frame1321317492)

        const card1Image = getImg('about_card1_image', this.card1Props?.image)
        if (card1Image && card1Image !== this.card1Props?.image) {
          this.card1Props = { ...(this.card1Props || {}), image: card1Image }
        }
        const card2Image = getImg('about_card2_image', this.card2Props?.image)
        if (card2Image && card2Image !== this.card2Props?.image) {
          this.card2Props = { ...(this.card2Props || {}), image: card2Image }
        }
        const card3Image = getImg('about_card3_image', this.image1)
        if (card3Image && card3Image !== this.image1) {
          this.image1 = card3Image
        }

        const partner1 = getImg('home_partner_logo1', this.image2)
        if (partner1 && partner1 !== this.image2) this.image2 = partner1
        const partner2 = getImg('home_partner_logo2', this.image3)
        if (partner2 && partner2 !== this.image3) this.image3 = partner2
        const partner3 = getImg('home_partner_logo3', this.image4)
        if (partner3 && partner3 !== this.image4) this.image4 = partner3
        const partner4 = getImg('home_partner_logo4', this.image5)
        if (partner4 && partner4 !== this.image5) this.image5 = partner4
      } catch (_) { /* noop */ }
    },
    async loadLatestData() {
      try {
        // MockServerが有効な場合はそれを使用する（開発時など）
        if (mockServer.isEnabled) {
            console.log("Fetching data from MockServer...");
            const allNews = await mockServer.getAllNews();
            const seminars = await mockServer.getSeminars();
            const publications = await mockServer.getPublications();
            const notices = await mockServer.getNotices();
            this.updatePageData(allNews, seminars, publications, notices);
            return;
        }

        console.log("Fetching data from API...");
        // APIからデータを取得
        const [newsResponse, seminarsResponse, publicationsResponse, noticesResponse] = await Promise.all([
          apiClient.getNews({ per_page: 5 }),
          apiClient.getSeminars(),
          apiClient.getPublications(),
          apiClient.getNotices({ per_page: 5 })
        ]);

        const seminars = seminarsResponse?.data || [];
        const publications = publicationsResponse?.data?.publications || publicationsResponse?.data || [];
        const notices = (noticesResponse?.data?.data) || (noticesResponse?.data?.notices) || (noticesResponse?.data) || [];

        // お知らせ枠は「お知らせ管理」の内容を表示する
        this.updatePageData(/*allNews not used*/[], seminars, publications, notices);

      } catch (error) {
        console.error('CMSデータの取得に失敗:', error);
        // エラー時は何もしない（デフォルトデータが既に表示されているため）
      }
    },

    updatePageData(allNews, seminars, publications, notices) {
      let latestNews = [];
      
      try {
        // お知らせ枠はお知らせを使用
        const rawNotices = Array.isArray(notices) ? notices.slice(0, 5) : [];
        latestNews = rawNotices.map(n => ({
          id: n.id,
          date: n.published_at || n.created_at,
          title: n.title,
          type: 'notice'
        }));
        
        // 安全にプロパティを更新
        if (this && this.dynamicNewsItems !== undefined) {
          this.dynamicNewsItems = latestNews;
        }
      } catch (error) {
        console.error('updatePageData でエラーが発生:', error);
        return; // エラー時は処理を中断
      }
      
      // 既存のデータ変数に最新情報をマッピング
      if (latestNews.length > 0 && this) {
        // 1件目
        const news1 = latestNews[0];
        this.date1 = this.formatDate(news1.date);
        this.hotInformationVol319 = news1.title;
        
        // 2件目
        if (latestNews[1]) {
          const news2 = latestNews[1];
          this.date2 = this.formatDate(news2.date);
          this.text123 = news2.title;
        }
        
        // 3件目
        if (latestNews[2]) {
          const news3 = latestNews[2];
          this.date3 = this.formatDate(news3.date);
          this.hotInformationVol318 = news3.title;
        }
        
        // 4件目
        if (latestNews[3]) {
          const news4 = latestNews[3];
          this.date4 = this.formatDate(news4.date);
          this.hotInformationVol3161 = news4.title;
        }
        
        // 5件目
        if (latestNews[4]) {
          const news5 = latestNews[4];
          this.date5 = this.formatDate(news5.date);
          this.hotInformationVol3162 = news5.title;
        }
      }
      
      // セミナー件数表示を削除
      this.seminar = 'seminar';
      
      // 刊行物件数表示を削除
      this.publications1 = 'publications';
      const publicationArray = Array.isArray(publications) ? publications : [];
      if (publicationArray.length > 0) {
        this.allPublications = publicationArray; // 全データを保存
        this.currentIndex = 0;
        this.refreshVisiblePublications();
      }
      
      // お知らせ件数表示を削除
      this.Information1 = 'information';
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = date.getMonth() + 1;
      const day = date.getDate();
      return `${year}.${String(month).padStart(2, '0')}.${String(day).padStart(2, '0')}`;
    },
    goToMainPublication() {
      // メインは常に最新の1件目（allPublications[0]）
      const main = this.allPublications[0]
      if (main && main.id) this.$router.push(`/publication/${main.id}`)
    },
    goToPublication(index) {
      // 右側の相対インデックスから遷移（others配列から）
      const len = this.allPublications.length
      if (len <= 1) return
      const othersLen = len - 1
      const absolute = 1 + ((this.currentIndex + index) % othersLen + othersLen) % othersLen
      const publication = this.allPublications[absolute]
      if (publication && publication.id) this.$router.push(`/publication/${publication.id}`)
    },
    prevPublication() {
      const len = this.allPublications.length
      if (len <= 1) return
      const othersLen = len - 1
      this.currentIndex = (this.currentIndex - 1 + othersLen) % othersLen
      this.refreshVisiblePublications()
    },
    nextPublication() {
      const len = this.allPublications.length
      if (len <= 1) return
      const othersLen = len - 1
      this.currentIndex = (this.currentIndex + 1) % othersLen
      this.refreshVisiblePublications()
    },
    refreshVisiblePublications() {
      const len = this.allPublications.length
      if (!len) return
      // メイン: 常に最新（配列の先頭）
      const main = this.allPublications[0]
      this.dynamicMainPublication = {
        x22: main?.cover_image || main?.image_url || this.frame13213174741Props.x22,
        title: main?.title || '',
        date: main?.publication_date ? this.formatDate(main.publication_date) : ''
      }
      // 右側: 先頭を除いたothersを循環で3件表示
      const othersLen = Math.max(0, len - 1)
      const defaultImages = [
        this.frame13213174751Props.x22,
        this.frame13213174752Props.x22,
        this.frame13213174752Props.x22
      ]
      const list = []
      for (let i = 0; i < 3; i++) {
        if (!othersLen) {
          list.push({ x22: defaultImages[i], hotInformationVol324: '', date: '' })
          continue
        }
        const absolute = 1 + ((this.currentIndex + i) % othersLen)
        const item = this.allPublications[absolute]
        list.push({
          x22: item?.cover_image || item?.image_url || defaultImages[i],
          hotInformationVol324: item?.title || '',
          date: item?.publication_date ? this.formatDate(item.publication_date) : '',
          id: item?.id
        })
      }
      this.dynamicPublications = list
    },
    getCategoryLabel(newsItem) {
      // お知らせの種類に応じてカテゴリーラベルを返す
      if (!newsItem) return 'NEWS';
      
      switch (newsItem.type) {
        case 'seminar':
          return 'SEMINAR';
        case 'publication':
          return 'PUBLICATION';
        case 'notice':
          return 'NEWS';
        default:
          return 'NEWS';
      }
    },
    getCategoryClass(newsItem) {
      // お知らせの種類に応じてCSSクラスを返す
      if (!newsItem) return 'news-category';
      
      switch (newsItem.type) {
        case 'seminar':
          return 'seminar-category';
        case 'publication':
          return 'publication-category';
        case 'notice':
          return 'news-category';
        default:
          return 'news-category';
      }
    },
    goToNewsDetail(index) {
      // 指定されたインデックスのお知らせ詳細ページに遷移
      if (this.dynamicNewsItems.length > index && this.dynamicNewsItems[index]) {
        const newsItem = this.dynamicNewsItems[index];
        this.$router.push(`/news/${newsItem.id}`);
      }
    },
    goToNewsList() {
      // お知らせ一覧ページに遷移
      this.$router.push('/news');
    },
    goToPublicationList() {
      // 刊行物一覧ページに遷移
      this.$router.push('/publication');
    },
    goToSeminar() {
      // セミナーページに遷移
      this.$router.push('/seminar');
    },
    goToPublication() {
      // 刊行物ページに遷移
      this.$router.push('/publication');
    },
    goToNews() {
      // 経済指標ページに遷移
      this.$router.push('/economic-indicators');
    },
    goToMembership() {
      // 会員ページに遷移
      this.$router.push('/membership');
    },
    goToEconomicIndicators() {
      // 経済指標ページに遷移
      this.$router.push('/economic-indicators');
    },
    goToContact() {
      // お問い合わせページに遷移
      this.$router.push('/contact');
    },
    goToFinancialReport() {
      // 財務報告書ページに遷移
      this.$router.push('/financial-report');
    }
  }
  ,
  computed: {
    resolvedHeroSlides() {
      return (this.cmsSlides && this.cmsSlides.length) ? this.cmsSlides : this.heroSlides
    }
  }
};
</script>

<style scoped>
.home-page {
  background: #ffffff;
  min-height: 100vh;
  width: 100%;
  max-width: 100vw;
  overflow-x: hidden;
  box-sizing: border-box;
}

/* Hero Section */
.hero-section {
  width: 100%;
  height: 900px;
  padding: 50px;
  position: relative;
  box-sizing: border-box;
}

.hero-image {
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 20px;
  height: 100%;
  width: 100%;
  position: relative;
  overflow: hidden;
}

.hero-overlay {
  background-color: rgba(77, 77, 77, 0.2);
  border-radius: 10px;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: flex-start;
  padding: 170px 70px;
}

.hero-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 100%;
  max-width: 911px;
}

.hero-title-wrapper {
  align-items: center;
  background-color: var(--white);
  border-radius: 15px;
  display: inline-flex;
  gap: 10px;
  justify-content: flex-start;
  padding: 15px 25px;
  width: fit-content;
}

.hero-title {
  font-family: var(--font-family-inter);
  font-size: 48px;
  font-weight: 700;
  color: var(--black);
  letter-spacing: 0;
  line-height: normal;
  margin: 0;
}

.hero-subtitle-wrapper {
  align-items: center;
  background-color: var(--white);
  border-radius: 15px;
  display: inline-flex;
  gap: 10px;
  justify-content: flex-start;
  overflow: hidden;
  padding: 15px 25px;
  width: fit-content;
}

.hero-subtitle {
  font-family: var(--font-family-inter);
  font-size: 48px;
  font-weight: 700;
  color: var(--black);
  letter-spacing: 0;
  line-height: normal;
  margin: 0;
}

.hero-button-wrapper {
  align-items: center;
  background-color: var(--mandy);
  border-radius: 10px;
  display: inline-flex;
  gap: 20px;
  justify-content: flex-start;
  overflow: hidden;
  padding: 15px 25px;
  width: fit-content;
  cursor: pointer;
  transition: all 0.3s ease;
}

.hero-button-wrapper:hover {
  opacity: 0.8;
}

.hero-button-text {
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
  color: var(--white);
  letter-spacing: 0;
  line-height: normal;
  margin: 0;
}

.hero-button-icon {
  background-color: var(--white);
  border-radius: 8px;
  height: 27px;
  width: 27px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.vector-2 {
  height: 12px;
  width: 14px;
}

.frame-1321317611 {
  align-items: center;
  background-color: var(--mandy);
  border-radius: 10px;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 20px;
  justify-content: center;
  overflow: hidden;
  padding: 15px 25px;
  position: relative;
}

.text-3-1 {
  letter-spacing: 0;
  line-height: normal;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.frame-1321317612 {
  background-color: var(--white);
  border-radius: 8px;
  height: 27px;
  overflow: hidden;
  position: relative;
  width: 27px;
}

.vector-2 {
  height: 12px;
  left: 6px;
  position: absolute;
  top: 8px;
  width: 14px;
}

.frame-1321317457 {
  align-items: flex-end;
  background-color: var(--white);
  border-radius: 10px;
  display: flex;
  height: auto;
  overflow: hidden;
  padding: 20px;
  width: 80%;
  max-width: 100vw;
  margin: -150px auto 0;
  position: relative; /* z-indexが効く */
  z-index: 2; /* overlap-group7 より上 */
  gap: 20px;
  box-sizing: border-box;
}

.vector-13 {
  height: 120px;
  margin-top: 50px;
  margin-bottom: -5px;
  width: 100vw;
  min-width: 100vw;
  max-width: 100vw;
  position: relative;
  left: 50%;
  transform: translateX(-50%);
  object-fit: fill;
  object-position: center;
  box-sizing: border-box;
}

.frame-1321317490 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  overflow: hidden;
  width: calc(25% - 15px);
  flex-shrink: 0;
  aspect-ratio: 284 / 148;
  transition: all 0.3s ease;
}

.frame-1321317490:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.frame-1321317491 {
  align-items: flex-end;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center;
  padding: 0px 70px;
  position: relative;
  width: 100%;
}

.text-1 {
  align-self: stretch;
  letter-spacing: -0.3px;
  line-height: 60px;
  position: relative;
  text-align: center;
  font-size: 18px;
  font-weight: 600;
}

.seminar,
.publications,
.Information {
  align-self: stretch;
  letter-spacing: -0.48px;
  line-height: 60px;
  margin-top: -30px;
  position: relative;
  text-align: center;
  font-size: 28px;
  font-weight: 700;
}

.frame-1321317491-1 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  overflow: hidden;
  width: calc(25% - 15px);
  flex-shrink: 0;
  aspect-ratio: 284 / 148;
  transition: all 0.3s ease;
}

.frame-1321317491-1:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.frame-1321317491-2 {
  align-items: center;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center;
  padding: 0px 27px;
  position: relative;
  width: 100%;
}

.frame-1321317490-1 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  overflow: hidden;
  width: calc(25% - 15px);
  flex-shrink: 0;
  aspect-ratio: 283 / 148;
  transition: all 0.3s ease;
}

.frame-1321317490-1:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.frame-1321317491-3 {
  align-items: center;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center;
  padding: 0px 42px;
  position: relative;
  width: 100%;
}

.text-2 {
  align-self: stretch;
  letter-spacing: -0.32px;
  line-height: 60px;
  position: relative;
  text-align: center;
}

.frame-1321317492 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  overflow: hidden;
  width: calc(25% - 15px);
  flex-shrink: 0;
  aspect-ratio: 284 / 148;
  transition: all 0.3s ease;
}

.frame-1321317492:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.frame-1321317491-4 {
  align-items: center;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 100%;
  justify-content: center;
  padding: 0px 70px;
  position: relative;
  width: 100%;
}

.membership {
  letter-spacing: -0.48px;
  line-height: 60px;
  margin-left: -6.5px;
  margin-right: -6.5px;
  margin-top: -30px;
  position: relative;
  text-align: center;
  white-space: nowrap;
  width: fit-content;
  font-size: 28px;
  font-weight: 700;
}



.overlap-group8 {
  height: auto;
  position: relative;
  width: 100%;
  max-width: 100vw;
  z-index: 6;
  box-sizing: border-box;
}

.main {
  align-items: center;
  background-color: var(--cararra);
  display: flex;
  flex-direction: column;
  gap: 50px;
  padding: 0px 50px 50px;
  position: relative;
  width: 100%;
}

.about {
  align-self: center;
  height: auto;
  position: relative;
  width: 100%;
  max-width: 1340px;
}

.flex-col-1 {
  align-items: center;
  display: flex;
  flex-direction: column;
  width: 100%;
  height: auto;
}

.ico_logo-2 {
  height: 35px;
  position: relative;
  width: 100%;
  max-width: 1340px;
}

/* Section Headers */
.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-top: 20px;
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
  background: #1A1A1A;
}

.divider-text {
  color: #DA5761;
  font-size: 20px;
  font-weight: 700;
}

.vector-16 {
  height: 2px;
  width: 69px;
}

.for-you {
  color: var(--hot-pink);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-xl);
  font-weight: 700;
  height: 30px;
  letter-spacing: 0;
  line-height: 30px;
  margin-left: 21px;
  min-width: 89px;
  white-space: nowrap;
}

.vector-15 {
  height: 2px;
  width: 69px;
}

.text-48 {
  color: var(--ship-gray);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-xl);
  font-weight: 400;
  letter-spacing: -0.4px;
  line-height: 40px;
  margin-top: 25px;
  min-height: 80px;
  text-align: center;
  width: 100%;
  max-width: 1340px;
}

.frame-1321317462 {
  align-items: center;
  align-self: center;
  display: flex;
  flex-direction: column;
  gap: 10px;
  position: relative;
  width: 100%;
  max-width: 100%;
  overflow: hidden;
}

.frame-1321317456 {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 40px;
  position: relative;
  width: 100%;
  max-width: 2000px;
  box-sizing: border-box;
}

.frame-1321317482 {
  display: flex;
  gap: 20px;
  overflow: hidden;
  position: relative;
  width: 100%;
  justify-content: center;
  align-items: flex-start;
  box-sizing: border-box;
}


.card {
  position: relative;
  width: calc((100% - 40px) / 3);
  flex-shrink: 0;
  aspect-ratio: 434 / 330;
}

.image {
  align-items: flex-end;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  height: 100%;
  overflow: hidden;
  position: relative;
  width: 100%;
}

.button {
  align-items: center;
  background-color: #1A1A1A;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 50px;
  position: relative;
  min-width: 200px;
  max-width: 100%;
  box-sizing: border-box;
}

.text-55 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.content-view {
  align-items: flex-start;
  align-self: center;
  background-color: var(--white);
  border-radius: 20px;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  gap: 30px;
  overflow: hidden;
  padding: 50px;
  position: relative;
  width: 100%;
  max-width: 2000px;
}

.frame-1321317480 {
  align-items: center;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  justify-content: space-between;
  position: relative;
  width: 100%;
}

.group-10 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  height: auto;
  width: auto;
}


.flex-row {
  align-items: center;
  display: flex;
  gap: 23px;
  height: 36px;
  left: 0;
  min-width: 209px;
  position: absolute;
  top: 0;
}

.vector-6 {
  height: 2px;
  object-fit: cover;
  width: 60px;
  min-height: 2px;
}


.text-36 {
  font-size: 24px !important;
}

.frame-1321317467 {
  align-items: flex-start;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  position: relative;
  width: 100%;
  margin-bottom: 40px;
}

/* News List Styles */
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
  background: #da5761;
  margin-left: 20px;
}

.news-category.seminar {
  background: #da5761;
  color: white;
  padding: 5px 25px;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
}

.news-category.publication {
  background: #da5761;
  color: white;
  padding: 5px 25px;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
}

.news-category.news-category {
  background: #da5761;
  color: white;
  padding: 5px 25px;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: 500;
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

.news-arrow {
  color: #da5761;
  margin-left: 15px;
  transition: transform 0.3s;
}

.news-item:hover .news-arrow {
  transform: translateX(5px);
}

.frame-13213174 {
  align-self: stretch;
  height: 80px;
  position: relative;
  width: 100%;
}

.frame-13 {
  align-items: center;
  border-bottom: 0.5px dashed;
  border-bottom-width: 1px;
  border-color: var(--mandy);
  display: flex;
  height: 79px;
  position: relative;
  top: 1px;
  width: 100%;
  max-width: 100%;
}

.date {
  height: 13px;
  letter-spacing: 0;
  line-height: 36px;
  margin-bottom: 2px;
  min-width: 93px;
  white-space: nowrap;
}

.hot-info {
  height: 13px;
  letter-spacing: 0;
  line-height: 36px;
  margin-bottom: 2px;
  margin-left: 20px;
  min-width: 200px;
  white-space: nowrap;
  flex: 1;
}

.caret-circle-right {
  align-items: flex-start;
  display: flex;
  overflow: hidden;
  width: 33px;
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

.overlap-group {
  align-items: center;
  align-self: center;
  background-image: url(/img/vector-73.svg);
  background-size: 100% 100%;
  display: flex;
  height: 27px;
  justify-content: flex-end;
  margin-left: 3px;
  min-width: 27px;
  padding: 0 9.3px;
}

.vector {
  height: 12px;
  width: 7px;
}

.vector-3 {
  height: 33px;
  margin-top: -2103px;
  width: 33px;
}

.text-58 {
  height: 13px;
  letter-spacing: 0;
  line-height: 36px;
  margin-bottom: 2px;
  margin-left: 20px;
  min-width: 200px;
  white-space: nowrap;
  flex: 1;
}

.caret-circle-right-1 {
  align-items: flex-start;
  display: flex;
  overflow: hidden;
  width: 33px;
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

.vector-4 {
  height: 33px;
  margin-top: -2183px;
  width: 33px;
}

.vector-5 {
  height: 33px;
  margin-top: -2263px;
  width: 33px;
}

.vector-7 {
  height: 33px;
  margin-top: -2343px;
  width: 33px;
}

.vector-8 {
  height: 33px;
  margin-top: -2423px;
  width: 33px;
}

.frame-1321317484 {
  display: flex;
  flex-direction: column; /* 縦に並べる */
  align-items: flex-start; /* 左寄せ */
  gap: 50px;               /* 要素間のスペース */
  background-color: var(--cararra);
  padding: 50px;
  width: 100%;             /* 親に合わせる */
  z-index: 5;              /* 他要素と重なる場合だけ */
}


.group-25 {
  height: auto;
  position: relative;
  width: auto;
}

.group-20 {
  align-items: center;
  display: flex;
  gap: 26px;
  min-width: 191px;
}

.text-36 {
  height: 36px;
  letter-spacing: 0;
  line-height: 36px;
  min-width: 72px;
  white-space: nowrap;
}

.ation {
  letter-spacing: 0;
  line-height: 72px;
  white-space: nowrap;
  font-size: 48px;
}

.frame-1321317472 {
  align-items: flex-start;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 20px;
  position: relative;
  width: 100%;
  justify-content: flex-start;
}

.frame-1321317485 {
  align-items: flex-start;
  align-self: stretch;
  background-color: var(--white);
  display: flex;
  flex-direction: column;
  gap: 20px;
  justify-content: flex-end;
  overflow: hidden;
  position: relative;
  flex: 1;
}

.frame-1321317487 {
  align-items: flex-end;
  align-self: stretch;
  background-color: var(--white);
  display: flex;
  height: 91px;
  justify-content: space-between;
  position: relative;
  width: 100%;
}

/* frame-1321317487のボタンを横並びに（768px以上のみ） */
@media (min-width: 769px) {
  .frame-1321317487 .button-4 {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    gap: 10px;
  }
}

.group-18 {
  height: 43px;
  position: relative;
  width: 88px;
}

.vector-9 {
  height: 43px;
  left: 0;
  position: absolute;
  top: 0;
  width: 23px;
}

.vector-10 {
  height: 43px;
  left: 65px;
  position: absolute;
  top: 0;
  width: 24px;
}

.frame-1321317486 {
  align-items: center;
  background-color: var(--white);
  display: inline-flex;
  flex: 0 0 auto;
  gap: 20px;
  margin-right: -183px;
  position: relative;
}

.frame-1321317475 {
  height: 286px;
  overflow: hidden;
  position: relative;
  width: 259px;
}

.overlap-group3 {
  height: 286px;
  position: relative;
  width: 265px;
}

.x2-2 {
  height: 252px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 76px;
}

.overlap-group-3 {
  height: 69px;
  left: 49px;
  position: absolute;
  top: 217px;
  width: 158px;
}

.rectangle-6 {
  height: 69px;
  left: 0;
  position: absolute;
  top: 0;
  width: 27px;
}

.date-1 {
  height: 15px;
  left: 8px;
  letter-spacing: 0;
  line-height: 15px;
  position: absolute;
  top: 5px;
  width: 58px;
}

.viwe-more-1 {
  height: 12px;
  left: 8px;
  letter-spacing: 0;
  line-height: 12px;
  position: absolute;
  text-decoration: underline;
  top: 49px;
  white-space: nowrap;
}

.hot-information-vol324 {
  height: 17px;
  left: 8px;
  letter-spacing: 0;
  line-height: 16.5px;
  position: absolute;
  top: 20px;
  width: 150px;
}


.vector-container {
  align-items: flex-start;
  display: flex;
  width: 100%;
  overflow: hidden;
}

.vector-11 {
  height: 2px;
  object-fit: cover;
  width: 48.7%;
  max-width: 603px;
  min-height: 2px;
}

.vector-13-1 {
  height: 2px;
  object-fit: cover;
  width: 14.8%;
  max-width: 184px;
  min-height: 2px;
}

.vector-12 {
  height: 2px;
  object-fit: cover;
  width: 36.5%;
  max-width: 452px;
  min-height: 2px;
}

.group-25-1 {
  height: auto;
  position: relative;
  width: auto;
}


.content-view-1 {
  height: 398px;
  position: relative;
  width: 100%;
}

.frame-1321317473 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 10px;
  left: 609px;
  position: absolute;
  top: 0;
  width: calc(100% - 609px);
}

/* 1550px以上でframe-1321317473の位置を調整 */
@media (min-width: 1550px) {
  .frame-1321317473 {
    left: 920px !important;
    width: calc(100% - 920px) !important;
    height: 500px !important;
  }
}

.frame-1321317473-1 {
  align-self: stretch;
  height: 398px;
  overflow: hidden;
  position: relative;
  width: 100%;
}

.overlap-group-container {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 15px;
  position: relative;
  top: 1px;
  width: 100%;
}

.overlap-group3-1 {
  height: 122px;
  position: relative;
  width: 100%;
  transition: all 0.3s ease;
}

.overlap-group3-1:hover {
  opacity: 0.8;
}

.x2-2-1,
.x2-3,
.x2-4 {
  height: 122px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 100%;
  min-width: 100%;
  max-width: 100%;
}

.overlap-group2 {
  align-items: center;
  background-color: var(--white);
  border-radius: 10px 0px 0px 0px;
  display: flex;
  gap: 41px;
  height: 60px;
  justify-content: flex-end;
  right: 0;
  min-width: 309px;
  padding: 1.3px 12.7px;
  position: absolute;
  bottom: 0;
}

.flex-col-2 {
  align-items: flex-start;
  align-self: flex-end;
  display: flex;
  flex-direction: column;
  gap: 7px;
  min-height: 41px;
  width: 220px;
}

.text-4 {
  height: 13px;
  letter-spacing: 0;
  line-height: 18px;
  white-space: nowrap;
}

.viwe-more {
  height: 16px;
  letter-spacing: 0;
  line-height: 15px;
  text-decoration: underline;
  white-space: nowrap;
}

.caret-right {
  align-items: flex-start;
  display: flex;
  gap: 957px;
  margin-bottom: 0.96px;
  overflow: hidden;
  width: 17px;
}

.vector-14,
.vector-1 {
  align-self: center;
  height: 13px;
  margin-left: 5px;
  margin-top: 0;
  width: 7px;
}

.vector-17 {
  height: 17px;
  margin-top: -4002.07px;
  width: 17px;
}

.overlap-group-1 {
  height: 123px;
  position: relative;
  width: 100%;
  aspect-ratio: 318 / 123;
  transition: all 0.3s ease;
}

.overlap-group-1:hover {
  opacity: 0.8;
}

.overlap-group-2 {
  align-items: center;
  background-color: var(--white);
  border-radius: 10px 0px 0px 0px;
  display: flex;
  gap: 42px;
  height: 60px;
  justify-content: flex-end;
  right: 0;
  min-width: 314px;
  padding: 1.3px 13px;
  position: absolute;
  bottom: 0;
}

.flex-col {
  align-items: flex-start;
  align-self: flex-end;
  display: flex;
  flex-direction: column;
  gap: 7px;
  min-height: 41px;
  width: 224px;
}

.vector-1 {
  align-self: center;
  height: 13px;
  margin-left: 5px;
  width: 7px;
}

.vector-18 {
  height: 17px;
  margin-top: -4140px;
  width: 17px;
}

.vector-19 {
  height: 17px;
  margin-top: -4278px;
  width: 17px;
}

.vector-14-1 {
  height: 120px;
  width: 100vw;
  min-width: 100vw;
  max-width: 100vw;
  position: relative;
  left: 50%;
  transform: translateX(-50%);
  z-index: 8;
  box-sizing: border-box;
  margin-top: -5px;
}





.content-view-3 {
  align-items: center;
  background-color: var(--celeste);
  display: flex;
  flex-direction: column;
  gap: 50px;
  margin-right: -1px;
  padding: 50px;
  position: relative;
  width: 100%;
  max-width: 100vw;
  z-index: 2;
  box-sizing: border-box;
}

.frame-1321317457-1 {
  align-items: center;
  align-self: center;
  display: flex;
  flex: 0 0 auto;
  gap: 20px;
  position: relative;
  width: 100%;
  max-width: 1200px;
  justify-content: space-between;
  box-sizing: border-box;
}

.content-view-3 a {
  display: block;
  width: calc((100% - 60px) / 4);
  text-decoration: none;
  flex: 1;
}

.image-1,
.image-2,
.image-3,
.image-4 {
  height: 100px;
  object-fit: contain;
  position: relative;
  width: 100%;
  background: transparent;
}

.vector-5-1 {
  height: 1px;
  margin-bottom: -0.5px;
  object-fit: cover;
  position: relative;
  width: 1240px;
}



/* Responsive Design */
@media (max-width: 1700px) {
  .home-page {
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
  }
  
  .content-view {
    max-width: 100%;
    padding: 50px 30px;
  }
  
  .main {
    padding: 0px 30px 50px;
    width: 100%;
    max-width: 100vw;
    box-sizing: border-box;
    gap: 40px;
  }

  .hero-overlay {
    padding: 200px 50px;
  }

  .frame-1321317457 {
    width: 87%;
    flex-wrap: wrap;
    gap: 15px;
    margin: -150px auto 0;
  }
  
  .about {
    max-width: 100%;
  }
  
  .text-48 {
    width: 100%;
    max-width: 100%;
  }
  
  .hot-info,
  .text-58 {
    min-width: 150px;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .frame-1321317608 {
    width: 100%;
    max-width: 100%;
  }
  
  .frame-1321317482 {
    gap: 15px;
  }
  
  .card {
    width: calc((100% - 30px) / 3);
  }
}


@media (max-width: 1200px) {
  .hero-section {
    height: 800px;
    padding: 40px 30px;
  }
  
  .hero-overlay {
    padding: 200px 50px;
  }
  
  .hero-content {
    max-width: 800px;
    gap: 18px;
  }
  
  .hero-title,
  .hero-subtitle {
    font-size: 40px;
    color: #1A1A1A;
  }
  
  .hero-title-wrapper,
  .hero-subtitle-wrapper {
    padding: 12px 20px;
  }
  
  .hero-button-wrapper {
    padding: 12px 20px;
    gap: 15px;
  }
  
  .hero-button-text {
    font-size: 18px;
  }
  
  .hero-button-icon {
    height: 24px;
    width: 24px;
  }
  
  .frame-1321317457 {
    width: 90%;
    flex-wrap: wrap;
    gap: 15px;
    margin: -220px auto 0;
  }
  
  .frame-1321317490,
  .frame-1321317491-1,
  .frame-1321317490-1,
  .frame-1321317492 {
    width: calc(50% - 7.5px);
    min-width: 160px;
    height: 150px;
  }
  
  .frame-1321317490 .text-1,
  .frame-1321317491-1 .text-1,
  .frame-1321317490-1 .text-2,
  .frame-1321317492 .text-2 {
    font-size: 16px;
    line-height: 50px;
    margin-bottom: -10px;
    font-weight: 600;
  }
  
  .frame-1321317490 .seminar,
  .frame-1321317491-1 .publications,
  .frame-1321317490-1 .Information,
  .frame-1321317492 .membership {
    font-size: 24px;
    line-height: 50px;
    margin-top: 0;
    font-weight: 700;
  }
  
  .frame-1321317482 {
    gap: 15px;
  }
  
  .card {
    width: calc((100% - 30px) / 3);
  }
  
  .frame-1321317472 {
    gap: 30px;
  }
  
  .frame-1321317485 {
    width: 100%;
  }
  
  .frame-1321317486 {
    flex-wrap: nowrap;
    gap: 15px;
    justify-content: flex-start;
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
  }
  
  .frame-1321317475 {
    width: calc((100% - 45px) / 4);
    min-width: 200px;
  }
}

@media (max-width: 1100px) {
  .frame-1321317482 {
    flex-direction: column;
    gap: 20px;
    align-items: center;
  }
  
  .card {
    width: 100%;
    max-width: 500px;
  }
  
  .frame-1321317472 {
    flex-direction: column;
    gap: 25px;
    align-items: flex-start;
    justify-content: flex-start;
  }
  
  .content-view-1 {
    height: auto !important;
    min-height: 398px !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 30px !important;
    align-items: stretch !important;
    position: relative !important;
  }
  
  .content-view-1 > * {
    width: 100% !important;
    max-width: 100% !important;
    position: relative !important;
  }
  
  .frame-1321317473 {
    position: static !important;
    left: auto !important;
    top: auto !important;
    width: 100% !important;
    margin-top: 20px !important;
    transform: none !important;
    z-index: auto !important;
  }
  
  .frame-1321317473-1 {
    height: auto !important;
    min-height: 398px !important;
  }
  
  
  
  
  .frame-1321317473 * {
    position: relative !important;
    left: auto !important;
    top: auto !important;
  }
  
  .overlap-group-container {
    position: relative !important;
    top: 0 !important;
  }
  
  .overlap-group3-1,
  .overlap-group-1 {
    position: relative !important;
    left: auto !important;
    top: auto !important;
  }
  
  .overlap-group2,
  .overlap-group-2 {
    position: absolute !important;
    bottom: 0 !important;
    right: 0 !important;
    background-color: var(--white) !important;
    border-radius: 10px 0px 0px 0px !important;
    z-index: 10 !important;
  }
  
  .x2-2-1,
  .x2-3,
  .x2-4 {
    position: relative !important;
    left: auto !important;
    top: auto !important;
    z-index: 1 !important;
    height: 122px !important;
    width: 100% !important;
    object-fit: cover !important;
  }
  
  .frame-1321317486 {
    flex-wrap: nowrap;
    gap: 15px;
    justify-content: flex-start;
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
  }

  .frame-1321317487 {
    height: auto;
  }
}

@media (max-width: 1150px) {
  .section-header {
    gap: 25px !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .ation {
    font-size: 44px !important;
    line-height: 52px !important;
  }

  
  .divider-text {
    font-size: 18px !important;
  }

  .text-36 {
    font-size: 22px !important;
  }

  .group-10 {
    gap: 15px !important;
  }

  .vector-6 {
    height: 2px !important;
    min-height: 2px !important;
  }

  .vector-11,
  .vector-13-1,
  .vector-12 {
    height: 2px !important;
    min-height: 2px !important;
  }

  .overlap-group2,
  .overlap-group-2 {
    width: 250px !important;
    min-width: 0px !important;
  }
}

@media (max-width: 1100px) {
  .overlap-group2,
  .overlap-group-2 {
    width: 300px !important;
    min-width: 0px !important;
  }
}


@media (max-width: 900px) {
  .hero-section {
    height: 800px;
    padding: 30px 20px;
  }
  
  .hero-overlay {
    padding: 250px 40px;
  }
  
  .hero-title,
  .hero-subtitle {
    font-size: 28px;
    color: #1A1A1A;
  }
  
  .section-title {
    font-size: 29px !important;
  }
  
  .ation {
    font-size: 40px !important;
    line-height: 48px !important;
  }

  
  .hero-button-text {
    font-size: 16px;
  }
  
  .section-header {
    gap: 20px !important;
  }

  /* フォントサイズ調整 */
  .section-title {
    font-size: 29px !important;
  }

  .divider-text {
    font-size: 16px !important;
  }
  
  .frame-1321317457 {
    width: 90%;
    flex-wrap: wrap;
    gap: 15px;
    margin: -200px auto 0;
    display: flex;
    flex-direction: row;
  }
  
  .frame-1321317490,
  .frame-1321317491-1,
  .frame-1321317490-1,
  .frame-1321317492 {
    width: calc(50% - 7.5px);
    min-width: 130px;
    height: 120px;
  }
  
  .frame-1321317490 .text-1,
  .frame-1321317491-1 .text-1,
  .frame-1321317490-1 .text-2,
  .frame-1321317492 .text-2 {
    font-size: 14px;
    line-height: 40px;
    margin-bottom: 6px;
    font-weight: 600;
  }
  
  .frame-1321317490 .seminar,
  .frame-1321317491-1 .publications,
  .frame-1321317490-1 .Information,
  .frame-1321317492 .membership {
    font-size: 20px;
    line-height: 40px;
    margin-top: 0;
    font-weight: 700;
  }
  
  .main {
    padding: 0px 20px 50px;
    gap: 35px;
  }
  
  .content-view {
    padding: 30px 20px;
  }
  
  .frame-1321317482 {
    flex-direction: column;
    gap: 15px;
    align-items: center;
  }
  
  .card {
    width: 100%;
    max-width: 100%;
  }
  
  .frame-1321317472 {
    gap: 20px;
    align-items: flex-start;
    justify-content: flex-start;
  }
  
  .frame-1321317486 {
    flex-wrap: nowrap;
    gap: 15px;
    justify-content: flex-start;
    overflow-x: auto;
    overflow-y: hidden;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
  }
  
  .frame-1321317475 {
    width: 100%;
    max-width: 300px;
  }
  
  .frame-1321317473-1 {
    position: relative;
    left: 0;
    width: 100%;
  }
  
  /* 768px以下でoverlap-group-1の縦横比を維持 */
  .overlap-group-1 {
    aspect-ratio: 318 / 123;
    height: auto;
  }
  
  .overlap-group-container {
    align-items: center;
  }
  
  .overlap-group3-1,
  .overlap-group-1 {
    width: 100%;
  }
  
  .overlap-group2,
  .overlap-group-2 {
    width: 50%;
    min-width: auto;
    justify-content: space-between;
    padding: 10px 15px;
  }
  
  .flex-col,
  .flex-col-2 {
    width: auto;
    flex: 1;
  }
  
  .caret-right {
    gap: 0;
    margin-bottom: 0;
  }
  
  /* 900px以下でボタンの表示制御 */
  .desktop-button {
    display: none;
  }
  
  .mobile-button {
    display: block;
  }
  
  /* 900px以下でmobile-buttonのテキストとアイコンを横並びに */
  .mobile-button.button-4 {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    gap: 10px;
    margin: 0 auto;
    justify-content: center;
  }
  
  .frame-1321317467 {
    margin-bottom: 0px;
  }
  
  /* 900px以下でoverlap-group-1の高さをoverlap-group3-1に合わせる */
  .overlap-group-1 {
    height: 122px !important;
    width: 100% !important;
    aspect-ratio: auto;
  }
  
  .overlap-group-1 img:not(.vector-14):not(.vector-17):not(.vector-18):not(.vector-19) {
    height: 122px !important;
    width: 100% !important;
    object-fit: cover !important;
  }
  
  .frame-1321317487 {
    height: auto;
  }

  .text-36 {
    font-size: 20px !important;
  }
}

@media (max-width: 768px) {
  .hero-section {
    height: 500px;
    padding: 20px 15px;
  }
  
  .hero-overlay {
    padding: 130px 30px;
  }
  
  .hero-title,
  .hero-subtitle {
    font-size: 22px;
  }
  
  .section-title {
    font-size: 27px !important;
  }
  
  .ation {
    font-size: 36px !important;
    line-height: 44px !important;
  }

  
  .hero-button-wrapper {
    padding: 12px 20px;
    gap: 15px;
  }
  
  .hero-button-text {
    font-size: 14px;
  }
  
  .hero-button-icon {
    height: 24px;
    width: 24px;
  }
  
  .vector-2 {
    height: 10px;
    width: 12px;
  }
  
  .frame-1321317457 {
    padding: 15px;
    flex-wrap: wrap;
    gap: 10px;
    margin: -120px auto 0;
  }
  
  /* 768px以下でcontent-view-3を2列に */
  .content-view-3 {
    padding: 30px 20px;
    gap: 30px;
  }
  
  .frame-1321317457-1 {
    display: flex !important;
    gap: 15px;
  }
  
  /* 768px以下で出版物ボタンの表示制御 */
  .desktop-publication-button {
    display: none !important;
  }
  
  /* frame-1321317487の中のボタンを確実に非表示 */
  .frame-1321317487 .desktop-publication-button,
  .frame-1321317487 .button-4,
  .frame-1321317487 x-button3 {
    display: none !important;
    visibility: hidden !important;
  }
  
  
  .mobile-publication-button-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin-top: 20px;
  }
  
  .mobile-publication-button {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    gap: 10px;
    justify-content: center;
    margin: 0 auto;
  }
  
  .content-view-3 a {
    width: calc(50% - 7.5px) !important;
  }
  
  .image-1,
  .image-2,
  .image-3,
  .image-4 {
    width: 100% !important;
    height: auto;
    flex-shrink: 0;
  }
  
  .frame-1321317490,
  .frame-1321317491-1,
  .frame-1321317490-1,
  .frame-1321317492 {
    width: calc(50% - 5px);
    min-width: 150px;
    height: 100px;
  }
  
  .frame-1321317491,
  .frame-1321317491-2,
  .frame-1321317491-3,
  .frame-1321317491-4 {
    padding: 0px 15px;
  }
  
  .frame-1321317490 .text-1,
  .frame-1321317491-1 .text-1,
  .frame-1321317490-1 .text-2,
  .frame-1321317492 .text-2 {
    font-size: 13px;
    line-height: 25px;
    margin-bottom: 4px;
    font-weight: 600;
  }
  
  .frame-1321317490 .seminar,
  .frame-1321317491-1 .publications,
  .frame-1321317490-1 .Information,
  .frame-1321317492 .membership {
    font-size: 17px;
    line-height: 25px;
    margin-top: 0;
    font-weight: 700;
  }
  
  .main {
    padding: 0px 15px 30px;
    gap: 30px;
  }
  
  .content-view {
    padding: 20px 15px;
  }
  
  .main-publication-wrapper {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
  }
  
  .text-48 {
    font-size: 16px;
    line-height: 28px;
    margin-top: 20px;
  }
  
  /* 768px以下でoverlap-group-1の高さをoverlap-group3-1に合わせる */
  .overlap-group-1 {
    height: 122px !important;
    width: 100% !important;
    aspect-ratio: auto;
  }
  
  .overlap-group-1 img:not(.vector-14):not(.vector-17):not(.vector-18):not(.vector-19) {
    height: 122px !important;
    width: 100% !important;
    object-fit: cover !important;
  }
  
  /* News List Responsive for 768px */
  .news-item {
    padding: 15px 15px;
    gap: 20px;
  }
  
  .news-meta {
    gap: 15px;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .news-date {
    font-size: 16px;
    margin-bottom: 5px;
  }
  
  .news-title {
    font-size: 16px;
    margin-top: 5px;
  }
  
  .news-category {
    padding: 3px 10px;
    font-size: 0.7rem;
    margin-bottom: 5px;
  }

  .vector-13, 
  .vector-14-1 {
    height: 80px;
  }
  
  /* Date and category on same line for 768px */
  .news-date-category-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
  }
  
  .news-date {
    margin-bottom: 0;
  }
  
  .news-category {
    margin-bottom: 0;
  }
  
  .for-you {
    font-size: 18px;
    margin-left: 15px;
  }
  
  .vector-16,
  .vector-15 {
    width: 50px;
  }
  
  .frame-1321317480 {
    gap: 20px;
  }
  
  
  .frame-13 {
    padding: 10px 0;
    gap: 8px;
  }
  
  .date {
    font-size: 12px;
    line-height: 18px;
    min-width: 70px;
  }
  
  .hot-info,
  .text-58 {
    font-size: 12px;
    line-height: 18px;
    margin-left: 8px;
    min-width: 120px;
  }
  
  .category-badge {
    font-size: 9px;
    padding: 2px 5px;
    margin-left: 8px;
    margin-right: 8px;
    min-width: 45px;
  }
  
  .caret-circle-right,
  .caret-circle-right-1 {
    width: 20px;
  }
  
  .overlap-group {
    height: 18px;
    min-width: 18px;
    padding: 0 5px;
  }
  
  .vector {
    height: 7px;
    width: 4px;
  }
  
  .vector-3,
  .vector-4,
  .vector-5,
  .vector-7,
  .vector-8 {
    height: 20px;
    width: 20px;
  }
  
  .frame-1321317484 {
    padding: 20px 10px;
    gap: 20px;
  }
  
  .frame-1321317485 {
    gap: 10px;
  }
  
  .frame-1321317487 {
    padding: 10px;
  }
  
  .frame-1321317486 {
    gap: 10px;
  }
  
  .frame-1321317475 {
    min-height: 150px;
  }
  
  .overlap-group-container {
    gap: 8px;
  }
  
  .overlap-group3-1,
  .overlap-group-1 {
    min-height: 100px;
  }
  
  /* 480px以下でoverlap-group-1の高さをoverlap-group3-1に合わせる */
  .overlap-group-1 {
    height: 122px !important;
    width: 100% !important;
    aspect-ratio: auto;
  }
  
  .overlap-group-1 img:not(.vector-14):not(.vector-17):not(.vector-18):not(.vector-19) {
    height: 122px !important;
    width: 100% !important;
    object-fit: cover !important;
  }
  
  .overlap-group2,
  .overlap-group-2 {
    padding: 8px 12px;
  }
  
  .text-4 {
    font-size: 9px;
    line-height: 12px;
  }
  
  .viwe-more {
    font-size: 7px;
    line-height: 10px;
  }
  
  .vector-14,
  .vector-1 {
    height: 8px !important;
    width: 4px !important;
  }
  
  .vector-17,
  .vector-18,
  .vector-19 {
    height: 10px !important;
    width: 10px !important;
  }
  
  .content-view-3 {
    padding: 20px 10px;
  }
  
  .frame-1321317457-1 {
    gap: 10px;
  }
  
  .image-1,
  .image-2,
  .image-3,
  .image-4 {
    width: 100%;
    height: auto;
  }
  
  .vector-5-1 {
    width: 100%;
    max-width: 100%;
  }

  .text-36 {
    font-size: 19px !important;
  }



  .group-10 {
    gap: 15px;
  }

  .vector-6 {
    height: 2px !important;
    min-height: 2px !important;
  }

  .vector-11,
  .vector-13-1,
  .vector-12 {
    height: 2px !important;
    min-height: 2px !important;
  }

  .group-20 {
    margin-bottom: 0px;
  }

  .group-25,
  .group-25-1 {
    height: auto;
  }

  .content-view {
    gap: 30px;
  }
}

@media (max-width: 600px) {
  /* 600px以下でframe-1321317457の位置調整 */
  .frame-1321317457 {
    padding: 10px;
    margin-top: -60px;
    flex-wrap: wrap;
    gap: 8px;
  }
}

@media (max-width: 480px) {
  .hero-content {
    gap: 10px;
  }
  
  .hero-section {
    height: 400px;
    padding: 15px 10px;
  }
  
  .hero-overlay {
    padding: 90px 10px;
  }
  
  .section-title {
    font-size: 22px !important;
  }
  
  .ation {
    font-size: 32px !important;
    line-height: 40px !important;
  }

  
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
  
  .hero-title,
  .hero-subtitle {
    font-size: 18px;
    color: #1A1A1A;
  }
  
  .hero-button-wrapper {
    padding: 10px 15px;
    gap: 10px;
  }
  
  .hero-button-text {
    font-size: 12px;
  }
  
  .hero-button-icon {
    height: 20px;
    width: 20px;
  }
  
  .vector-2 {
    height: 8px;
    width: 10px;
  }
  
  /* 480px以下でボタンの表示制御 */
  .desktop-button {
    display: none !important;
  }
  
  .mobile-button {
    display: block !important;
  }
  
  .frame-1321317457 {
    padding: 8px;
    margin-top: -60px;
    flex-wrap: wrap;
    gap: 6px;
  }
  
  /* 480px以下でoverlap-group-1の縦横比を維持 */
  .overlap-group-1 {
    aspect-ratio: 318 / 123;
    height: auto;
  }
  
  /* 480px以下でcontent-view-3の画像を100%幅に */
  .content-view-3 {
    padding: 20px 10px;
    gap: 20px;
  }
  
  .frame-1321317457-1 {
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .content-view-3 a {
    width: 100%;
  }
  
  .image-1,
  .image-2,
  .image-3,
  .image-4 {
    width: 100%;
    height: auto;
  }
  
  .frame-1321317490,
  .frame-1321317491-1,
  .frame-1321317490-1,
  .frame-1321317492 {
    width: calc(50% - 4px);
    min-width: 140px;
    height: 60px;
  }
  
  .frame-1321317491,
  .frame-1321317491-2,
  .frame-1321317491-3,
  .frame-1321317491-4 {
    padding: 0px;
  }
  
  .frame-1321317490 .text-1,
  .frame-1321317491-1 .text-1,
  .frame-1321317490-1 .text-2,
  .frame-1321317492 .text-2 {
    font-size: 12px;
    line-height: 20px;
    margin-bottom: 3px;
    font-weight: 600;
  }
  
  .frame-1321317490 .seminar,
  .frame-1321317491-1 .publications,
  .frame-1321317490-1 .Information,
  .frame-1321317492 .membership {
    font-size: 16px;
    line-height: 20px;
    margin-top: 0;
    font-weight: 700;
  }
  
  .main {
    padding: 0px 10px 20px;
    gap: 25px;
  }
  
  .content-view {
    padding: 15px 10px;
    gap: 30px;
  }
  
  .text-48 {
    font-size: 14px;
    line-height: 24px;
    margin-top: 15px;
  }
  
  .section-header {
    gap: 22px !important;
  }

  .divider-text {
    font-size: 17px !important;
  }
  
  .for-you {
    font-size: 16px;
    margin-left: 10px;
  }
  
  .vector-16,
  .vector-15 {
    width: 40px;
  }
  
  .frame-1321317480 {
    gap: 15px;
  }
  
  
  .frame-13 {
    padding: 10px 0;
    gap: 8px;
  }
  
  .date {
    font-size: 12px;
    line-height: 18px;
    min-width: 70px;
  }
  
  .hot-info,
  .text-58 {
    font-size: 12px;
    line-height: 18px;
    margin-left: 8px;
    min-width: 120px;
  }
  
  .category-badge {
    font-size: 9px;
    padding: 2px 5px;
    margin-left: 8px;
    margin-right: 8px;
    min-width: 45px;
  }
  
  .caret-circle-right,
  .caret-circle-right-1 {
    width: 20px;
  }
  
  .overlap-group {
    height: 18px;
    min-width: 18px;
    padding: 0 5px;
  }
  
  .vector {
    height: 7px;
    width: 4px;
  }
  
  .vector-3,
  .vector-4,
  .vector-5,
  .vector-7,
  .vector-8 {
    height: 20px;
    width: 20px;
  }
  
  .frame-1321317484 {
    padding: 20px 10px;
    gap: 20px;
  }
  
  .frame-1321317485 {
    gap: 10px;
  }
  
  .frame-1321317487 {
    padding: 10px;
  }
  
  .frame-1321317486 {
    gap: 10px;
  }
  
  .frame-1321317475 {
    min-height: 150px;
  }
  
  .overlap-group-container {
    gap: 8px;
  }
  
  .overlap-group3-1,
  .overlap-group-1 {
    min-height: 100px;
  }

  .content-view-1 {
    gap: 10px !important;
  }
  
  .overlap-group2,
  .overlap-group-2 {
    padding: 8px 12px;
    width: 70% !important;
  }
  
  .text-4 {
    font-size: 9px;
    line-height: 12px;
  }
  
  .viwe-more {
    font-size: 7px;
    line-height: 10px;
  }
  
  .vector-14,
  .vector-1 {
    height: 8px !important;
    width: 4px !important;
  }
  
  .vector-17,
  .vector-18,
  .vector-19 {
    height: 10px !important;
    width: 10px !important;
  }
  
  .content-view-3 {
    padding: 20px 10px;
  }
  
  .frame-1321317457-1 {
    gap: 10px;
  }
  
  .content-view-3 a {
    width: 100%;
  }
  
  .image-1,
  .image-2,
  .image-3,
  .image-4 {
    width: 100%;
    height: auto;
  }

   .frame-1321317472 {
    gap: 10px !important;
  }
  
  /* 900px以上でボタンの表示制御 */
  .desktop-button {
    display: block;
  }
  
  .mobile-button {
    display: none;
  }
  
     /* News List Responsive for 480px */
   .news-item {
     padding: 10px 10px;
     gap: 15px;
   }
   
   .news-meta {
     gap: 10px;
     flex-direction: column;
     align-items: flex-start;
   }
   
   .news-date {
     font-size: 14px;
     margin-bottom: 3px;
   }
   
   .news-title {
     font-size: 14px;
     margin-top: 3px;
   }
   
   .news-category {
     padding: 2px 8px;
     font-size: 0.6rem;
     margin-bottom: 3px;
   }
   
   /* Date and category on same line for 480px */
   .news-date-category-row {
     display: flex;
     align-items: center;
     gap: 8px;
     margin-bottom: 3px;
   }
   
   .news-date {
     margin-bottom: 0;
   }
   
   .news-category {
     margin-bottom: 0;
   }

   .vector-13, 
  .vector-14-1 {
    height: 40px;
  }

  .vector-13 {
    margin-top: 20px;
  }

  .text-36 {
    font-size: 18px !important;
  }

  .vector-6 {
    width: 40px !important;
    height: 2px !important;
    min-height: 2px !important;
  }

  .vector-11,
  .vector-13-1,
  .vector-12 {
    height: 2px !important;
    min-height: 2px !important;
  }

  .group-10 {
    gap: 10px !important;
  }
}

/* 大画面での画像コンテナの幅制御 */
@media (min-width: 1400px) {
  .frame-1321317457-1 {
    max-width: 1000px;
    justify-content: center;
    gap: 30px;
  }
  
  .content-view-3 a {
    width: 220px;
    flex: none;
  }
}

@media (min-width: 1600px) {
  .frame-1321317457-1 {
    max-width: 1200px;
    gap: 40px;
  }
  
  .content-view-3 a {
    width: 250px;
  }
}

/* 768px以上で出版物ボタンの表示制御 */
@media (min-width: 769px) {
  .desktop-publication-button {
    display: block;
  }
  
  .mobile-publication-button-wrapper {
    display: none;
  }
  
  .mobile-publication-button {
    display: none;
  }
}

/* 900px以上でボタンの表示制御 */
@media (min-width: 900px) {
  .desktop-button {
    display: block;
  }
  
  .mobile-button {
    display: none;
  }
  
  /* 900px以上でdesktop-buttonのテキストとアイコンを横並びに */
  .desktop-button.button-4 {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    gap: 10px;
  }
}

/* タッチデバイス用のホバー効果 */
@media (hover: none) {
  .news-item-clickable:hover {
    background-color: transparent;
    transform: none;
  }
  
  .news-item:hover {
    box-shadow: none;
    transform: none;
  }
  
  .main-publication-wrapper:hover,
  .publication-item-wrapper:hover {
    transform: none;
    opacity: 1;
  }
  
  .main-publication-wrapper {
    height: auto !important;
  }
}

/* 336px以下の画面用の追加設定 */
@media (max-width: 336px) {
  .frame-1321317457 {
    flex-wrap: wrap;
    gap: 5px;
    padding: 5px;
    margin-top: -60px;
  }
  
  .frame-1321317490,
  .frame-1321317491-1,
  .frame-1321317490-1,
  .frame-1321317492 {
    width: calc(50% - 2.5px);
    min-width: calc(50% - 2.5px);
    max-width: calc(50% - 2.5px);
    flex: 0 0 calc(50% - 2.5px);
  }
}

/* 高解像度ディスプレイ対応 */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .hero-image {
    background-size: cover;
  }
  
  .frame-1321317490,
  .frame-1321317491-1,
  .frame-1321317490-1,
  .frame-1321317492 {
    background-size: cover;
  }
}

/* 印刷用スタイル */
@media print {
  .hero-section {
    height: auto;
    min-height: auto;
  }
  
  .hero-overlay {
    background-color: transparent;
  }
  
  .hero-title,
  .hero-subtitle {
    color: #000;
  }
  
  .frame-1321317457 {
    display: none;
  }
}

.main-publication-wrapper {
  cursor: pointer;
  transition: transform 0.3s ease, opacity 0.3s ease;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* 基本スタイル */
.frame-1321317474 {
  width: auto;
  max-width: none;
}

.frame-1321317473-2 {
  width: auto;
  max-width: none;
}

.x2-2-2 {
  width: auto;
  max-width: none;
}

/* 1550px以上でmain-publication-wrapperの横幅を900pxに固定 */
@media (min-width: 1550px) {
  .content-view-1 {
    height: 500px !important;
    min-height: 500px !important;
  }
  
  .frame-1321317473-1 {
    height: 500px !important;
  }
  
  .overlap-group-container {
    height: 500px !important;
    min-height: 500px !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 15px !important;
  }
  
  .overlap-group-container .overlap-group3-1,
  .overlap-group-container .overlap-group-1 {
    height: calc((500px - 30px) / 3) !important;
    flex: 1 !important;
  }
  
  .overlap-group-container .x2-2-1,
  .overlap-group-container .x2-3,
  .overlap-group-container .x2-4 {
    height: calc((500px - 30px) / 3) !important;
  }
  
  .overlap-group-container .overlap-group2,
  .overlap-group-container .overlap-group-2 {
    width: 350px !important;
    height: 70px !important;
    min-width: 350px !important;
    justify-content: space-between !important;
  }
  
  .overlap-group-container .flex-col-2,
  .overlap-group-container .flex-col {
    align-items: flex-start !important;
    text-align: left !important;
    flex: 1 !important;
  }
  
  .main-publication-wrapper {
    width: 900px !important;
    max-width: 900px !important;
  }
  
  .frame-1321317474 {
    width: 900px !important;
    max-width: 900px !important;
    min-width: 900px !important;
  }
  
  .frame-1321317473-2 {
    width: 900px !important;
    max-width: 900px !important;
    min-width: 900px !important;
  }
  
  .x2-2-2 {
    width: 900px !important;
    max-width: 900px !important;
    min-width: 900px !important;
  }
}

.main-publication-wrapper:hover {
  transform: scale(1.02);
  opacity: 0.9;
}

.frame-1321317472 {
  align-items: flex-start;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 20px;
  position: relative;
  width: 100%;
  justify-content: flex-start;
}
</style>
