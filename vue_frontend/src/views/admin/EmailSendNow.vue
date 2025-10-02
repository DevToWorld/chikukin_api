<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">テスト/即時メール送信</h1>
        <button class="back-btn" @click="$router.back()">戻る</button>
      </div>

      <div class="panel">
        <p class="hint">SMTP未設定の場合はアプリのログに出力されます（storage/logs/laravel.log）。</p>
        <form @submit.prevent="sendNow">
          <div class="form-row">
            <label>宛先(To)</label>
            <input v-model="form.to" type="text" class="input" placeholder="mail@example.com または カンマ区切り" required />
          </div>
          <div class="form-row">
            <label>件名</label>
            <input v-model="form.subject" type="text" class="input" placeholder="件名" required />
          </div>
          <div class="form-row">
            <label>本文(HTML)</label>
            <textarea v-model="form.body_html" class="textarea" rows="8" placeholder="<p>HTML本文</p>"></textarea>
          </div>
          <div class="form-row">
            <label>本文(テキスト)</label>
            <textarea v-model="form.body_text" class="textarea" rows="6" placeholder="テキスト本文"></textarea>
          </div>
          <div class="actions">
            <button type="submit" class="send-btn" :disabled="sending">{{ sending ? '送信中...' : '送信' }}</button>
          </div>
          <p v-if="error" class="error">{{ error }}</p>
          <p v-if="success" class="success">送信しました（メーラー: {{ mailer }}）</p>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '@/services/apiClient'

export default {
  name: 'EmailSendNow',
  components: { AdminLayout },
  data() {
    return { form:{ to:'', subject:'', body_html:'', body_text:'' }, sending:false, error:'', success:false, mailer:'smtp' }
  },
  methods: {
    async sendNow() {
      this.error=''; this.success=false; this.sending=true
      try {
        const to = this.form.to.split(',').map(s=>s.trim()).filter(Boolean)
        const res = await apiClient.sendSimpleEmail({ ...this.form, to })
        if (res && res.success) { this.success=true; this.mailer = res.mailer || 'smtp' } else { this.error = res?.message || '送信に失敗しました' }
      } catch(e){ this.error='送信に失敗しました' } finally { this.sending=false }
    }
  }
}
</script>

<style scoped>
.dashboard { background:#fff; border-radius:8px; overflow:hidden; }
.page-header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #e5e5e5; }
.page-title { font-size:20px; font-weight:600; }
.back-btn { background:#fff; border:1px solid #ddd; border-radius:6px; padding:6px 10px; }
.panel { padding:16px 20px; }
.hint { color:#666; font-size:13px; margin-bottom:12px; }
.form-row { display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
.input, .textarea { border:1px solid #ddd; border-radius:6px; padding:8px 10px; font-size:14px; }
.actions { display:flex; justify-content:flex-end; }
.send-btn { background:#DA5761; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer; }
.error { color:#da5761; margin-top:8px; }
.success { color:#2e7d32; margin-top:8px; }
</style>

