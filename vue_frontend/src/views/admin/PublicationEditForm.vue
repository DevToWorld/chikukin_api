<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <div class="header-left">
          <button @click="goBack" class="back-btn">
            ← 刊行物管理に戻る
          </button>
          <h1 class="page-title">{{ isNew ? '新規刊行物作成' : '刊行物編集' }}</h1>
        </div>
        <div class="header-actions">
          <button @click="handleLogout" class="logout-btn">ログアウト</button>
          </div>
        </div>

        <!-- 画像アップロード（PDFファイルのアップロードは廃止。URL入力を使用） -->
        <div class="form-section">
          <h3 class="section-title">サムネイル</h3>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">サムネイル画像（カバー）</label>
              <div class="image-picker">
                <img v-if="coverPreview || formData.cover_image" :src="coverPreview || formData.cover_image" alt="cover preview" class="image-preview" />
                <input type="file" accept="image/*" @change="onPickCover" />
              </div>
              <p class="form-help">推奨サイズの比率を維持してアップロードしてください。（最大5MB）</p>
            </div>
          </div>
        </div>

      <!-- フォームコンテナ -->
      <div class="form-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error && !isNew" class="error-container">
          <div class="error-message">{{ error }}</div>
        </div>
        <div v-else class="form-content">
          <form @submit.prevent="handleSubmit" class="edit-form">
            <!-- 刊行物基本情報 -->
            <div class="form-section">
              <h3 class="section-title">基本情報</h3>
              
              <div class="form-group">
                <label for="title" class="form-label">
                  タイトル <span class="required">*</span>
                </label>
                <input
                  id="title"
                  v-model="formData.title"
                  type="text"
                  required
                  class="form-input"
                  placeholder="刊行物のタイトルを入力してください"
                />
              </div>

              <div class="form-group">
                <label for="description" class="form-label">
                  概要説明 <span class="required">*</span>
                </label>
                <textarea
                  id="description"
                  v-model="formData.description"
                  rows="4"
                  required
                  class="form-input"
                  placeholder="刊行物の概要説明を入力してください"
                />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="category" class="form-label">
                    カテゴリー <span class="required">*</span>
                  </label>
                  <select
                    id="category"
                    v-model="formData.category"
                    required
                    class="form-input"
                  >
                    <option value="">カテゴリーを選択</option>
                    <option v-for="c in categoryOptions" :key="c.id" :value="c.slug">
                      {{ c.name }}
                    </option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="type" class="form-label">
                    種別 <span class="required">*</span>
                  </label>
                  <select
                    id="type"
                    v-model="formData.type"
                    required
                    class="form-input"
                  >
                    <option value="">種別を選択</option>
                    <option value="pdf">PDF</option>
                    <option value="book">冊子</option>
                    <option value="digital">デジタル</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- 発行情報 -->
            <div class="form-section">
              <h3 class="section-title">発行情報</h3>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="publication_date" class="form-label">
                    発行日 <span class="required">*</span>
                  </label>
                  <input
                    id="publication_date"
                    v-model="formData.publication_date"
                    type="date"
                    required
                    class="form-input"
                  />
                </div>
                
                <div class="form-group">
                  <label for="issue_number" class="form-label">
                    号数
                  </label>
                  <input
                    id="issue_number"
                    v-model="formData.issue_number"
                    type="text"
                    class="form-input"
                    placeholder="例: Vol.10, No.2"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="author" class="form-label">
                    著者・編集者
                  </label>
                  <input
                    id="author"
                    v-model="formData.author"
                    type="text"
                    class="form-input"
                    placeholder="著者名または編集者名"
                  />
                </div>
                
                <div class="form-group">
                  <label for="pages" class="form-label">
                    ページ数
                  </label>
                  <input
                    id="pages"
                    v-model="formData.pages"
                    type="number"
                    min="1"
                    class="form-input"
                    placeholder="ページ数"
                  />
                </div>
              </div>
            </div>

            <!-- ファイル情報 -->
            <div class="form-section">
              <h3 class="section-title">ファイル情報</h3>
              
              <div class="form-group">
                <label for="file_url" class="form-label">
                  ファイルURL
                </label>
                <input
                  id="file_url"
                  v-model="formData.file_url"
                  type="url"
                  class="form-input"
                  placeholder="https://example.com/publication.pdf"
                />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="file_size" class="form-label">
                    ファイルサイズ（MB）
                  </label>
                  <input
                    id="file_size"
                    v-model="formData.file_size"
                    type="number"
                    step="0.1"
                    min="0"
                    class="form-input"
                    placeholder="ファイルサイズ"
                  />
                </div>
                
                <div class="form-group">
                  <label for="download_count" class="form-label">
                    ダウンロード数
                  </label>
                  <input
                    id="download_count"
                    v-model="formData.download_count"
                    type="number"
                    min="0"
                    class="form-input"
                    placeholder="ダウンロード数"
                    :disabled="isNew"
                  />
                </div>
              </div>
            </div>

            <!-- 公開設定 -->
            <div class="form-section">
              <h3 class="section-title">公開範囲</h3>
              <div class="form-group">
                <label for="membership_level" class="form-label">
                  対象会員レベル <span class="required">*</span>
                </label>
                <select
                  id="membership_level"
                  v-model="formData.membership_level"
                  required
                  class="form-input"
                >
                  <option value="free">一般公開（無料）</option>
                  <option value="standard">スタンダード会員以上</option>
                  <option value="premium">プレミアム会員のみ</option>
                </select>
                <p class="form-help">選択したレベル以上の会員に表示・ダウンロードが可能になります</p>
              </div>

              <div class="form-group">
                <label class="checkbox-label">
                  <input
                    v-model="formData.is_published"
                    type="checkbox"
                    class="checkbox-input"
                  />
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-text">刊行物を公開する</span>
                </label>
                <p class="form-help">
                  チェックを外すと、この刊行物は非公開になります
                </p>
              </div>
            </div>

            <!-- エラー・成功メッセージ -->
            <div v-if="submitError" class="message error-message">
              {{ submitError }}
            </div>

            <div v-if="successMessage" class="message success-message">
              {{ successMessage }}
            </div>

            <!-- アクションボタン -->
            <div class="form-actions">
              <button
                type="button"
                @click="goBack"
                class="btn btn-secondary"
              >
                キャンセル
              </button>
              <button
                type="submit"
                :disabled="loading || submitLoading"
                class="btn btn-primary"
              >
                {{ submitLoading ? '保存中...' : '保存' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import apiClient from '../../services/apiClient'
import mockServer from '@/mockServer'

export default {
  name: 'PublicationEditForm',
  components: {
    AdminLayout
  },
  data() {
    return {
      formData: {
        title: '',
        description: '',
        category: '',
        type: '',
        publication_date: '',
        issue_number: '',
        author: '',
        pages: null,
        file_url: '',
        file_size: null,
        download_count: 0,
        is_published: false,
        membership_level: 'free'
      },
      coverImageFile: null,
      coverPreview: '',
      // カテゴリー選択肢（管理のカテゴリー管理と同期）
      categories: [],
      loading: false,
      submitLoading: false,
      error: '',
      submitError: '',
      successMessage: ''
    }
  },
  computed: {
    isNew() {
      return this.$route.name === 'publicationNew' || !this.publicationId
    },
    publicationId() {
      return this.$route.params.id || this.$route.query.id
    },
    // 表示用に is_active を考慮、ソート順も維持
    categoryOptions() {
      if (!this.categories || this.categories.length === 0) return []
      // is_active が存在する場合は true のみ表示
      const opts = this.categories
        .filter(c => (typeof c.is_active === 'boolean' ? c.is_active : true))
        .sort((a, b) => (a.sort_order ?? 0) - (b.sort_order ?? 0) || (a.id - b.id))

      // 編集時に現在の値が非アクティブ等で表示対象外の場合は追加しておく
      const current = this.formData?.category
      if (current && !opts.some(c => c.slug === current)) {
        const found = this.categories.find(c => c.slug === current)
        if (found) {
          opts.unshift(found)
        } else {
          // カテゴリーマスタに存在しないスラッグの場合のフォールバック表示
          opts.unshift({ id: 0, name: `現在の値: ${current}`, slug: current, sort_order: -999 })
        }
      }
      return opts
    }
  },
  async mounted() {
    const token = localStorage.getItem('admin_token')
    
    if (!token) {
      this.$router.push('/admin')
      return
    }

    // カテゴリーの読み込み（最初に実行）
    await this.loadCategories()

    if (!this.isNew) {
      await this.fetchPublicationData()
    }
  },
  methods: {
    onPickCover(e) {
      const f = e?.target?.files?.[0]
      this.coverImageFile = f || null
      if (f) {
        try { this.coverPreview = URL.createObjectURL(f) } catch(_) { this.coverPreview = '' }
      } else {
        this.coverPreview = ''
      }
    },
    async loadCategories() {
      try {
        // 管理APIからカテゴリー一覧を取得
        const res = await apiClient.getPublicationCategories()
        if (res && res.success) {
          // data 直下 or data.categories いずれにも対応
          this.categories = Array.isArray(res.data) ? res.data : (res.data?.categories || [])
          // 正常取得できたら終了
          return
        }
        throw new Error('カテゴリー取得に失敗しました')
      } catch (e) {
        console.warn('カテゴリー読み込みに失敗。デフォルトにフォールバックします。', e)
        // フォールバック（旧固定値）
        this.categories = [
          { id: 1, name: '研究レポート', slug: 'research', sort_order: 1, is_active: true },
          { id: 2, name: '季報', slug: 'quarterly', sort_order: 2, is_active: true },
          { id: 3, name: '特別レポート', slug: 'special', sort_order: 3, is_active: true },
          { id: 4, name: '調査資料', slug: 'survey', sort_order: 4, is_active: true },
        ]
      }
    },
    async fetchPublicationData() {
      this.loading = true
      this.error = ''

      try {
        const res = await apiClient.get(`/api/admin/publications/${this.publicationId}`)
        // 旧ルートは publication オブジェクトを直接返す（success ラッパーのみ）
        if (res && res.success && res.data) {
          const data = res.data.publication || res.data
          this.formData = {
            ...data,
            issue_number: data.issue_number || '',
            download_count: data.download_count || 0
          }
        } else {
          throw new Error('Publication not found')
        }
      } catch (err) {
        this.error = '刊行物データの取得に失敗しました'
        console.error(err)
      } finally {
        this.loading = false
      }
    },
    async handleSubmit() {
      this.submitLoading = true
      this.submitError = ''
      this.successMessage = ''

      try {
        const token = localStorage.getItem('admin_token')
        if (!token) {
          this.$router.push('/admin')
          return
        }
        // Build multipart form
        const fd = new FormData()
        const payload = { ...this.formData }
        // Do NOT send existing cover_image URL as a string when uploading.
        // The admin backend expects cover_image to be a file if present.
        delete payload.cover_image
        // Normalize booleans and skip empty strings (e.g., pages: '')
        Object.entries(payload).forEach(([k, v]) => {
          if (v === null || v === undefined) return
          if (typeof v === 'boolean') {
            fd.append(k, v ? '1' : '0')
            return
          }
          if (typeof v === 'string' && v.trim() === '') return
          fd.append(k, v)
        })
        if (this.coverImageFile) fd.append('cover_image', this.coverImageFile)

        let endpoint = '/api/admin/publications'
        let method = 'POST'
        if (!this.isNew) {
          endpoint = `/api/admin/publications/${this.publicationId}`
          // Use POST with _method=PUT for wide PHP compatibility
          fd.append('_method', 'PUT')
        }
        const res = await apiClient.upload(endpoint, fd, { method })
        if (!res?.success) throw new Error(res?.message || res?.error || '保存に失敗')
        this.successMessage = this.isNew ? '刊行物を作成しました' : '刊行物を更新しました'
        if (this.isNew) setTimeout(() => { this.$router.push('/admin/publication') }, 1200)
      } catch (err) {
        if (err.response?.data?.errors) {
          this.submitError = Object.values(err.response.data.errors).flat().join(', ')
        } else {
          this.submitError = this.isNew ? '刊行物の作成に失敗しました' : '刊行物の更新に失敗しました'
        }
        console.error(err)
      } finally {
        this.submitLoading = false
      }
    },
    goBack() {
      this.$router.push('/admin/publication')
    },
    handleLogout() {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('adminUser')
      // モックサーバーを使用するため、認証ヘッダーの削除は不要
      this.$router.push('/admin')
    }
  }
}
</script>

<style scoped>
.dashboard {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  min-height: calc(100vh - 120px);
}

.image-picker { display:flex; gap:12px; align-items:center; }
.image-preview { width:160px; height:100px; object-fit:cover; border:1px solid #e5e5e5; border-radius:6px; }

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
  background-color: #f8f9fa;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.back-btn {
  background: none;
  border: none;
  color: #0066cc;
  font-size: 14px;
  cursor: pointer;
  padding: 8px 0;
  transition: color 0.2s;
}

.back-btn:hover {
  color: #0052a3;
  text-decoration: underline;
}

.page-title {
  font-size: 24px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.logout-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.logout-btn:hover {
  background-color: #555;
}

.form-container {
  padding: 24px;
  max-width: 800px;
  margin: 0 auto;
}

.loading {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 16px;
}

.error-container {
  padding: 20px;
  text-align: center;
}

.error-message {
  background-color: #fee2e2;
  border: 1px solid #fca5a5;
  color: #dc2626;
  padding: 16px;
  border-radius: 8px;
  font-size: 14px;
}

.form-section {
  margin-bottom: 32px;
}

.section-title {
  font-size: 18px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0 0 16px 0;
  padding-bottom: 8px;
  border-bottom: 2px solid #da5761;
}

.form-group {
  margin-bottom: 24px;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #1A1A1A;
  margin-bottom: 8px;
}

.required {
  color: #dc2626;
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e5e5;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
  box-sizing: border-box;
  font-family: inherit;
}

.form-input:focus {
  outline: none;
  border-color: #da5761;
}

.form-input:disabled {
  background-color: #f5f5f5;
  color: #666;
  cursor: not-allowed;
}

.form-help {
  font-size: 12px;
  color: #666;
  margin-top: 4px;
  margin-bottom: 0;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  padding: 16px;
  border: 2px solid #e5e5e5;
  border-radius: 6px;
  transition: border-color 0.2s;
}

.checkbox-label:hover {
  border-color: #da5761;
}

.checkbox-input {
  display: none;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  position: relative;
  transition: all 0.2s;
}

.checkbox-input:checked + .checkbox-custom {
  background-color: #da5761;
  border-color: #da5761;
}

.checkbox-input:checked + .checkbox-custom::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 6px;
  width: 4px;
  height: 8px;
  border: 2px solid white;
  border-left: none;
  border-top: none;
  transform: rotate(45deg);
}

.checkbox-text {
  font-size: 14px;
  font-weight: 500;
  color: #1A1A1A;
}

.message {
  padding: 16px;
  border-radius: 6px;
  margin-bottom: 24px;
  font-size: 14px;
}

.success-message {
  background-color: #d1fae5;
  border: 1px solid #6ee7b7;
  color: #065f46;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 24px;
  border-top: 1px solid #e5e5e5;
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  min-width: 120px;
}

.btn-secondary {
  background-color: #f5f5f5;
  color: #1A1A1A;
  border: 1px solid #d1d5db;
}

.btn-secondary:hover {
  background-color: #e5e5e5;
}

.btn-primary {
  background-color: #da5761;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #c44853;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    gap: 16px;
    align-items: stretch;
  }
  
  .header-left {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  
  .form-container {
    padding: 16px;
  }
  
  .page-title {
    font-size: 20px;
  }
  
  .form-actions {
    flex-direction: column-reverse;
  }
  
  .btn {
    width: 100%;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .page-header {
    padding: 16px;
  }
  
  .form-container {
    padding: 12px;
  }
  
  .page-title {
    font-size: 18px;
  }
  
  .form-section {
    margin-bottom: 24px;
  }
}
</style>
