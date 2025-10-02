<template>
  <div class="container-center-horizontal">
    <div class="notice-detail-mobile screen">
      <Navigation2 />
      <div class="mobile-content-wrapper">
        <div class="hero-section-mobile">
          <img
            class="hero-image-mobile"
            :src="heroImageProps.heroImage || '/img/hero-image-1.png'"
            alt="Hero Image"
          />
        </div>
        
        <div class="main-content-mobile">
          <div class="breadcrumb-mobile">
            <div class="breadcrumb-item-mobile">{{ text236 }}</div>
            <div class="breadcrumb-separator-mobile">{{ text237 }}</div>
            <div class="breadcrumb-item-mobile">{{ text238 }}</div>
            <div class="breadcrumb-separator-mobile">{{ text239 }}</div>
            <div class="breadcrumb-item-mobile">{{ text240 }}</div>
          </div>
          
          <div class="notice-content-mobile">
            <div class="notice-header-mobile">
              <div class="notice-date-mobile">{{ date }}</div>
              <div class="notice-category-mobile">
                <div class="category-label-mobile">{{ book }}</div>
              </div>
            </div>
            
            <div class="notice-title-mobile">{{ hotInformationVol319 }}</div>
            
            <div class="notice-body-mobile">
              <p class="notice-text-mobile">
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br />
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br />
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br /><br />
                
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br />
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br />
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br /><br />
                
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！<br />
                Hot information Vol.319掲載しました！Hot information Vol.319掲載しました！
              </p>
            </div>
            
            <div class="action-button-mobile">
              <x-button2 :text250="xButton2Props.text250" />
            </div>
          </div>
        </div>
      </div>
      <NavigationFooter2 />
    </div>
  </div>
</template>

<script>
import Navigation2 from "./Navigation2";
import NavigationFooter2 from "./NavigationFooter2";
import XButton2 from "./XButton2";

export default {
  name: "NoticeDetailMobile",
  components: {
    Navigation2,
    NavigationFooter2,
    XButton2,
  },
  props: {
    text236: {
      type: String,
      default: "トップ",
    },
    text237: {
      type: String,
      default: ">",
    },
    text238: {
      type: String,
      default: "お知らせ",
    },
    text239: {
      type: String,
      default: ">",
    },
    text240: {
      type: String,
      default: "お知らせ詳細",
    },
    date: {
      type: String,
      default: "2024.01.01",
    },
    book: {
      type: String,
      default: "重要",
    },
    hotInformationVol319: {
      type: String,
      default: "Hot information Vol.319を掲載しました！",
    },
    heroImageProps: {
      type: Object,
      default: () => ({
        heroImage: "/img/hero-image-1.png",
        economicAndStatisticalReports: "NOTICE",
        text25: "お知らせ詳細",
        className: "hero-image-mobile",
      }),
    },
    xButton2Props: {
      type: Object,
      default: () => ({
        text250: "戻る",
      }),
    },
  },
};
</script>

<style scoped>
.notice-detail-mobile {
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 768px;
  margin: 0 auto;
  background-color: var(--cararra);
  min-height: 100vh;
}

.mobile-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding: 0 0 20px 0;
}

.hero-section-mobile {
  width: 100%;
  overflow: hidden;
}

.hero-image-mobile {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.main-content-mobile {
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding: 0 20px;
  flex: 1;
}

.breadcrumb-mobile {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px;
  padding: 15px 0;
}

.breadcrumb-item-mobile {
  font-size: 12px;
  color: var(--ship-gray);
  line-height: 1.2;
}

.breadcrumb-separator-mobile {
  font-size: 12px;
  color: var(--ship-gray);
}

.notice-content-mobile {
  background-color: var(--white);
  border-radius: 20px;
  padding: 30px 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.notice-header-mobile {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding-bottom: 15px;
  border-bottom: 0.5px dashed var(--ship-gray);
}

.notice-date-mobile {
  font-size: 16px;
  color: var(--ship-gray);
}

.notice-category-mobile {
  display: flex;
  align-items: center;
}

.category-label-mobile {
  background-color: var(--cosmos);
  border-radius: 5px;
  padding: 6px 12px;
  font-size: 14px;
  font-weight: bold;
  color: var(--ship-gray);
}

.notice-title-mobile {
  font-size: 24px;
  font-weight: bold;
  color: var(--eerie-black);
  line-height: 1.4;
}

.notice-body-mobile {
  flex: 1;
}

.notice-text-mobile {
  font-size: 16px;
  color: var(--ship-gray);
  line-height: 1.6;
  margin: 0;
}

.action-button-mobile {
  display: flex;
  justify-content: center;
  padding-top: 20px;
}

/* Tablet styles */
@media (max-width: 1024px) and (min-width: 768px) {
  .main-content-mobile {
    padding: 0 40px;
  }
  
  .hero-image-mobile {
    height: 250px;
  }
  
  .notice-content-mobile {
    padding: 40px 30px;
  }
  
  .notice-title-mobile {
    font-size: 28px;
  }
  
  .notice-text-mobile {
    font-size: 18px;
  }
}

/* Mobile styles */
@media (max-width: 767px) {
  .mobile-content-wrapper {
    padding: 0 0 15px 0;
    gap: 15px;
  }
  
  .main-content-mobile {
    padding: 0 15px;
    gap: 15px;
  }
  
  .hero-image-mobile {
    height: 160px;
  }
  
  .breadcrumb-mobile {
    padding: 10px 0;
  }
  
  .breadcrumb-item-mobile,
  .breadcrumb-separator-mobile {
    font-size: 11px;
  }
  
  .notice-content-mobile {
    padding: 20px 15px;
    gap: 15px;
    border-radius: 15px;
  }
  
  .notice-header-mobile {
    gap: 10px;
    padding-bottom: 10px;
  }
  
  .notice-date-mobile {
    font-size: 14px;
  }
  
  .category-label-mobile {
    padding: 4px 10px;
    font-size: 12px;
  }
  
  .notice-title-mobile {
    font-size: 20px;
  }
  
  .notice-text-mobile {
    font-size: 14px;
  }
  
  .action-button-mobile {
    padding-top: 15px;
  }
}

/* Extra small mobile styles */
@media (max-width: 480px) {
  .main-content-mobile {
    padding: 0 10px;
  }
  
  .notice-content-mobile {
    padding: 15px 10px;
  }
  
  .notice-title-mobile {
    font-size: 18px;
  }
  
  .notice-text-mobile {
    font-size: 13px;
  }
}

@media (max-width: 375px) {
  .breadcrumb-mobile {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }
  
  .notice-header-mobile {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>