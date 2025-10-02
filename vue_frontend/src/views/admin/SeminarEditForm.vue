<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <div class="header-left">
          <button @click="goBack" class="back-btn">
            ← セミナー管理に戻る
          </button>
          <h1 class="page-title">{{ isNew ? '新規セミナー作成' : 'セミナー編集' }}</h1>
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
            <!-- セミナー基本情報 -->
            <div class="form-section">
              <h3 class="section-title">基本情報</h3>
              
              <div class="form-group">
                <label for="title" class="form-label">
                  セミナータイトル <span class="required">*</span>
                </label>
                <input
                  id="title"
                  v-model="formData.title"
                  type="text"
                  required
                  class="form-input"
                  placeholder="セミナーのタイトルを入力してください"
                />
              </div>

              <div class="form-group">
                <label for="description" class="form-label">
                  詳細説明 <span class="required">*</span>
                </label>
                <textarea
                  id="description"
                  v-model="formData.description"
                  rows="4"
                  required
                  class="form-input"
                  placeholder="セミナーの詳細説明を入力してください"
                />
              </div>
            </div>

            <!-- スケジュール情報 -->
            <div class="form-section">
              <h3 class="section-title">スケジュール</h3>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="date" class="form-label">
                    開催日 <span class="required">*</span>
                  </label>
                  <input
                    id="date"
                    v-model="formData.date"
                    type="date"
                    required
                    class="form-input"
                  />
                </div>
                
                <div class="form-group">
                  <label for="start_time" class="form-label">
                    開始時間 <span class="required">*</span>
                  </label>
                  <input
                    id="start_time"
                    v-model="formData.start_time"
                    type="time"
                    required
                    class="form-input"
                  />
                </div>
                
                <div class="form-group">
                  <label for="end_time" class="form-label">
                    終了時間 <span class="required">*</span>
                  </label>
                  <input
                    id="end_time"
                    v-model="formData.end_time"
                    type="time"
                    required
                    class="form-input"
                  />
                </div>
              </div>
            </div>

            <!-- 開催情報 -->
            <div class="form-section">
              <h3 class="section-title">開催情報</h3>
              
              <div class="form-group">
                <label for="location" class="form-label">
                  開催場所 <span class="required">*</span>
                </label>
                <input
                  id="location"
                  v-model="formData.location"
                  type="text"
                  required
                  class="form-input"
                  placeholder="例: ちくぎん地域経済研究所 会議室A"
                />
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label for="capacity" class="form-label">
                    定員
                  </label>
                  <input
                    id="capacity"
                    v-model="formData.capacity"
                    type="number"
                    min="1"
                    class="form-input"
                    placeholder="定員数を入力"
                  />
                </div>
                
                <div class="form-group">
                  <label for="fee" class="form-label">
                    参加費
                  </label>
                  <input
                    id="fee"
                    v-model="formData.fee"
                    type="number"
                    min="0"
                    class="form-input"
                    placeholder="参加費（円）"
                  />
                </div>
              </div>
            </div>

            <!-- 画像設定 -->
            <div class="form-section">
              <h3 class="section-title">画像設定</h3>
              
              <div class="form-group">
                <label for="featured_image" class="form-label">
                  セミナー画像
                </label>
                <div v-if="formData.featured_image" class="image-preview-container">
                  <img :src="formData.featured_image" alt="セミナー画像" class="image-preview" />
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
                <p class="help-text">推奨サイズ: 800×450px（16:9）、最大5MB</p>
              </div>
            </div>

            <!-- ステータス -->
            <div class="form-section">
              <h3 class="section-title">公開範囲</h3>
              <div class="form-row">
                <div class="form-group">
                  <label for="membership_requirement" class="form-label">
                    対象会員レベル <span class="required">*</span>
                  </label>
                  <select
                    id="membership_requirement"
                    v-model="formData.membership_requirement"
                    required
                    class="form-input"
                  >
                    <option value="free">一般公開（無料）</option>
                    <option value="standard">スタンダード会員以上</option>
                    <option value="premium">プレミアム会員のみ</option>
                  </select>
                  <p class="form-help">選択したレベル以上の会員に表示・申込が可能になります</p>
                </div>
              </div>

              <h3 class="section-title">ステータス</h3>
              <div class="form-group">
                <label for="status" class="form-label">
                  ステータス <span class="required">*</span>
                </label>
                <select
                  id="status"
                  v-model="formData.status"
                  required
                  class="form-input"
                >
                  <option value="">ステータスを選択</option>
                  <option value="scheduled">予定</option>
                  <option value="ongoing">申し込み受付中</option>
                  <option value="completed">終了</option>
                  <option value="cancelled">中止</option>
                </select>
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
  name: 'SeminarEditForm',
  components: {
    AdminLayout
  },
  data() {
    return {
      formData: {
        title: '',
        description: '',
        date: '',
        start_time: '',
        end_time: '',
        location: '',
        capacity: null,
        fee: null,
        status: '',
        membership_requirement: 'free',
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
      return this.$route.name === 'seminarNew' || !this.seminarId
    },
    seminarId() {
      return this.$route.params.id || this.$route.query.id
    }
  },
  async mounted() {
    const token = localStorage.getItem('admin_token')
    
    if (!token) {
      this.$router.push('/admin/login')
      return
    }

    // 新規作成の場合はデータ取得をスキップ
    if (!this.isNew) {
      await this.fetchSeminarData()
    }
  },
  methods: {
    async fetchSeminarData() {
      this.loading = true
      this.error = ''

      try {
        // 管理APIから取得（draft等でも取得可能）
        const res = await apiClient.get(`/api/admin/seminars/${this.seminarId}`)
        if (res.success && res.data && res.data.seminar) {
          this.formData = res.data.seminar
        } else {
          throw new Error('Seminar not found')
        }
      } catch (err) {
        this.error = 'セミナーデータの取得に失敗しました'
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
          this.$router.push('/admin/login')
          return
        }

        // 送信前にバリデーションに通るよう整形
        const payload = { ...this.formData }
        // 空文字を削除（nullable の項目で 422 にならないように）
        if (!payload.status) delete payload.status
        if (payload.capacity === '' || payload.capacity === null) delete payload.capacity
        if (payload.fee === '' || payload.fee === null) delete payload.fee
        // 数値化
        if (payload.capacity !== undefined) payload.capacity = parseInt(payload.capacity, 10)
        if (payload.fee !== undefined) payload.fee = parseFloat(payload.fee)
        // 画像URLの長さ安全化
        if (payload.featured_image && typeof payload.featured_image === 'string') {
          payload.featured_image = payload.featured_image.substring(0, 480)
        }

        // CORS/環境差異を考慮し、multipart + _method=PUT で送信（JSONより堅牢）
        const toStr = v => (v === undefined || v === null ? '' : String(v))
        const fd = new FormData()
        Object.entries(payload).forEach(([k, v]) => {
          if (v !== undefined && v !== null) fd.append(k, toStr(v))
        })

        let url = (await import('../../config/api.js')).getApiUrl('/api/admin/seminars')
        let method = 'POST'
        if (!this.isNew) {
          url = (await import('../../config/api.js')).getApiUrl(`/api/admin/seminars/${this.seminarId}`)
          fd.append('_method', 'PUT')
        }

        const resp = await fetch(url, {
          method,
          headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` },
          body: fd
        })
        const data = await resp.json().catch(() => ({}))
        if (!resp.ok || !data?.success) {
          const msg = data?.message || (this.isNew ? '作成に失敗' : '更新に失敗')
          const det = data?.errors || data?.error?.details
          if (det) {
            const first = Object.entries(det)[0]
            const val = Array.isArray(first?.[1]) ? first[1][0] : first?.[1]
            this.submitError = first ? `${msg}: ${first[0]} - ${val}` : msg
          } else {
            this.submitError = msg
          }
          throw new Error(this.submitError)
        }
        this.successMessage = this.isNew ? 'セミナーを作成しました' : 'セミナーを更新しました'
        if (this.isNew) {
          setTimeout(() => { this.$router.push('/admin/seminar') }, 1200)
        } else {
          await this.fetchSeminarData()
        }
      } catch (err) {
        if (!this.submitError) {
          this.submitError = this.isNew ? 'セミナーの作成に失敗しました' : 'セミナーの更新に失敗しました'
        }
        console.error(err)
      } finally {
        this.submitLoading = false
      }
    },
    goBack() {
      this.$router.push('/admin/seminar')
    },
    handleLogout() {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('adminUser')
      this.$router.push('/admin/login')
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

      // 管理メディアAPIへアップロードしてURLを取得（base64は長すぎて弾かれるため）
      try {
        const token = localStorage.getItem('admin_token')
        if (!token) {
          alert('管理者ログインが必要です')
          this.$router.push('/admin/login')
          return
        }
        const form = new FormData()
        form.append('file', file)
        form.append('directory', 'public/media/seminars')
        const res = await fetch((await import('../../config/api.js')).getApiUrl('/api/admin/media/upload'), {
          method: 'POST',
          headers: { 'Authorization': `Bearer ${token}` },
          body: form
        })
        const data = await res.json()
        if (!res.ok || !data?.file?.url) {
          throw new Error(data?.message || 'アップロードに失敗しました')
        }
        this.formData.featured_image = data.file.url // 例: /storage/...
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

/* 画像アップロード関連のスタイル */
.image-preview-container {
  margin-bottom: 16px;
}

.image-preview {
  max-width: 100%;
  max-height: 300px;
  border-radius: 8px;
  border: 2px solid #e5e5e5;
  display: block;
  margin-bottom: 12px;
}

.remove-image-btn {
  background-color: #dc2626;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.remove-image-btn:hover {
  background-color: #b91c1c;
}

.image-upload-container {
  position: relative;
}

.file-input {
  position: absolute;
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  z-index: -1;
}

.file-input-label {
  display: inline-block;
  padding: 12px 24px;
  background-color: #f5f5f5;
  color: #1A1A1A;
  border: 2px dashed #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.file-input-label:hover {
  background-color: #e5e5e5;
  border-color: #da5761;
}

.help-text {
  font-size: 12px;
  color: #666;
  margin-top: 8px;
}
</style>
