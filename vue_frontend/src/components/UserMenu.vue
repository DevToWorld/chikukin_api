<template>
  <div class="user-menu">
    <div v-if="!isLoggedIn" class="auth-buttons">
      <button @click="openLogin" class="login-btn">ログイン</button>
      <button @click="openRegister" class="register-btn">新規登録</button>
    </div>
    
    <div v-else class="user-info">
      <div class="user-dropdown" @click="toggleDropdown">
        <span class="user-name">{{ userName }}</span>
        <span class="membership-badge" :class="membershipClass">
          {{ membershipLabel }}
        </span>
        <span class="dropdown-arrow">▼</span>
      </div>
      
      <div v-if="dropdownOpen" class="dropdown-menu">
        <div class="dropdown-header">
          <p class="user-email">{{ userEmail }}</p>
          <p class="membership-info">{{ membershipLabel }}会員</p>
        </div>
        <div class="dropdown-divider"></div>
        <button @click="viewProfile" class="dropdown-item">
          プロフィール
        </button>
        <button @click="viewMembership" class="dropdown-item">
          会員プラン
        </button>
        <div class="dropdown-divider"></div>
        <button @click="logout" class="dropdown-item logout">
          ログアウト
        </button>
      </div>
    </div>

    <LoginModal 
      :is-open="loginModalOpen" 
      @close="loginModalOpen = false"
      @login-success="handleLoginSuccess"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import LoginModal from './LoginModal.vue';
import { getApiUrl } from '@/config/api';

export default {
  name: 'UserMenu',
  components: {
    LoginModal
  },
  setup() {
    const isLoggedIn = ref(false);
    const user = ref(null);
    const dropdownOpen = ref(false);
    const loginModalOpen = ref(false);
    const registerMode = ref(false);

    const userName = computed(() => user.value?.name || '');
    const userEmail = computed(() => user.value?.email || '');
    const membershipType = computed(() => user.value?.membership_type || 'free');
    
    const membershipLabel = computed(() => {
      switch (membershipType.value) {
        case 'premium':
          return 'プレミアム';
        case 'standard':
          return 'スタンダード';
        default:
          return '無料';
      }
    });

    const membershipClass = computed(() => {
      return `membership-${membershipType.value}`;
    });

    const checkAuth = () => {
      const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken');
      const userData = localStorage.getItem('memberUser') || localStorage.getItem('user');
      if (token && userData) {
        try {
          user.value = JSON.parse(userData);
          isLoggedIn.value = true;
        } catch (error) {
          console.error('Failed to parse user data:', error);
          logout();
        }
      }
    };

    const openLogin = () => {
      registerMode.value = false;
      loginModalOpen.value = true;
    };

    const openRegister = () => {
      registerMode.value = true;
      loginModalOpen.value = true;
    };

    const toggleDropdown = () => {
      dropdownOpen.value = !dropdownOpen.value;
    };

    const closeDropdown = (event) => {
      if (!event.target.closest('.user-info')) {
        dropdownOpen.value = false;
      }
    };

    const handleLoginSuccess = (data) => {
      user.value = data.user;
      isLoggedIn.value = true;
      loginModalOpen.value = false;
      dropdownOpen.value = false;
    };

    const viewProfile = () => {
      dropdownOpen.value = false;
      console.log('View profile');
    };

    const viewMembership = () => {
      dropdownOpen.value = false;
      console.log('View membership');
    };

    const logout = async () => {
      const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken');
      // API側のログアウトを両方叩く（失敗しても続行）
      try { await fetch(getApiUrl('/api/logout'), { method: 'POST', headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } }) } catch(e) {}
      try { await fetch(getApiUrl('/api/member-auth/logout'), { method: 'POST', headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } }) } catch(e) {}
      // ローカルキャッシュを完全削除
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      localStorage.removeItem('memberToken');
      localStorage.removeItem('memberUser');
      user.value = null;
      isLoggedIn.value = false;
      dropdownOpen.value = false;
    };

    onMounted(() => {
      checkAuth();
      document.addEventListener('click', closeDropdown);
    });

    onUnmounted(() => {
      document.removeEventListener('click', closeDropdown);
    });

    return {
      isLoggedIn,
      user,
      dropdownOpen,
      loginModalOpen,
      userName,
      userEmail,
      membershipType,
      membershipLabel,
      membershipClass,
      openLogin,
      openRegister,
      toggleDropdown,
      handleLoginSuccess,
      viewProfile,
      viewMembership,
      logout
    };
  }
};
</script>

<style scoped>
.user-menu {
  position: relative;
}

.auth-buttons {
  display: flex;
  gap: 10px;
}

.login-btn, .register-btn {
  padding: 8px 20px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s;
}

.login-btn {
  background-color: transparent;
  color: #1A1A1A;
  border: 1px solid #ddd;
}

.login-btn:hover {
  opacity: 0.8;
}

.register-btn {
  background-color: #4CAF50;
  color: white;
}

.register-btn:hover {
  background-color: #45a049;
}

.user-info {
  position: relative;
}

.user-dropdown {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 15px;
  background-color: #f9f9f9;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.user-dropdown:hover {
  background-color: #f0f0f0;
}

.user-name {
  font-weight: 500;
  color: #1A1A1A;
}

.membership-badge {
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
}

.membership-free {
  background-color: #e0e0e0;
  color: #666;
}

.membership-standard {
  background-color: #2196F3;
  color: white;
}

.membership-premium {
  background-color: #FFD700;
  color: #1A1A1A;
}

.dropdown-arrow {
  font-size: 10px;
  color: #666;
  margin-left: 5px;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 10px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  min-width: 200px;
  z-index: 1000;
  overflow: hidden;
}

.dropdown-header {
  padding: 15px;
  background-color: #f9f9f9;
}

.user-email {
  margin: 0 0 5px 0;
  color: #666;
  font-size: 14px;
}

.membership-info {
  margin: 0;
  color: #1A1A1A;
  font-size: 12px;
  font-weight: bold;
}

.dropdown-divider {
  height: 1px;
  background-color: #eee;
  margin: 0;
}

.dropdown-item {
  display: block;
  width: 100%;
  padding: 12px 15px;
  text-align: left;
  background: none;
  border: none;
  color: #1A1A1A;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.dropdown-item:hover {
  background-color: #f5f5f5;
}

.dropdown-item.logout {
  color: #f44336;
}

.dropdown-item.logout:hover {
  background-color: #fee;
}
</style>
