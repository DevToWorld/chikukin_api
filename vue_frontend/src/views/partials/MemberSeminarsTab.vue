<template>
  <div class="seminars-tab">
    <div class="filters">
      <select v-model="filters.status" @change="load" class="filter-select">
        <option value="">すべて</option>
        <option value="scheduled">募集中</option>
        <option value="ongoing">開催中</option>
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
      <label class="toggle">
        <input type="checkbox" v-model="onlyReservable" />
        <span>予約可能のみ</span>
      </label>
    </div>
    <div v-if="loading" class="loading">読み込み中...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <table v-else class="data-table">
      <thead>
        <tr>
          <th>{{ labels?.thDate || '日付' }}</th>
          <th>{{ labels?.thTitle || 'タイトル' }}</th>
          <th>{{ labels?.thVenue || '会場' }}</th>
          <th>{{ labels?.thStatus || 'ステータス' }}</th>
          <th class="actions-col">{{ labels?.thActions || '操作' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s in filteredSeminars" :key="s.id" :class="{ completed: s.status==='completed' }">
          <td>{{ formatDate(s.date) }}</td>
          <td>{{ s.title }}</td>
          <td>{{ s.location || '-' }}</td>
          <td>{{ statusLabel(s.status) }}</td>
          <td class="actions">
            <button 
              class="fav-btn" 
              :class="{ active: isFav(s.id) }" 
              @click="toggleFavorite(s, $event)"
              :title="isFav(s.id) ? 'お気に入り解除' : 'お気に入り追加'"
            >{{ isFav(s.id) ? '★' : '☆' }}</button>
            <button v-if="canReserve(s)" class="reserve-btn" @click="openReservation(s)">{{ labels?.btnReserve || '予約する' }}</button>
            <span v-else class="disabled-text">{{ disabledReason(s) }}</span>
          </td>
        </tr>
        <tr v-if="filteredSeminars.length===0"><td colspan="5" class="empty">{{ labels?.empty || '該当するセミナーがありません' }}</td></tr>
      </tbody>
    </table>
    
    <!-- 予約モーダル -->
    <div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
      <div class="modal">
        <h3>セミナー予約</h3>
        <p class="modal-sub" v-if="selectedSeminar">{{ selectedSeminar.title }}（{{ formatDate(selectedSeminar.date) }}）</p>
        <form @submit.prevent="submitReservation">
          <div class="form-row">
            <div class="form-group">
              <label>お名前 *</label>
              <input v-model="form.name" type="text" class="form-input" required />
            </div>
            <div class="form-group">
              <label>メールアドレス *</label>
              <input v-model="form.email" type="email" class="form-input" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>会社名</label>
              <input v-model="form.company" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label>電話番号</label>
              <input v-model="form.phone" type="text" class="form-input" />
            </div>
          </div>
          <div class="form-group">
            <label>ご要望・備考</label>
            <textarea v-model="form.special_requests" class="form-textarea" rows="4"></textarea>
          </div>
          <p v-if="error" class="error">{{ error }}</p>
          <div class="form-actions">
            <button type="button" class="cancel-button" @click="closeModal" :disabled="submitting">キャンセル</button>
            <button type="submit" class="save-button" :disabled="submitting">{{ submitting ? '送信中...' : '送信する' }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '@/services/apiClient.js'

export default {
  name: 'MemberSeminarsTab',
  props: {
    labels: { type: Object, default: () => ({}) }
  },
  data() {
    const now = new Date()
    return {
      loading: false,
      error: '',
      seminars: [],
      years: Array.from({length: 6}).map((_,i)=> now.getFullYear()-i),
      filters: { status: 'scheduled', year: '', month: '' },
      onlyReservable: true,
      showModal: false,
      submitting: false,
      selectedSeminar: null,
      form: { name:'', email:'', company:'', phone:'', special_requests:'' },
      favoriteIds: new Set()
    }
  },
  mounted() { this.init() },
  computed: {
    filteredSeminars() {
      const list = Array.isArray(this.seminars) ? this.seminars : []
      return this.onlyReservable ? list.filter(s => this.canReserve(s)) : list
    }
  },
  methods: {
    async init() {
      await Promise.all([this.load(), this.loadFavorites()])
    },
    async load() {
      this.loading = true
      this.error = ''
      try {
        const res = await apiClient.getMemberSeminars({ ...this.filters, per_page: 20 })
        if (res && res.success) {
          const nested = (res.data && (res.data.seminars || res.data.data?.seminars)) || []
          this.seminars = Array.isArray(nested) ? nested : []
        } else {
          this.seminars = []
          this.error = res?.error || res?.message || '読み込みに失敗しました'
        }
      } catch(e) { this.error = '読み込みに失敗しました' } finally { this.loading = false }
    },
    async loadFavorites() {
      try {
        const res = await apiClient.getSeminarFavorites()
        if (res && res.success) {
          const list = Array.isArray(res.data) ? res.data : []
          this.favoriteIds = new Set(list.map(x => x.id))
        }
      } catch (e) {
        // noop
      }
    },
    formatDate(d) { return d ? new Date(d).toLocaleDateString('ja-JP') : '-' },
    statusLabel(s) {
      return { scheduled:'募集中', ongoing:'開催中', completed:'終了', cancelled:'中止' }[s] || s
    },
    canReserve(s) { return s && s.status==='scheduled' && s.can_register_for_user },
    disabledReason(s) {
      if (s.status==='completed') return '終了'
      if (s.status==='cancelled') return '中止'
      return '申込不可'
    },
    openReservation(s) {
      this.selectedSeminar = s
      try {
        const u = JSON.parse(localStorage.getItem('memberUser') || 'null')
        this.form.name = u?.representative_name || u?.name || ''
        this.form.email = u?.email || ''
        this.form.company = u?.company_name || ''
        this.form.phone = u?.phone || ''
      } catch(e) {}
      this.form.special_requests = ''
      this.error = ''
      this.showModal = true
    },
    closeModal() { if (!this.submitting) this.showModal = false },
    async submitReservation() {
      if (!this.selectedSeminar) return
      this.submitting = true
      this.error = ''
      try {
        const res = await apiClient.registerForSeminar(this.selectedSeminar.id, this.form)
        if (res && res.success) {
          alert('予約を受け付けました')
          this.showModal = false
          // 親へ通知（マイページ側で申込状況タブを更新）
          this.$emit('reservation-made', res.data?.registration || { seminar_id: this.selectedSeminar.id })
          this.load()
        } else {
          this.error = res?.message || '予約に失敗しました'
        }
      } catch(e) { this.error = 'サーバーエラーが発生しました' } finally { this.submitting = false }
    },
    isFav(id) {
      try { return !!(this.favoriteIds && this.favoriteIds.has(id)) } catch(e) { return false }
    },
    // Backward-compat alias in case an older compiled template calls isFavorite
    isFavorite(id) {
      return this.isFav(id)
    },
    async toggleFavorite(s, ev) {
      if (ev && typeof ev.stopPropagation === 'function') ev.stopPropagation()
      try {
        if (this.isFav(s.id)) {
          const res = await apiClient.removeSeminarFavorite(s.id)
          if (!res || res.success === false) return
          this.favoriteIds.delete(s.id)
          this.favoriteIds = new Set(this.favoriteIds)
          this.$emit('seminar-favorite-updated', { id: s.id, favorited: false })
        } else {
          const res = await apiClient.addSeminarFavorite(s.id)
          if (!res || res.success === false) return
          this.favoriteIds.add(s.id)
          this.favoriteIds = new Set(this.favoriteIds)
          this.$emit('seminar-favorite-updated', { id: s.id, favorited: true })
        }
      } catch (e) {
        // ignore
      }
    }
  }
}
</script>

<style scoped>
.filters { display:flex; gap:8px; margin-bottom:12px; }
.filter-select { padding:6px 10px; }
.toggle { display:flex; align-items:center; gap:6px; color:#333; font-size:14px; }
.data-table { width:100%; border-collapse: collapse; }
.data-table th, .data-table td { border-bottom:1px solid #eee; padding:10px; text-align:left; }
.completed { color:#777; background:#fafafa; }
.empty { color:#777; text-align:center; }
.error { color:#da5761; padding:12px 0; }
.actions-col { width: 140px; }
.actions { white-space: nowrap; }
.fav-btn { padding: 4px 8px; margin-right: 8px; background:#fff; border:1px solid #ddd; border-radius:6px; cursor:pointer; }
.fav-btn.active { color:#f5a623; border-color:#f5a623; }
.reserve-btn { padding: 6px 12px; background: var(--mandy, #DA5761); color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:13px; }
.reserve-btn:hover { background: var(--hot-pink, #E56B75); }
.disabled-text { color:#999; font-size:12px; }

/* モーダル */
.modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; z-index:1000; }
.modal { background:#fff; width:min(680px, 92vw); border-radius:12px; padding:24px; box-shadow:0 10px 30px rgba(0,0,0,0.2); }
.modal-sub { margin: 6px 0 12px; color:#555; }
.form-row { display:flex; gap:16px; }
.form-group { flex:1; display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
.form-input, .form-textarea { border:1px solid #ddd; border-radius:6px; padding:8px 10px; font-size:14px; }
.form-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:8px; }
.cancel-button { padding:8px 16px; background:#fff; border:1px solid #ddd; border-radius:6px; cursor:pointer; }
.save-button { padding:8px 16px; background: var(--mandy, #DA5761); color:#fff; border:none; border-radius:6px; cursor:pointer; }
</style>
