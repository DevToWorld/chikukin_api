import apiClient from '@/services/apiClient'

const MEMBERSHIP_LEVELS = {
  FREE: 'free',
  STANDARD: 'standard',
  PREMIUM: 'premium'
}

const MEMBERSHIP_HIERARCHY = {
  [MEMBERSHIP_LEVELS.FREE]: 0,
  [MEMBERSHIP_LEVELS.STANDARD]: 1,
  [MEMBERSHIP_LEVELS.PREMIUM]: 2
}

const state = {
  isLoggedIn: false,
  user: null,
  member: null,
  token: localStorage.getItem('auth_token') || null,
  membershipType: null,
  membershipExpiresAt: null
}

const getters = {
  isAuthenticated: state => state.isLoggedIn,
  
  currentUser: state => state.user || state.member,
  
  membershipLevel: state => {
    if (!state.isLoggedIn) return null
    return state.membershipType || MEMBERSHIP_LEVELS.FREE
  },
  
  membershipLevelValue: (state, getters) => {
    const level = getters.membershipLevel
    return level ? MEMBERSHIP_HIERARCHY[level] : -1
  },
  
  canAccess: (state, getters) => (requiredLevel) => {
    if (!requiredLevel || requiredLevel === MEMBERSHIP_LEVELS.FREE) {
      return true
    }
    
    if (!state.isLoggedIn) {
      return false
    }
    
    const userLevelValue = getters.membershipLevelValue
    const requiredLevelValue = MEMBERSHIP_HIERARCHY[requiredLevel]
    
    return userLevelValue >= requiredLevelValue
  },
  
  canViewButRestricted: (state, getters) => (requiredLevel) => {
    if (!requiredLevel || requiredLevel === MEMBERSHIP_LEVELS.FREE) {
      return false
    }
    
    if (!state.isLoggedIn) {
      return true
    }
    
    const userLevelValue = getters.membershipLevelValue
    const requiredLevelValue = MEMBERSHIP_HIERARCHY[requiredLevel]
    
    return userLevelValue < requiredLevelValue
  },
  
  isStandardOrHigher: (state, getters) => {
    return getters.membershipLevelValue >= MEMBERSHIP_HIERARCHY[MEMBERSHIP_LEVELS.STANDARD]
  },
  
  isPremium: (state, getters) => {
    return getters.membershipLevelValue === MEMBERSHIP_HIERARCHY[MEMBERSHIP_LEVELS.PREMIUM]
  },
  
  membershipExpired: state => {
    if (!state.membershipExpiresAt) return false
    return new Date(state.membershipExpiresAt) < new Date()
  }
}

const mutations = {
  SET_AUTH(state, { token, user }) {
    state.token = token
    state.isLoggedIn = true
    
    if (user) {
      if (user.membership_type) {
        state.user = user
        state.membershipType = user.membership_type
        state.membershipExpiresAt = user.membership_expires_at
      } else if (user.company_name) {
        state.member = user
        state.membershipType = user.membership_type || MEMBERSHIP_LEVELS.FREE
        state.membershipExpiresAt = user.expiry_date
      }
    }
    
    if (token) {
      localStorage.setItem('auth_token', token)
    }
  },
  
  SET_USER(state, user) {
    if (user) {
      if (user.membership_type && !user.company_name) {
        state.user = user
        state.membershipType = user.membership_type
        state.membershipExpiresAt = user.membership_expires_at
      } else if (user.company_name) {
        state.member = user
        state.membershipType = user.membership_type || MEMBERSHIP_LEVELS.FREE
        state.membershipExpiresAt = user.expiry_date
      }
      state.isLoggedIn = true
    }
  },
  
  SET_MEMBERSHIP(state, { type, expiresAt }) {
    state.membershipType = type
    state.membershipExpiresAt = expiresAt
  },
  
  LOGOUT(state) {
    state.token = null
    state.isLoggedIn = false
    state.user = null
    state.member = null
    state.membershipType = null
    state.membershipExpiresAt = null
    localStorage.removeItem('auth_token')
  }
}

const actions = {
  async login({ commit }, credentials) {
    try {
      const response = await apiClient.login(credentials)
      
      if (response.success) {
        const { token, user, member } = response.data
        commit('SET_AUTH', { 
          token, 
          user: user || member 
        })
        return { success: true }
      }
      
      return { 
        success: false, 
        error: response.error || 'ログインに失敗しました' 
      }
    } catch (error) {
      console.error('Login error:', error)
      return { 
        success: false, 
        error: 'ログインに失敗しました' 
      }
    }
  },
  
  async fetchUser({ commit, state }) {
    if (!state.token) return { success: false }
    
    try {
      const response = await apiClient.getUser()
      
      if (response.success) {
        commit('SET_USER', response.data)
        return { success: true }
      }
      
      return { success: false }
    } catch (error) {
      console.error('Fetch user error:', error)
      return { success: false }
    }
  },
  
  async checkAuth({ commit, state }) {
    const token = state.token || localStorage.getItem('auth_token')
    
    if (!token) {
      commit('LOGOUT')
      return false
    }
    
    try {
      const response = await apiClient.getUser()
      
      if (response.success) {
        commit('SET_AUTH', { 
          token, 
          user: response.data 
        })
        return true
      }
      
      commit('LOGOUT')
      return false
    } catch (error) {
      console.error('Auth check error:', error)
      commit('LOGOUT')
      return false
    }
  },
  
  logout({ commit }) {
    apiClient.logout()
    commit('LOGOUT')
  },
  
  updateMembership({ commit }, membershipData) {
    commit('SET_MEMBERSHIP', membershipData)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}