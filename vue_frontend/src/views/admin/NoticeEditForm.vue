<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <div class="header-left">
          <button @click="goBack" class="back-btn">
            ← お知らせ管理に戻る
          </button>
          <h1 class="page-title">{{ isNew ? '新規お知らせ作成' : 'お知らせ編集' }}</h1>
        </div>
        <div class="header-actions">
          <button @click="handleLogout" class="logout-btn">ログアウト</button>
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
            <!-- 画像設定 -->
            <div class="form-section">
              <h3 class="section-title">画像設定</h3>

              <div class="form-group">
                <label for="featured_image" class="form-label">
                  お知らせ画像（サムネイル）
                </label>
                <div v-if="formData.featured_image" class="image-preview-container">
                  <img :src="formData.featured_image" alt="お知らせ画像" class="image-preview" />
                  <button type="button" @click="removeImage" class="remove-image-btn">画像を削除</button>
                </div>
                <div class="image-upload-container">
                  <input
                    id="featured_image"
                    type="file"
                    @change="handleImageUpload"
                    accept="image/*"
                    class="file-input"
                    ref="imageInput"
                  />
                  <label for="featured_image" class="file-input-label">
                    画像を選択
                  </label>
                </div>
                <p class="form-help">推奨サイズ: 800×450px（16:9）、最大5MB</p>
              </div>
            </div>

            <!-- お知らせ基本情報 -->
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
                  placeholder="お知らせのタイトルを入力してください"
                />
              </div>

              <div class="form-group">
                <label for="content" class="form-label">
                  内容 <span class="required">*</span>
                </label>
                <textarea
                  id="content"
                  v-model="formData.content"
                  rows="6"
                  required
                  class="form-input"
                  placeholder="お知らせの内容を入力してください"
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
                    <option value="important">重要なお知らせ</option>
                    <option value="event">イベント情報</option>
                    <option value="system">システムメンテナンス</option>
                    <option value="general">一般情報</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="priority" class="form-label">
                    優先度 <span class="required">*</span>
                  </label>
                  <select
                    id="priority"
                    v-model="formData.priority"
                    required
                    class="form-input"
                  >
                    <option value="">優先度を選択</option>
                    <option value="high">高</option>
                    <option value="medium">中</option>
                    <option value="low">低</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- 公開設定 -->
            <div class="form-section">
              <h3 class="section-title">公開設定</h3>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="publish_date" class="form-label">
                    公開開始日 <span class="required">*</span>
                  </label>
                  <input
                    id="publish_date"
                    v-model="formData.publish_date"
                    type="date"
                    required
                    class="form-input"
                  />
                </div>
                
                <div class="form-group">
                  <label for="expire_date" class="form-label">
                    公開終了日
                  </label>
                  <input
                    id="expire_date"
                    v-model="formData.expire_date"
                    type="date"
                    class="form-input"
                  />
                  <p class="form-help">
                    指定しない場合は期限なしで公開されます
                  </p>
                </div>
              </div>

              <div class="form-group">
                <label class="checkbox-label">
                  <input
                    v-model="formData.is_pinned"
                    type="checkbox"
                    class="checkbox-input"
                  />
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-text">お知らせをピン留めする（上部に固定表示）</span>
                </label>
              </div>

              <div class="form-group">
                <label class="checkbox-label">
                  <input
                    v-model="formData.send_notification"
                    type="checkbox"
                    class="checkbox-input"
                  />
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-text">会員にメール通知を送信する</span>
                </label>
                <p class="form-help">
                  新規作成時のみ有効です
                </p>
              </div>

              <div class="form-group">
                <label class="checkbox-label">
                  <input
                    v-model="formData.is_published"
                    type="checkbox"
                    class="checkbox-input"
                  />
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-text">お知らせを公開する</span>
                </label>
                <p class="form-help">
                  チェックを外すと、このお知らせは非公開になります
                </p>
              </div>
            </div>

            <!-- リンク情報 -->
            <div class="form-section">
              <h3 class="section-title">リンク情報（任意）</h3>
              
              <div class="form-group">
                <label for="link_url" class="form-label">
                  関連リンクURL
                </label>
                <input
                  id="link_url"
                  v-model="formData.link_url"
                  type="url"
                  class="form-input"
                  placeholder="https://example.com"
                />
              </div>

              <div class="form-group">
                <label for="link_text" class="form-label">
                  リンクテキスト
                </label>
                <input
                  id="link_text"
                  v-model="formData.link_text"
                  type="text"
                  class="form-input"
                  placeholder="詳細はこちら"
                />
                <p class="form-help">
                  リンクに表示するテキストを入力してください
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

