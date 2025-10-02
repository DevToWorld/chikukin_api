<template>
  <div class="navigation" :class="{ 'menu-open': isMenuOpen }">
    <div class="logo-section" :style="{ height: logoSectionHeight }">
      <view-component />
      <group1 />
    </div>
    <div class="nav-controls" ref="navControls">
      <div class="main-nav">
        <x-button v-if="!isMobile" />
        <x-button2 v-if="!isMobile" />
        <button class="hamburger-menu" @click="toggleMenu">
          <div class="hamburger-line"></div>
          <div class="hamburger-line"></div>
          <div class="hamburger-line"></div>
        </button>
      </div>
      <div class="sub-nav">
        <items />
      </div>
    </div>
    
    <!-- PC Menu Modal -->
    <pc-menu-modal :isOpen="isMenuOpen" @close="closeMenu" />
  </div>
</template>

<script>
import ViewComponent from "./ViewComponent";
import Group1 from "./Group1";
import XButton from "./XButton";
import XButton2 from "./XButton2";
import Items from "./ItemsNav";
import PcMenuModal from "./PcMenuModal.vue";

export default {
  name: "Navigation",
  components: {
    ViewComponent,
    Group1,
    XButton: XButton,
    XButton2,
    Items,
    PcMenuModal,
  },
  data() {
    return {
      isMenuOpen: false,
      isMobile: false,
      logoSectionHeight: 'auto'
    };
  },
  mounted() {
    this.checkScreenSize();
    this.updateLogoSectionHeight();
    window.addEventListener('resize', this.checkScreenSize);
    window.addEventListener('resize', this.updateLogoSectionHeight);
    // Re-render app when media registry updates so <img/hero backgrounds> refresh without manual resize
    try { window.addEventListener('cms-media-updated', this._onMediaUpdated) } catch (_) {}
  },
  methods: {
    _onMediaUpdated() {
      try { this.$root && this.$root.$forceUpdate() } catch(_) {}
      try { this.$forceUpdate() } catch(_) {}
    },
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
      this.toggleBodyScroll();
      console.log('Menu toggled:', this.isMenuOpen);
    },
    closeMenu() {
      this.isMenuOpen = false;
      this.toggleBodyScroll();
    },
    checkScreenSize() {
      this.isMobile = window.innerWidth <= 800;
    },
    toggleBodyScroll() {
      if (this.isMenuOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    },
    updateLogoSectionHeight() {
      this.$nextTick(() => {
        if (this.$refs.navControls) {
          const navControlsHeight = this.$refs.navControls.offsetHeight;
          this.logoSectionHeight = `${navControlsHeight}px`;
        }
      });
    }
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.checkScreenSize);
    window.removeEventListener('resize', this.updateLogoSectionHeight);
    try { window.removeEventListener('cms-media-updated', this._onMediaUpdated) } catch (_) {}
    // メニューが開いている状態でコンポーネントが破棄される場合の処理
    if (this.isMenuOpen) {
      document.body.style.overflow = '';
    }
  }
};
</script>

<style>
.navigation {
  align-items: center;
  background-color: #ffffff;
  box-shadow: 0px 3px 20px #00000026;
  display: flex;
  justify-content: space-between;
  padding: 15px 20px;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 20px;
  min-width: 0;
  flex-shrink: 1;
  transition: gap 0.3s ease;
  height: auto;
  min-height: auto;
}

.nav-controls {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: auto;
  min-width: 0;
}

.main-nav {
  display: flex;
  align-items: center;
  gap: 15px;
  justify-content: flex-end;
  min-width: 300px;
}

.sub-nav {
  display: flex;
  align-items: center;
  gap: 15px;
  justify-content: flex-start;
}

.hamburger-menu {
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 4px;
  width: 26px;
  height: 24px;
  justify-content: center;
  align-items: center;
  transition: all 0.3s ease;
}

.hamburger-line {
  width: 18px;
  height: 2px;
  background-color: #1A1A1A;
  transition: all 0.3s ease;
  transform-origin: center;
}

.hamburger-menu:hover .hamburger-line {
  background-color: #DA5761;
}

/* メニューが開いている時のアニメーション */
.navigation.menu-open .hamburger-line:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.navigation.menu-open .hamburger-line:nth-child(2) {
  opacity: 0;
}

.navigation.menu-open .hamburger-line:nth-child(3) {
  transform: rotate(-45deg) translate(7px, -6px);
}

/* サブナビのレスポンシブ対応 */
@media (max-width: 1350px) {
  .sub-nav {
    display: none;
  }

  .navigation {
    padding: 20px 15px;
  }
}

@media (max-width: 1000px) {
  .navigation {
    padding: 18px 15px;
  }
}

/* モバイルでボタンを非表示 */
@media (max-width: 800px) {
  .x-button,
  .x-button2 {
    display: none;
  }

  .navigation {
    padding: 18px 15px;
  }
  
  .logo-section {
    gap: 12px;
    height: auto;
    min-height: auto;
  }
  
  .main-nav {
    min-width: 250px;
  }
}

@media (max-width: 600px) {
  .navigation {
    padding: 16px 10px;
  }
  
  .logo-section {
    gap: 8px;
    height: auto;
    min-height: auto;
  }
  
  .main-nav {
    min-width: 200px;
    gap: 10px;
  }
}

@media (max-width: 480px) {
  .navigation {
    padding: 14px 8px;
  }
  
  .logo-section {
    gap: 6px;
    height: auto;
    min-height: auto;
  }
  
  .main-nav {
    min-width: auto;
    gap: 8px;
  }
}
</style>
