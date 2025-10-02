<template>
  <div class="registrations-tab">
    <div class="filters">
      <select v-model="filters.status" @change="load" class="filter-select">
        <option value="">すべて</option>
        <option value="scheduled">募集中</option>
        <option value="completed">終了</option>
      </select>
      <select v-model="filters.year" @change="load" class="filter-select">
        <option value="">年度</option>
        <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
      </select>
      <select v-model="filters.month" @change="load" class="filter-select">
        <option value="">月</option>
        <option v-for="m in 12" :key="m" :value="m">{{ m }}月</option>
      </select>
    </div>
    <div v-if="loading" class="loading">読み込み中...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <table v-else class="data-table">
      <thead>
        <tr>
          <th>{{ labels?.thDate || '日付' }}</th>
          <th>{{ labels?.thTitle || 'タイトル' }}</th>
          <th>{{ labels?.thNumber || '申込番号' }}</th>
          <th>{{ labels?.thApproval || '承認状態' }}</th>
          <th>{{ labels?.thSeminarStatus || '開催状態' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="r in rows" :key="r.id" :class="{ completed: r.seminar_status==='completed' }">
          <td>{{ formatDate(r.date) }}</td>
          <td>{{ r.title }}</td>
          <td>{{ r.registration_number }}</td>
          <td>{{ approvalLabel(r.approval_status) }}</td>
          <td>{{ statusLabel(r.seminar_status) }}</td>
        </tr>
        <tr v-if="rows.length===0"><td colspan="5" class="empty">{{ labels?.empty || '申込履歴はありません' }}</td></tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import apiClient from '@/services/apiClient.js'

export default {
  name: 'MemberSeminarRegistrationsTab',
  props: {
    labels: { type: Object, default: () => ({}) }
  },
  data() {
    const now = new Date()
    return { loading:false, error:'', rows:[], years:Array.from({length:6}).map((_,i)=> now.getFullYear()-i), filters:{status:'',year:'',month:''} }
  },
  mounted() { this.load() },
  methods: {
    async load() {
      this.loading = true
      this.error = ''
      try {
        const res = await apiClient.getMySeminarRegistrations({ ...this.filters, per_page: 50 })
        if (res.success) this.rows = (res.data?.data) || res.data || []
        else this.error = res.error || res.message || '読み込みに失敗しました'
      } catch(e) { this.error = '読み込みに失敗しました' } finally { this.loading = false }
    },
    formatDate(d){ return d ? new Date(d).toLocaleDateString('ja-JP') : '-' },
    approvalLabel(s){ return { pending:'承認待ち', approved:'承認', rejected:'却下' }[s] || s },
    statusLabel(s){ return { scheduled:'募集中', ongoing:'開催中', completed:'終了', cancelled:'中止' }[s] || s }
  }
}
</script>

<style scoped>
.filters { display:flex; gap:8px; margin-bottom:12px; }
.data-table { width:100%; border-collapse: collapse; }
.data-table th, .data-table td { border-bottom:1px solid #eee; padding:10px; text-align:left; }
.completed { color:#777; background:#fafafa; }
.error { color:#da5761; padding:12px 0; }
.empty { color:#777; text-align:center; }
</style>
