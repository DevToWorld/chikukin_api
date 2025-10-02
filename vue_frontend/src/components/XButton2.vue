<template>
  <!-- ログイン時はマイページへ遷移できるボタンに変更 -->
  <router-link v-if="loggedIn" to="/myaccount" class="button-2">
    <div class="text-62 valign-text-middle inter-bold-white-13px">マイページ</div>
  </router-link>
  <!-- 未ログイン時は従来通りログインへ -->
  <router-link v-else to="/login" class="button-2">
    <div class="text-62 valign-text-middle inter-bold-white-13px">ログイン</div>
  </router-link>
  
</template>

<script>
export default {
  name: "XButton2",
  data() {
    return {
      forceKey: 0,
      lsLoggedIn: false
    }
  },
  computed: {
    loggedIn() {
      // Vuexを優先（リアクティブ）。未設定時はローカルストレージの反応型フラグを参照
      const viaStore = this.$store?.getters?.['auth/isAuthenticated']
      if (typeof viaStore === 'boolean') return viaStore
      return this.lsLoggedIn
    }
  },
  mounted() {
    this.updateLsLoggedIn()
    window.addEventListener('storage', this.updateLsLoggedIn)
    window.addEventListener('focus', this.updateLsLoggedIn)
    document.addEventListener('visibilitychange', this.updateLsLoggedIn)
  },
  beforeDestroy() {
    window.removeEventListener('storage', this.updateLsLoggedIn)
    window.removeEventListener('focus', this.updateLsLoggedIn)
    document.removeEventListener('visibilitychange', this.updateLsLoggedIn)
  },
  methods: {
    updateLsLoggedIn() {
      try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken')
        const user = localStorage.getItem('memberUser')
        this.lsLoggedIn = !!token && !!user
      } catch (e) {
        this.lsLoggedIn = false
      }
    }
  }
};
</script>

<style>
.button-2 {
  align-items: center;
  background-color: #DA5761;
  border: none !important;
  outline: none !important;
  border-radius: 8px;
  box-shadow: 0px 1px 2px #0000000d;
  display: inline-flex;
  flex: 0 0 auto;
  flex-direction: row;
  height: 40px;
  justify-content: center;
  padding: 0px 24px 0px 20px;
  position: relative;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.button-2:hover {
  background-color: #da5761;
  transform: translateY(-2px);
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
}

.text-62 {
  letter-spacing: 0;
  line-height: 40px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}
</style>
