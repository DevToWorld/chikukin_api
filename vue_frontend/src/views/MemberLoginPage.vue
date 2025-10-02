<template>
  <div class="member-login-page">
    <!-- Navigation -->
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="会員ログインページ"
      subtitle="login page"
      heroImage="/img/Image_fx8.jpg"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['会員ログイン']" />

    <!-- Main Content -->
    <div class="main-content">
      <div class="content-header">
        <h2 class="page-title">会員ログインページ</h2>
        <div class="title-decoration">
          <div class="line-left"></div>
          <span class="title-english">login page</span>
          <div class="line-right"></div>
        </div>
      </div>

      <div class="content-container">
        <!-- Login Description -->
        <div class="intro-section">
          <p class="intro-text">
            メールアドレスおよびパスワードを入力のうえ「ログイン」ボタンを押してください。<br>
            ※ログイン情報は、各会員様の窓口ご担当宛にメールまたは郵送にてご案内しております。
          </p>
        </div>

        <!-- Login Form -->
        <div class="login-form-wrapper">
          <form @submit.prevent="handleLogin" class="login-form">
            <div class="form-inputs">
              <div class="input-field">
                <input
                  v-model="email"
                  type="email"
                  required
                  class="form-input"
                  placeholder="| ID"
                />
              </div>
              
              <div class="input-field">
                <input
                  v-model="password"
                  type="password"
                  required
                  class="form-input"
                  placeholder="| PW"
                />
              </div>
            </div>
            
            <button
              type="submit"
              :disabled="loading"
              class="login-button"
            >
              <span>{{ loading ? 'ログイン中...' : 'ログインをする' }}</span>
              <svg class="login-icon" width="19" height="19" viewBox="0 0 19 19" fill="none">
                <rect x="0.5" y="0.5" width="18" height="18" rx="5" fill="white"/>
                <path d="M13.7193 10.2752L10.2194 13.875C10.1464 13.95 10.0475 13.9922 9.94427 13.9922C9.84107 13.9922 9.74211 13.95 9.66914 13.875C9.59617 13.7999 9.55517 13.6981 9.55517 13.592C9.55517 13.4858 9.59617 13.3841 9.66914 13.309L12.5055 10.3922L4.88888 10.3922C4.78574 10.3922 4.68683 10.35 4.6139 10.275C4.54097 10.2 4.5 10.0983 4.5 9.99219C4.5 9.88611 4.54097 9.78437 4.6139 9.70936C4.68683 9.63435 4.78574 9.59221 4.88888 9.59221L12.5055 9.59221L9.66914 6.67537C9.59617 6.60032 9.55517 6.49853 9.55517 6.39239C9.55517 6.28625 9.59617 6.18446 9.66914 6.1094C9.74211 6.03435 9.84107 5.99219 9.94427 5.99219C10.0475 5.99219 10.1464 6.03435 10.2194 6.1094L13.7193 9.7092C13.7554 9.74635 13.7841 9.79046 13.8037 9.83902C13.8233 9.88758 13.8333 9.93962 13.8333 9.99219C13.8333 10.0448 13.8233 10.0968 13.8037 10.1454C13.7841 10.1939 13.7554 10.238 13.7193 10.2752Z" fill="#1A1A1A"/>
              </svg>
            </button>
            
            <div v-if="error" class="error-message">
              {{ error }}
            </div>
            
            <p class="password-reset-text">
              パスワードを忘れた方は<router-link to="/contact" class="password-reset-link">こちらからお問い合わせ</router-link>してください
            </p>
          </form>
        </div>
      </div>
    </div>

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer Navigation -->
    <Footer v-bind="frame132131753022Props" />
    
    <!-- Fixed Side Buttons -->
    <FixedSideButtons position="bottom" />
  </div>
</template>

<script>
import Navigation from '@/components/Navigation.vue'
import HeroSection from '@/components/HeroSection.vue'
import Breadcrumbs from '@/components/Breadcrumbs.vue'
import AccessSection from '@/components/AccessSection.vue'
import Footer from '@/components/Footer.vue'
import FixedSideButtons from '@/components/FixedSideButtons.vue'
import { frame132131753022Data } from '@/data'
import apiClient from '@/services/apiClient'

