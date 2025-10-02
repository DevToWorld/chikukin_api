<template>
  <div class="admin-reset-page">
    <div class="card">
      <h2>管理者パスワード再設定</h2>
      <p class="note">メールに記載のトークンとメールアドレスが自動入力されます。</p>
      <form @submit.prevent="reset">
        <div class="form-group">
          <label>メールアドレス</label>
          <input v-model="email" type="email" class="form-input" required />
        </div>
        <div class="form-group">
          <label>トークン</label>
          <input v-model="token" type="text" class="form-input" required />
        </div>
        <div class="form-group">
          <label>新しいパスワード</label>
          <input v-model="password" type="password" class="form-input" required minlength="8" />
        </div>
        <div class="form-group">
          <label>新しいパスワード（確認）</label>
          <input v-model="passwordConfirm" type="password" class="form-input" required minlength="8" />
        </div>
        <div class="actions">
          <button class="btn" :disabled="loading">{{ loading ? '送信中...' : '再設定' }}</button>
        </div>
        <div v-if="message" class="message">{{ message }}</div>
        <div v-if="error" class="error">{{ error }}</div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { getApiUrl } from '@/config/api'

export default {
  name: 'AdminResetPassword',
  data() {
    return {
      email: '',
      token: '',
      password: '',
      passwordConfirm: '',
      loading: false,
      message: '',
      error: ''
    }
  },
  mounted() {
    const q = this.$route.query
    if (q.email) this.email = q.email
    if (q.token) this.token = q.token
  },
  methods: {
    async reset() {
      this.loading = true
      this.message = ''
      this.error = ''
      try {
        const res = await axios.post(getApiUrl('/api/admin/password/reset'), {
          email: this.email,
          token: this.token,
          password: this.password,
          password_confirmation: this.passwordConfirm
        })
        if (res.data?.success) {
          this.message = 'パスワードを再設定しました。ログイン画面に戻ります。'
          setTimeout(() => this.$router.push('/admin'), 1500)
        } else {
          this.error = res.data?.message || '再設定に失敗しました'
        }
      } catch (e) {
        this.error = e?.response?.data?.message || '再設定に失敗しました'
      } finally { this.loading = false }
    }
  }
}
</script>

<style scoped>
.admin-reset-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f5f5f5; padding: 20px; }
.card { background: #fff; padding: 24px; border-radius: 10px; width: 420px; max-width: 95%; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
h2 { margin: 0 0 8px; font-size: 20px; }
.note { color: #666; margin: 0 0 16px; font-size: 13px; }
.form-group { margin-bottom: 12px; }
.form-input { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; }
.actions { margin-top: 8px; display: flex; justify-content: flex-end; }
.btn { background: #ff6b9d; border: none; color: #fff; padding: 10px 16px; border-radius: 6px; cursor: pointer; }
.message { margin-top: 10px; color: #28a745; }
.error { margin-top: 10px; color: #dc3545; }
</style>

