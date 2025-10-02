<template>
  <div class="contact-section">
    <div class="overlap-group6" :style="bgStyle">
      <div class="rectangle-9"></div>
      <div class="text-45 valign-text-middle">
        <CmsText :pageKey="cmsKey" fieldKey="contact_title" tag="span" :fallback="'株式会社ちくぎん地域経済研究所'" />
      </div>
      <div class="group-30-1">
        <img
          class="vector-16-1"
          src="/img/vector-15-1.svg"
          alt="Vector 16"
        />
        <div class="about-us valign-text-middle inter-bold-white-20px">
          <CmsText :pageKey="cmsKey" fieldKey="contact_label" tag="span" :fallback="'CONTACT'" />
        </div>
        <img
          class="vector-15-1"
          src="/img/vector-15-1.svg"
          alt="Vector 15"
        />
      </div>
      <div class="text-46 valign-text-middle inter-bold-white-20px">
        <CmsText :pageKey="cmsKey" fieldKey="contact_subtitle" tag="span" :fallback="'様々な分野の調査研究を通じ、企業活動などをサポートします。'" />
      </div>
      <div class="button-3" @click="goToContact">
        <div class="text-44 valign-text-middle inter-bold-white-15px">
          <CmsText :pageKey="cmsKey" fieldKey="contact_cta" tag="span" :fallback="'お問い合わせはこちら'" />
        </div>
        <frame13213176122 />
      </div>
    </div>
  </div>
</template>

<script>
import Frame13213176122 from "./Frame13213176122";
import CmsText from '@/components/CmsText.vue'
export default {
  name: "ContactSection",
  components: {
    Frame13213176122,
    CmsText,
  },
  props: {
    // Optional: override background image via media registry key
    mediaKey: {
      type: String,
      default: 'contact_section_bg'
    },
    // Fallback image path
    backgroundImage: {
      type: String,
      default: '/img/Image_fx1.jpg'
    },
    // Optional: page-specific override key. When omitted, falls back to 'site'
    cmsPageKey: {
      type: String,
      default: ''
    }
  },
  async mounted() {
    if (this.mediaKey) {
      try {
        const mod = await import('@/composables/usePageMedia')
        const { usePageMedia } = mod
        this._pageMedia = usePageMedia()
        await this._pageMedia.ensure(this.cmsKey)
        this._media = this._pageMedia._media
        // Reactivity bridge: re-render when media images resolve
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
      } catch (e) { /* noop */ }
    }
  },
  computed: {
    cmsKey() {
      // No 'site' page exists in backend; use an existing page as a safe default
      return this.cmsPageKey || 'contact'
    },
    resolvedBg() {
      try {
        if (this._pageMedia) {
          // Use slot=mediaKey with default=mediaKey (per-page mapping allows override)
          const v = this._pageMedia.getResponsiveSlot(this.mediaKey, this.mediaKey, this.backgroundImage)
          if (v) return v
        }
        const key = this.mediaKey
        if (key && this._media) {
          if (this._media.getResponsiveImage) return this._media.getResponsiveImage(key, this.backgroundImage) || this.backgroundImage
          if (this._media.getImage) return this._media.getImage(key, this.backgroundImage) || this.backgroundImage
        }
      } catch(_) {}
      return this.backgroundImage
    },
    bgStyle() {
      return { backgroundImage: `url('${this.resolvedBg}')` }
    }
  },
  methods: {
    goToContact() {
      this.$router.push('/contact');
    }
  }
};
</script>

<style>
.overlap-group6 {
  background-position: 50% 50%;
  background-size: cover;
  height: auto;
  min-height: 351px;
  width: 100%;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
  padding: 100px;
  box-sizing: border-box;
}

.rectangle-9 {
  background-color: #4d4d4db2;
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
  z-index: 1;
}

.button-3 {
  align-items: center;
  background-color: #9C3940;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  position: relative;
  z-index: 2;
  transition: background-color 0.3s ease;
  cursor: pointer;
}

