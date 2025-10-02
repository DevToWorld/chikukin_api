<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <h1 class="page-title">メディア管理</h1>
        <button @click="load" class="add-btn">再読み込み</button>
      </div>

      <!-- 検索セクション -->
      <div class="search-section">
        <div class="search-container">
          <span class="search-label">検索する</span>
          <input 
            v-model="searchKeyword" 
            type="text" 
            placeholder="キーワードを入力する"
            class="search-input"
            @keyup.enter="performSearch"
          >
          <button @click="performSearch" class="search-btn">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" fill="currentColor"/>
            </svg>
          </button>
        </div>
        <div class="search-container" style="margin-top:8px; justify-content:flex-end;">
          <label style="display:flex; gap:8px; align-items:center; cursor:pointer;">
            <input type="checkbox" v-model="groupResponsiveDuplicates" />
            <span>バリアントがベースと同一なら隠す</span>
          </label>
          <label style="display:flex; gap:8px; align-items:center; cursor:pointer;">
            <input type="checkbox" v-model="hidePlaceholders" />
            <span>プレースホルダ行を隠す</span>
          </label>
        </div>
      </div>

      <!-- データテーブル -->
      <div class="table-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error" class="error">{{ error }}</div>
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th style="width: 160px;">プレビュー</th>
                <th>ページキー</th>
                <th>種別/場所</th>
                <th>URL</th>
                <th>更新日</th>
                <th style="width:140px;">操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, idx) in pagedRows" :key="row._id">
                <td>
                  <img :src="row.url" alt="preview" style="max-width:140px; max-height:70px; object-fit:cover; border-radius:4px;" v-if="row.url" />
                  <div v-else style="color:#888; font-size:12px;">（未設定）</div>
                </td>
                <td>{{ row.pageKey }}</td>
                <td>{{ displayKey(row) }}</td>
                <td class="file-url">{{ row.url }}</td>
                <td>{{ formatDate(row.updated_at) }}</td>
                <td>
                  <button 
                    class="edit-btn" 
                    @click="pickReplace((currentPage - 1) * pageSize + idx)"
                    :title="actionTitle(row)"
                    :disabled="isKvRow(row)"
                  >画像で置換</button>
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>

      <!-- ページネーション -->
      <div class="pagination" v-if="!loading && !error && totalPages > 1">
        <button class="page-btn" :disabled="currentPage===1" @click="goToPage(1)">最初</button>
        <button class="page-btn" :disabled="currentPage===1" @click="prevPage">前へ</button>
        <span class="page-info">
          <button 
            v-for="n in pageButtons" 
            :key="n.key" 
            class="page-number"
            :class="{ active: n.page === currentPage, ellipsis: n.ellipsis }"
            :disabled="n.ellipsis"
            @click="!n.ellipsis && goToPage(n.page)"
          >{{ n.label }}</button>
        </span>
        <button class="page-btn" :disabled="currentPage===totalPages" @click="nextPage">次へ</button>
        <button class="page-btn" :disabled="currentPage===totalPages" @click="goToPage(totalPages)">最後</button>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '@/services/apiClient.js'
import { getApiBaseUrl } from '@/config/api.js'

