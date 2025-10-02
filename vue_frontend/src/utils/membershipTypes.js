/**
 * 会員種別の定義とユーティリティ
 * ここに全ての会員種別関連の情報を一元化
 */

// 会員種別の定義（順序も重要：レベル順）
export const MEMBERSHIP_TYPES = [
  {
    id: 'free',
    label: '無料会員',
    level: 1,
    color: '#6c757d',
    bgColor: '#f8f9fa',
    description: '基本的なサービスをご利用いただけます',
    features: [
      '基本的な情報閲覧',
      'セミナーへの参加申込み'
    ]
  },
  {
    id: 'basic',
    label: 'ベーシック会員',
    level: 1, // freeと同等レベル
    color: '#6c757d',
    bgColor: '#f8f9fa',
    description: '基本的なサービスをご利用いただけます',
    features: [
      '基本的な情報閲覧',
      'セミナーへの参加申込み'
    ]
  },
  {
    id: 'standard',
    label: 'スタンダード会員',
    level: 2,
    color: '#388e3c',
    bgColor: '#e8f5e8',
    description: 'より充実したサービスをご利用いただけます',
    features: [
      '無料会員のすべての機能',
      '会員名簿の閲覧',
      '一部資料のダウンロード',
      'セミナーの優先予約'
    ]
  },
  {
    id: 'premium',
    label: 'プレミアム会員',
    level: 3,
    color: '#f57c00',
    bgColor: '#fff3e0',
    description: '全てのサービスをご利用いただけます',
    features: [
      'スタンダード会員のすべての機能',
      '全資料のダウンロード',
      '会員名簿のCSVエクスポート',
      '特別セミナーへの招待',
      '個別相談サービス'
    ]
  }
]

// ID → 定義のマップ
export const MEMBERSHIP_MAP = MEMBERSHIP_TYPES.reduce((map, type) => {
  map[type.id] = type
  return map
}, {})

// レベル → 定義のマップ
export const MEMBERSHIP_LEVELS = MEMBERSHIP_TYPES.reduce((map, type) => {
  if (!map[type.level]) map[type.level] = []
  map[type.level].push(type)
  return map
}, {})

/**
 * 会員種別のラベルを取得
 */
export function getMembershipLabel(typeId) {
  return MEMBERSHIP_MAP[typeId]?.label || 'ゲスト'
}

/**
 * 会員種別のレベルを取得
 */
export function getMembershipLevel(typeId) {
  return MEMBERSHIP_MAP[typeId]?.level || 0
}

/**
 * 会員種別の色情報を取得
 */
export function getMembershipColors(typeId) {
  const type = MEMBERSHIP_MAP[typeId]
  if (!type) return { color: '#6c757d', bgColor: '#f8f9fa' }
  return { color: type.color, bgColor: type.bgColor }
}

/**
 * アクセス権限をチェック
 * @param {string} currentType - 現在の会員種別
 * @param {string} requiredType - 必要な会員種別
 * @param {boolean} exact - 完全一致を要求するか（false の場合はレベル以上）
 */
export function canAccess(currentType, requiredType, exact = false) {
  if (!currentType || currentType === 'guest') return false
  if (!requiredType) return true
  
  const currentLevel = getMembershipLevel(currentType)
  const requiredLevel = getMembershipLevel(requiredType)
  
  if (currentLevel === 0) return false
  
  if (exact) {
    return currentLevel === requiredLevel
  } else {
    return currentLevel >= requiredLevel
  }
}

/**
 * 指定されたレベル以上の会員種別を取得
 */
export function getMembershipTypesAtLeast(minLevel) {
  return MEMBERSHIP_TYPES.filter(type => type.level >= minLevel)
}

/**
 * 会員種別の選択肢を取得（セレクトボックス用）
 */
export function getMembershipOptions(includeAll = true) {
  const options = MEMBERSHIP_TYPES.map(type => ({
    value: type.id,
    label: type.label
  }))
  
  if (includeAll) {
    options.unshift({ value: '', label: '全ての会員種別' })
  }
  
  return options
}

/**
 * 会員種別の説明を取得
 */
export function getMembershipDescription(typeId) {
  return MEMBERSHIP_MAP[typeId]?.description || ''
}

/**
 * 会員種別の機能一覧を取得
 */
export function getMembershipFeatures(typeId) {
  return MEMBERSHIP_MAP[typeId]?.features || []
}

/**
 * アップグレード可能な会員種別を取得
 */
export function getUpgradeOptions(currentType) {
  const currentLevel = getMembershipLevel(currentType)
  return MEMBERSHIP_TYPES.filter(type => type.level > currentLevel)
}

/**
 * バリデーション: 有効な会員種別かチェック
 */
export function isValidMembershipType(typeId) {
  return typeId in MEMBERSHIP_MAP
}

/**
 * CSSクラス名を生成
 */
export function getMembershipCssClass(typeId) {
  return `membership-${typeId}`
}