.button-3:hover {
  background-color: #8a3238;
}

.text-44 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.text-45 {
  color: var(--white);
  font-family: var(--font-family-inter);
  font-size: 40px;
  font-weight: 700;
  height: 60px;
  letter-spacing: 0;
  line-height: 60px;
  white-space: nowrap;
  text-align: center;
  position: relative;
  z-index: 2;
}

.text-46 {
  height: 30px;
  letter-spacing: 0;
  line-height: 30px;
  white-space: nowrap;
  text-align: center;
  position: relative;
  z-index: 2;
}

.group-30-1 {
  align-items: center;
  display: flex;
  height: 30px;
  min-width: 270px;
  position: relative;
  z-index: 2;
}

.vector-16-1 {
  height: 2px;
  width: 69px;
}

.about-us {
  height: 30px;
  letter-spacing: 0;
  line-height: 30px;
  margin-left: 21px;
  min-width: 89px;
  white-space: nowrap;
}

.vector-15-1 {
  height: 2px;
  margin-left: 20px;
  width: 69px;
}

/* レスポンシブ対応 */
@media (max-width: 1150px) {
  .overlap-group6 {
    height: auto !important;
    min-height: 300px !important;
    padding: 40px !important;
    gap: 18px !important;
  }
  
  .rectangle-9 {
    height: 100% !important;
  }
  
  .text-45 {
    font-size: 32px !important;
    height: auto !important;
    min-height: 48px !important;
    line-height: 48px !important;
    white-space: normal !important;
    padding: 0 20px !important;
    box-sizing: border-box !important;
    width: 100% !important;
  }
  
  .text-46 {
    white-space: nowrap !important;
    padding: 0 30px !important;
    box-sizing: border-box !important;
    width: 100% !important;
    color: var(--white) !important;
  }
  
  .button-3 {
    width: 320px !important;
  }
  
  .group-30-1 {
    min-width: 240px !important;
    justify-content: center !important;
  }
  
  .vector-16-1,
  .vector-15-1 {
    width: 60px !important;
  }
  
  .about-us {
    font-size: 18px !important;
    min-width: 80px !important;
    color: var(--white) !important;
  }
}

@media (max-width: 800px) {
  .overlap-group6 {
    height: auto !important;
    min-height: 300px !important;
    padding: 60px 30px !important;
    gap: 16px !important;
  }
  
  .rectangle-9 {
    height: 100% !important;
  }
  
  .text-45 {
    font-size: 28px !important;
    height: auto !important;
    min-height: 42px !important;
    line-height: 42px !important;
    white-space: normal !important;
    padding: 0 25px !important;
    box-sizing: border-box !important;
    width: 100% !important;
  }
  
  .group-30-1 {
    min-width: 220px !important;
    justify-content: center !important;
  }
  
  .vector-16-1,
  .vector-15-1 {
    width: 55px !important;
  }
  
  .about-us {
    font-size: 16px !important;
    height: auto !important;
    min-height: 24px !important;
    line-height: 24px !important;
    min-width: 70px !important;
    margin-left: 18px !important;
    color: var(--white) !important;
  }
  
  .text-46 {
    font-size: 16px !important;
    height: auto !important;
    min-height: 24px !important;
    line-height: 24px !important;
    white-space: nowrap !important;
    padding: 0 40px !important;
    box-sizing: border-box !important;
    width: 100% !important;
    color: var(--white) !important;
  }
  
  .button-3 {
    width: 300px !important;
    padding: 12px 0px !important;
  }
  
  .text-44 {
    font-size: 13px !important;
    line-height: 19.5px !important;
  }
}

