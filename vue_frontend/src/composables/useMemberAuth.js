// 会員認証とアクセス制御のユーティリティ
import mockServer from '../mockServer'
import { 
  getMembershipLabel, 
  getMembershipLevel, 
  canAccess,
  isValidMembershipType 
} from '../utils/membershipTypes'

// グローバルな状態管理（Vue 2互換のシンプルなストア）
const store = {
  state: {
    currentMember: null,
    memberToken: null
  },
  
  setMember(member, token) {
    // 正規化（API/モック間のキー揺れ対策）
    const normalized = normalizeMember(member)
    this.state.currentMember = normalized
    this.state.memberToken = token
    
    if (normalized && token) {
      localStorage.setItem('memberToken', token)
      localStorage.setItem('memberUser', JSON.stringify(normalized))
    }
  },
  
  clearMember() {
    this.state.currentMember = null
    this.state.memberToken = null
    localStorage.removeItem('memberToken')
    localStorage.removeItem('memberUser')
  }
}

// 初期化時にlocalStorageから会員情報を復元
const initializeMember = () => {
  const storedToken = localStorage.getItem('memberToken') || localStorage.getItem('auth_token')
  const storedUser = localStorage.getItem('memberUser')
  
  if (storedToken && storedUser) {
    try {
      store.state.memberToken = storedToken
      store.state.currentMember = normalizeMember(JSON.parse(storedUser))
    } catch (error) {
      console.error('Failed to parse stored user data:', error)
      store.clearMember()
    }
  }
}

// 初期化実行
initializeMember()

export function useMemberAuth() {
  const ensureLoaded = () => {
    if (!store.state.currentMember) {
      const token = localStorage.getItem('memberToken') || localStorage.getItem('auth_token')
      const raw = localStorage.getItem('memberUser')
      if (token && raw) {
        try {
          store.state.memberToken = token
          store.state.currentMember = normalizeMember(JSON.parse(raw))
        } catch (e) {
          console.warn('Failed to refresh member from localStorage:', e)
        }
      }
    }
  }
  // 会員情報の取得
  const getMemberInfo = () => {
    ensureLoaded()
    return store.state.currentMember
  }

  // ログイン状態の確認
  const isLoggedIn = () => {
    ensureLoaded()
    const token = store.state.memberToken || localStorage.getItem('memberToken') || localStorage.getItem('auth_token')
    return !!store.state.currentMember && !!token
  }

  // 会員ランクの取得
  const getMembershipType = () => {
    ensureLoaded()
    const u = store.state.currentMember
    return u?.membership_type || u?.membershipType || 'guest'
  }

  // 会員ランクのラベル取得（ユーティリティを使用）
  const getMembershipLabelLocal = (type) => {
    const memberType = type || getMembershipType()
    return getMembershipLabel(memberType)
  }

  // ログイン処理
  const login = async (email, password) => {
    try {
      const result = await mockServer.memberLogin(email, password)
      if (result.success) {
        store.setMember(result.member, result.token)
        return { success: true }
      }
      return { success: false, error: result.error || 'ログインに失敗しました' }
    } catch (error) {
      console.error('Login error:', error)
      return { success: false, error: 'ログインに失敗しました' }
    }
  }

  // ログアウト処理
  const logout = () => {
    store.clearMember()
  }

  // コンテンツへのアクセス可否確認（ユーティリティを使用）
  const canAccessContent = (requiredType, exact = false) => {
    const currentType = getMembershipType()
    return canAccess(currentType, requiredType, exact)
  }

  // アップグレード処理
  const upgradeMembership = async (newType) => {
    try {
      // バリデーション
      if (!isValidMembershipType(newType)) {
        return { success: false, error: '無効な会員種別です' }
      }
      
      // 実際のAPIコールをここに実装
      // 今はモック実装
      const currentMember = store.state.currentMember
      if (currentMember) {
        const updatedMember = normalizeMember({ ...currentMember, membership_type: newType })
        store.setMember(updatedMember, store.state.memberToken)
        return { success: true }
      }
      return { success: false, error: 'ログインが必要です' }
    } catch (error) {
      console.error('Upgrade error:', error)
      return { success: false, error: 'アップグレードに失敗しました' }
    }
  }

  // 公開API
  return {
    // State getters
    getMemberInfo,
    isLoggedIn,
    getMembershipType,
    getMembershipLabel: getMembershipLabelLocal,
    
    // Actions
    login,
    logout,
    canAccessContent,
    upgradeMembership,
    
    // Direct state access for reactivity in Vue 2 components
    get currentMember() {
      return store.state.currentMember
    },
    get memberToken() {
      return store.state.memberToken
    }
  }
}

// ユーザーオブジェクトの正規化（membership_type と membershipType を揃える）
function normalizeMember(u) {
  if (!u || typeof u !== 'object') return u
  const type = u.membership_type || u.membershipType || null
  if (type) {
    u.membership_type = type
    u.membershipType = type
  }
  return u
}
