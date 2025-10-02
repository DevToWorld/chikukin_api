<template>
  <div class="items-1 inter-bold-black-15px">
    <div class="text-6 valign-text-middle" @click="navigateTo('aboutus')" style="cursor: pointer;">私たちについて</div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 1"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('seminars')" style="cursor: pointer;">セミナー</div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 2"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('publications')" style="cursor: pointer;">刊行物</div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 3"
    />
    <div class="text-6 valign-text-middle dropdown-item" @mouseenter="showDropdown('info')" @mouseleave="hideDropdown('info')" style="cursor: pointer; position: relative;">
      各種情報
      <div class="dropdown-menu" v-show="activeDropdown === 'info'" @mouseenter="showDropdown('info')" @mouseleave="hideDropdown('info')">
        <div class="dropdown-sub-item" @click="navigateTo('economic-indicators')">経済指標</div>
        <div class="dropdown-sub-item" @click="navigateTo('economic-statistics')">経済・調査統計</div>
      </div>
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 4"
    />
    <div class="text-6 valign-text-middle dropdown-item" @mouseenter="showDropdown('membership')" @mouseleave="hideDropdown('membership')" style="cursor: pointer; position: relative;">
      入会案内
      <div class="dropdown-menu" v-show="activeDropdown === 'membership'" @mouseenter="showDropdown('membership')" @mouseleave="hideDropdown('membership')">
        <div class="dropdown-sub-item" @click="navigateTo('membership/standard')">スタンダード</div>
        <div class="dropdown-sub-item" @click="navigateTo('membership/premium')">プレミアム</div>
      </div>
    </div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 5"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('news')" style="cursor: pointer;">お知らせ</div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 6"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('company')" style="cursor: pointer;">会社概要</div>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 7"
    />
    <div class="text-6 valign-text-middle" @click="navigateTo('faq')" style="cursor: pointer;">よくある質問</div>
  </div>
</template>

<script>
export default {
  name: "Items",
  data() {
    return {
      activeDropdown: null,
      hideTimer: null
    };
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
  left: 0;
  position: absolute;
  top: 45px;
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
