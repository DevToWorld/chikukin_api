// APIクライアントとmockServerのフォールバック機能を提供
import apiClient from './apiClient'
import mockServer from '../mockServer'

class ApiWithFallback {
  constructor() {
    this.useFallback = false
    this.testConnection()
  }

  // API接続テスト
  async testConnection() {
    try {
      // ヘルスチェックエンドポイントを試す
      const response = await fetch(apiClient.baseURL + '/api/health', {
        method: 'GET',
        signal: AbortSignal.timeout(3000) // 3秒タイムアウト
      })
      
      this.useFallback = !response.ok
    } catch (error) {
      console.warn('API connection failed, using mockServer fallback:', error.message)
      this.useFallback = true
    }
  }

  // セミナー関連
  async getSeminars(params = {}) {
    if (this.useFallback) {
      return mockServer.getSeminars(params)
    }
    try {
      return await apiClient.getSeminars(params)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getSeminars(params)
    }
  }

  async getSeminar(id) {
    if (this.useFallback) {
      return mockServer.getSeminar(id)
    }
    try {
      return await apiClient.getSeminar(id)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getSeminar(id)
    }
  }

  // 刊行物関連
  async getPublications(params = {}) {
    if (this.useFallback) {
      return mockServer.getPublications(params)
    }
    try {
      return await apiClient.getPublications(params)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getPublications(params)
    }
  }

  async getPublication(id) {
    if (this.useFallback) {
      return mockServer.getPublication(id)
    }
    try {
      return await apiClient.getPublication(id)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getPublication(id)
    }
  }

  // お知らせ関連
  async getNews(params = {}) {
    if (this.useFallback) {
      return mockServer.getNews(params)
    }
    try {
      return await apiClient.getNews(params)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getNews(params)
    }
  }

  async getNewsItem(id) {
    if (this.useFallback) {
      return mockServer.getNewsDetail(id)
    }
    try {
      return await apiClient.getNewsItem(id)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getNewsDetail(id)
    }
  }

  // 会員認証関連
  async memberLogin(email, password) {
    if (this.useFallback) {
      return mockServer.memberLogin(email, password)
    }
    try {
      const result = await apiClient.login({ email, password })
      // APIレスポンスをmockServer形式に変換
      if (result.token && result.user) {
        return {
          success: true,
          token: result.token,
          member: {
            id: result.user.id,
            name: result.user.name,
            email: result.user.email,
            company: result.user.company,
            membershipType: result.user.membership_type || 'basic'
          }
        }
      }
      return { success: false, error: 'ログインに失敗しました' }
    } catch (error) {
      console.warn('API login failed, falling back to mockServer:', error)
      return mockServer.memberLogin(email, password)
    }
  }

  async getMemberInfo(token) {
    if (this.useFallback) {
      return mockServer.getCurrentMember(token)
    }
    try {
      // 実際のAPI実装時はここでトークンを使用してユーザー情報を取得
      return mockServer.getCurrentMember(token)
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.getCurrentMember(token)
    }
  }

  // 管理者認証関連
  async adminLogin(username, password) {
    if (this.useFallback) {
      return mockServer.adminLogin(username, password)
    }
    try {
      const result = await apiClient.adminLogin({ username, password })
      if (result.token) {
        return {
          success: true,
          token: result.token,
          user: result.user
        }
      }
      return { success: false, error: 'ログインに失敗しました' }
    } catch (error) {
      console.warn('Admin API login failed, falling back to mockServer:', error)
      return mockServer.adminLogin(username, password)
    }
  }

  // お問い合わせ送信
  async submitInquiry(inquiryData) {
    if (this.useFallback) {
      return mockServer.submitInquiry(inquiryData)
    }
    try {
      await apiClient.submitInquiry(inquiryData)
      return { success: true }
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.submitInquiry(inquiryData)
    }
  }

  // セミナー申込み
  async registerForSeminar(seminarId, registrationData) {
    if (this.useFallback) {
      return mockServer.registerForSeminar(seminarId, registrationData)
    }
    try {
      await apiClient.registerForSeminar(seminarId, registrationData)
      return { success: true }
    } catch (error) {
      console.warn('API call failed, falling back to mockServer:', error)
      return mockServer.registerForSeminar(seminarId, registrationData)
    }
  }

  // API接続状態を取得
  isUsingFallback() {
    return this.useFallback
  }

  // API接続を再テスト
  async retryConnection() {
    await this.testConnection()
    return !this.useFallback
  }
}

// シングルトンインスタンスをエクスポート
export default new ApiWithFallback()