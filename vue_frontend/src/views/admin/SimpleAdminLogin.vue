<template>
  <div style="min-height: 100vh; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center;">
    <div style="background-color: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
      <h1 style="font-size: 1.5rem; font-weight: bold; text-align: center; margin-bottom: 1.5rem;">管理者ログイン</h1>
      
      <form @submit.prevent="handleLogin">
        <div style="margin-bottom: 1rem;">
          <label for="email" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
            メールアドレス
          </label>
          <input
            id="email"
            v-model="email"
            type="email"
            required
            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;"
            placeholder="admin@example.com"
          />
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label for="password" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">
            パスワード
          </label>
          <input
            id="password"
            v-model="password"
            type="password"
            required
            style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem;"
            placeholder="********"
          />
        </div>

        <div v-if="error" style="margin-bottom: 1rem; padding: 0.75rem; background-color: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 6px;">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="loading"
          style="width: 100%; background-color: #2563eb; color: white; padding: 0.75rem 1rem; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer;"
          :style="loading ? 'opacity: 0.5; cursor: not-allowed;' : 'opacity: 1;'"
        >
          {{ loading ? 'ログイン中...' : 'ログイン' }}
        </button>
      </form>

      <div style="margin-top: 1rem; padding: 0.75rem; background-color: #f3f4f6; border-radius: 6px;">
        <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">
          デバッグ用自動ログイン機能付き<br>
          ログインボタンを押すと自動的にデータベースの管理者でログインします
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { getApiUrl } from '@/config/api'
import apiClient from '@/services/apiClient'

export default {
  name: 'SimpleAdminLogin',
  data() {
    return {
      email: 'admin@chikugin-cri.co.jp',
      password: 'admin123',
      loading: false,
      error: ''
    }
  },
  mounted() {
    // デバッグ用：既存のトークンをクリア
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
        // まずデバッグエンドポイントで自動ログインを試みる
        console.log('Trying debug login first...')
        // Railway APIのURLを直接使用
        const apiUrl = 'https://heroic-celebration-production.up.railway.app/api/debug/admin-login'
        console.log('Calling:', apiUrl)
        const debugResponse = await axios.post(apiUrl)
        
        if (debugResponse.data.success) {
          console.log('Debug login successful!')
          localStorage.setItem('admin_token', debugResponse.data.token)
          localStorage.setItem('adminUser', JSON.stringify(debugResponse.data.user))
          
          // apiClientにもトークンを設定
          apiClient.setToken(debugResponse.data.token)
          
          axios.defaults.headers.common['Authorization'] = `Bearer ${debugResponse.data.token}`
          
          alert('ログイン成功！ダッシュボードに遷移します。')
          this.$router.push('/admin/member-list')
          return
        }
      } catch (debugErr) {
        console.log('Debug login failed, trying normal login...', debugErr.message)
        
        // デバッグログインが失敗した場合、通常のログインを試みる
        try {
          const response = await axios.post(getApiUrl('/api/admin/login'), {
            email: this.email,
            password: this.password
          })

          localStorage.setItem('admin_token', response.data.token)
          localStorage.setItem('adminUser', JSON.stringify(response.data.user))
          
          // apiClientにもトークンを設定
          apiClient.setToken(response.data.token)
          
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
          
          alert('ログイン成功！ダッシュボードに遷移します。')
          this.$router.push('/admin/member-list')
        } catch (err) {
          if (err.response?.status === 422) {
            this.error = 'メールアドレスまたはパスワードが正しくありません'
          } else if (err.response?.status === 403) {
            this.error = '管理者権限がありません'
          } else if (err.response?.data?.message) {
            this.error = err.response.data.message
          } else {
            this.error = 'ログインに失敗しました。データベースの管理者アカウントを確認してください。'
          }
          console.error('Login error:', err)
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
