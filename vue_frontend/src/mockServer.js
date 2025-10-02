// Mock API Server for testing CMS functionality
const mockData = {
  seminars: [
    {
      id: 1,
      title: '採用力強化！経営・人事向け　面接官トレーニングセミナー',
      description: '優秀な人材を見極め、獲得するための面接技術を習得できるセミナーを開催します。',
      date: '2025-06-15',
      start_time: '14:00',
      end_time: '17:00',
      location: 'ちくぎん地域経済研究所 会議室A',
      capacity: 30,
      fee: 5000,
      status: 'ongoing',
      featured_image: '/img/image-1.png' // デフォルト画像
    },
    {
      id: 2,
      title: 'DX推進セミナー',
      description: 'デジタルトランスフォーメーションの基礎から実践まで学べるセミナーです。',
      date: '2025-06-20',
      start_time: '13:00',
      end_time: '16:00',
      location: 'オンライン開催',
      capacity: 100,
      fee: 3000,
      status: 'scheduled',
      featured_image: '/img/image-1.png' // デフォルト画像
    }
  ],
  publications: [
    {
      id: 1,
      title: '事業継承から描く九州の未来',
      description: '最新の地域経済動向レポート。九州地域の製造業の動向と今後の展望について特集。',
      publication_date: '2025-04-28',
      category: 'research',
      type: 'pdf',
      author: 'ちくぎん地域経済研究所',
      pages: 45,
      file_url: '/files/kyushu-future.pdf',
      image_url: '', // 空にしてデフォルト画像を使用
      file_size: 2.1,
      download_count: 234,
      is_published: true,
      membershipLevel: 'premium' // プレミアム会員限定
    },
    {
      id: 2,
      title: '経営参考BOOK vol.52',
      description: '事業承継をテーマに、成功事例と具体的な進め方を解説。',
      publication_date: '2025-04-28',
      category: 'quarterly',
      type: 'pdf',
      author: 'ちくぎん地域経済研究所',
      pages: 32,
      file_url: '/files/management-book-52.pdf',
      image_url: '', // 空にしてデフォルト画像を使用
      file_size: 1.8,
      download_count: 156,
      is_published: true,
      membershipLevel: 'standard' // スタンダード会員以上
    },
    {
      id: 3,
      title: 'Hot Information Vol.323',
      description: '2025年度の経済展望と地域企業が取り組むべき課題について特集。',
      publication_date: '2025-04-28',
      category: 'special',
      type: 'pdf',
      author: 'ちくぎん地域経済研究所',
      pages: 28,
      file_url: '/files/hot-info-323.pdf',
      image_url: '', // 空にしてデフォルト画像を使用
      file_size: 1.5,
      download_count: 89,
      is_published: true,
      membershipLevel: 'basic' // ベーシック会員以上
    },
    {
      id: 4,
      title: 'Hot Information Vol.324',
      description: 'DX推進のポイントや、新たな成長戦略について詳しく解説。',
      publication_date: '2025-05-01',
      category: 'special',
      type: 'pdf',
      author: 'ちくぎん地域経済研究所',
      pages: 35,
      file_url: '/files/hot-info-324.pdf',
      image_url: '/img/-----2-2-4.png', // この1つだけCMS画像設定例として残す
      file_size: 2.0,
      download_count: 45,
      is_published: true,
      membershipLevel: 'free' // 無料公開
    }
  ],
  notices: [
    {
      id: 1,
      title: '2025年度経済展望セミナー開催のお知らせ',
      content: '来る3月15日に、2025年度の九州地域経済展望についてのセミナーを開催いたします。詳細は後日お知らせいたします。',
      excerpt: '3月15日にセミナーを開催',
      category: 'notice',
      slug: 'seminar-2025-economic-outlook',
      is_important: true,
      is_published: true,
      published_at: '2025-01-15 10:00:00',
      created_at: '2025-01-15 10:00:00',
      updated_at: '2025-01-15 10:00:00',
      date: '2025-01-15',
      isImportant: true
    },
    {
      id: 2,
      title: 'GW休業のお知らせ',
      content: '5月3日から5月6日まで休業いたします。ご不便をおかけしますが、よろしくお願いいたします。',
      excerpt: 'GW期間中の休業について',
      category: 'important',
      slug: 'gw-holiday-notice',
      is_important: true,
      is_published: true,
      published_at: '2025-01-10 09:00:00',
      created_at: '2025-01-10 09:00:00',
      updated_at: '2025-01-10 09:00:00',
      date: '2025-01-10',
      isImportant: true
    },
    {
      id: 3,
      title: 'ホームページリニューアルのお知らせ',
      content: 'より使いやすいホームページにリニューアルしました。新機能も追加されています。',
      excerpt: 'ホームページをリニューアル',
      category: 'notice',
      slug: 'website-renewal',
      is_important: false,
      is_published: true,
      published_at: '2025-01-08 14:00:00',
      created_at: '2025-01-08 14:00:00',
      updated_at: '2025-01-08 14:00:00',
      date: '2025-01-08',
      isImportant: false
    },
    {
      id: 4,
      title: '新年のご挨拶',
      content: '明けましておめでとうございます。本年もどうぞよろしくお願いいたします。',
      excerpt: '新年のご挨拶',
      category: 'notice',
      slug: 'new-year-greeting-2025',
      is_important: false,
      is_published: true,
      published_at: '2025-01-01 00:00:00',
      created_at: '2025-01-01 00:00:00',
      updated_at: '2025-01-01 00:00:00',
      date: '2025-01-01',
      isImportant: false
    },
    {
      id: 5,
      title: 'オンラインセミナー配信開始',
      content: 'オンラインでのセミナー配信サービスを開始しました。遠方の方もぜひご参加ください。',
      excerpt: 'オンラインセミナー開始',
      category: 'event',
      slug: 'online-seminar-start',
      is_important: false,
      is_published: true,
      published_at: '2024-12-15 11:00:00',
      created_at: '2024-12-15 11:00:00',
      updated_at: '2024-12-15 11:00:00',
      date: '2024-12-15',
      isImportant: false
    }
  ],
  media: [
    {
      id: 1,
      title: '地域経済レポート2025',
      url: '/media/report-2025.jpg',
      type: 'image',
      uploadedDate: '2025-05-01'
    },
    {
      id: 2,
      title: 'セミナー資料',
      url: '/media/seminar-doc.pdf',
      type: 'document',
      uploadedDate: '2025-04-28'
    }
  ],
  members: [
    {
      id: 1,
      name: '山田太郎',
      email: 'yamada@example.com',
      company: '株式会社サンプル',
      membershipType: 'premium',
      joinedDate: '2024-01-15',
      status: 'active'
    },
    {
      id: 2,
      name: '鈴木花子',
      email: 'suzuki@example.com',
      company: '有限会社テスト',
      membershipType: 'standard',
      joinedDate: '2024-03-20',
      status: 'active'
    }
  ],
  pages: {
    // 基本ページ
    home: {
      title: 'トップページ',
      content: 'ちくぎん地域経済研究所のメインページ',
      lastUpdated: '2025-05-01',
      images: [
        { id: 1, url: '/img/hero-image.png', alt: 'ヒーロー画像', description: 'トップページのメイン画像' },
        { id: 2, url: '/img/image-1.png', alt: 'サービス画像1', description: 'サービス紹介用画像' },
        { id: 3, url: '/img/image-2.png', alt: 'サービス画像2', description: 'サービス紹介用画像' }
      ]
    },
    about: {
      title: 'ちくぎん地域経済研究所について',
      content: '地域経済の発展に貢献する研究機関です。',
      lastUpdated: '2025-05-01',
      images: [
        { id: 1, url: '/img/-----2-2.png', alt: '会社概要画像', description: '会社概要ページのメイン画像' },
        { id: 2, url: '/img/-----2-3.png', alt: '企業理念画像', description: '企業理念を表す画像' }
      ]
    },
    services: {
      title: 'サービス一覧',
      content: '当研究所が提供するサービスの一覧です。',
      lastUpdated: '2025-04-20',
      images: [
        { id: 1, url: '/img/-----2-4.png', alt: 'サービス概要画像', description: 'サービス概要のメイン画像' },
        { id: 2, url: '/img/---1.png', alt: 'コンサルティング画像', description: 'コンサルティングサービスの画像' },
        { id: 3, url: '/img/---2.png', alt: 'セミナー画像', description: 'セミナーサービスの画像' }
      ]
    },
    contact: {
      title: 'お問い合わせ',
      content: 'お問い合わせフォームや連絡先情報を掲載しています。',
      lastUpdated: '2025-04-15',
      images: [
        { id: 1, url: '/img/---3.png', alt: 'お問い合わせ画像', description: 'お問い合わせページのメイン画像' }
      ]
    },
    faq: {
      title: 'よくある質問',
      content: 'よくいただくご質問とその回答をまとめています。',
      lastUpdated: '2025-04-10',
      images: []
    },
    privacy: {
      title: 'プライバシーポリシー',
      content: '個人情報の取り扱いについて説明しています。',
      lastUpdated: '2025-03-25',
      images: []
    },
    terms: {
      title: '利用規約',
      content: '当サイトをご利用いただく際の規約です。',
      lastUpdated: '2025-03-25',
      images: []
    },
    // お知らせ・お知らせ関連
    news: {
      title: 'お知らせ',
      content: 'お知らせ一覧ページ',
      lastUpdated: '2025-04-28'
    },
    newsDetail: {
      title: 'お知らせ詳細',
      content: 'お知らせ記事の詳細ページ',
      lastUpdated: '2025-04-28'
    },
    // セミナー関連
    seminars: {
      title: 'セミナー',
      content: 'セミナー情報ページ',
      lastUpdated: '2025-04-28'
    },
    seminarRegistration: {
      title: 'セミナー申込',
      content: 'セミナー申込フォーム',
      lastUpdated: '2025-04-28'
    },
    // 刊行物関連
    publications: {
      title: '刊行物',
      content: '刊行物一覧ページ',
      lastUpdated: '2025-04-28'
    },
    publicationDetail: {
      title: '刊行物詳細',
      content: '刊行物詳細ページ',
      lastUpdated: '2025-04-28'
    },
    // 会員関連
    memberLogin: {
      title: '会員ログイン',
      content: '会員ログインページ',
      lastUpdated: '2025-04-28'
    },
    memberRegister: {
      title: '会員登録',
      content: '会員登録ページ',
      lastUpdated: '2025-04-28'
    },
    myAccount: {
      title: 'マイアカウント',
      content: '会員マイページ',
      lastUpdated: '2025-04-28'
    },
    upgrade: {
      title: 'プランアップグレード',
      content: '会員プランアップグレードページ',
      lastUpdated: '2025-04-28'
    },
    // その他のページ
    faq: {
      title: 'よくある質問',
      content: 'よくある質問と回答',
      lastUpdated: '2025-04-28'
    },
    apply: {
      title: '申込みフォーム',
      content: '各種申込みフォーム',
      lastUpdated: '2025-04-28'
    },
    contact: {
      title: 'お問い合わせ',
      content: 'お問い合わせフォーム',
      lastUpdated: '2025-04-28'
    },
    glossary: {
      title: '用語集',
      content: '経済用語集',
      lastUpdated: '2025-04-28'
    },
    privacy: {
      title: 'プライバシーポリシー',
      content: 'プライバシーポリシーページ',
      lastUpdated: '2025-04-28'
    },
    terms: {
      title: '利用規約',
      content: '利用規約ページ',
      lastUpdated: '2025-04-28'
    },
    // 管理画面関連
    adminLogin: {
      title: '管理者ログイン',
      content: '管理者ログインページ',
      lastUpdated: '2025-04-28'
    },
    adminDashboard: {
      title: '管理ダッシュボード',
      content: '管理画面のダッシュボード',
      lastUpdated: '2025-04-28'
    },
    // テスト・開発用
    test: {
      title: 'テストページ',
      content: 'Vue.js動作確認用テストページ',
      lastUpdated: '2025-04-28'
    }
  }
}

