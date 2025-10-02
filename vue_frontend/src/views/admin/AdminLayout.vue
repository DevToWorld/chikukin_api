<template>
  <div class="admin-layout">
    <!-- ヘッダー -->
    <header class="header">
      <div class="header-content">
        <div class="logo-section">
          <router-link to="/" class="logo-link">
            <img src="/img/ico-logo-1-1.svg" alt="ちくぎん地域経済研究所" class="logo">
            <span class="company-name">株式会社ちくぎん地域経済研究所</span>
          </router-link>
          <span class="company-url">https://www.chikugin-ri.co.jp/</span>
        </div>
        <button @click="handleLogout" class="logout-btn">ログアウト</button>
      </div>
    </header>

    <div class="main-container">
      <!-- サイドバーナビゲーション -->
      <nav class="sidebar">
        <ul class="nav-menu">
          <li>
            <router-link to="/admin/member-list" class="nav-item" :class="{ active: isActive('/admin/member-list') }">
              会員管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/seminar" class="nav-item" :class="{ active: isActive('/admin/seminar') }">
              セミナー管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/publication" class="nav-item" :class="{ active: isActive('/admin/publication') }">
              刊行物管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/economic-reports" class="nav-item" :class="{ active: isActive('/admin/economic-reports') }">
              経済統計レポート管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/economic-indicators" class="nav-item" :class="{ active: isActive('/admin/economic-indicators') }">
              経済指標管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/economic-indicator-categories" class="nav-item" :class="{ active: isActive('/admin/economic-indicator-categories') }">
              経済指標カテゴリ管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/news" class="nav-item" :class="{ active: isActive('/admin/news') }">
              お知らせ管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/inquiries" class="nav-item" :class="{ active: isActive('/admin/inquiries') }">
              お問い合わせ管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/media" class="nav-item" :class="{ active: isActive('/admin/media') }">
              メディア管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/media-library" class="nav-item" :class="{ active: isActive('/admin/media-library') }">
              メディアライブラリ
            </router-link>
          </li>
          <li v-if="isSuperAdmin">
            <router-link to="/admin/admin-users" class="nav-item" :class="{ active: isActive('/admin/admin-users') }">
              管理者管理
            </router-link>
          </li>
          <li>
            <router-link v-if="false" to="/admin/pages" class="nav-item" :class="{ active: isActive('/admin/pages') }">
              各ページ管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/cms-v2" class="nav-item" :class="{ active: isActive('/admin/cms-v2') }">
              各ページ管理
            </router-link>
          </li>
          <li>
            <router-link to="/admin/mailmagazine" class="nav-item" :class="{ active: isActive('/admin/mailmagazine') }">
              一括メール管理
            </router-link>
          </li>
        </ul>
      </nav>

      <!-- メインコンテンツエリア -->
      <main class="content">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'AdminLayout',
  data() {
    return {
      adminUser: null,
    }
  },
  created() {
    try { this.adminUser = JSON.parse(localStorage.getItem('adminUser') || 'null') } catch { this.adminUser = null }
  },
  methods: {
    isActive(path) {
      return this.$route.path === path || this.$route.path.startsWith(path + '/')
    },
    handleLogout() {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('adminUser')
      delete axios.defaults.headers.common['Authorization']
      this.$router.push('/admin')
    }
  },
  computed: {
    isSuperAdmin() {
      const role = this.adminUser?.role || null
      return role === 'super_admin'
    }
  }
}
</script>

<style scoped>
.admin-layout {
  min-height: 100vh;
  background-color: #f5f5f5;
}

.header {
  background-color: white;
  border-bottom: 1px solid #e5e5e5;
  padding: 12px 24px;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1400px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 12px;
    align-items: flex-start;
  }
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 12px;
}

.logo-link {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  transition: opacity 0.2s;
}

.logo-link:hover {
  opacity: 0.8;
}

.logo {
  height: 32px;
  width: auto;
}

.company-name {
  font-size: 16px;
  font-weight: 600;
  color: #1A1A1A;
}

.company-url {
  font-size: 12px;
  color: #666;
}

.logout-btn {
  background-color: #1A1A1AA1A;
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

.main-container {
  display: flex;
  max-width: 1400px;
  margin: 0 auto;
  min-height: calc(100vh - 65px);
}

@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }
}

.sidebar {
  width: 200px;
  background-color: white;
  border-right: 1px solid #e5e5e5;
  padding: 20px 0;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #e5e5e5;
  }
}

.nav-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-menu li {
  margin-bottom: 4px;
}

.nav-item {
  display: block;
  padding: 12px 24px;
  color: #1A1A1AA1A;
  text-decoration: none;
  font-size: 14px;
  transition: background-color 0.2s;
}

.nav-item:hover {
  background-color: #f8f8f8;
  color: #1A1A1AA1A;
  text-decoration: none;
}

.nav-item.active {
  background-color: #ff6b9d;
  color: white;
}

.nav-item.active:hover {
  background-color: #ff5a8c;
  color: white;
}

.content {
  flex: 1;
  padding: 20px;
  background-color: #f5f5f5;
}
</style>

<style>
/* Admin global button styling (unscoped): unify to pink or black */
:root {
  --admin-pink: #da5761;
  --admin-pink-hover: #c44853;
  --admin-black: #1A1A1A;
  --admin-black-hover: #555555;
}

/* Primary (pink) */
.btn.btn-primary,
.add-btn,
.save-btn,
.modal .btn-save,
.pagination .pagination-btn.active {
  background-color: var(--admin-pink) !important;
  color: #fff !important;
  border: none !important;
}
.btn.btn-primary:hover,
.add-btn:hover,
.save-btn:hover,
.modal .btn-save:hover,
.pagination .pagination-btn.active:hover {
  background-color: var(--admin-pink-hover) !important;
}

/* Secondary/utility (black) */
.btn.btn-secondary,
.edit-btn,
.delete-btn,
.page-btn,
.pagination-btn,
.btn-cancel,
.logout-btn,
.link-btn {
  background-color: var(--admin-black) !important;
  color: #fff !important;
  border: none !important;
}
.btn.btn-secondary:hover,
.edit-btn:hover,
.delete-btn:hover,
.page-btn:hover,
.pagination-btn:hover,
.btn-cancel:hover,
.logout-btn:hover,
.link-btn:hover {
  background-color: var(--admin-black-hover) !important;
}

/* Neutral buttons that were outlines keep shape but adopt colors */
.btn,
.add-btn,
.save-btn,
.edit-btn,
.delete-btn,
.page-btn,
.pagination-btn,
.btn-cancel,
.link-btn {
  border-radius: 6px;
  padding: 8px 12px;
  cursor: pointer;
  transition: background-color 0.2s ease, opacity 0.2s ease;
}

/* Disabled states */
.btn[disabled],
button:disabled,
.pagination-btn:disabled {
  opacity: 0.6 !important;
  cursor: not-allowed !important;
}
</style>
