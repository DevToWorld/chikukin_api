import store from '@/store'

const getMembershipText = (level) => {
  switch (level) {
    case 'standard':
      return 'スタンダード'
    case 'premium':
      return 'プレミアム'
    default:
      return '会員'
  }
}

const createOverlay = (requiredLevel) => {
  const overlay = document.createElement('div')
  overlay.className = 'restriction-overlay-inline'
  overlay.innerHTML = `
    <span class="lock-icon">🔒</span>
    <span class="restriction-text">${getMembershipText(requiredLevel)}会員限定</span>
  `
  return overlay
}

const applyRestriction = (el, binding) => {
  const { requiredLevel, showOverlay = true, blurLevel = 4 } = binding.value || {}
  
  if (!requiredLevel) return
  
  const canView = store.getters['auth/canViewButRestricted'](requiredLevel)
  
  if (canView) {
    el.style.position = 'relative'
    el.style.filter = `blur(${blurLevel}px)`
    el.style.userSelect = 'none'
    el.style.pointerEvents = 'none'
    el.classList.add('restricted-content')
    
    if (showOverlay && !el.querySelector('.restriction-overlay-inline')) {
      const overlay = createOverlay(requiredLevel)
      el.appendChild(overlay)
    }
  } else {
    const canAccess = store.getters['auth/canAccess'](requiredLevel)
    
    if (canAccess) {
      el.style.filter = ''
      el.style.userSelect = ''
      el.style.pointerEvents = ''
      el.classList.remove('restricted-content')
      
      const overlay = el.querySelector('.restriction-overlay-inline')
      if (overlay) {
        overlay.remove()
      }
    }
  }
}

export default {
  bind(el, binding) {
    applyRestriction(el, binding)
    
    el._restrictionWatcher = store.watch(
      (state, getters) => getters['auth/membershipLevel'],
      () => applyRestriction(el, binding)
    )
  },
  
  update(el, binding) {
    applyRestriction(el, binding)
  },
  
  unbind(el) {
    if (el._restrictionWatcher) {
      el._restrictionWatcher()
      delete el._restrictionWatcher
    }
    
    el.style.filter = ''
    el.style.userSelect = ''
    el.style.pointerEvents = ''
    el.classList.remove('restricted-content')
    
    const overlay = el.querySelector('.restriction-overlay-inline')
    if (overlay) {
      overlay.remove()
    }
  }
}