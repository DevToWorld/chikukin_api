<template>
  <AdminLayout>
    <div class="dashboard">
      <div class="page-header">
        <h1 class="page-title">メディアライブラリ</h1>
        <div class="header-actions">
          <button class="secondary-btn" @click="refresh">再読み込み</button>
        </div>
      </div>

      <div class="toolbar">
        <div class="left">
          <label>
            ディレクトリ
            <select v-model="directory" @change="refresh">
              <option value="public/media">public/media</option>
              <option v-for="d in directories" :key="d.path" :value="d.path">{{ d.path }}</option>
            </select>
          </label>
        </div>
        <div class="right">
          <input type="file" @change="onPickFile" />
          <button class="primary-btn" :disabled="!pickedFile || uploading" @click="upload">
            {{ uploading ? 'アップロード中...' : 'アップロード' }}
          </button>
        </div>
      </div>

      <div class="stats" v-if="stats">
        <div>ファイル数: {{ stats.total_files }}</div>
        <div>容量合計: {{ stats.total_size_formatted }}</div>
      </div>

      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th style="width:140px;">プレビュー</th>
                <th>ファイル名</th>
                <th>サイズ</th>
                <th>URL</th>
                <th>更新日時</th>
                <th style="width:220px;">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="f in files" :key="f.id">
                <td>
                  <img v-if="f.type==='image'" :src="resolvedUrl(f.url)" alt="preview" class="preview" />
                  <span v-else class="filetype">{{ f.type }}</span>
                </td>
                <td>
                  <div v-if="renameId===f.id" class="rename-row">
                    <input v-model="renameName" class="rename-input" />
                    <button class="small-btn" @click="confirmRename(f)">保存</button>
                    <button class="small-btn ghost" @click="cancelRename">キャンセル</button>
                  </div>
                  <div v-else class="file-name">{{ f.name }}</div>
                  <div class="file-path">{{ f.path }}</div>
                </td>
                <td>{{ prettyBytes(f.size) }}</td>
                <td class="file-url">
                  <span>{{ resolvedUrl(f.url) }}</span>
                  <button class="small-btn ghost" @click="copyUrl(resolvedUrl(f.url))">コピー</button>
                </td>
                <td>{{ formatDate(f.last_modified) }}</td>
                <td>
                  <button class="small-btn" @click="startRename(f)">名称変更</button>
                  <button class="small-btn danger" @click="remove(f)" :disabled="deletingId===f.id">
                    {{ deletingId===f.id ? '削除中...' : '削除' }}
                  </button>
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
import { getApiBaseUrl } from '@/config/api.js'

