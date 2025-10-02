export function getMemberInfo() {
  try {
    const raw = localStorage.getItem('memberUser')
    return raw ? JSON.parse(raw) : null
  } catch (e) { return null }
}

export function hasPaidMembership() {
  const u = getMemberInfo()
  const t = u?.membership_type || null
  return t === 'standard' || t === 'premium'
}

export function shouldBlurPublicContent() {
  // 非会員/無料はぼかし、標準/プレミアムはクリア
  return !hasPaidMembership()
}