export default {
  name: 'MediaManagement',
  components: {
    AdminLayout
  },
  data() {
    return {
      allRows: [], // 全件
      rows: [], // 互換のため残すが、描画はpagedRowsを使用
      loading: false,
      error: '',
      searchKeyword: '',
      selectedMedia: [],
      filePickIndex: -1,
      currentPage: 1,
      pageSize: 20,
      // 表示整理オプション
      groupResponsiveDuplicates: true, // ベースと同じURLのバリアントは隠す
      hidePlaceholders: false, // プレースホルダ（/img/hero-image.png 等）を隠す
    }
  },
  async mounted() {
    await this.load()
  },
  methods: {
    displayKey(row) {
      try {
        // HTML埋め込み由来
        if (row.source === 'html') return '本文中の画像'

        const original = String(row.key || '')
        // 'images.xxx' などのプレフィックスを除去
        const afterImages = original.startsWith('images.') ? original.slice('images.'.length) : original
        const parts = afterImages.split('.')
        const leaf = parts[parts.length - 1]

        // レスポンシブのバリアント
        let variant = ''
        let base = leaf
        if (leaf.endsWith('_mobile')) { variant = '（モバイル）'; base = leaf.replace(/_mobile$/, '') }
        else if (leaf.endsWith('_tablet')) { variant = '（タブレット）'; base = leaf.replace(/_tablet$/, '') }

        // 既知キーの日本語マッピング
        const M = {
          // 会社概要
          'company_profile_philosophy': '会社概要・経営理念',
          'company_profile_message': '会社概要・ご挨拶',
          'company_profile_staff_morita': '会社概要・所員（森田）',
          'company_profile_staff_mizokami': '会社概要・所員（溝上）',
          'company_profile_staff_kuga': '会社概要・所員（空閑）',
          'company_profile_staff_takada': '会社概要・所員（髙田）',
          'company_profile_staff_nakamura': '会社概要・所員（中村）',
          // セクション背景
          'contact_section_bg': 'お問い合わせセクション・背景',
        }
        if (M[base]) return M[base] + variant

        // company_profile_* の一般処理
        if (base.startsWith('company_profile_')) {
          const rest = base.replace('company_profile_', '')
          if (rest.startsWith('staff_')) {
            const name = rest.replace('staff_', '')
            return `会社概要・所員（${this.jaName(name)}）` + variant
          }
          return '会社概要・' + this.humanize(rest) + variant
        }

        // それ以外は人間可読化
        return this.humanize(base) + variant
      } catch (_) { return row.key || '' }
    },
    humanize(s) {
      if (!s) return ''
      return String(s).replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
    },
    jaName(k) {
      const map = {
        morita: '森田', mizokami: '溝上', kuga: '空閑', takada: '髙田', nakamura: '中村'
      }
      return map[k] || this.humanize(k)
    },
    isRegistryKey(key) {
      if (!key || typeof key !== 'string') return false
      return key.startsWith('hero_') || key.startsWith('company_profile_') || key === 'contact_section_bg'
    },
    // KV(キービジュアル)かどうか
    isKvRow(row) {
      const k = (row && row.key) ? String(row.key) : ''
      // mediaレジストリ内の hero_ 系はKVとして扱う
      return (row && row.pageKey === 'media' && k.startsWith('hero_'))
    },
    isModelRow(row) {
      return ['news','news_article','publication','economic_report','seminar'].includes(row.model)
    },
    actionTitle(row) {
      if (this.isKvRow(row)) return 'KV画像は各ページ編集で変更してください'
      if (this.isModelRow(row)) return 'モデル画像/HTMLを置換'
      return '画像で置換'
    },
    async load() {
      this.loading = true
      this.error = ''
      try {
        const res = await apiClient.get('/api/admin/pages/media-usage', { silent: true })
        const items = res?.data?.data?.items || res?.data?.items || []
        const apiBase = getApiBaseUrl()
        const isPlaceholder = (u) => {
          if (!u) return true
          return u === '/img/hero-image.png' || u === 'img/hero-image.png' ||
                 u === '/img/-----1-1.png' || u === 'img/-----1-1.png' ||
                 u === '/img/image-1.png' || u === 'img/image-1.png'
        }
        const resolveUrl = (rawUrl) => {
          let url = rawUrl || ''
          if (!url) return ''
          if (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('//')) return url
          if (url.startsWith('/storage/') || url.startsWith('storage/')) {
            const path = url.startsWith('/storage/') ? url : `/${url}`
            return `${apiBase}${path}`
          }
          return url // '/img' 等はそのまま
        }
        // インデックス（media のキー別に参照できるように）
        const mediaIndex = {}
        for (const it of items) {
          if ((it.page_key || '') === 'media' && it.key) {
            mediaIndex[it.key] = it
          }
        }
        const mapped = items.map(it => {
          const rawUrl = it.url || ''
          let url = resolveUrl(rawUrl)
          // レスポンシブ・バリアントが未設定の場合はベースキーのプレビューを見せる
          if ((it.page_key || '') === 'media' && typeof it.key === 'string') {
            const m = it.key.match(/^(.*)_(mobile|tablet)$/)
            if (m) {
              const baseKey = m[1]
              const base = mediaIndex[baseKey]
              const baseResolved = base && base.url ? resolveUrl(base.url) : ''
              if (isPlaceholder(rawUrl) && baseResolved && !isPlaceholder(baseResolved)) {
                url = baseResolved
              }
              return ({
                _id: `mu-${it.page_key}-${it.key}-${Math.random()}`,
                id: it.id || null,
                pageKey: it.page_key,
                model: it.model || 'page_content',
                key: it.key,
                url,
                source: it.source || 'json',
                updated_at: it.updated_at,
                // メタ
                isVariant: true,
                baseKey,
                baseUrl: baseResolved || '',
                isPlaceholder: isPlaceholder(rawUrl),
              })
            }
          }

          return ({
            _id: `mu-${it.page_key}-${it.key}-${Math.random()}`,
            id: it.id || null,
            pageKey: it.page_key,
            model: it.model || 'page_content',
            key: it.key,
            url,
            source: it.source || 'json',
            updated_at: it.updated_at,
            // メタ
            isVariant: false,
            baseKey: null,
            baseUrl: '',
            isPlaceholder: isPlaceholder(rawUrl),
          })
        })
        this.allRows = mapped
        this.rows = mapped
        this.currentPage = 1
      } catch (e) {
        this.error = 'メディアの取得に失敗しました'
      } finally {
        this.loading = false
      }
    },
    pickReplace(idx) {
      // 表示中の行（フィルタ/ページング適用後）の行を正しく取得
      const start = (this.currentPage - 1) * this.pageSize
      const row = this.filteredRows[start + idx]
      if (!row) return
      // KVはここでは編集不可（BlockCMS/ページ毎のKVアップロードで対応）
      if (this.isKvRow(row)) {
        alert('KV画像はメディア管理では変更できません。各ページのKVアップローダーから変更してください。')
        return
      }
      // 動的にfile要素を起こす（1行1入力を避ける）
      const input = document.createElement('input')
      input.type = 'file'
      input.accept = 'image/*'
      input.onchange = async (e) => {
        const file = e.target.files[0]
        if (!file) return
        try {
          const fd = new FormData()
          fd.append('image', file)
          let endpoint = ''
          if (row.source === 'json') {
            fd.append('key', row.key)
            // Redirect known registry keys to media page even if they were discovered under other pages
            const pageKeyUsed = this.isRegistryKey(row.key) ? 'media' : row.pageKey
            endpoint = `/api/admin/pages/${pageKeyUsed}/replace-image`
          } else if (row.source === 'html' && row.model === 'page_content') {
            fd.append('old_url', row.url)
            endpoint = `/api/admin/pages/${row.pageKey}/replace-html-image`
          } else if (this.isModelRow(row) && row.key && (row.key === 'featured_image' || row.key === 'cover_image')) {
            fd.append('model', row.model)
            fd.append('id', row.id)
            fd.append('field', row.key)
            endpoint = `/api/admin/media/replace-model-image`
          } else if (this.isModelRow(row) && row.source === 'html') {
            fd.append('model', row.model)
            fd.append('id', row.id)
            fd.append('field', 'content')
            fd.append('old_url', row.url)
            endpoint = `/api/admin/media/replace-model-html-image`
          }
          const url = endpoint
          const res = await apiClient.upload(url, fd)
          if (res && (res.success || res.data)) {
            alert('画像を置換しました')
            this.load()
          } else {
            alert(res?.message || '置換に失敗しました')
          }
        } catch (err) {
          console.error('replace image failed', err)
          alert('置換に失敗しました')
        }
      }
      input.click()
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      const year = date.getFullYear()
      const month = date.getMonth() + 1
      const day = date.getDate()
      const weekdays = ['日', '月', '火', '水', '木', '金', '土']
      const weekday = weekdays[date.getDay()]
      
      return `${year}年${month}月${day}日(${weekday})`
    },
    performSearch() {
      // キー/URLを対象にクライアントフィルタ（簡易）: ページを先頭に戻す
      this.currentPage = 1
    },
    uploadNewMedia() {},
    goToPage(n) {
      if (n < 1 || n > this.totalPages) return
      this.currentPage = n
    },
    nextPage() { this.goToPage(this.currentPage + 1) },
    prevPage() { this.goToPage(this.currentPage - 1) },
  }
,
  computed: {
    filteredRows() {
      const kw = (this.searchKeyword || '').toLowerCase().trim()
      // Exclude model thumbnails (publication/economic_report/seminar) to avoid混同
      let list = this.allRows.filter(r => !this.isModelRow(r))
      if (kw) {
        list = list.filter(r =>
          (r.pageKey||'').toLowerCase().includes(kw) ||
          (r.key||'').toLowerCase().includes(kw) ||
          (r.url||'').toLowerCase().includes(kw)
        )
      }
      if (this.hidePlaceholders) {
        list = list.filter(r => !r.isPlaceholder)
      }
      if (this.groupResponsiveDuplicates) {
        list = list.filter(r => !(r.isVariant && r.url && r.baseUrl && r.url === r.baseUrl))
      }
      return list
    },
    totalPages() {
      return Math.max(1, Math.ceil(this.filteredRows.length / this.pageSize))
    },
    pagedRows() {
      const start = (this.currentPage - 1) * this.pageSize
      return this.filteredRows.slice(start, start + this.pageSize)
    },
    pageButtons() {
      const buttons = []
      const TP = this.totalPages
      const CP = this.currentPage
      const add = (page, label = null) => buttons.push({ key: `p-${page}-${label||page}` , page, label: label || page })
      const ell = () => buttons.push({ key: `e-${buttons.length}` , page: 0, label: '…', ellipsis: true })
      if (TP <= 9) {
        for (let i=1;i<=TP;i++) add(i)
      } else {
        add(1); add(2)
        if (CP > 5) ell()
        const start = Math.max(3, CP - 2)
        const end = Math.min(TP - 2, CP + 2)
        for (let i=start; i<=end; i++) add(i)
        if (CP < TP - 4) ell()
        add(TP-1); add(TP)
      }
      return buttons
    }
  }
}
</script>