@media (max-width: 600px) {
  .overlap-group6 {
    height: auto !important;
    min-height: 280px !important;
    padding: 50px 25px !important;
    gap: 14px !important;
  }
  
  .rectangle-9 {
    height: 100% !important;
  }
  
  .text-45 {
    font-size: 24px !important;
    height: auto !important;
    min-height: 36px !important;
    line-height: 36px !important;
    white-space: normal !important;
    padding: 0 30px !important;
    box-sizing: border-box !important;
    width: 100% !important;
  }
  
  .group-30-1 {
    min-width: 200px !important;
    justify-content: center !important;
  }
  
  .vector-16-1,
  .vector-15-1 {
    width: 45px !important;
  }
  
  .about-us {
    font-size: 15px !important;
    height: auto !important;
    min-height: 22px !important;
    line-height: 22px !important;
    min-width: 65px !important;
    margin-left: 15px !important;
    color: var(--white) !important;
  }
  
  .text-46 {
    font-size: 14px !important;
    height: auto !important;
    min-height: 20px !important;
    line-height: 20px !important;
    white-space: nowrap !important;
    padding: 0 50px !important;
    box-sizing: border-box !important;
    width: 100% !important;
    color: var(--white) !important;
  }
  
  .button-3 {
    width: 260px !important;
    padding: 10px 0px !important;
  }
  
  .text-44 {
    font-size: 12px !important;
    line-height: 18px !important;
  }
}

@media (max-width: 480px) {
  .overlap-group6 {
    height: auto !important;
    min-height: 250px !important;
    padding: 40px 20px !important;
    gap: 12px !important;
  }
  
  .rectangle-9 {
    height: 100% !important;
  }
  
  .text-45 {
    font-size: 20px !important;
    height: auto !important;
    min-height: 30px !important;
    line-height: 30px !important;
    white-space: normal !important;
    padding: 0 25px !important;
    box-sizing: border-box !important;
    width: 100% !important;
  }
  
  .group-30-1 {
    min-width: 180px !important;
    justify-content: center !important;
  }
  
  .vector-16-1,
  .vector-15-1 {
    width: 35px !important;
  }
  
  .about-us {
    font-size: 13px !important;
    height: auto !important;
    min-height: 20px !important;
    line-height: 20px !important;
    color: var(--white) !important;
  }
  
  .text-46 {
    font-size: 12px !important;
    height: auto !important;
    min-height: 18px !important;
    line-height: 18px !important;
    white-space: nowrap !important;
    padding: 0 40px !important;
    box-sizing: border-box !important;
    width: 100% !important;
    color: var(--white) !important;
  }
  
  .button-3 {
    width: 220px !important;
    padding: 8px 0px !important;
  }
  
  .text-44 {
    font-size: 11px !important;
    line-height: 16.5px !important;
  }
}

@media (max-width: 400px) {
  .overlap-group6 {
    height: auto !important;
    min-height: 220px !important;
    padding: 30px 15px !important;
    gap: 10px !important;
  }
  
  .rectangle-9 {
    height: 220px !important;
  }
  
  .text-45 {
    font-size: 18px !important;
    height: auto !important;
    min-height: 27px !important;
    line-height: 27px !important;
    padding: 0 20px !important;
    width: 100% !important;
  }
  
  .group-30-1 {
    min-width: 160px !important;
    justify-content: center !important;
  }
  
  .vector-16-1,
  .vector-15-1 {
    width: 30px !important;
  }
  
  .about-us {
    font-size: 12px !important;
    height: auto !important;
    min-height: 18px !important;
    line-height: 18px !important;
    color: var(--white) !important;
  }
  
  .text-46 {
    font-size: 11px !important;
    height: auto !important;
    min-height: 16px !important;
    line-height: 16px !important;
    white-space: nowrap !important;
    padding: 0 30px !important;
    width: 100% !important;
    color: var(--white) !important;
  }
  
  .button-3 {
    width: 200px !important;
    padding: 6px 0px !important;
  }
  
  .text-44 {
    font-size: 10px !important;
    line-height: 15px !important;
  }
}
</style>
