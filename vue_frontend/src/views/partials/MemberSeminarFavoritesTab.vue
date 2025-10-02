<template>
  <div class="seminar-favorites-tab">
    <div v-if="loading" class="loading">読み込み中...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <table v-else class="data-table">
      <thead>
        <tr>
          <th>{{ labels?.thDate || '日付' }}</th>
          <th>{{ labels?.thTitle || 'タイトル' }}</th>
          <th>{{ labels?.thStatus || 'ステータス' }}</th>
          <th>{{ labels?.thFavoritedAt || 'お気に入り日時' }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s in items" :key="s.id">
          <td>{{ formatDate(s.date) }}</td>
          <td>{{ s.title }}</td>
          <td>{{ statusLabel(s.status) }}</td>
          <td>{{ formatDateTime(s.favorited_at) }}</td>
          <td><button class="remove-btn" @click="unfav(s)">{{ labels?.btnRemove || '削除' }}</button></td>
        </tr>
        <tr v-if="items.length===0"><td colspan="5" class="empty">{{ labels?.empty || 'お気に入りセミナーはありません' }}</td></tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import apiClient from '@/services/apiClient.js'

export default {
  name: 'MemberSeminarFavoritesTab',
  props: {
    labels: { type: Object, default: () => ({}) }
  },
  data() {
    return { loading:false, error:'', items:[] }
  },
  mounted() { this.load() },
  methods: {
    async load() {
      this.loading = true
      this.error = ''
      try {
        const res = await apiClient.getSeminarFavorites()
        if (res.success) this.items = res.data || []
        else this.error = res.error || res.message || '読み込みに失敗しました'
      } catch(e) { this.error = '読み込みに失敗しました' } finally { this.loading = false }
    },
    async unfav(s) {
      await apiClient.removeSeminarFavorite(s.id)
      this.items = this.items.filter(x=>x.id!==s.id)
    },
    formatDate(d) { return d ? new Date(d).toLocaleDateString('ja-JP') : '-' },
    formatDateTime(d) { return d ? new Date(d).toLocaleString('ja-JP') : '-' },
    statusLabel(s) { return { scheduled:'募集中', ongoing:'開催中', completed:'終了', cancelled:'中止' }[s] || s }
  }
}
</script>

<style scoped>
.data-table { width:100%; border-collapse: collapse; }
.data-table th, .data-table td { border-bottom:1px solid #eee; padding:10px; text-align:left; }
 .remove-btn { padding:8px 12px; border:1px solid var(--mandy, #DA5761); color: var(--mandy, #DA5761); background:#fff; border-radius:6px; cursor:pointer; font-weight:600; }
 .remove-btn:hover { background: var(--mandy, #DA5761); color:#fff; }
.empty { color:#777; text-align:center; }
.error { color:#da5761; padding:12px 0; }
</style>
