<template>
  <div class="content-view-2">
    <div class="frame-1321317467-2">
      <group10 />
      <div class="frame-1321317467-1">
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3333.8918862916157!2d130.5312406760479!3d33.32164182344082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1z56aP5bKh55yM5LmF55WZ57Gz5biC55m-5bm05YWs5ZySMeeVqjHlj7fkuYXnlZnnsbPjg6rjgrXjg7zjg4Hjgrvjg7Pjgr_jg7zjg5Pjg6s26ZqO!5e0!3m2!1sja!2sjp!4v1757141499018!5m2!1sja!2sjp" 
          class="rectangle-3" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <div class="frame-1321317466">
          <div class="group-container">
            <div class="group-12">
              <div class="text-3 valign-text-middle inter-bold-mandy-20px">
                <CmsText :pageKey="cmsKey" fieldKey="access_title_1" tag="span" :fallback="text75" />
              </div>
              <p class="x12km-14 valign-text-middle inter-normal-ship-gray-16px">
                <CmsText :pageKey="cmsKey" fieldKey="access_subtitle_1" tag="span" :fallback="text77" />
              </p>
              <CmsText :pageKey="cmsKey" fieldKey="access_body_1" tag="div" class="text-11 valign-text-middle inter-normal-ship-gray-16px" type="html" :fallback="text76" />
              <p class="x12km-14-1 valign-text-middle inter-normal-ship-gray-16px">
                <CmsText :pageKey="cmsKey" fieldKey="access_phone" tag="span" :fallback="phone" />
              </p>
              <p class="x19km-23 valign-text-middle inter-normal-ship-gray-16px">
                <CmsText :pageKey="cmsKey" fieldKey="access_hours" tag="span" :fallback="x900" />
              </p>
            </div>
            <div class="group-12">
              <div class="text-3 valign-text-middle inter-bold-mandy-20px">
                <CmsText :pageKey="cmsKey" fieldKey="access_title_2" tag="span" :fallback="text74" />
              </div>
              <p class="x12km-14 valign-text-middle inter-normal-ship-gray-16px">
                <a href="https://maps.app.goo.gl/L8xgusobgPJdpPbp9" target="_blank" class="station-link">
                  <CmsText :pageKey="cmsKey" fieldKey="access_station_1" tag="span" :fallback="x12Km141" />
                </a>
              </p>
              <p class="x12km-14-1 valign-text-middle inter-normal-ship-gray-16px">
                <a href="https://maps.app.goo.gl/StTHwPn9fJzSDemF8" target="_blank" class="station-link">
                  <CmsText :pageKey="cmsKey" fieldKey="access_station_2" tag="span" :fallback="x12Km142" />
                </a>
              </p>
              <p class="x19km-23 valign-text-middle inter-normal-ship-gray-16px">
                <a href="https://maps.app.goo.gl/z5LoBXRdCUbaSYRS9" target="_blank" class="station-link">
                  <CmsText :pageKey="cmsKey" fieldKey="access_bus_1" tag="span" :fallback="x19Km23" />
                </a>
              </p>
            </div>
            <div class="group-12">
              <div class="text-3 valign-text-middle inter-bold-mandy-20px">
                <CmsText :pageKey="cmsKey" fieldKey="access_title_3" tag="span" :fallback="text78" />
              </div>
              <p class="x12km-14 valign-text-middle inter-normal-ship-gray-16px">
                <CmsText :pageKey="cmsKey" fieldKey="access_route_1" tag="span" :fallback="x497M6" />
              </p>
              <p class="x12km-14-1 valign-text-middle inter-normal-ship-gray-16px">
                <CmsText :pageKey="cmsKey" fieldKey="access_route_2" tag="span" :fallback="x811M10" />
              </p>
              <p class="x19km-23 valign-text-middle inter-normal-ship-gray-16px">
                <CmsText :pageKey="cmsKey" fieldKey="access_route_3" tag="span" :fallback="d1830M10" />
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</template>

<script>
import Group10 from "./Group10";
import { homePageData } from "../data";
import CmsText from '@/components/CmsText.vue'

export default {
  name: "AccessSection",
  components: {
    Group10,
    CmsText,
  },
  data() {
    return {
      // content-view-2に必要なデータ
      rectangle3: homePageData.rectangle3,
      text75: homePageData.text75,
      text77: homePageData.text77,
      text76: homePageData.text76,
      phone: homePageData.phone,
      x900: homePageData.x900,
      text74: homePageData.text74,
      x12Km141: homePageData.x12Km141,
      x12Km142: homePageData.x12Km142,
      x19Km23: homePageData.x19Km23,
      text78: homePageData.text78,
      x497M6: homePageData.x497M6,
      x811M10: homePageData.x811M10,
      d1830M10: homePageData.d1830M10,
    };
  },
  props: {
    // Optional: page-specific override key. When omitted, falls back to 'site'
    cmsPageKey: {
      type: String,
      default: ''
    }
  },
  mounted() {
    this.adjustRectangleHeight();
    window.addEventListener('resize', this.adjustRectangleHeight);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.adjustRectangleHeight);
  },
  computed: {
    // Use explicit page key if provided; otherwise fall back to an existing page
    // to avoid 404. Access content is now managed under 'company-profile'.
    cmsKey() {
      return this.cmsPageKey || 'company-profile'
    }
  },
  methods: {
    adjustRectangleHeight() {
      this.$nextTick(() => {
        const frame1321317466 = this.$el.querySelector('.frame-1321317466');
        const rectangle3 = this.$el.querySelector('.rectangle-3');
        
        if (frame1321317466 && rectangle3) {
          const frameHeight = frame1321317466.offsetHeight;
          // iframeの場合は最小高さを設定
          const minHeight = Math.max(frameHeight, 400);
          rectangle3.style.height = minHeight + 'px';
        }
      });
    }
  }
};
</script>