export default {
  name: 'MediaLibrary',
  components: { AdminLayout },
  data() {
    return {
      directory: 'public/media',
      directories: [],
      files: [],
      loading: false,
      uploading: false,
      deletingId: '',
      error: '',
      pickedFile: null,
      renameId: '',
      renameName: '',
      stats: null,
    }
  },
  mounted() {
    this.bootstrap()
  },
  methods: {
    async bootstrap() {
      await Promise.all([this.refresh(), this.fetchDirectories(), this.fetchStats()])
    },
    resolvedUrl(url) {
      if (!url) return ''
      if (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('//')) return url
      if (url.startsWith('/storage/') || url.startsWith('storage/')) {
        const path = url.startsWith('/storage/') ? url : `/${url}`
        return `${getApiBaseUrl()}${path}`
      }
      return url
    },
    prettyBytes(bytes) {
      if (!bytes && bytes !== 0) return ''
      const units = ['B','KB','MB','GB','TB']
      let i = 0
      let v = bytes
      while (v >= 1024 && i < units.length - 1) { v /= 1024; i++ }
      return `${Math.round(v * 100) / 100} ${units[i]}`
    },
    formatDate(ts) {
      try {
        const d = new Date(ts * 1000)
        return isNaN(d) ? '' : d.toLocaleString()
      } catch (e) { return '' }
    },
    async fetchDirectories() {
      try {
        const { data } = await apiClient.listMediaDirectories()
        this.directories = Array.isArray(data) ? data.filter(d => d.path !== this.directory) : []
      } catch (_) { /* ignore */ }
    },
    async fetchStats() {
      try {
        const { data } = await apiClient.mediaStats()
        this.stats = data
      } catch (_) { /* ignore */ }
    },
    async refresh() {
      this.loading = true
      this.error = ''
      try {
        const { data } = await apiClient.listMedia({ directory: this.directory, per_page: 200 })
        this.files = data?.data || []
      } catch (e) {
        this.error = 'メディアの取得に失敗しました'
      } finally {
        this.loading = false
      }
    },
    onPickFile(e) {
      const f = e.target.files && e.target.files[0]
      this.pickedFile = f || null
    },
    async upload() {
      if (!this.pickedFile) return
      this.uploading = true
      try {
        await apiClient.uploadMedia(this.pickedFile, { directory: this.directory })
        this.pickedFile = null
        await Promise.all([this.refresh(), this.fetchStats()])
      } catch (e) {
        alert('アップロードに失敗しました')
      } finally {
        this.uploading = false
      }
    },
    startRename(f) {
      this.renameId = f.id
      this.renameName = f.name.replace(/\.[^.]+$/, '')
    },
    cancelRename() {
      this.renameId = ''
      this.renameName = ''
    },
    async confirmRename(f) {
      try {
        await apiClient.renameMedia(f.path, this.renameName)
        this.cancelRename()
        await this.refresh()
      } catch (e) {
        alert('名称変更に失敗しました')
      }
    },
    async remove(f) {
      if (!confirm(`削除しますか？\n${f.name}`)) return
      this.deletingId = f.id
      try {
        await apiClient.deleteMedia(f.path)
        await Promise.all([this.refresh(), this.fetchStats()])
      } catch (e) {
        alert('削除に失敗しました')
      } finally {
        this.deletingId = ''
      }
    },
    async copyUrl(url) {
      try {
        await navigator.clipboard.writeText(url)
      } catch (_) {
        // fallback
        const ta = document.createElement('textarea')
        ta.value = url
        document.body.appendChild(ta)
        ta.select()
        document.execCommand('copy')
        document.body.removeChild(ta)
      }
      alert('URLをコピーしました')
    },
  }
}
</script>

<style scoped>
.dashboard { background:#fff; border:1px solid #e5e5e5; border-radius:8px; margin:16px; }
.page-header { display:flex; justify-content:space-between; align-items:center; padding:16px 24px; border-bottom:1px solid #e5e5e5; }
.page-title { font-size:20px; font-weight:600; }
.header-actions { display:flex; gap:8px; }
.toolbar { display:flex; justify-content:space-between; gap:16px; padding:12px 24px; border-bottom:1px solid #eee; }
.toolbar .left, .toolbar .right { display:flex; align-items:center; gap:12px; }
.stats { display:flex; gap:24px; padding:8px 24px; color:#555; border-bottom:1px solid #f0f0f0; }
.table-container { overflow-x:auto; }
.loading, .error { padding:24px; text-align:center; }
.data-table { width:100%; border-collapse:collapse; }
.data-table th { background:#fafafa; border-bottom:1px solid #eee; text-align:left; padding:10px 12px; font-weight:600; }
.data-table td { border-bottom:1px solid #f0f0f0; padding:12px; vertical-align:top; }
.preview { max-width:120px; max-height:64px; object-fit:cover; border-radius:4px; border:1px solid #eee; }
.filetype { color:#666; font-size:12px; }
.file-name { font-weight:500; }
.file-path { color:#888; font-size:12px; }
.file-url { color:#0066cc; text-decoration:none; word-break:break-all; }
.primary-btn { background:#1A1A1A; color:#fff; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; }
.secondary-btn { background:#fff; color:#1A1A1A; border:1px solid #ddd; padding:8px 12px; border-radius:4px; cursor:pointer; }
.small-btn { background:#1A1A1A; color:#fff; border:none; padding:6px 10px; border-radius:4px; font-size:12px; cursor:pointer; }
.small-btn.ghost { background:#fff; color:#333; border:1px solid #ddd; }
.small-btn.danger { background:#da5761; }
.rename-row { display:flex; gap:8px; align-items:center; }
.rename-input { border:1px solid #ddd; border-radius:4px; padding:6px 8px; }
</style>

