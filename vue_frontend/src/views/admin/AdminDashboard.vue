<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-semibold">管理者ダッシュボード</h1>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-700">{{ adminUser?.name }}</span>
            <button
              @click="handleLogout"
              class="text-sm text-red-600 hover:text-red-800"
            >
              ログアウト
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-medium mb-4">ページ管理</h2>
        
        <div v-if="loading" class="text-center py-4">
          読み込み中...
        </div>

        <div v-else-if="error" class="text-red-600">
          {{ error }}
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ページキー
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  タイトル
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  公開状態
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  最終更新
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  操作
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="page in pages" :key="page.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ page.page_key }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ page.title }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                      page.is_published
                        ? 'bg-green-100 text-green-800'
                        : 'bg-gray-100 text-gray-800'
                    ]"
                  >
                    {{ page.is_published ? '公開中' : '非公開' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(page.updated_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <router-link
                    :to="`/admin/pages/${page.page_key}/edit`"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    編集
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium mb-4">新規ページ作成</h2>
        <router-link
          to="/admin/pages/new"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          新規作成
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { getApiUrl } from '@/config/api'

export default {
  name: 'AdminDashboard',
  data() {
    return {
      pages: [],
      loading: false,
      error: '',
      adminUser: null
    }
  },
  async mounted() {
    const token = localStorage.getItem('admin_token')
    const userStr = localStorage.getItem('adminUser')
    
    if (!token) {
      this.$router.push('/admin')
      return
    }

    this.adminUser = userStr ? JSON.parse(userStr) : null
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    
    await this.fetchPages()
  },
  methods: {
    async fetchPages() {
      this.loading = true
      this.error = ''

      try {
        const response = await axios.get(getApiUrl('/api/admin/pages'))
        this.pages = response.data
      } catch (err) {
        this.error = 'ページの取得に失敗しました'
        console.error(err)
      } finally {
        this.loading = false
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('ja-JP') + ' ' + date.toLocaleTimeString('ja-JP')
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