<style scoped>
/* Access Section */
.content-view-2 {
  align-items: center;
  background-color: var(--white);
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding: 80px 50px;
  position: relative;
  width: 100%;
  z-index: 3;
  box-sizing: border-box;
}

.frame-1321317467-2 {
  align-items: flex-start;
  background-color: var(--white);
  display: flex;
  flex-direction: column;
  gap: 30px;
  position: relative;
  width: 100%;
  max-width: 2000px;
  z-index: 3;
  box-sizing: border-box;
  padding: 0 50px;
}

.frame-1321317467-1 {
  align-items: flex-start;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 40px;
  position: relative;
  width: 100%;
  min-height: auto;
}

.rectangle-3 {
  height: 100%;
  position: relative;
  width: 45%;
  max-width: 692px;
  flex-shrink: 0;
  border-radius: 10px;
  aspect-ratio: 692 / 400;
  border: 0;
}

.frame-1321317466 {
  display: flex;
  align-items: flex-start;
  position: relative;
  width: auto;
  flex-shrink: 0;
  box-sizing: border-box;
  height: auto;
}

.group-container {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.group-12 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  height: fit-content;
  width: fit-content;
}

.text-3 {
  color: #DA5761;
  font-size: 20px;
  font-weight: 700;
  line-height: 1.5;
}

.x12km-14, .x12km-14-1, .x19km-23 {
  color: #3F3F3F;
  font-size: 16px;
  font-weight: 400;
  line-height: 1.6;
}

.text-11 {
  color: #3F3F3F;
  font-size: 16px;
  font-weight: 400;
  line-height: 1.6;
}

.station-link {
  color: #3F3F3F;
  text-decoration: none;
  transition: color 0.3s ease;
  cursor: pointer;
}

.station-link:hover {
  color: #DA5761;
  text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 1150px) {
  .content-view-2 {
    padding: 60px 40px !important;
    gap: 20px !important;
  }
  
  .frame-1321317467-2 {
    padding: 0 30px !important;
    gap: 30px !important;
  }
  
  .frame-1321317467-1 {
    flex-direction: column !important;
    gap: 30px !important;
  }
  
  .rectangle-3 {
    width: 100% !important;
    max-width: none !important;
    order: 2 !important;
    height: auto !important;
    aspect-ratio: 692 / 400 !important;
  }
  
  .frame-1321317466 {
    order: 1 !important;
    width: 100% !important;
  }
  
  .group-container {
    gap: 25px !important;
  }
  
  .text-3 {
    font-size: 18px !important;
  }
  
  .x12km-14, .x12km-14-1, .x19km-23, .text-11 {
    font-size: 15px !important;
  }
}

@media (max-width: 768px) {
  .content-view-2 {
    padding: 50px 30px !important;
    gap: 18px !important;
  }
  
  .frame-1321317467-2 {
    padding: 0 30px !important;
    gap: 30px !important;
  }
  
  .frame-1321317467-1 {
    gap: 25px !important;
  }
  
  .rectangle-3 {
    height: auto !important;
    aspect-ratio: 692 / 400 !important;
  }
  
  .group-container {
    gap: 22px !important;
  }
  
  .text-3 {
    font-size: 17px !important;
  }
  
  .x12km-14, .x12km-14-1, .x19km-23, .text-11 {
    font-size: 14px !important;
  }
}

@media (max-width: 600px) {
  .content-view-2 {
    padding: 40px 25px !important;
    gap: 16px !important;
  }
  
  .frame-1321317467-2 {
    padding: 0 25px !important;
    gap: 30px !important;
  }
  
  .frame-1321317467-1 {
    gap: 20px !important;
  }
  
  .rectangle-3 {
    height: auto !important;
    aspect-ratio: 692 / 400 !important;
  }
  
  .group-container {
    gap: 20px !important;
  }
  
  .text-3 {
    font-size: 16px !important;
  }
  
  .x12km-14, .x12km-14-1, .x19km-23, .text-11 {
    font-size: 13px !important;
  }
}

@media (max-width: 480px) {
  .content-view-2 {
    padding: 30px 20px !important;
    gap: 14px !important;
  }
  
  .frame-1321317467-2 {
    padding: 0 !important;
    gap: 30px !important;
  }
  
  .frame-1321317467-1 {
    gap: 18px !important;
  }
  
  .rectangle-3 {
    height: auto !important;
    aspect-ratio: 692 / 400 !important;
  }
  
  .group-container {
    gap: 18px !important;
  }
  
  .text-3 {
    font-size: 15px !important;
  }
  
  .x12km-14, .x12km-14-1, .x19km-23, .text-11 {
    font-size: 12px !important;
  }
}

@media (max-width: 400px) {
  .content-view-2 {
    padding: 25px 15px !important;
    gap: 12px !important;
  }
  
  .frame-1321317467-1 {
    gap: 15px !important;
  }
  
  .group-container {
    gap: 15px !important;
  }
  
  .text-3 {
    font-size: 14px !important;
  }
  
  .x12km-14, .x12km-14-1, .x19km-23, .text-11 {
    font-size: 11px !important;
  }
}
</style>