export default {
  name: 'MemberLoginPage',
  components: {
    Navigation,
    HeroSection,
    Breadcrumbs,
    AccessSection,
    Footer,
    FixedSideButtons
  },
  data() {
    return {
      email: '',
      password: '',
      loading: false,
      error: '',
      frame132131753022Props: frame132131753022Data
    }
  },
  methods: {
    async handleLogin() {
      this.loading = true
      this.error = ''

      try {
        const res = await apiClient.login({ email: this.email, password: this.password })
        if (res && res.success && (res.access_token || res.token || res.data?.access_token)) {
          const token = res.access_token || res.token || res.data?.access_token
          // Backend returns `member` for member auth
          const user = res.member || res.data?.member || res.user || res.data?.user || null
          // 永続化
          localStorage.setItem('auth_token', token)
          // useMemberAuth互換
          localStorage.setItem('memberToken', token)
          if (user) localStorage.setItem('memberUser', JSON.stringify(user))
          // Vuexにも反映（v-restricted連動）
          if (this.$store) {
            this.$store.commit('auth/SET_AUTH', { token, user })
          }
          const redirect = this.$route.query.redirect || '/myaccount'
          this.$router.replace(redirect)
        } else {
          throw new Error(res?.message || 'ログインに失敗しました')
        }
      } catch (error) {
        this.error = 'ログインに失敗しました。メールアドレスとパスワードを確認してください。'
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.member-login-page {
  background: #ECECEC;
  min-height: 100vh;
  width: 100%;
}

/* Main Content */
.main-content {
  width: 100%;
  padding: 70px 50px;
  background: #ECECEC;
}

.content-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  margin-bottom: 40px;
}

.page-title {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 36px;
  font-weight: 700;
  color: #1A1A1A;
  letter-spacing: -0.72px;
  text-align: center;
  margin: 0;
}

.title-decoration {
  display: flex;
  align-items: center;
  gap: 15px;
  width: auto;
  min-width: 306px;
}

.line-left, .line-right {
  width: 80px;
  height: 2px;
  background: #DA5761;
  flex-shrink: 0;
}

.title-english {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: #DA5761;
}

.content-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
  width: 100%;
  max-width: 1140px;
  margin: 0 auto;
}

.intro-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
  width: 100%;
}

.intro-text {
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  line-height: normal;
  letter-spacing: -0.36px;
  text-align: center;
  margin: 0;
  max-width: 1014px;
}

/* Login Form */
.login-form-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 15px;
  padding: 40px 32px;
  border-radius: 16px;
  width: 100%;
  max-width: 480px;
}

.login-form {
  display: flex;
  width: 100%;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 40px;
}

.form-inputs {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 20px;
  width: 100%;
}

.input-field {
  width: 100%;
}

.form-input {
  display: flex;
  height: 55px;
  padding: 10px 20px;
  align-items: center;
  gap: 10px;
  width: 100%;
  border-radius: 10px;
  background: #FFFFFF;
  border: none;
  color: #727272;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 1.5;
  box-sizing: border-box;
}

.form-input:focus {
  outline: 2px solid #DA5761;
  outline-offset: -2px;
}

.form-input::placeholder {
  color: #727272;
}

.login-button {
  display: flex;
  width: 100%;
  max-width: 360px;
  padding: 14px 24px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  border-radius: 8px;
  background: #1A1A1A;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.login-button:hover:not(:disabled) {
  opacity: 0.8;
}

.login-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.login-button span {
  color: #FFFFFF;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 15px;
  font-weight: 700;
  line-height: 1.5;
}

.login-icon {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
}

.error-message {
  background: rgba(218, 87, 97, 0.08);
  border: 1px solid rgba(218, 87, 97, 0.2);
  border-radius: 8px;
  padding: 12px 16px;
  margin-top: -8px;
  color: #DA5761;
  width: 100%;
  max-width: 360px;
}

.password-reset-text {
  color: #3F3F3F;
  text-align: center;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 1.5;
  margin: 0;
  white-space: nowrap;
}

.password-reset-text a {
  color: #DA5761;
  text-decoration: none;
}

.password-reset-text a:hover {
  text-decoration: underline;
}

.password-reset-link {
  color: #DA5761 !important;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.password-reset-link:hover {
  text-decoration: underline;
  color: #C44A54 !important;
}



/* Responsive Design */
@media (max-width: 768px) {
  .main-content {
    padding: 40px 20px;
  }
  
  .content-container {
    padding: 30px 20px;
  }
  
  .page-title {
    font-size: 28px;
  }
  
  .password-reset-text {
    font-size: 16px;
    white-space: normal;
    line-height: 1.6;
  }
  
}

@media (max-width: 480px) {
  .main-content {
    padding: 30px 15px;
  }
  
  .content-container {
    padding: 20px 15px;
  }
  
  .page-title {
    font-size: 24px;
  }
  
  .password-reset-text {
    font-size: 14px;
    white-space: normal;
    line-height: 1.5;
  }
}
</style>
