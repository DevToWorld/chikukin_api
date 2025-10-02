<template>
  <div v-if="isOpen" class="login-modal-overlay" @click="closeModal">
    <div class="login-modal-content" @click.stop>
      <button class="close-btn" @click="closeModal">×</button>
      
      <div class="modal-header">
        <h2>{{ isLoginMode ? 'ログイン' : '新規登録' }}</h2>
      </div>

      <form @submit.prevent="handleSubmit" class="login-form">
        <div v-if="!isLoginMode" class="form-group">
          <label for="name">お名前</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            class="form-input"
            required
          />
        </div>

        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input
            id="email"
            v-model="formData.email"
            type="email"
            class="form-input"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">パスワード</label>
          <input
            id="password"
            v-model="formData.password"
            type="password"
            class="form-input"
            required
          />
        </div>

        <div v-if="!isLoginMode" class="form-group">
          <label for="password_confirmation">パスワード（確認）</label>
          <input
            id="password_confirmation"
            v-model="formData.password_confirmation"
            type="password"
            class="form-input"
            required
          />
        </div>

        <div v-if="!isLoginMode" class="form-group">
          <label for="membership_type">会員プラン</label>
          <select
            id="membership_type"
            v-model="formData.membership_type"
            class="form-input"
          >
            <option value="free">無料会員</option>
            <option value="standard">スタンダード会員</option>
            <option value="premium">プレミアム会員</option>
          </select>
        </div>

        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>

        <div v-if="successMessage" class="success-message">
          {{ successMessage }}
        </div>

        <button type="submit" class="submit-btn" :disabled="isLoading">
          {{ isLoading ? '処理中...' : (isLoginMode ? 'ログイン' : '登録') }}
        </button>
      </form>

      <div class="modal-footer">
        <p v-if="isLoginMode">
          アカウントをお持ちでない方は
          <button @click="toggleMode" class="link-btn">新規登録</button>
        </p>
        <p v-else>
          既にアカウントをお持ちの方は
          <button @click="toggleMode" class="link-btn">ログイン</button>
        </p>
      </div>

      <div v-if="!isLoginMode" class="membership-info">
        <h3>会員プランの特徴</h3>
        <div class="plans">
          <div class="plan">
            <h4>無料会員</h4>
            <ul>
              <li>基本的な経済統計の閲覧</li>
              <li>お知らせ記事の一部閲覧</li>
            </ul>
          </div>
          <div class="plan">
            <h4>スタンダード会員</h4>
            <ul>
              <li>経済統計の詳細データ</li>
              <li>財務レポートの閲覧</li>
              <li>全お知らせ記事の閲覧</li>
              <li>データエクスポート（制限付き）</li>
            </ul>
          </div>
          <div class="plan">
            <h4>プレミアム会員</h4>
            <ul>
              <li>全機能へのアクセス</li>
              <li>APIアクセス</li>
              <li>カスタムレポート作成</li>
              <li>優先サポート</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, watch } from 'vue';
import { useStore } from 'vuex';
import apiClient from '@/services/apiClient';
import { getApiUrl } from '@/config/api';