// localStorage を使用してデータを永続化
const STORAGE_KEY = 'cms_mock_data'

class MockAPIServer {
  constructor() {
    // 本番環境ではmockServerを無効化
    this.isEnabled = process.env.NODE_ENV !== 'production'
    
    if (this.isEnabled) {
      this.loadData()
    } else {
      console.log('MockServer disabled in production environment')
      this.data = { seminars: [], publications: [], news: [], pages: {} }
    }
  }

  loadData() {
    const savedData = localStorage.getItem(STORAGE_KEY)
    console.log('Loading data from localStorage...')
    if (savedData) {
      this.data = JSON.parse(savedData)
      console.log('Data loaded from localStorage:', this.data)
      console.log('Pages in loaded data:', this.data.pages)
    } else {
      console.log('No saved data, using default mockData')
      this.data = mockData
      this.saveData()
      console.log('Default data saved and loaded:', this.data)
    }
  }

  saveData() {
    if (this.isEnabled) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.data))
    }
  }

  // デバッグ用: localStorageデータをクリア
  clearStorageData() {
    localStorage.removeItem(STORAGE_KEY)
    console.log('MockServer localStorage data cleared')
    return 'MockServer localStorage cleared'
  }

  // Seminars
  getSeminars() {
    if (!this.isEnabled) {
      return Promise.reject(new Error('MockServer disabled'))
    }
    return Promise.resolve(this.data.seminars)
  }

  getSeminar(id) {
    const seminar = this.data.seminars.find(s => s.id === parseInt(id))
    if (seminar) {
      return Promise.resolve(seminar)
    }
    return Promise.reject(new Error('Seminar not found'))
  }

  createSeminar(data) {
    const newSeminar = {
      ...data,
      id: Math.max(...this.data.seminars.map(s => s.id), 0) + 1
    }
    this.data.seminars.push(newSeminar)
    this.saveData()
    return Promise.resolve(newSeminar)
  }

  updateSeminar(id, data) {
    const index = this.data.seminars.findIndex(s => s.id === parseInt(id))
    if (index !== -1) {
      // 画像URLも含めて更新
      this.data.seminars[index] = { ...this.data.seminars[index], ...data }
      this.saveData()
      return Promise.resolve(this.data.seminars[index])
    }
    return Promise.reject(new Error('Seminar not found'))
  }

  deleteSeminar(id) {
    const index = this.data.seminars.findIndex(s => s.id === parseInt(id))
    if (index !== -1) {
      this.data.seminars.splice(index, 1)
      this.saveData()
      return Promise.resolve({ success: true })
    }
    return Promise.reject(new Error('Seminar not found'))
  }

  // Publications
  getPublications() {
    if (!this.isEnabled) {
      return Promise.reject(new Error('MockServer disabled'))
    }
    return Promise.resolve(this.data.publications)
  }
  
  getPublication(id) {
    const publication = this.data.publications.find(p => p.id === parseInt(id))
    if (publication) {
      return Promise.resolve(publication)
    }
    return Promise.reject(new Error('Publication not found'))
  }

  createPublication(data) {
    const newPublication = {
      ...data,
      id: Math.max(...this.data.publications.map(p => p.id), 0) + 1
    }
    this.data.publications.push(newPublication)
    this.saveData()
    return Promise.resolve(newPublication)
  }

  updatePublication(id, data) {
    const index = this.data.publications.findIndex(p => p.id === parseInt(id))
    if (index !== -1) {
      this.data.publications[index] = { ...this.data.publications[index], ...data }
      this.saveData()
      return Promise.resolve(this.data.publications[index])
    }
    return Promise.reject(new Error('Publication not found'))
  }

  deletePublication(id) {
    const index = this.data.publications.findIndex(p => p.id === parseInt(id))
    if (index !== -1) {
      this.data.publications.splice(index, 1)
      this.saveData()
      return Promise.resolve({ success: true })
    }
    return Promise.reject(new Error('Publication not found'))
  }

  // Notices
  getNotices() {
    return Promise.resolve(this.data.notices)
  }

  getNotice(id) {
    const notice = this.data.notices.find(n => n.id === parseInt(id))
    if (notice) {
      return Promise.resolve(notice)
    }
    return Promise.reject(new Error('Notice not found'))
  }

  createNotice(data) {
    const newNotice = {
      ...data,
      id: Math.max(...this.data.notices.map(n => n.id), 0) + 1
    }
    this.data.notices.push(newNotice)
    this.saveData()
    return Promise.resolve(newNotice)
  }

  updateNotice(id, data) {
    const index = this.data.notices.findIndex(n => n.id === parseInt(id))
    if (index !== -1) {
      this.data.notices[index] = { ...this.data.notices[index], ...data }
      this.saveData()
      return Promise.resolve(this.data.notices[index])
    }
    return Promise.reject(new Error('Notice not found'))
  }

  deleteNotice(id) {
    const index = this.data.notices.findIndex(n => n.id === parseInt(id))
    if (index !== -1) {
      this.data.notices.splice(index, 1)
      this.saveData()
      return Promise.resolve({ success: true })
    }
    return Promise.reject(new Error('Notice not found'))
  }

  // Media
  getMedia() {
    return Promise.resolve(this.data.media)
  }

  getMediaItem(id) {
    const media = this.data.media.find(m => m.id === parseInt(id))
    if (media) {
      return Promise.resolve(media)
    }
    return Promise.reject(new Error('Media not found'))
  }

  uploadMedia(data) {
    const newMedia = {
      ...data,
      id: Math.max(...this.data.media.map(m => m.id), 0) + 1,
      uploadedDate: new Date().toISOString()
    }
    this.data.media.push(newMedia)
    this.saveData()
    return Promise.resolve(newMedia)
  }

  updateMedia(id, data) {
    const index = this.data.media.findIndex(m => m.id === parseInt(id))
    if (index !== -1) {
      this.data.media[index] = { ...this.data.media[index], ...data }
      this.saveData()
      return Promise.resolve(this.data.media[index])
    }
    return Promise.reject(new Error('Media not found'))
  }

  deleteMedia(id) {
    const index = this.data.media.findIndex(m => m.id === parseInt(id))
    if (index !== -1) {
      this.data.media.splice(index, 1)
      this.saveData()
      return Promise.resolve({ success: true })
    }
    return Promise.reject(new Error('Media not found'))
  }

  // Members
  getMembers() {
    return Promise.resolve(this.data.members)
  }

  getMember(id) {
    const member = this.data.members.find(m => m.id === parseInt(id))
    if (member) {
      return Promise.resolve(member)
    }
    return Promise.reject(new Error('Member not found'))
  }

  updateMember(id, data) {
    const index = this.data.members.findIndex(m => m.id === parseInt(id))
    if (index !== -1) {
      this.data.members[index] = { ...this.data.members[index], ...data }
      this.saveData()
      return Promise.resolve(this.data.members[index])
    }
    return Promise.reject(new Error('Member not found'))
  }

  // Member Authentication
  memberLogin(email, password) {
    // 簡易的な認証（実際のパスワード検証の代わり）
    const member = this.data.members.find(m => m.email === email && m.status === 'active')
    
    if (member) {
      // パスワードは仮で "password123" とする
      if (password === 'password123') {
        const token = 'member_token_' + Date.now()
        const userData = {
          id: member.id,
          name: member.name,
          email: member.email,
          company: member.company,
          membershipType: member.membershipType,
          expiryDate: member.expiryDate || '2025-12-31',
          token: token
        }
        return Promise.resolve({
          success: true,
          user: userData,
          token: token
        })
      }
    }
    
    return Promise.reject(new Error('メールアドレスまたはパスワードが正しくありません'))
  }

  // Get current member from token
  getCurrentMember(token) {
    if (!token || !token.startsWith('member_token_')) {
      return null
    }
    // 簡易実装: トークンがあれば最初のアクティブ会員を返す
    const member = this.data.members.find(m => m.status === 'active')
    if (member) {
      return {
        id: member.id,
        name: member.name,
        email: member.email,
        company: member.company,
        membershipType: member.membershipType,
        expiryDate: member.expiryDate || '2025-12-31'
      }
    }
    return null
  }

  // Check if member can access content
  canAccessContent(contentLevel, userMembershipType) {
    const levels = {
      'free': 0,
      'basic': 1,
      'standard': 2,
      'premium': 3
    }
    
    const contentRequirement = levels[contentLevel] || 0
    const userLevel = userMembershipType ? (levels[userMembershipType] || 0) : 0
    
    return userLevel >= contentRequirement
  }

  // Get restriction message
  getRestrictionMessage(contentLevel, userMembershipType) {
    if (!userMembershipType) {
      return '会員登録してアクセス'
    }
    
    const messages = {
      'basic': 'ベーシック会員以上で閲覧可能',
      'standard': 'スタンダード会員以上で閲覧可能',
      'premium': 'プレミアム会員限定コンテンツ'
    }
    
    return messages[contentLevel] || 'アップグレードが必要です'
  }

  memberRegister(data) {
    // 既存メールチェック
    const existingMember = this.data.members.find(m => m.email === data.email)
    if (existingMember) {
      return Promise.reject(new Error('このメールアドレスは既に登録されています'))
    }
    
    const newMember = {
      id: Math.max(...this.data.members.map(m => m.id), 0) + 1,
      name: data.name,
      email: data.email,
      company: data.company || '',
      membershipType: data.membershipType || 'basic', // デフォルトはベーシック会員
      joinedDate: new Date().toISOString().split('T')[0],
      status: 'active'
    }
    
    this.data.members.push(newMember)
    this.saveData()
    
    const token = 'member_token_' + Date.now()
    return Promise.resolve({
      success: true,
      user: newMember,
      token: token
    })
  }

  // Pages
  async getPages() {
    console.log('MockServer getPages called')
    
    try {
      // まずAPIからデータを取得してみる
      const apiClient = await import('./services/apiClient').then(m => m.default)
      // 互換性のために両方のキーを確認
      const token = localStorage.getItem('admin_token') || localStorage.getItem('adminToken')
      if (!token) {
        throw new Error('Admin token not found')
      }
      apiClient.setToken(token)
      const response = await apiClient.getPageContents()
      
      if (response.success && response.data && response.data.pages && response.data.pages.length > 0) {
        console.log('Pages loaded from API:', response.data.pages.length)
        
        // APIから取得したデータを整形
        const pagesArray = response.data.pages.map(page => ({
          pageKey: page.page_key,
          title: page.title,
          content: page.content,
          meta_description: page.meta_description,
          meta_keywords: page.meta_keywords,
          is_published: page.is_published,
          lastUpdated: page.updated_at,
          images: page.content.images || []
        }))
        
        // ローカルデータも更新
        pagesArray.forEach(page => {
          this.data.pages[page.pageKey] = {
            title: page.title,
            content: page.content,
            lastUpdated: page.lastUpdated,
            images: page.images
          }
        })
        
        this.saveData()
        console.log('Returning API pages array:', pagesArray)
        return Promise.resolve(pagesArray)
      }
    } catch (error) {
      console.warn('Failed to load pages from API, falling back to local data:', error)
    }
    
    // APIからのデータ取得に失敗した場合、ローカルデータを返す
    console.log('Current local pages data:', this.data.pages)
    const pagesArray = Object.entries(this.data.pages).map(([key, value]) => ({
      pageKey: key,
      ...value
    }))
    console.log('Returning local pages array:', pagesArray)
    return Promise.resolve(pagesArray)
  }

  async getPage(pageKey) {
    try {
      // まずAPIからデータを取得してみる
      const apiClient = await import('./services/apiClient').then(m => m.default)
      // 互換性のために両方のキーを確認
      const token = localStorage.getItem('admin_token') || localStorage.getItem('adminToken')
      if (token) {
        apiClient.setToken(token)
      }
      const response = await apiClient.getPageContent(pageKey)
      
      if (response.success && response.data && response.data.page) {
        console.log(`Page ${pageKey} loaded from API`)
        
        // APIから取得したデータを整形
        const page = {
          pageKey: response.data.page.page_key,
          title: response.data.page.title,
          content: response.data.page.content,
          meta_description: response.data.page.meta_description,
          meta_keywords: response.data.page.meta_keywords,
          is_published: response.data.page.is_published,
          lastUpdated: response.data.page.updated_at,
          images: response.data.page.content.images || []
        }
        
        // ローカルデータも更新
        this.data.pages[pageKey] = {
          title: page.title,
          content: page.content,
          lastUpdated: page.lastUpdated,
          images: page.images
        }
        
        this.saveData()
        return Promise.resolve(page)
      }
    } catch (error) {
      console.warn(`Failed to load page ${pageKey} from API, falling back to local data:`, error)
    }
    
    // APIからのデータ取得に失敗した場合、ローカルデータを返す
    const page = this.data.pages[pageKey]
    if (page) {
      return Promise.resolve({ pageKey, ...page })
    }
    return Promise.reject(new Error('Page not found'))
  }
  
  createPage(pageKey, data) {
    if (this.data.pages[pageKey]) {
      return Promise.reject(new Error('Page already exists'))
    }
    
    // 画像データを特別に処理
    if (data.images && Array.isArray(data.images)) {
      // Base64データURLを処理
      const processedImages = data.images.map(img => {
        // すでに処理済みの画像はそのまま返す
        if (!img.url.startsWith('data:')) {
          return img;
        }
        
        // Base64データURLの場合は、仮のURLに変換
        const randomId = Math.floor(Math.random() * 1000000);
        return {
          ...img,
          url: `/img/uploaded-${randomId}.png` // 仮のURL
        };
      });
      
      data.images = processedImages;
    }
    
    this.data.pages[pageKey] = {
      title: data.title || 'New Page',
      content: data.content || {},
      meta_description: data.meta_description || '',
      meta_keywords: data.meta_keywords || '',
      is_published: data.is_published || false,
      images: data.images || [],
      lastUpdated: new Date().toISOString(),
      createdAt: new Date().toISOString()
    }
    
    console.log(`New page ${pageKey} created with images:`, this.data.pages[pageKey].images);
    this.saveData()
    return Promise.resolve({ pageKey, ...this.data.pages[pageKey] })
  }

  updatePage(pageKey, data) {
    if (this.data.pages[pageKey]) {
      // 画像データを特別に処理
      if (data.images && Array.isArray(data.images)) {
        // Base64データURLを処理（実際の実装では保存やアップロード処理が必要）
        const processedImages = data.images.map(img => {
          // すでに処理済みの画像はそのまま返す
          if (!img.url.startsWith('data:')) {
            return img;
          }
          
          // Base64データURLの場合は、仮のURLに変換（実際の実装ではアップロード処理が必要）
          // 実際のプロダクション環境では、Base64をファイルに変換してサーバーにアップロードする
          const randomId = Math.floor(Math.random() * 1000000);
          return {
            ...img,
            url: `/img/uploaded-${randomId}.png` // 仮のURL
          };
        });
        
        data.images = processedImages;
      }
      
      this.data.pages[pageKey] = {
        ...this.data.pages[pageKey],
        ...data,
        lastUpdated: new Date().toISOString()
      }
      
      console.log(`Page ${pageKey} updated with images:`, this.data.pages[pageKey].images);
      this.saveData()
      return Promise.resolve({ pageKey, ...this.data.pages[pageKey] })
    }
    return Promise.reject(new Error('Page not found'))
  }

  // Get all news items for NewsPage
  getAllNews() {
    if (!this.isEnabled) {
      return Promise.reject(new Error('MockServer disabled'))
    }
    const news = []
    
    // Add notices as news items
    this.data.notices.forEach(notice => {
      news.push({
        id: notice.id,
        date: notice.published_at || notice.date,
        category: notice.category,
        title: notice.title,
        description: notice.content,
        excerpt: notice.excerpt,
        type: 'notice',
        is_important: notice.is_important || notice.isImportant,
        is_published: notice.is_published,
        slug: notice.slug
      })
    })
    
    // Convert seminars to news items
    this.data.seminars.forEach(seminar => {
      news.push({
        id: `seminar-${seminar.id}`,
        date: seminar.date,
        category: 'seminar',
        title: seminar.title,
        description: seminar.description,
        type: 'seminar'
      })
    })
    
    // Convert publications to news items
    this.data.publications.forEach(publication => {
      news.push({
        id: `publication-${publication.id}`,
        date: publication.publication_date, // 正しいフィールド名を使用
        category: 'publication',
        title: publication.title,
        description: publication.description,
        type: 'publication'
      })
    })
    
    // Sort by date (newest first)
    news.sort((a, b) => new Date(b.date) - new Date(a.date))
    
    return Promise.resolve(news)
  }

  // セミナー関連メソッド（互換性のため残す）
  // 注意: このメソッドはthis.data.seminarsを使用するように更新
  getSeminars() {
    // this.data.seminarsに保存されたデータを返す
    // imageフィールドをfeatured_imageにマッピング
    const seminars = this.data.seminars.map(s => ({
      ...s,
      image: s.featured_image || '/img/image-1.png',
      fee: s.fee ? `${s.fee}円` : '会員無料',
      status: s.status === 'ongoing' ? 'current' : s.status
    }))
    
    return Promise.resolve(seminars)
  }

  getSeminarById(id) {
    const seminar = this.data.seminars.find(s => s.id === parseInt(id))
    if (seminar) {
      // imageフィールドをfeatured_imageにマッピング
      return Promise.resolve({
        ...seminar,
        image: seminar.featured_image || '/img/image-1.png',
        fee: seminar.fee ? `${seminar.fee}円` : '会員無料',
        status: seminar.status === 'ongoing' ? 'current' : seminar.status
      })
    }
    return Promise.reject(new Error('Seminar not found'))
  }

  // News categories methods
  getNewsCategories() {
    const categories = [
      { id: 1, name: 'お知らせ', slug: 'notice', color: '#da5761' },
      { id: 2, name: '重要', slug: 'important', color: '#ff4444' },
      { id: 3, name: 'イベント', slug: 'event', color: '#4CAF50' },
      { id: 4, name: 'メディア', slug: 'media', color: '#2196F3' }
    ]
    return Promise.resolve(categories)
  }

  // Publication categories methods
  getPublicationCategories() {
    const categories = [
      { id: 1, name: '調査研究', slug: 'research', sort_order: 1 },
      { id: 2, name: '定期刊行物', slug: 'quarterly', sort_order: 2 },
      { id: 3, name: '特別企画', slug: 'special', sort_order: 3 },
      { id: 4, name: '統計資料', slug: 'statistics', sort_order: 4 }
    ]
    return Promise.resolve(categories)
  }
}

// Export singleton instance
const mockServer = new MockAPIServer()

// デバッグ用: グローバル関数としてlocalStorageクリア機能を提供
window.clearMockData = () => {
  return mockServer.clearStorageData()
}

// デバッグ用: mockServerの状態確認
window.checkMockServer = () => {
  console.log('MockServer enabled:', mockServer.isEnabled)
  console.log('Current environment:', process.env.NODE_ENV)
  console.log('Current host:', window.location.host)
  return {
    enabled: mockServer.isEnabled,
    environment: process.env.NODE_ENV,
    host: window.location.host
  }
}

export default mockServer