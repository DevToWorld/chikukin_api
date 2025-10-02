// API設定
const API_CONFIG = {
  mock: {
    baseURL: 'http://localhost', // ローカルモックサーバー
  },
  development: {
    //baseURL: 'https://heroic-celebration-production.up.railway.app',
    baseURL: 'http://localhost',
  },
  production: {
    //baseURL: 'https://heroic-celebration-production.up.railway.app',
    baseURL: 'https://apilinkinc.topaz.ne.jp',
  }
}

// 現在の環境を判定
const getCurrentEnvironment = () => {
  // Vercelデプロイ時は本番APIを使用
  if (process.env.NODE_ENV === 'production') {
    return 'production'
  }
  
  // ローカルホストの時はモックサーバーを使用
  if (typeof window !== 'undefined' && window.location.hostname === 'localhost') {
    return 'mock'
  }
  
  return 'development'
}

// 現在の環境に基づいてAPI設定を取得
export const getApiConfig = () => {
  const env = getCurrentEnvironment()
  return API_CONFIG[env]
}

// APIベースURLを取得
export const getApiBaseUrl = () => {
  return getApiConfig().baseURL
}

// 完全なAPIエンドポイントURLを生成
export const getApiUrl = (endpoint) => {
  if (!endpoint) return getApiBaseUrl()
  const lower = String(endpoint).toLowerCase()
  // 絶対URLはそのまま返す（http/https/プロトコル相対）
  if (lower.startsWith('http://') || lower.startsWith('https://') || lower.startsWith('//')) {
    return endpoint
  }
  const baseUrl = getApiBaseUrl()
  const normalizedEndpoint = endpoint.startsWith('/') ? endpoint : `/${endpoint}`
  return `${baseUrl}${normalizedEndpoint}`
}

// デバッグ用：現在の環境とAPI設定を表示
export const debugApiConfig = () => {
  const env = getCurrentEnvironment()
  const config = API_CONFIG[env]
  console.log('🔧 API Config Debug:', {
    environment: env,
    baseURL: config.baseURL,
    hostname: typeof window !== 'undefined' ? window.location.hostname : 'server',
    nodeEnv: process.env.NODE_ENV
  })
  return { environment: env, config }
}

export default {
  getApiConfig,
  getApiBaseUrl,
  getApiUrl,
  debugApiConfig
}