export default {
  name: 'NoticeEditForm',
  components: {
    AdminLayout
  },
  data() {
    return {
      formData: {
        title: '',
        content: '',
        category: '',
        priority: '',
        publish_date: '',
        expire_date: '',
        is_pinned: false,
        send_notification: false,
        is_published: false,
        link_url: '',
        link_text: '',
        featured_image: ''
      },
      loading: false,
      submitLoading: false,
      error: '',
      submitError: '',
      successMessage: ''
    }
  },
  computed: {
    isNew() {
      // /admin/news/register の場合は新規、/admin/news/edit/:id は編集
      return !(this.noticeId)
    },
    noticeId() {
      return this.$route.params.id || this.$route.query.id
    }
  },
  async mounted() {
    const token = localStorage.getItem('admin_token')
    
    if (!token) {
      this.$router.push('/admin')
      return
    }

    if (!this.isNew) {
      await this.fetchNoticeData()
    } else {
      // 新規作成時のデフォルト値
      const today = new Date().toISOString().split('T')[0]
      this.formData.publish_date = today
    }
  },
  methods: {
    async fetchNoticeData() {
      this.loading = true
      this.error = ''

      try {
        const res = await apiClient.get(`/api/admin/notices/${this.noticeId}`)
        // NoticeController returns raw model or {notice:...} depending on wrapper
        const data = (res && res.id) ? res : (res?.notice || res?.data || res)
        if (data && data.id) {
          this.formData = {
            title: data.title,
            content: data.content,
            category: data.category || 'notice',
            publish_date: (data.published_at || '').split('T')[0] || '',
            is_pinned: !!data.is_featured,
            is_published: !!data.is_published,
            featured_image: data.featured_image || ''
          }
        } else {
          throw new Error('Notice not found')
        }
      } catch (err) {
        this.error = 'お知らせデータの取得に失敗しました'
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
        // Map to NoticeController expected fields
        const payload = {
          title: this.formData.title,
          content: this.formData.content,
          summary: this.formData.summary || this.formData.content?.slice(0, 120) || '',
          category: this.formData.category || 'notice',
          status: (this.formData.is_published || this.formData.is_pinned) ? 'published' : 'draft',
          published_at: this.formData.publish_date || null,
          featured: !!this.formData.is_pinned,
          featured_image: this.formData.featured_image || ''
        }
        if (this.isNew) {
          const res = await apiClient.post('/api/admin/notices', payload)
          if (!res || res.status === 'error') throw new Error(res.message || '作成に失敗')
          this.successMessage = 'お知らせを作成しました'
          setTimeout(() => { this.$router.push('/admin/news') }, 1200)
        } else {
          const res = await apiClient.put(`/api/admin/notices/${this.noticeId}`, payload)
          if (!res || res.status === 'error') throw new Error(res.message || '更新に失敗')
          this.successMessage = 'お知らせを更新しました'
        }
      } catch (err) {
        if (err.response?.data?.errors) {
          this.submitError = Object.values(err.response.data.errors).flat().join(', ')
        } else {
          this.submitError = this.isNew ? 'お知らせの作成に失敗しました' : 'お知らせの更新に失敗しました'
        }
        console.error(err)
      } finally {
        this.submitLoading = false
      }
    },
    async handleImageUpload(event) {
      const file = event.target.files[0]
      if (!file) return

      if (file.size > 5 * 1024 * 1024) {
        alert('画像ファイルは5MB以下にしてください')
        return
      }
      if (!file.type.startsWith('image/')) {
        alert('画像ファイルを選択してください')
        return
      }

      try {
        const form = new FormData()
        form.append('file', file)
        form.append('directory', 'public/media/notices')
        const res = await apiClient.upload('/api/admin/media/upload', form)
        if (!res || res.success === false || !res.file?.url) {
          const msg = res?.error || res?.message || 'アップロードに失敗しました'
          throw new Error(msg)
        }
        this.formData.featured_image = res.file.url
      } catch (e) {
        console.error('Image upload failed:', e)
        alert('画像のアップロードに失敗しました')
      }
    },
    removeImage() {
      this.formData.featured_image = ''
      if (this.$refs.imageInput) {
        this.$refs.imageInput.value = ''
      }
    },
    goBack() {
      this.$router.push('/admin/news')
    },
    handleLogout() {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('adminUser')
      delete axios.defaults.headers.common['Authorization']
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
  margin-bottom: 8px;
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

/* 画像アップロード UI */
.image-preview-container {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}
.image-preview {
  width: 200px;
  height: auto;
  border: 1px solid #e5e5e5;
  border-radius: 6px;
}
.remove-image-btn {
  background: #fff;
  color: #da5761;
  border: 1px solid #da5761;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
}
.remove-image-btn:hover { background: #da5761; color: #fff; }
.image-upload-container { display: flex; align-items: center; gap: 12px; }
.file-input { display: none; }
.file-input-label {
  background-color: #1A1A1A;
  color: white;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  display: inline-block;
}
.help-text { font-size: 12px; color: #666; margin-top: 8px; }

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
