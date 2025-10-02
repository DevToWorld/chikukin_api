const express = require('express')
const cors = require('cors')
const app = express()
const PORT = 3001

// CORS設定
app.use(cors())
app.use(express.json())

// モックデータ
const mockData = {
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
  publications: [
    {
      id: 1,
      title: '九州の未来 vol.45',
      description: '九州地域の経済動向と今後の展望について詳しく解説。',
      publication_date: '2025-04-30',
      category: 'quarterly',
      type: 'pdf',
      author: 'ちくぎん地域経済研究所',
      pages: 45,
      file_url: '/files/kyushu-future.pdf',
      image_url: '',
      file_size: 2.1,
      download_count: 234,
      is_published: true,
      membershipLevel: 'premium'
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
      image_url: '/img/-----2-2-4.png',
      file_size: 2.0,
      download_count: 45,
      is_published: true,
      membershipLevel: 'free'
    }
  ],
  seminars: [
    {
      id: 1,
      title: '2025年度経済展望セミナー',
      description: '九州地域の2025年度経済展望について詳しく解説します。',
      date: '2025-03-15',
      time: '14:00-16:00',
      location: 'オンライン',
      capacity: 100,
      registered_count: 45,
      status: 'active',
      is_published: true
    }
  ],
  news: [
    {
      id: 1,
      title: '2025年度経済展望セミナー開催のお知らせ',
      content: '来る3月15日に、2025年度の九州地域経済展望についてのセミナーを開催いたします。',
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
    }
  ]
}

// 会員ログイン（フロントエンド用エンドポイント）
app.post('/api/member-auth/login', (req, res) => {
  const { email, password } = req.body
  
  if (password === 'password123') {
    const member = mockData.members.find(m => m.email === email && m.status === 'active')
    
    if (member) {
      const token = 'member_token_' + Date.now()
      const userData = {
        id: member.id,
        name: member.name,
        email: member.email,
        company: member.company,
        membershipType: member.membershipType,
        expiryDate: '2025-12-31',
        token: token
      }
      
      res.json({
        success: true,
        user: userData,
        token: token
      })
    } else {
      res.status(401).json({
        success: false,
        message: 'メールアドレスまたはパスワードが正しくありません'
      })
    }
  } else {
    res.status(401).json({
      success: false,
      message: 'メールアドレスまたはパスワードが正しくありません'
    })
  }
})

// 会員ログイン（旧エンドポイント）
app.post('/api/members/login', (req, res) => {
  const { email, password } = req.body
  
  if (password === 'password123') {
    const member = mockData.members.find(m => m.email === email && m.status === 'active')
    
    if (member) {
      const token = 'member_token_' + Date.now()
      const userData = {
        id: member.id,
        name: member.name,
        email: member.email,
        company: member.company,
        membershipType: member.membershipType,
        expiryDate: '2025-12-31',
        token: token
      }
      
      res.json({
        success: true,
        user: userData,
        token: token
      })
    } else {
      res.status(401).json({
        success: false,
        message: 'メールアドレスまたはパスワードが正しくありません'
      })
    }
  } else {
    res.status(401).json({
      success: false,
      message: 'メールアドレスまたはパスワードが正しくありません'
    })
  }
})

// 現在の会員情報取得（複数エンドポイント対応）
app.get('/api/members/me', (req, res) => {
  const token = req.headers.authorization?.replace('Bearer ', '')
  
  if (!token || !token.startsWith('member_token_')) {
    return res.status(401).json({ message: 'Unauthorized' })
  }
  
  const member = mockData.members.find(m => m.status === 'active')
  if (member) {
    res.json({
      success: true,
      data: {
        id: member.id,
        name: member.name,
        email: member.email,
        company: member.company,
        membershipType: member.membershipType,
        membership_type: member.membershipType, // 互換性のため
        expiryDate: '2025-12-31',
        expiry_date: '2025-12-31' // 互換性のため
      }
    })
  } else {
    res.status(404).json({ success: false, message: 'Member not found' })
  }
})

// フロントエンド用の会員情報取得エンドポイント
app.get('/api/member-auth/me', (req, res) => {
  const token = req.headers.authorization?.replace('Bearer ', '')
  
  if (!token || !token.startsWith('member_token_')) {
    return res.status(401).json({ message: 'Unauthorized' })
  }
  
  const member = mockData.members.find(m => m.status === 'active')
  if (member) {
    res.json({
      success: true,
      data: {
        id: member.id,
        name: member.name,
        email: member.email,
        company: member.company,
        membershipType: member.membershipType,
        membership_type: member.membershipType, // 互換性のため
        expiryDate: '2025-12-31',
        expiry_date: '2025-12-31' // 互換性のため
      }
    })
  } else {
    res.status(404).json({ success: false, message: 'Member not found' })
  }
})

// MyAccountPage用のプロフィール取得エンドポイント
app.get('/api/member/my-profile', (req, res) => {
  const token = req.headers.authorization?.replace('Bearer ', '')
  
  if (!token || !token.startsWith('member_token_')) {
    return res.status(401).json({ message: 'Unauthorized' })
  }
  
  const member = mockData.members.find(m => m.status === 'active')
  if (member) {
    res.json({
      success: true,
      data: {
        id: member.id,
        name: member.name,
        email: member.email,
        company: member.company,
        membershipType: member.membershipType,
        membership_type: member.membershipType,
        expiryDate: '2025-12-31',
        expiry_date: '2025-12-31',
        joinedDate: member.joinedDate,
        phone: '090-1234-5678',
        postalCode: '100-0001',
        address: '東京都千代田区千代田1-1'
      }
    })
  } else {
    res.status(404).json({ success: false, message: 'Member not found' })
  }
})

