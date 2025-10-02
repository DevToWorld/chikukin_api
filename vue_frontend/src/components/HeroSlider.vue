<template>
  <div class="hero-slider">
    <div class="slider-container">
      <div 
        class="slide-track" 
        :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
      >
        <div 
          v-for="(slide, index) in slides" 
          :key="index"
          class="slide"
          :style="{ backgroundImage: `url('${slide.image}')` }"
        >
          <div class="slide-overlay">
            <div class="slide-content">
              <div class="slide-title" v-if="slide.title">{{ slide.title }}</div>
              <div class="slide-subtitle" v-if="slide.subtitle">{{ slide.subtitle }}</div>
              <div class="slide-button" v-if="slide.buttonText" @click="handleSlideClick(slide)">
                {{ slide.buttonText }}
                <svg class="button-icon" viewBox="0 0 24 24" fill="none">
                  <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                  <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#DA5761"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Navigation Dots -->
      <div class="slider-dots" v-if="slides.length > 1">
        <button 
          v-for="(slide, index) in slides" 
          :key="index"
          class="dot"
          :class="{ active: currentSlide === index }"
          @click="goToSlide(index)"
        >
          <svg width="12" height="12" viewBox="0 0 12 12">
            <circle 
              cx="6" 
              cy="6" 
              r="5" 
              :fill="currentSlide === index ? 'white' : 'none'" 
              :stroke="currentSlide === index ? 'white' : 'white'" 
              stroke-width="2"
            />
          </svg>
        </button>
      </div>
      
    </div>
  </div>
</template>

<script>
export default {
  name: "HeroSlider",
  props: {
    slides: {
      type: Array,
      required: true,
      default: () => []
    },
    autoPlay: {
      type: Boolean,
      default: true
    },
    autoPlayInterval: {
      type: Number,
      default: 5000
    }
  },
  data() {
    return {
      currentSlide: 0,
      autoPlayTimer: null
    }
  },
  mounted() {
    if (this.autoPlay && this.slides.length > 1) {
      this.startAutoPlay()
    }
  },
  beforeUnmount() {
    this.stopAutoPlay()
  },
  methods: {
    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.slides.length
    },
    previousSlide() {
      this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1
    },
    goToSlide(index) {
      this.currentSlide = index
    },
    startAutoPlay() {
      this.autoPlayTimer = setInterval(() => {
        this.nextSlide()
      }, this.autoPlayInterval)
    },
    stopAutoPlay() {
      if (this.autoPlayTimer) {
        clearInterval(this.autoPlayTimer)
        this.autoPlayTimer = null
      }
    },
    handleSlideClick(slide) {
      if (slide.onClick) {
        slide.onClick()
      } else if (slide.link) {
        this.$router.push(slide.link)
      }
    }
  }
}
</script>

<style scoped>
.hero-slider {
  width: 100%;
  height: 900px;
  padding: 50px 50px 50px 50px;
  padding-top: 130px;
  position: relative;
  box-sizing: border-box;
  overflow: hidden;
}

.slider-container {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
  border-radius: 20px;
}

.slide-track {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 0.5s ease-in-out;
}

.slide {
  min-width: 100%;
  width: 100%;
  height: 100%;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 20px;
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}

.slide-overlay {
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

.slide-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 100%;
  max-width: 911px;
}

.slide-subtitle {
  align-items: center;
  background-color: var(--white);
  border-radius: 15px;
  display: inline-flex;
  gap: 10px;
  justify-content: flex-start;
  overflow: hidden;
  padding: 15px 25px;
  width: fit-content;
  font-family: var(--font-family-inter);
  font-size: 48px;
  font-weight: 700;
  color: var(--black);
  letter-spacing: 0;
  line-height: normal;
  margin: 0;
}

.slide-title {
  align-items: center;
  background-color: var(--white);
  border-radius: 15px;
  display: inline-flex;
  gap: 10px;
  justify-content: flex-start;
  padding: 15px 25px;
  width: fit-content;
  font-family: var(--font-family-inter);
  font-size: 48px;
  font-weight: 700;
  color: var(--black);
  letter-spacing: 0;
  line-height: normal;
  margin: 0;
}

.slide-button {
  align-items: center;
  background-color: var(--mandy);
  border-radius: 8px;
  display: inline-flex;
  gap: 20px;
  justify-content: flex-start;
  overflow: hidden;
  padding: 15px 25px;
  width: fit-content;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: var(--font-family-inter);
  font-size: 16px;
  font-weight: 700;
  color: white;
}

.slide-button:hover {
  opacity: 0.8;
}

.button-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
}

.slider-dots {
  position: absolute;
  bottom: 120px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 10;
}

