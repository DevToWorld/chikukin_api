<template>
  <div class="member-register-page">
    <div class="register-container">
      <div class="register-card">
        <div class="logo-section">
          <img
            class="logo"
            src="/img/ico-logo-2.svg"
            alt="Logo"
          />
          <h1 class="title inter-bold-black-24px">新規会員登録</h1>
          <p class="subtitle inter-normal-ship-gray-16px">アカウントを作成して全機能にアクセス</p>
        </div>
        
        <form @submit.prevent="handleRegister" class="register-form">
          <div class="form-row">
            <div class="form-group">
              <label for="lastName" class="form-label inter-semi-bold-ship-gray-12px">
                姓
              </label>
              <input
                id="lastName"
                v-model="formData.lastName"
                type="text"
                required
                class="form-input inter-normal-ship-gray-16px"
                placeholder="田中"
              />
            </div>
            <div class="form-group">
              <label for="firstName" class="form-label inter-semi-bold-ship-gray-12px">
                名
              </label>
              <input
                id="firstName"
                v-model="formData.firstName"
                type="text"
                required
                class="form-input inter-normal-ship-gray-16px"
                placeholder="太郎"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="email" class="form-label inter-semi-bold-ship-gray-12px">
              メールアドレス
            </label>
            <input
              id="email"
              v-model="formData.email"
              type="email"
              required
              class="form-input inter-normal-ship-gray-16px"
              placeholder="member@example.com"
            />
          </div>

          <div class="form-group">
            <label for="phone" class="form-label inter-semi-bold-ship-gray-12px">
              電話番号（任意）
            </label>
            <input
              id="phone"
              v-model="formData.phone"
              type="tel"
              class="form-input inter-normal-ship-gray-16px"
              placeholder="090-1234-5678"
            />
          </div>

          <div class="form-group">
            <label for="password" class="form-label inter-semi-bold-ship-gray-12px">
              パスワード
            </label>
            <input
              id="password"
              v-model="formData.password"
              type="password"
              required
              class="form-input inter-normal-ship-gray-16px"
              placeholder="8文字以上"
              @input="checkPasswordStrength"
            />
            <div class="password-strength">
              <div class="strength-bar">
                <div class="strength-fill" :style="{ width: passwordStrength + '%', backgroundColor: passwordStrengthColor }"></div>
              </div>
              <span class="strength-text inter-normal-ship-gray-10px">{{ passwordStrengthText }}</span>
            </div>
          </div>

          <div class="form-group">
            <label for="passwordConfirm" class="form-label inter-semi-bold-ship-gray-12px">
              パスワード（確認）
            </label>
            <input
              id="passwordConfirm"
              v-model="formData.passwordConfirm"
              type="password"
              required
              class="form-input inter-normal-ship-gray-16px"
              placeholder="パスワードを再入力"
            />
          </div>

          <div class="form-group">
            <label for="membershipType" class="form-label inter-semi-bold-ship-gray-12px">
              会員プラン選択
            </label>
            <div class="membership-plans">
              <div 
                class="plan-card"
                :class="{ active: formData.membershipType === 'basic' }"
                @click="formData.membershipType = 'basic'"
              >
                <div class="plan-header">
                  <img src="/img/vector-70.svg" alt="Basic" class="plan-icon" />
                  <h3 class="inter-bold-black-15px">ベーシック</h3>
                </div>
                <p class="plan-price inter-bold-mandy-20px">無料</p>
                <ul class="plan-features inter-normal-ship-gray-10px">
                  <li>基本統計の閲覧</li>
                  <li>お知らせ記事（一部）</li>
                </ul>
              </div>

              <div 
                class="plan-card"
                :class="{ active: formData.membershipType === 'standard' }"
                @click="formData.membershipType = 'standard'"
              >
                <div class="plan-header">
                  <img src="/img/vector-71.svg" alt="Standard" class="plan-icon" />
                  <h3 class="inter-bold-black-15px">スタンダード</h3>
                </div>
                <p class="plan-price inter-bold-mandy-20px">¥1,980/月</p>
                <ul class="plan-features inter-normal-ship-gray-10px">
                  <li>全統計データ</li>
                  <li>財務レポート</li>
                  <li>データエクスポート</li>
                </ul>
              </div>

              <div 
                class="plan-card"
                :class="{ active: formData.membershipType === 'premium' }"
                @click="formData.membershipType = 'premium'"
              >
                <div class="plan-header">
                  <img src="/img/vector-73.svg" alt="Premium" class="plan-icon" />
                  <h3 class="inter-bold-black-15px">プレミアム</h3>
                </div>
                <p class="plan-price inter-bold-mandy-20px">¥4,980/月</p>
                <ul class="plan-features inter-normal-ship-gray-10px">
                  <li>全機能アクセス</li>
                  <li>APIアクセス</li>
                  <li>優先サポート</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input
                type="checkbox"
                v-model="formData.agreeTerms"
                required
                class="checkbox"
              />
              <span class="inter-normal-ship-gray-15px">
                <a href="/terms" target="_blank" class="link">利用規約</a>および
                <a href="/privacy" target="_blank" class="link">プライバシーポリシー</a>に同意します
              </span>
            </label>
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input
                type="checkbox"
                v-model="formData.newsletter"
                class="checkbox"
              />
              <span class="inter-normal-ship-gray-15px">
                最新情報やお得な情報をメールで受け取る
              </span>
            </label>
          </div>

          <div v-if="error" class="error-message inter-normal-ship-gray-15px">
            <img src="/img/vector-28.svg" alt="Error" class="error-icon" />
            {{ error }}
          </div>

          <button
            type="submit"
            :disabled="loading || !formData.agreeTerms"
            class="register-button inter-bold-white-15px"
          >
            {{ loading ? '登録中...' : '会員登録' }}
          </button>

          <div class="divider">
            <span class="divider-text inter-normal-ship-gray-10px">または</span>
          </div>

          <router-link
            to="/login"
            class="login-button inter-bold-mandy-15px"
          >
            既にアカウントをお持ちの方
          </router-link>
        </form>

        <div class="footer-text inter-normal-ship-gray-10px">
          セキュアな接続で保護されています
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { getApiUrl } from '@/config/api'

