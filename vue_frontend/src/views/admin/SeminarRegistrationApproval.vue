<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">セミナー申込承認</h1>
      </div>
      <div class="filters-section">
        <select v-model="status" class="filter-select" @change="loadRegistrations()">
          <option value="">すべて</option>
          <option value="pending">承認待ち</option>
          <option value="approved">承認</option>
          <option value="rejected">却下</option>
        </select>
        <button class="save-btn" :disabled="selected.length===0" @click="bulkApprove">選択を承認</button>
      </div>
      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th><input type="checkbox" :checked="allChecked" @change="toggleAll($event)" /></th>
                <th>ID</th>
                <th>申込番号</th>
                <th>氏名</th>
                <th>会社</th>
                <th>メール</th>
                <th>承認</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in registrations" :key="r.id">
                <td><input type="checkbox" :value="r.id" v-model="selected" /></td>
                <td>{{ r.id }}</td>
                <td>{{ r.registration_number }}</td>
                <td>{{ r.name }}</td>
                <td>{{ r.company }}</td>
                <td>{{ r.email }}</td>
                <td>{{ r.approval_status }}</td>
                <td>
                  <button class="small-btn" @click="approve(r)">承認</button>
                  <button class="small-btn danger" @click="reject(r)">却下</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '@/services/apiClient.js'

export default {
  name: 'SeminarRegistrationApproval',
  components: { AdminLayout },
  data() {
    return {
      loading: false,
      error: '',
      registrations: [],
      status: 'pending',
      selected: []
    }
  },
  computed: {
    seminarId() { return this.$route.params.id },
    allChecked() { return this.registrations.length>0 && this.selected.length===this.registrations.length }
  },
  mounted() { this.loadRegistrations() },
  methods: {
    async loadRegistrations() {
      this.loading = true
      this.error = ''
      try {
        const params = {}
        if (this.status) params.status = this.status
        const res = await apiClient.get(`/api/admin/seminar/${this.seminarId}/registrations`, { params })
        if (res.success) {
          // APIはLaravelのpaginateを返す
          this.registrations = res.data.data || res.data
        } else throw new Error(res.error || res.message)
      } catch (e) { this.error = '読み込みに失敗しました' } finally { this.loading = false }
    },
    toggleAll(ev) { this.selected = ev.target.checked ? this.registrations.map(r=>r.id) : [] },
    async approve(r) {
      await apiClient.post(`/api/admin/seminar/${this.seminarId}/registrations/${r.id}/approve`)
      this.loadRegistrations()
    },
    async reject(r) {
      const reason = prompt('却下理由（任意）')
      await apiClient.post(`/api/admin/seminar/${this.seminarId}/registrations/${r.id}/reject`, { reason })
      this.loadRegistrations()
    },
    async bulkApprove() {
      await apiClient.post(`/api/admin/seminar/${this.seminarId}/registrations/bulk-approve`, { registration_ids: this.selected })
      this.selected = []
      this.loadRegistrations()
    }
  }
}
</script>

<style scoped>
.dashboard { background: white; border-radius: 8px; overflow: hidden; }
.page-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e5e5e5; }
.page-title { font-size: 24px; font-weight: 600; }
.filters-section { padding: 16px 24px; display: flex; gap: 12px; align-items: center; border-bottom: 1px solid #e5e5e5; }
.filter-select { padding: 8px 12px; border: 1px solid #d0d0d0; border-radius: 4px; }
.table-container { overflow-x: auto; }
.loading, .error { padding: 40px; text-align: center; }
.error { color: #da5761; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { border-bottom: 1px solid #e5e5e5; padding: 12px 16px; text-align: left; }
.small-btn { padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; background: white; cursor: pointer; margin-right: 6px; }
.small-btn.danger { border-color: #dc3545; color: #dc3545; }
</style>