.dot {
  width: 12px;
  height: 12px;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.dot svg {
  width: 100%;
  height: 100%;
  transition: all 0.3s ease;
}


/* レスポンシブ対応 */

@media (max-width: 1400px) {
  .hero-slider {
    height: 850px;
    padding: 45px 40px;
    padding-top: 125px;
  }
  
  .slide-overlay {
    padding: 180px 60px;
  }
  
  .slide-content {
    max-width: 900px;
    gap: 18px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 44px;
    padding: 12px 22px;
  }
  
  .slide-button {
    font-size: 18px;
    padding: 15px 28px;
    gap: 18px;
  }
  
  .button-icon {
    width: 22px;
    height: 22px;
  }
  
  .dot {
    width: 14px;
    height: 14px;
  }
}

@media (max-width: 1350px) {
  .hero-slider {
    padding-top: 120px;
  }
}

@media (max-width: 1200px) {
  .hero-slider {
    height: 800px;
    padding: 40px 30px;
    padding-top: 110px;
  }
  
  .slide-overlay {
    padding: 200px 50px;
  }
  
  .slide-content {
    max-width: 800px;
    gap: 16px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 40px;
    padding: 12px 20px;
  }
  
  .slide-button {
    font-size: 16px;
    padding: 14px 25px;
    gap: 16px;
  }
  
  .button-icon {
    width: 20px;
    height: 20px;
  }
  
  .slider-dots {
    bottom: 200px;
  }
  
  .dot {
    width: 13px;
    height: 13px;
  }
}

@media (max-width: 1000px) {
  .hero-slider {
    height: 750px;
    padding: 35px 25px;
    padding-top: 100px;
  }
  
  .slide-overlay {
    padding: 220px 45px;
  }
  
  .slide-content {
    max-width: 700px;
    gap: 15px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 36px;
    padding: 10px 18px;
  }
  
  .slide-button {
    font-size: 15px;
    padding: 12px 22px;
    gap: 15px;
  }
  
  .button-icon {
    width: 18px;
    height: 18px;
  }
  
  .dot {
    width: 12px;
    height: 12px;
  }
}

@media (max-width: 900px) {
  .hero-slider {
    height: 700px;
    padding: 30px 20px;
    padding-top: 90px;
  }
  
  .slide-overlay {
    padding: 200px 40px;
  }
  
  .slide-content {
    max-width: 600px;
    gap: 14px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 32px;
    padding: 10px 16px;
  }
  
  .slide-button {
    font-size: 14px;
    padding: 12px 20px;
    gap: 14px;
  }
  
  .button-icon {
    width: 16px;
    height: 16px;
  }

  .slider-dots {
    bottom: 190px;
  }
  
  .dot {
    width: 11px;
    height: 11px;
  }
}

@media (max-width: 768px) {
  .hero-slider {
    height: 600px;
    padding: 20px 15px;
    padding-top: 75px;
  }
  
  .slide-overlay {
    padding: 130px 30px;
  }
  
  .slide-content {
    max-width: 100%;
    gap: 12px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 28px;
    padding: 8px 14px;
  }
  
  .slide-button {
    font-size: 13px;
    padding: 10px 18px;
    gap: 12px;
  }
  
  .button-icon {
    width: 14px;
    height: 14px;
  }

  .slider-dots {
    bottom: 110px;
  }
  
  .dot {
    width: 10px;
    height: 10px;
  }
}

@media (max-width: 600px) {
  .hero-slider {
    height: 450px;
    padding: 15px 10px;
    padding-top: 70px;
  }
  
  .slide-overlay {
    padding: 70px 20px;
  }
  
  .slide-content {
    gap: 10px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 24px;
    padding: 6px 12px;
  }
  
  .slide-button {
    font-size: 12px;
    padding: 8px 16px;
    gap: 10px;
  }

  .slider-dots {
    bottom: 55px;
  }
  
  .dot {
    width: 9px;
    height: 9px;
  }
}

@media (max-width: 480px) {
  .hero-slider {
    height: 400px;
    padding: 8px 5px;
    padding-top: 58px;
  }
  
  .slide-overlay {
    padding: 75px 12px;
  }
  
  .slide-content {
    gap: 6px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 15px;
    padding: 5px 10px;
  }
  
  .slide-button {
    font-size: 10px;
    padding: 6px 12px;
    gap: 8px;
  }

  .slider-dots {
    bottom: 60px;
  }
  
  .dot {
    width: 8px;
    height: 8px;
  }
}

@media (max-width: 360px) {
  .hero-slider {
    height: 220px;
    padding: 5px 3px;
    padding-top: 55px;
  }
  
  .slide-overlay {
    padding: 30px 8px;
  }
  
  .slide-content {
    gap: 4px;
  }
  
  .slide-title,
  .slide-subtitle {
    font-size: 16px;
    padding: 3px 6px;
  }
  
  .slide-button {
    font-size: 9px;
    padding: 5px 10px;
    gap: 6px;
  }
  
  .button-icon {
    width: 8px;
    height: 8px;
  }
  
  .dot {
    width: 7px;
    height: 7px;
  }
}
</style>
