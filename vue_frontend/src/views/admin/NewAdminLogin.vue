<template>
  <div class="login-page">
    <div class="login-container">
      <div class="login-card">
        <!-- ロゴセクション -->
        <div class="logo-section">
          <img src="/img/ico-logo-1-1.svg" alt="ちくぎん地域経済研究所" class="logo">
          <div class="company-info">
            <h1 class="company-name">株式会社ちくぎん地域経済研究所</h1>
            <p class="company-url">https://www.chikugin-ri.co.jp/</p>
          </div>
        </div>

        <!-- ログインフォーム -->
        <div class="form-section">
          <h2 class="form-title">管理者ログイン</h2>
          
          <form @submit.prevent="handleLogin" class="login-form">
            <div class="form-group">
              <label for="email" class="form-label">メールアドレス</label>
              <input
                id="email"
                v-model="email"
                type="email"
                required
                class="form-input"
                placeholder="admin@example.com"
              />
            </div>

            <div class="form-group">
              <label for="password" class="form-label">パスワード</label>
              <input
                id="password"
                v-model="password"
                type="password"
                required
                class="form-input"
                placeholder="パスワードを入力してください"
              />
            </div>

            <div v-if="error" class="error-message">
              {{ error }}
            </div>

            <button
              type="submit"
              :disabled="loading"
              class="login-btn"
              :class="{ loading: loading }"
            >
              {{ loading ? 'ログイン中...' : 'ログイン' }}
            </button>
          </form>

          <!-- テスト用情報 -->
          <div class="test-info">
            <p class="test-title">テスト用アカウント</p>
            <p class="test-details">
              Email: admin@example.com<br>
              Password: password123
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'NewAdminLogin',
  data() {
    return {
      email: 'admin@chikugin-cri.co.jp',
      password: 'admin123',
      loading: false,
      error: ''
    }
  },
  mounted() {
    // 古いトークンをクリア
    console.log('Clearing old tokens...')
    localStorage.removeItem('admin_token')
    localStorage.removeItem('adminUser')
    localStorage.removeItem('adminToken') // 古いキーも削除
  },
  methods: {
    async handleLogin() {
      this.loading = true
      this.error = ''

      try {
        // デバッグ用自動ログインエンドポイントを使用
        const response = await fetch(this.getApiUrl('/api/debug/admin-login'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        })

        const data = await response.json()

        if (data.success && data.token) {
          // 有効なトークンをlocalStorageに保存
          localStorage.setItem('admin_token', data.token)
          localStorage.setItem('adminUser', JSON.stringify(data.user))
          
          // apiClientにもトークンを設定
          if (window.apiClient) {
            window.apiClient.setToken(data.token)
          }
          
          console.log('Login successful with token:', data.token.substring(0, 20) + '...')
          this.$router.push('/admin/member-list')
        } else {
          // デバッグログインが失敗した場合のフォールバック
          if (this.email === 'admin@example.com' && this.password === 'password123') {
            // モックデータでログイン（開発用）
            const mockAdminData = {
              token: 'mock-admin-token-' + Date.now(),
              user: {
                id: 1,
                email: 'admin@example.com',
                name: '管理者',
                role: 'admin'
              }
            }

            localStorage.setItem('admin_token', mockAdminData.token)
            localStorage.setItem('adminUser', JSON.stringify(mockAdminData.user))
            
            this.$router.push('/admin/member-list')
          } else {
            this.error = data.message || 'ログインに失敗しました。管理者アカウントが存在しない可能性があります。'
          }
        }
      } catch (err) {
        this.error = 'ログインに失敗しました: ' + err.message
        console.error('Login error:', err)
      } finally {
        this.loading = false
      }
    },
    
    getApiUrl(endpoint) {
      // Railway APIのURLを使用
      const baseUrl = 'https://heroic-celebration-production.up.railway.app'
      // エンドポイントが/で始まっていない場合は追加
      const normalizedEndpoint = endpoint.startsWith('/') ? endpoint : `/${endpoint}`
      return baseUrl + normalizedEndpoint
    }
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.login-container {
  width: 100%;
  max-width: 500px;
}

.login-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.logo-section {
  background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
  padding: 32px 24px;
  text-align: center;
  color: white;
}

.logo {
  height: 48px;
  width: auto;
  margin-bottom: 16px;
  filter: brightness(0) invert(1);
}

.company-info {
  margin-top: 16px;
}

.company-name {
  font-size: 20px;
  font-weight: 600;
  margin: 0 0 8px 0;
  color: white;
}

.company-url {
  font-size: 14px;
  margin: 0;
  opacity: 0.9;
}

.form-section {
  padding: 32px 24px;
}

.form-title {
  font-size: 24px;
  font-weight: 600;
  color: #1A1A1AA1A;
  text-align: center;
  margin: 0 0 32px 0;
}

.login-form {
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  color: #1A1A1AA1A;
  margin-bottom: 8px;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e5e5;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.2s, box-shadow 0.2s;
  box-sizing: border-box;
}

.form-input:focus {
  outline: none;
  border-color: #ff6b9d;
  box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

.form-input::placeholder {
  color: #999;
}

.error-message {
  background-color: #fee2e2;
  border: 1px solid #fca5a5;
  color: #dc2626;
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 14px;
  margin-bottom: 20px;
}

.login-btn {
  width: 100%;
  background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
  color: white;
  border: none;
  padding: 14px 16px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.login-btn:hover:not(:disabled) {
  opacity: 0.8;
}

.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
}

.login-btn.loading {
  position: relative;
}

.test-info {
  background-color: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 16px;
  text-align: center;
}

.test-title {
  font-size: 14px;
  font-weight: 600;
  color: #1A1A1AA1A;
  margin: 0 0 8px 0;
}

.test-details {
  font-size: 12px;
  color: #666;
  margin: 0;
  line-height: 1.5;
}

@media (max-width: 480px) {
  .login-page {
    padding: 10px;
  }
  
  .logo-section {
    padding: 24px 16px;
  }
  
  .form-section {
    padding: 24px 16px;
  }
  
  .company-name {
    font-size: 18px;
  }
  
  .form-title {
    font-size: 20px;
  }
}
</style>