// ダッシュボード情報取得エンドポイント
app.get('/api/member/dashboard', (req, res) => {
  const token = req.headers.authorization?.replace('Bearer ', '')
  
  if (!token || !token.startsWith('member_token_')) {
    return res.status(401).json({ message: 'Unauthorized' })
  }
  
  res.json({
    success: true,
    data: {
      totalDownloads: 15,
      favoriteSeminars: 3,
      upcomingSeminars: 2,
      recentActivity: [
        { type: 'download', title: '九州の未来 vol.45', date: '2025-01-10' },
        { type: 'seminar', title: '2025年度経済展望セミナー', date: '2025-01-08' }
      ]
    }
  })
})

// お気に入り一覧取得エンドポイント
app.get('/api/member/favorites', (req, res) => {
  const token = req.headers.authorization?.replace('Bearer ', '')
  
  if (!token || !token.startsWith('member_token_')) {
    return res.status(401).json({ message: 'Unauthorized' })
  }
  
  res.json({
    success: true,
    data: []
  })
})

// CMSページコンテンツ取得エンドポイント
app.get('/api/page-content/:pageName', (req, res) => {
  const { pageName } = req.params
  
  // 基本的なCMSコンテンツを返す
  const cmsContent = {
    my_account: {
      labels: {
        tabs: {
          profile: 'アカウント情報',
          seminars: 'セミナー',
          seminarFavorites: 'セミナーお気に入り',
          registrations: '申込状況',
          membership: '会員プラン',
          downloads: 'ダウンロード履歴',
          favorites: 'お気に入り',
          settings: '設定'
        },
        buttons: {
          cancel: 'キャンセル',
          submit: '送信する',
          submitting: '送信中...'
        }
      }
    }
  }
  
  res.json({
    success: true,
    data: {
      page: {
        content: cmsContent[pageName] || {}
      }
    }
  })
})

// 業種マスタ取得エンドポイント
app.get('/api/industries', (req, res) => {
  res.json({
    success: true,
    data: [
      { id: 1, name: '製造業' },
      { id: 2, name: '建設業' },
      { id: 3, name: '小売業' },
      { id: 4, name: 'サービス業' },
      { id: 5, name: 'IT・通信業' },
      { id: 6, name: '金融業' },
      { id: 7, name: '不動産業' },
      { id: 8, name: '運輸業' },
      { id: 9, name: '農業' },
      { id: 10, name: 'その他' }
    ]
  })
})

// 刊行物一覧
app.get('/api/publications', (req, res) => {
  res.json({
    data: mockData.publications,
    meta: {
      total: mockData.publications.length,
      per_page: 12,
      current_page: 1,
      last_page: 1
    }
  })
})

// セミナー一覧
app.get('/api/seminars', (req, res) => {
  res.json({
    data: mockData.seminars,
    meta: {
      total: mockData.seminars.length,
      per_page: 10,
      current_page: 1,
      last_page: 1
    }
  })
})

// ニュース一覧
app.get('/api/news', (req, res) => {
  res.json({
    data: mockData.news,
    meta: {
      total: mockData.news.length,
      per_page: 10,
      current_page: 1,
      last_page: 1
    }
  })
})

// 管理画面ログイン
app.post('/api/admin/login', (req, res) => {
  const { email, password } = req.body
  
  if (email === 'admin@example.com' && password === 'password123') {
    const token = 'admin_token_' + Date.now()
    res.json({
      success: true,
      user: {
        id: 1,
        name: 'Admin',
        email: 'admin@example.com',
        is_admin: true
      },
      token: token
    })
  } else {
    res.status(401).json({
      success: false,
      message: 'Invalid credentials'
    })
  }
})

// ルートパス
app.get('/', (req, res) => {
  res.json({ 
    message: 'Mock API Server is running',
    version: '1.0.0',
    endpoints: [
      'POST /api/member-auth/login',
      'POST /api/members/login',
      'GET /api/members/me',
      'GET /api/member-auth/me',
      'GET /api/member/my-profile',
      'GET /api/member/dashboard',
      'GET /api/member/favorites',
      'GET /api/page-content/:pageName',
      'GET /api/industries',
      'GET /api/publications',
      'GET /api/seminars',
      'GET /api/news',
      'POST /api/admin/login',
      'GET /api/health'
    ],
    testAccounts: {
      premium: 'yamada@example.com / password123',
      standard: 'suzuki@example.com / password123',
      admin: 'admin@example.com / password123'
    }
  })
})

// ヘルスチェック
app.get('/api/health', (req, res) => {
  res.json({ status: 'OK', message: 'Mock server is running' })
})

// サーバー起動
app.listen(PORT, () => {
  console.log(`🚀 Mock server running on http://localhost:${PORT}`)
  console.log(`📋 Available test accounts:`)
  console.log(`   Premium: yamada@example.com / password123`)
  console.log(`   Standard: suzuki@example.com / password123`)
  console.log(`   Admin: admin@example.com / password123`)
})

module.exports = app