export default {
  name: 'MemberRegisterPage',
  data() {
    return {
      formData: {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        password: '',
        passwordConfirm: '',
        membershipType: 'basic',
        agreeTerms: false,
        newsletter: false
      },
      loading: false,
      error: '',
      passwordStrength: 0,
      passwordStrengthText: '',
      passwordStrengthColor: '#ddd'
    }
  },
  methods: {
    checkPasswordStrength() {
      const password = this.formData.password
      let strength = 0
      
      if (password.length >= 8) strength += 25
      if (password.length >= 12) strength += 25
      if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25
      if (/[0-9]/.test(password)) strength += 12.5
      if (/[^a-zA-Z0-9]/.test(password)) strength += 12.5
      
      this.passwordStrength = strength
      
      if (strength <= 25) {
        this.passwordStrengthText = '弱い'
        this.passwordStrengthColor = '#ff4444'
      } else if (strength <= 50) {
        this.passwordStrengthText = '普通'
        this.passwordStrengthColor = '#ffaa00'
      } else if (strength <= 75) {
        this.passwordStrengthText = '強い'
        this.passwordStrengthColor = '#00aa00'
      } else {
        this.passwordStrengthText = 'とても強い'
        this.passwordStrengthColor = '#00ff00'
      }
    },
    async handleRegister() {
      this.loading = true
      this.error = ''

      if (this.formData.password !== this.formData.passwordConfirm) {
        this.error = 'パスワードが一致しません'
        this.loading = false
        return
      }

      if (this.formData.password.length < 8) {
        this.error = 'パスワードは8文字以上で入力してください'
        this.loading = false
        return
      }

      try {
        const response = await axios.post(getApiUrl('/api/register'), {
          name: `${this.formData.lastName} ${this.formData.firstName}`,
          email: this.formData.email,
          phone: this.formData.phone,
          password: this.formData.password,
          password_confirmation: this.formData.passwordConfirm,
          membership_type: this.formData.membershipType,
          newsletter: this.formData.newsletter
        })

        localStorage.setItem('memberToken', response.data.token)
        localStorage.setItem('memberUser', JSON.stringify(response.data.user))
        
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
        
        this.$router.push('/myaccount')
      } catch (err) {
        if (err.response?.data?.errors) {
          const firstError = Object.values(err.response.data.errors)[0]
          this.error = Array.isArray(firstError) ? firstError[0] : firstError
        } else if (err.response?.data?.message) {
          this.error = err.response.data.message
        } else {
          this.error = '登録に失敗しました'
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.member-register-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f5f5 0%, #ebebeb 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.register-container {
  width: 100%;
  max-width: 680px;
}

.register-card {
  background: var(--white);
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  padding: 48px 40px;
  position: relative;
  overflow: hidden;
}

.register-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--mandy) 0%, var(--hot-pink) 100%);
}

.logo-section {
  text-align: center;
  margin-bottom: 40px;
}

.logo {
  width: 60px;
  height: 60px;
  margin: 0 auto 20px;
}

.title {
  margin: 0 0 8px 0;
  color: var(--black);
}

.subtitle {
  margin: 0;
  color: var(--ship-gray);
  opacity: 0.8;
}

.register-form {
  margin-top: 32px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: var(--ship-gray);
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--celeste);
  border-radius: 8px;
  background: var(--white);
  transition: all 0.3s ease;
  color: var(--ship-gray);
}

