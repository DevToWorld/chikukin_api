<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <div class="header-left">
          <button @click="goBack" class="back-btn">
            ← メディア管理に戻る
          </button>
          <h1 class="page-title">{{ isNew ? '新規メディア追加' : 'メディア編集' }}</h1>
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
            <!-- メディア基本情報 -->
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
                  placeholder="メディアのタイトルを入力してください"
                />
              </div>

              <div class="form-group">
                <label for="description" class="form-label">
                  説明
                </label>
                <textarea
                  id="description"
                  v-model="formData.description"
                  rows="3"
                  class="form-input"
                  placeholder="メディアの説明を入力してください"
                />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="type" class="form-label">
                    メディアタイプ <span class="required">*</span>
                  </label>
                  <select
                    id="type"
                    v-model="formData.type"
                    required
                    class="form-input"
                  >
                    <option value="">タイプを選択</option>
                    <option value="image">画像</option>
                    <option value="video">動画</option>
                    <option value="audio">音声</option>
                    <option value="document">文書</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="category" class="form-label">
                    カテゴリー
                  </label>
                  <select
                    id="category"
                    v-model="formData.category"
                    class="form-input"
                  >
                    <option value="">カテゴリーを選択</option>
                    <option value="seminar">セミナー関連</option>
                    <option value="publication">刊行物関連</option>
                    <option value="news">お知らせ関連</option>
                    <option value="general">一般</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- ファイル情報 -->
            <div class="form-section">
              <h3 class="section-title">ファイル情報</h3>
              
              <div class="form-group">
                <label for="file_url" class="form-label">
                  ファイルURL <span class="required">*</span>
                </label>
                <input
                  id="file_url"
                  v-model="formData.file_url"
                  type="url"
                  required
                  class="form-input"
                  placeholder="https://example.com/media/file.jpg"
                />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="file_name" class="form-label">
                    ファイル名 <span class="required">*</span>
                  </label>
                  <input
                    id="file_name"
                    v-model="formData.file_name"
                    type="text"
                    required
                    class="form-input"
                    placeholder="example.jpg"
                  />
                </div>
                
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
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="mime_type" class="form-label">
                    MIMEタイプ
                  </label>
                  <input
                    id="mime_type"
                    v-model="formData.mime_type"
                    type="text"
                    class="form-input"
                    placeholder="image/jpeg, video/mp4, etc."
                  />
                </div>
                
                <div class="form-group">
                  <label for="alt_text" class="form-label">
                    代替テキスト（画像の場合）
                  </label>
                  <input
                    id="alt_text"
                    v-model="formData.alt_text"
                    type="text"
                    class="form-input"
                    placeholder="画像の代替テキスト"
                  />
                </div>
              </div>
            </div>

            <!-- メタデータ -->
            <div class="form-section">
              <h3 class="section-title">メタデータ</h3>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="width" class="form-label">
                    幅（px）
                  </label>
                  <input
                    id="width"
                    v-model="formData.width"
                    type="number"
                    min="1"
                    class="form-input"
                    placeholder="画像・動画の幅"
                  />
                </div>
                
                <div class="form-group">
                  <label for="height" class="form-label">
                    高さ（px）
                  </label>
                  <input
                    id="height"
                    v-model="formData.height"
                    type="number"
                    min="1"
                    class="form-input"
                    placeholder="画像・動画の高さ"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="duration" class="form-label">
                    再生時間（秒）
                  </label>
                  <input
                    id="duration"
                    v-model="formData.duration"
                    type="number"
                    min="0"
                    class="form-input"
                    placeholder="動画・音声の再生時間"
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
              <div class="form-group">
                <label class="checkbox-label">
                  <input
                    v-model="formData.is_public"
                    type="checkbox"
                    class="checkbox-input"
                  />
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-text">メディアを公開する</span>
                </label>
                <p class="form-help">
                  チェックを外すと、このメディアは非公開になります
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
import mockServer from '@/mockServer'

export default {
  name: 'MediaEditForm',
  components: {
    AdminLayout
  },
  data() {
    return {
      formData: {
        title: '',
        description: '',
        type: '',
        category: '',
        file_url: '',
        file_name: '',
        file_size: null,
        mime_type: '',
        alt_text: '',
        width: null,
        height: null,
        duration: null,
        download_count: 0,
        is_public: false
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
      return this.$route.params.id === 'new'
    },
    mediaId() {
      return this.$route.params.id
    }
  },
  async mounted() {
    const token = localStorage.getItem('admin_token')
    
    if (!token) {
      this.$router.push('/admin')
      return
    }

    // モックサーバーを使用するため、認証ヘッダーは不要
    
    if (!this.isNew) {
      await this.fetchMediaData()
    }
  },
  methods: {
    async fetchMediaData() {
      this.loading = true
      this.error = ''

      try {
        const data = await mockServer.getMediaItem(this.mediaId)
        // mockServerのデータ形式に合わせて調整
        this.formData = {
          ...data,
          description: data.description || '',
          category: data.category || '',
          file_url: data.url,
          file_name: data.title,
          is_public: true
        }
      } catch (err) {
        this.error = 'メディアデータの取得に失敗しました'
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
        if (this.isNew) {
          // mockServerのデータ形式に合わせて変換
          const mediaData = {
            title: this.formData.title || this.formData.file_name,
            url: this.formData.file_url,
            type: this.formData.type || 'document',
            description: this.formData.description,
            category: this.formData.category
          }
          await mockServer.uploadMedia(mediaData)
          this.successMessage = 'メディアを追加しました'
          setTimeout(() => {
            this.$router.push('/admin/media')
          }, 1500)
        } else {
          const mediaData = {
            title: this.formData.title || this.formData.file_name,
            url: this.formData.file_url,
            type: this.formData.type || 'document',
            description: this.formData.description,
            category: this.formData.category
          }
          await mockServer.updateMedia(this.mediaId, mediaData)
          this.successMessage = 'メディアを更新しました'
        }
      } catch (err) {
        if (err.response?.data?.errors) {
          this.submitError = Object.values(err.response.data.errors).flat().join(', ')
        } else {
          this.submitError = this.isNew ? 'メディアの追加に失敗しました' : 'メディアの更新に失敗しました'
        }
        console.error(err)
      } finally {
        this.submitLoading = false
      }
    },
    goBack() {
      this.$router.push('/admin/media')
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
