<template>
  <div class="items-1 inter-bold-black-15px">
    <div class="text-6 valign-text-middle" @click="navigateTo('aboutus')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_about" tag="span" :fallback="'私たちについて'" />
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 1"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('seminars')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_seminars" tag="span" :fallback="'セミナー'" />
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 2"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('publications')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_publications" tag="span" :fallback="'刊行物'" />
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 3"
    />
    <div class="text-6 valign-text-middle dropdown-item" @mouseenter="showDropdown('info')" @mouseleave="hideDropdown('info')" style="cursor: pointer; position: relative;">
      <CmsText pageKey="navigation" fieldKey="nav_information" tag="span" :fallback="'各種情報'" />
      <div class="dropdown-menu" v-show="activeDropdown === 'info'" @mouseenter="showDropdown('info')" @mouseleave="hideDropdown('info')">
        <div class="dropdown-sub-item" @click="navigateTo('economic-indicators')">
          <CmsText pageKey="navigation" fieldKey="nav_indicators" tag="span" :fallback="'経済指標'" />
        </div>
        <div class="dropdown-sub-item" @click="navigateTo('economic-statistics')">
          <CmsText pageKey="navigation" fieldKey="nav_statistics" tag="span" :fallback="'経済・調査統計'" />
        </div>
      </div>
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 4"
    />
    <div v-if="!loggedIn" class="text-6 valign-text-middle dropdown-item" @mouseenter="showDropdown('membership')" @mouseleave="hideDropdown('membership')" style="cursor: pointer; position: relative;">
      <CmsText pageKey="navigation" fieldKey="nav_membership" tag="span" :fallback="'入会案内'" />
      <div class="dropdown-menu" v-show="activeDropdown === 'membership'" @mouseenter="showDropdown('membership')" @mouseleave="hideDropdown('membership')">
        <div class="dropdown-sub-item" @click="navigateTo('membership/standard')">
          <CmsText pageKey="navigation" fieldKey="nav_membership_standard" tag="span" :fallback="'スタンダード'" />
        </div>
        <div class="dropdown-sub-item" @click="navigateTo('membership/premium')">
          <CmsText pageKey="navigation" fieldKey="nav_membership_premium" tag="span" :fallback="'プレミアム'" />
        </div>
      </div>
    </div>
    <div v-if="loggedIn" class="text-6 valign-text-middle" @click="navigateTo('membership/premium')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_premium_benefits" tag="span" :fallback="'プレミアム会員の特典'" />
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 5"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('news')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_news" tag="span" :fallback="'お知らせ'" />
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 6"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('company')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_company" tag="span" :fallback="'会社概要'" />
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 7"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('faq')" style="cursor: pointer;">
      <CmsText pageKey="navigation" fieldKey="nav_faq" tag="span" :fallback="'よくある質問'" />
    </div>
  </div>
</template>

<script>
import CmsText from '@/components/CmsText.vue'
export default {
  name: "ItemsNav",
  components: { CmsText },
  data() {
    return {
      activeDropdown: null,
      hideTimer: null
    };
  },
  computed: {
    loggedIn() {
      try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken')
        const user = localStorage.getItem('memberUser')
        return !!token && !!user
      } catch (e) {
        return false
      }
    }
  },
  methods: {
    navigateTo(route) {
      console.log('Navigating to:', route);
      // トップの場合はルートパス（/）に遷移
      if (route === '') {
        this.$router.push('/');
      } else {
        // その他の場合は通常のルーティング
        this.$router.push('/' + route);
      }
    },
    showDropdown(dropdownType) {
      // 既存のタイマーをクリア
      if (this.hideTimer) {
        clearTimeout(this.hideTimer);
        this.hideTimer = null;
      }
      this.activeDropdown = dropdownType;
    },
    hideDropdown(dropdownType) {
      // 少し遅延させてから非表示にする
      this.hideTimer = setTimeout(() => {
        if (this.activeDropdown === dropdownType) {
          this.activeDropdown = null;
        }
      }, 100);
    }
  }
};
</script>

<style>
.items-1 {
  align-items: center;
  display: inline-flex;
  gap: 15px;
  position: relative;
}

.text-6 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.vector-22 {
  height: 10px;
  position: relative;
  width: 1px;
}

.dropdown-item {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  min-width: 150px;
  padding: 8px 0;
  margin-top: 5px;
}

.dropdown-sub-item {
  padding: 8px 16px;
  color: #3f3f3f;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.dropdown-sub-item:hover {
  background-color: #f5f5f5;
  color: #DA5761;
}
</style>