.form-input:focus {
  outline: none;
  border-color: var(--mandy);
  box-shadow: 0 0 0 3px rgba(218, 87, 97, 0.1);
}

.form-input::placeholder {
  color: var(--celeste);
}

.password-strength {
  margin-top: 8px;
}

.strength-bar {
  height: 4px;
  background: var(--cararra);
  border-radius: 2px;
  overflow: hidden;
}

.strength-fill {
  height: 100%;
  transition: all 0.3s ease;
}

.strength-text {
  display: block;
  margin-top: 4px;
  color: var(--sonic-silver);
}

.membership-plans {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.plan-card {
  padding: 16px;
  border: 2px solid var(--celeste);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.plan-card:hover {
  border-color: var(--mandy);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.plan-card.active {
  border-color: var(--mandy);
  background: linear-gradient(135deg, rgba(218, 87, 97, 0.05) 0%, rgba(252, 104, 165, 0.05) 100%);
}

.plan-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
}

.plan-icon {
  width: 20px;
  height: 20px;
}

.plan-header h3 {
  margin: 0;
  color: var(--black);
}

.plan-price {
  margin: 8px 0;
  color: var(--mandy);
}

.plan-features {
  margin: 0;
  padding-left: 20px;
  color: var(--ship-gray);
  font-size: 12px;
}

.plan-features li {
  margin-bottom: 4px;
}

.checkbox-label {
  display: flex;
  align-items: flex-start;
  cursor: pointer;
}

.checkbox {
  margin-right: 8px;
  margin-top: 2px;
  width: 18px;
  height: 18px;
  accent-color: var(--mandy);
}

.link {
  color: var(--mandy);
  text-decoration: underline;
}

.link:hover {
  opacity: 0.8;
}

.error-message {
  background: rgba(218, 87, 97, 0.08);
  border: 1px solid rgba(218, 87, 97, 0.2);
  border-radius: 8px;
  padding: 12px 16px;
  margin-bottom: 24px;
  color: var(--mandy);
  display: flex;
  align-items: center;
  gap: 8px;
}

.error-icon {
  width: 16px;
  height: 16px;
  filter: brightness(0) saturate(100%) invert(40%) sepia(81%) saturate(479%) hue-rotate(315deg) brightness(91%) contrast(89%);
}

.register-button {
  width: 100%;
  padding: 14px 24px;
  background: linear-gradient(135deg, var(--mandy) 0%, var(--hot-pink) 100%);
  border: none;
  border-radius: 8px;
  color: var(--white);
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.register-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(218, 87, 97, 0.3);
}

.register-button:active:not(:disabled) {
  transform: translateY(0);
}

.register-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.divider {
  position: relative;
  text-align: center;
  margin: 24px 0;
}

.divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: var(--celeste);
}

.divider-text {
  position: relative;
  background: var(--white);
  padding: 0 16px;
  color: var(--sonic-silver);
}

.login-button {
  display: block;
  width: 100%;
  padding: 14px 24px;
  background: var(--white);
  border: 2px solid var(--mandy);
  border-radius: 8px;
  color: var(--mandy);
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  text-align: center;
  text-decoration: none;
}

.login-button:hover {
  background: var(--mandy);
  color: var(--white);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(218, 87, 97, 0.2);
}

.footer-text {
  text-align: center;
  margin-top: 24px;
  color: var(--sonic-silver);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
}

.footer-text::before {
  content: '\1F512';
  font-size: 12px;
}

@media (max-width: 640px) {
  .register-card {
    padding: 32px 24px;
  }
  
  .title {
    font-size: 20px;
  }
  
  .subtitle {
    font-size: 14px;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .membership-plans {
    grid-template-columns: 1fr;
  }
}
</style>