export default {
  name: 'LoginModal',
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close', 'login-success'],
  setup(props, { emit }) {
    const store = useStore();
    const isLoginMode = ref(true);
    const isLoading = ref(false);
    const errorMessage = ref('');
    const successMessage = ref('');

    const formData = reactive({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      membership_type: 'free'
    });

    const resetForm = () => {
      formData.name = '';
      formData.email = '';
      formData.password = '';
      formData.password_confirmation = '';
      formData.membership_type = 'free';
      errorMessage.value = '';
      successMessage.value = '';
    };

    const closeModal = () => {
      resetForm();
      emit('close');
    };

    const toggleMode = () => {
      isLoginMode.value = !isLoginMode.value;
      errorMessage.value = '';
      successMessage.value = '';
    };

    const handleSubmit = async () => {
      isLoading.value = true;
      errorMessage.value = '';
      successMessage.value = '';

      try {
        if (isLoginMode.value) {
          // 会員ログインフローに統一
          const res = await apiClient.login({ email: formData.email, password: formData.password });
          if (res && res.success && (res.access_token || res.token || res.data?.access_token)) {
            const token = res.access_token || res.token || res.data?.access_token;
            const user = res.member || res.data?.member || res.user || res.data?.user || null;

            // ローカルストレージ（全ヘッダーと他ページで統一して参照）
            localStorage.setItem('auth_token', token);
            localStorage.setItem('memberToken', token);
            if (user) localStorage.setItem('memberUser', JSON.stringify(user));

            // Vuexへも反映（セミナー詳細などの表示切替に必要）
            try { store.commit('auth/SET_AUTH', { token, user }); } catch (e) { /* store 未登録環境も想定 */ }

            successMessage.value = 'ログイン成功！';
            // 親に正規化して通知（data.user を期待する呼び出し元互換）
            emit('login-success', { user });

            setTimeout(() => { closeModal(); }, 800);
          } else {
            throw new Error(res?.message || res?.error || 'ログインに失敗しました');
          }
        } else {
          // 既存の登録フローは維持（戻り値に合わせてできる限り整合）
          const endpoint = '/api/register';
          const response = await fetch(`${getApiUrl(endpoint)}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: JSON.stringify(formData)
          });
          const data = await response.json();
          if (response.ok && data.success) {
            const token = data.access_token || data.token || null;
            const user = data.member || data.user || null;
            if (token) {
              localStorage.setItem('auth_token', token);
              localStorage.setItem('memberToken', token);
            }
            if (user) localStorage.setItem('memberUser', JSON.stringify(user));
            try { if (token && user) store.commit('auth/SET_AUTH', { token, user }); } catch(e) {}
            successMessage.value = '登録成功！';
            emit('login-success', { user });
            setTimeout(() => { closeModal(); }, 800);
          } else {
            if (data.errors) {
              const firstError = Object.values(data.errors)[0];
              errorMessage.value = Array.isArray(firstError) ? firstError[0] : firstError;
            } else {
              errorMessage.value = data.message || 'エラーが発生しました';
            }
          }
        }
      } catch (error) {
        console.error('Error:', error);
        errorMessage.value = 'ログインに失敗しました。メールアドレスとパスワードを確認してください。';
      } finally {
        isLoading.value = false;
      }
    };

    watch(() => props.isOpen, (newValue) => {
      if (!newValue) {
        resetForm();
      }
    });

    return {
      isLoginMode,
      isLoading,
      errorMessage,
      successMessage,
      formData,
      closeModal,
      toggleMode,
      handleSubmit
    };
  }
};
</script>

<style scoped>
.login-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.login-modal-content {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  padding: 30px;
  position: relative;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.close-btn {
  position: absolute;
  top: 15px;
  right: 15px;
  background: none;
  border: none;
  font-size: 32px;
  cursor: pointer;
  color: #666;
  line-height: 1;
  padding: 0;
  width: 30px;
  height: 30px;
}

.close-btn:hover {
  color: #000;
}

.modal-header {
  text-align: center;
  margin-bottom: 30px;
}

.modal-header h2 {
  color: #1A1A1A;
  font-size: 24px;
  margin: 0;
}

.login-form {
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #1A1A1A;
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  transition: border-color 0.3s;
}

.form-input:focus {
  outline: none;
  border-color: #4CAF50;
}

.submit-btn {
  width: 100%;
  padding: 14px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

.submit-btn:hover:not(:disabled) {
  background-color: #45a049;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-message {
  background-color: #fee;
  color: #c33;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 15px;
  font-size: 14px;
}

.success-message {
  background-color: #efe;
  color: #3c3;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 15px;
  font-size: 14px;
}

.modal-footer {
  text-align: center;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.modal-footer p {
  color: #666;
  margin: 0;
}

.link-btn {
  background: none;
  border: none;
  color: #4CAF50;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
  font-size: inherit;
}

.link-btn:hover {
  color: #45a049;
}

.membership-info {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.membership-info h3 {
  font-size: 18px;
  color: #1A1A1A;
  margin-bottom: 15px;
}

.plans {
  display: grid;
  gap: 15px;
}

.plan {
  padding: 15px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.plan h4 {
  margin: 0 0 10px 0;
  color: #4CAF50;
  font-size: 16px;
}

.plan ul {
  margin: 0;
  padding-left: 20px;
  color: #666;
  font-size: 14px;
}

.plan li {
  margin-bottom: 5px;
}
</style>
