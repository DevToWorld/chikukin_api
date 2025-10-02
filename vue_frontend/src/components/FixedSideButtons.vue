<template>
  <div class="fixed-side-buttons" :data-position="position">
    <button class="side-button login-side" @click="handleLogin">
      <svg width="26" height="25" viewBox="0 0 26 25" fill="none">
        <path d="M25.8666 24.5001C25.7789 24.6521 25.6526 24.7783 25.5005 24.8661C25.3484 24.9539 25.1759 25 25.0003 25H0.99903C0.82357 24.9998 0.651246 24.9535 0.499362 24.8657C0.347477 24.7778 0.221377 24.6516 0.133726 24.4996C0.0460738 24.3477 -4.50128e-05 24.1753 3.29665e-08 23.9999C4.50787e-05 23.8245 0.0462524 23.6521 0.133982 23.5002C2.03784 20.2094 4.97175 17.8497 8.39569 16.7311C6.70205 15.7231 5.3862 14.1871 4.65021 12.359C3.91422 10.5309 3.79879 8.51174 4.32164 6.6117C4.84448 4.71165 5.9767 3.03573 7.54442 1.84131C9.11214 0.646882 11.0287 0 12.9997 0C14.9707 0 16.8872 0.646882 18.455 1.84131C20.0227 3.03573 21.1549 4.71165 21.6777 6.6117C22.2006 8.51174 22.0852 10.5309 21.3492 12.359C20.6132 14.1871 19.2973 15.7231 17.6037 16.7311C21.0276 17.8497 23.9615 20.2094 25.8654 23.5002C25.9534 23.6521 25.9998 23.8245 26 24C26.0002 24.1755 25.9542 24.348 25.8666 24.5001Z" fill="white"/>
      </svg>
      <span>{{ displayLoginText }}</span>
    </button>
    <button class="side-button contact-side" @click="handleContact">
      <svg width="18" height="28" viewBox="0 0 18 28" fill="none">
        <path d="M15 0H3C2.20435 0 1.44129 0.316071 0.87868 0.87868C0.316071 1.44129 0 2.20435 0 3V25C0 25.7956 0.316071 26.5587 0.87868 27.1213C1.44129 27.6839 2.20435 28 3 28H15C15.7956 28 16.5587 27.6839 17.1213 27.1213C17.6839 26.5587 18 25.7956 18 25V3C18 2.20435 17.6839 1.44129 17.1213 0.87868C16.5587 0.316071 15.7956 0 15 0ZM3 2H15C15.2652 2 15.5196 2.10536 15.7071 2.29289C15.8946 2.48043 16 2.73478 16 3V4H2V3C2 2.73478 2.10536 2.48043 2.29289 2.29289C2.48043 2.10536 2.73478 2 3 2ZM15 26H3C2.73478 26 2.48043 25.8946 2.29289 25.7071C2.10536 25.5196 2 25.2652 2 25V24H16V25C16 25.2652 15.8946 25.5196 15.7071 25.7071C15.5196 25.8946 15.2652 26 15 26Z" fill="white"/>
      </svg>
      <span>{{ contactText }}</span>
    </button>
  </div>
</template>

<script>
import { useMemberAuth } from '@/composables/useMemberAuth'

export default {
  name: "FixedSideButtons",
  props: {
    loginText: {
      type: String,
      default: "ログイン"
    },
    contactText: {
      type: String,
      default: "お問い合わせ"
    },
    position: {
      type: String,
      default: "center", // "center", "bottom"
      validator: function(value) {
        return ["center", "bottom"].indexOf(value) !== -1;
      }
    }
  },
  computed: {
    // Vuex（auth）を優先し、なければローカルユーティリティで判定
    isAuthenticated() {
      try {
        if (this.$store && this.$store.getters) {
          return !!this.$store.getters['auth/isAuthenticated']
        }
      } catch (e) {}
      const { isLoggedIn } = useMemberAuth()
      return isLoggedIn()
    },
    displayLoginText() {
      return this.isAuthenticated ? 'マイページ' : this.loginText
    }
  },
  methods: {
    handleLogin() {
      const to = this.isAuthenticated ? '/myaccount' : '/member-login'
      this.$router.push(to)
    },
    handleContact() {
      this.$router.push('/contact')
    }
  }
};
</script>

<style scoped>
.fixed-side-buttons {
  position: fixed;
  top: 350px;
  right: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  z-index: 9999;
}

.fixed-side-buttons[data-position="center"] {
  top: 50%;
  transform: translateY(-50%);
}

.fixed-side-buttons[data-position="bottom"] {
  bottom: 20px;
}

.side-button {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 5px;
  color: white;
  font-size: 13px;
  font-weight: 700;
  text-align: center;
  transition: transform 0.2s ease;
}

.side-button:hover {
  transform: scale(1.05);
}

.login-side {
  background: #DA5761;
}

.contact-side {
  background: #9C3940;
}

.side-button svg {
  width: 26px;
  height: 25px;
}

.contact-side svg {
  width: 18px;
  height: 28px;
}

@media (max-width: 1150px) {
  .side-button {
  width: 85px;
  height: 85px;
  font-size: 10px;
  font-weight: 500;
  gap: 4px;
  }

  .side-button svg {
    width: 20px;
    height: 25px;
  }

  .contact-side svg {
    width: 16px;
    height: 28px;
  }
}

@media (max-width: 800px) {
  .side-button {
    display: none;
  }
}

</style>
