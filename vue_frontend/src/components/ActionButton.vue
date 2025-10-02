<template>
  <section class="cta-section">
    <div class="container">
      <div class="cta-buttons">
        <button class="cta-button primary" @click="handlePrimaryClick">
          {{ primaryText }}
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
            <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#DA5761"/>
          </svg>
        </button>
        <button v-if="showSecondary" class="cta-button secondary" @click="handleSecondaryClick">
          {{ secondaryText }}
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
            <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#DA5761"/>
          </svg>
        </button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "ActionButton",
  props: {
    primaryText: {
      type: String,
      default: "お問い合わせはこちら"
    },
    secondaryText: {
      type: String,
      default: "入会はこちら"
    },
    showSecondary: {
      type: Boolean,
      default: true
    },
    maxWidth: {
      type: String,
      default: "2000px"
    }
  },
  methods: {
    handlePrimaryClick() {
      this.$emit('primary-click');
    },
    handleSecondaryClick() {
      this.$emit('secondary-click');
    }
  }
};
</script>

<style scoped>
.cta-section {
  padding: 50px 50px 80px 50px;
  background: transparent;
}

.container {
  max-width: v-bind(maxWidth);
  margin: 0 auto;
}

.cta-buttons {
  display: flex;
  flex-direction: column;
  gap: 30px;
  align-items: center;
}

.cta-button {
  background: #9C3940;
  border: none;
  border-radius: 15px;
  color: #FFFFFF;
  font-family: var(--font-family-inter);
  font-size: 20px;
  font-weight: 700;
  line-height: 1.5;
  padding: 20px 100px;
  display: flex;
  align-items: center;
  gap: 20px;
  cursor: pointer;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  width: 100%;
  max-width: 2000px;
  justify-content: center;
}

.cta-button.secondary {
  background: #DA5761;
}

.cta-button:hover {
  opacity: 0.9;
}

/* レスポンシブ対応 */
@media (max-width: 1150px) {
  /* セクションのパディング調整 */
  .cta-section {
    padding: 50px 20px 80px 20px !important;
  }
  
  /* ボタンの調整 */
  .cta-button {
    padding: 20px 80px !important;
    font-size: 18px !important;
  }
  
  /* ボタン間のgap調整 */
  .cta-buttons {
    gap: 25px !important;
  }
}

/* Desktop: arrange buttons in two columns */
@media (min-width: 900px) {
  .cta-buttons {
    flex-direction: row;
    align-items: stretch;
  }
  .cta-button {
    flex: 1 1 0;
    width: auto;
    max-width: none;
  }
}

@media (max-width: 900px) {
  /* セクション幅の調整 */
  .cta-section {
    padding: 40px 20px 70px 20px !important;
  }
  
  /* ボタンの調整 */
  .cta-button {
    padding: 18px 70px !important;
    font-size: 17px !important;
  }
  
  /* ボタン間のgap調整 */
  .cta-buttons {
    gap: 22px !important;
  }
}

@media (max-width: 768px) {
  /* タブレット縦向き対応 */
  .cta-section {
    padding: 30px 20px 60px 20px !important;
  }
  
  .cta-button {
    padding: 15px 50px !important;
    font-size: 16px !important;
  }
  
  /* ボタン間のgap調整 */
  .cta-buttons {
    gap: 20px !important;
  }
}

@media (max-width: 480px) {
  /* スマートフォン対応 */
  .cta-section {
    padding: 25px 20px 50px 20px !important;
  }
  
  .cta-button {
    padding: 12px 40px !important;
    font-size: 15px !important;
  }
  
  /* ボタン間のgap調整 */
  .cta-buttons {
    gap: 18px !important;
  }
}
</style>