<style scoped>
.dashboard {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.page-title {
  font-size: 24px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0;
}

.add-btn {
  background-color: #da5761;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-btn:hover {
  background-color: #c44853;
}

.search-section {
  padding: 16px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.search-container {
  display: flex;
  align-items: center;
  gap: 12px;
  max-width: 500px;
  margin-left: auto;
}

.search-label {
  font-size: 14px;
  color: #1A1A1A;
  white-space: nowrap;
}

.search-input {
  flex: 1;
  border: 1px solid #d0d0d0;
  border-radius: 4px;
  padding: 8px 12px;
  font-size: 14px;
}

.search-btn {
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
  color: #666;
  transition: color 0.2s;
}

.search-btn:hover {
  color: #1A1A1A;
}

.table-container {
  overflow-x: auto;
}

.loading, .error {
  padding: 40px;
  text-align: center;
  color: #666;
}

.error {
  color: #da5761;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  background-color: #f8f8f8;
  border-bottom: 1px solid #e5e5e5;
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 14px;
  color: #1A1A1A;
}

.data-table td {
  border-bottom: 1px solid #e5e5e5;
  padding: 16px;
  font-size: 14px;
}

.file-name {
  color: #1A1A1A;
  font-weight: 500;
}

.upload-date {
  color: #666;
}

.alt-text {
  color: #666;
}

.file-url {
  color: #0066cc;
  text-decoration: underline;
  max-width: 200px;
  word-break: break-all;
}

.time {
  font-size: 12px;
  color: #999;
}

.edit-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.edit-btn:hover {
  background-color: #555;
}

.checkbox {
  width: 16px;
  height: 16px;
  cursor: pointer;
}

.pagination {
  padding: 16px 24px;
  text-align: center;
  border-top: 1px solid #e5e5e5;
}

.page-info {
  font-size: 14px;
  color: #da5761;
}

.page-btn {
  margin: 0 4px;
  padding: 6px 10px;
  border: 1px solid #ddd;
  background: #fff;
  border-radius: 4px;
  cursor: pointer;
}
.page-btn:disabled { opacity: .5; cursor: default; }

.page-number {
  margin: 0 4px;
  padding: 6px 10px;
  border: 1px solid #ddd;
  background: #fff;
  border-radius: 4px;
  cursor: pointer;
}
.page-number.active { background: #1A1A1A; color: #fff; border-color: #1A1A1A; }
.page-number.ellipsis { cursor: default; background: transparent; border-color: transparent; }
</style>
