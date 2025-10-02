#!/usr/bin/env node

// Test data script for CMS mock server
// This script adds comprehensive test data to localStorage for testing

const testData = {
  seminars: [
    {
      id: 1,
      title: '採用力強化！経営・人事向け　面接官トレーニングセミナー',
      description: '優秀な人材を見極め、獲得するための面接技術を習得できるセミナーです。実践的なロールプレイを通じて、適切な質問の仕方、評価基準の設定、面接官としての心構えなどを学んでいただけます。',
      date: '2025-09-15',
      start_time: '14:00',
      end_time: '17:00',
      location: 'ちくぎん地域経済研究所 会議室A',
      capacity: 30,
      fee: 5000,
      status: 'ongoing'
    },
    {
      id: 2,
      title: 'DX推進セミナー～デジタル変革の実践的アプローチ～',
      description: 'デジタルトランスフォーメーションの基礎から実践まで学べるセミナーです。成功事例の紹介、導入プロセスの解説、課題対応策など、DX推進に必要な要素を包括的にカバーします。',
      date: '2025-09-22',
      start_time: '13:00',
      end_time: '16:00',
      location: 'オンライン開催（Zoom）',
      capacity: 100,
      fee: 3000,
      status: 'scheduled'
    },
    {
      id: 3,
      title: '九州地域経済フォーラム2025',
      description: '九州地域の経済動向と今後の展望について専門家が解説します。地域企業の成長戦略、産業振興策、インバウンド対策など、地域経済の課題と機会を探ります。',
      date: '2025-10-08',
      start_time: '10:00',
      end_time: '16:30',
      location: 'アクロス福岡シンフォニーホール',
      capacity: 200,
      fee: 8000,
      status: 'scheduled'
    },
    {
      id: 4,
      title: '中小企業のためのAI活用セミナー',
      description: 'AI技術を中小企業でも実践的に活用する方法を学べるセミナーです。コスト効率の良いAIツールの選び方、導入事例、ROI計算など、具体的な活用方法を解説します。',
      date: '2025-10-15',
      start_time: '14:00',
      end_time: '17:30',
      location: 'ちくぎん地域経済研究所 大会議室',
      capacity: 50,
      fee: 4000,
      status: 'scheduled'
    },
    {
      id: 5,
      title: '事業承継セミナー～次世代への円滑な承継のために～',
      description: '事業承継の計画策定から実行まで、成功のポイントを解説します。税務・法務の留意点、後継者育成、従業員・取引先への対応など、実務に即した内容をお伝えします。',
      date: '2025-08-20',
      start_time: '13:30',
      end_time: '16:00',
      location: 'ちくぎん地域経済研究所 会議室B',
      capacity: 40,
      fee: 6000,
      status: 'completed'
    },
    {
      id: 6,
      title: '女性活躍推進セミナー～ダイバーシティ経営の実現に向けて～',
      description: '女性従業員の活躍推進とダイバーシティ経営について学ぶセミナーです。制度設計、組織風土改革、管理職の意識改革など、実践的な取り組み方法を紹介します。',
      date: '2025-07-25',
      start_time: '14:30',
      end_time: '16:30',
      location: 'オンライン開催（Teams）',
      capacity: 80,
      fee: 2500,
      status: 'completed'
    }
  ],
  publications: [
    {
      id: 1,
      title: 'HOT Information Vol.325',
      description: '九州地域の経済動向を詳細に分析した月刊レポート。製造業の設備投資動向、サービス業の雇用状況、観光業の回復状況など、最新のデータに基づいた分析をお届けします。',
      publishedDate: '2025-08-25',
      category: 'monthly',
      fileUrl: '/files/hot-info-325.pdf'
    },
    {
      id: 2,
      title: 'HOT Information Vol.324',
      description: '地域金融機関の融資動向と中小企業の資金調達環境について特集。新しい金融サービスの動向、フィンテックの活用事例、事業性評価融資の現状などを詳しく解説しています。',
      publishedDate: '2025-07-28',
      category: 'monthly',
      fileUrl: '/files/hot-info-324.pdf'
    },
    {
      id: 3,
      title: 'HOT Information Vol.323',
      description: 'コロナ後の消費行動の変化と小売業への影響を分析。Eコマースの成長、オムニチャネル戦略、地域商業施設の再生など、新しい消費トレンドを詳細に調査しています。',
      publishedDate: '2025-06-30',
      category: 'monthly',
      fileUrl: '/files/hot-info-323.pdf'
    },
    {
      id: 4,
      title: '経営参考BOOK vol.58',
      description: '持続可能な経営戦略とSDGsの実践について解説。環境配慮経営、社会貢献活動、ガバナンス強化など、ESG経営の具体的な取り組み方を事例を交えて紹介します。',
      publishedDate: '2025-08-15',
      category: 'book',
      fileUrl: '/files/management-book-58.pdf'
    },
    {
      id: 5,
      title: '経営参考BOOK vol.57',
      description: 'デジタルマーケティングの最新動向と実践手法を特集。SNS活用、コンテンツマーケティング、データ分析手法など、中小企業でも取り組めるマーケティング戦略を解説します。',
      publishedDate: '2025-07-10',
      category: 'book',
      fileUrl: '/files/management-book-57.pdf'
    },
    {
      id: 6,
      title: '九州経済白書2025',
      description: '九州地域経済の包括的な分析レポート。産業構造の変化、人口動態、インフラ整備状況、地域間連携など、九州経済の現状と将来展望を詳細に分析した年次レポートです。',
      publishedDate: '2025-06-01',
      category: 'report',
      fileUrl: '/files/kyushu-economic-whitepaper-2025.pdf'
    },
    {
      id: 7,
      title: 'スタートアップ支援ガイド2025',
      description: '九州地域でのスタートアップ創業支援に関する総合ガイド。資金調達方法、支援制度、成功事例、ネットワーキング機会など、起業家に必要な情報を網羅的にまとめています。',
      publishedDate: '2025-05-20',
      category: 'guide',
      fileUrl: '/files/startup-support-guide-2025.pdf'
    }
  ],
  notices: [
    {
      id: 1,
      title: '年末年始休業のお知らせ',
      content: '2025年12月28日（土）から2026年1月5日（日）まで年末年始休業とさせていただきます。新年は1月6日（月）より通常営業いたします。休業期間中にいただいたお問い合わせについては、営業開始日より順次対応させていただきます。',
      date: '2025-08-20',
      category: 'notice',
      isImportant: true
    },
    {
      id: 2,
      title: 'ホームページリニューアルのお知らせ',
      content: '当研究所のホームページをより使いやすくリニューアルいたしました。新しいデザインとナビゲーションにより、情報がより見つけやすくなりました。モバイル対応も強化し、スマートフォンやタブレットからもご利用いただきやすくなっております。',
      date: '2025-08-01',
      category: 'notice',
      isImportant: false
    },
    {
      id: 3,
      title: 'セミナー申込システムメンテナンスのお知らせ',
      content: '8月30日（金）午後11時から翌31日（土）午前6時まで、セミナー申込システムのメンテナンスを実施いたします。この間、オンラインでのセミナー申込ができませんので、お急ぎの場合はお電話にてお申し込みください。',
      date: '2025-08-25',
      category: 'maintenance',
      isImportant: true
    },
    {
      id: 4,
      title: '九州経済フォーラム2025 早期申込割引のご案内',
      content: '10月8日開催の九州経済フォーラム2025の早期申込割引を実施中です。9月15日までのお申し込みで参加費が20%割引となります。定員に限りがございますので、お早めにお申し込みください。',
      date: '2025-08-15',
      category: 'campaign',
      isImportant: false
    },
    {
      id: 5,
      title: '新刊「経営参考BOOK vol.58」発刊のお知らせ',
      content: '持続可能な経営戦略とSDGsの実践をテーマにした「経営参考BOOK vol.58」を発刊いたしました。環境配慮経営、社会貢献活動、ガバナンス強化など、ESG経営の具体的な取り組み方を事例を交えて紹介しています。会員の皆様には無料でお配りしております。',
      date: '2025-08-16',
      category: 'publication',
      isImportant: false
    },
    {
      id: 6,
      title: 'レポートダウンロードサービス開始のお知らせ',
      content: '会員限定で、過去のレポートをPDFでダウンロードできるサービスを開始いたします。会員専用ページからログインしてご利用ください。過去5年分のHOT Informationと経営参考BOOKがご利用いただけます。',
      date: '2025-07-30',
      category: 'service',
      isImportant: false
    },
    {
      id: 7,
      title: '夏季休業のお知らせ',
      content: '8月13日（火）から8月16日（金）まで夏季休業とさせていただきました。休業期間中にいただいたお問い合わせについては、8月19日（月）より順次対応させていただきます。緊急のご連絡は代表番号までお願いいたします。',
      date: '2025-08-05',
      category: 'notice',
      isImportant: true
    },
    {
      id: 8,
      title: 'AI活用セミナー追加開催のお知らせ',
      content: '10月15日開催の「中小企業のためのAI活用セミナー」は、おかげさまで満席となりました。そのため、11月12日（火）に追加開催を決定いたします。内容は同一ですので、前回お申し込みいただけなかった方は、ぜひこの機会にご参加ください。',
      date: '2025-08-22',
      category: 'seminar',
      isImportant: false
    }
  ],
  media: [
    {
      id: 1,
      title: '九州経済フォーラム2024の様子',
      url: '/media/kyushu-forum-2024.jpg',
      type: 'image',
      uploadedDate: '2025-07-01'
    },
    {
      id: 2,
      title: 'DX推進セミナー資料',
      url: '/media/dx-seminar-materials.pdf',
      type: 'document',
      uploadedDate: '2025-08-01'
    },
    {
      id: 3,
      title: '研究所紹介パンフレット',
      url: '/media/institute-brochure.pdf',
      type: 'document',
      uploadedDate: '2025-06-15'
    }
  ],
  members: [
    {
      id: 1,
      name: '田中一郎',
      email: 'tanaka@example.com',
      company: '株式会社九州テクノロジー',
      membershipType: 'premium',
      joinedDate: '2024-03-15',
      status: 'active'
    },
    {
      id: 2,
      name: '佐藤花子',
      email: 'sato@example.com',
      company: '有限会社福岡コンサルティング',
      membershipType: 'standard',
      joinedDate: '2024-05-20',
      status: 'active'
    },
    {
      id: 3,
      name: '山田太郎',
      email: 'yamada@example.com',
      company: '株式会社熊本商事',
      membershipType: 'premium',
      joinedDate: '2023-11-10',
      status: 'active'
    },
    {
      id: 4,
      name: '鈴木次郎',
      email: 'suzuki@example.com',
      company: '大分製造株式会社',
      membershipType: 'basic',
      joinedDate: '2024-07-05',
      status: 'active'
    }
  ],
  pages: {
    about: {
      title: 'ちくぎん地域経済研究所について',
      content: '筑邦銀行のシンクタンクとして設立された地域経済研究所です。九州地域の経済発展に貢献することを使命とし、経済調査、企業支援、人材育成など幅広い活動を行っています。',
      lastUpdated: '2025-08-01'
    },
    services: {
      title: 'サービス一覧',
      content: '経済調査レポートの発行、経営コンサルティング、各種セミナーの開催、企業研修など、地域企業の成長を多面的にサポートしています。',
      lastUpdated: '2025-07-28'
    },
    seminars: {
      title: 'セミナー',
      content: 'セミナー情報ページ',
      lastUpdated: '2025-04-28'
    },
    publications: {
      title: '刊行物',
      content: '刊行物一覧ページ',
      lastUpdated: '2025-04-28'
    },
    news: {
      title: 'お知らせ',
      content: 'お知らせ一覧ページ',
      lastUpdated: '2025-04-28'
    },
    contact: {
      title: 'お問い合わせ',
      content: 'お問い合わせフォーム',
      lastUpdated: '2025-04-28'
    },
    faq: {
      title: 'よくある質問',
      content: 'よくある質問と回答',
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
    }
  }
}

// Check if running in Node.js environment
if (typeof window === 'undefined') {
  // Node.js environment
  const { JSDOM } = require('jsdom')
  const dom = new JSDOM()
  global.localStorage = dom.window.localStorage
}

// Function to add test data to localStorage
function addTestData() {
  try {
    const STORAGE_KEY = 'cms_mock_data'
    
    // Store the test data
    localStorage.setItem(STORAGE_KEY, JSON.stringify(testData))
    
    console.log('✅ Test data has been successfully added to localStorage!')
    console.log('\n📊 Added data summary:')
    console.log(`- Seminars: ${testData.seminars.length} items`)
    console.log(`  - Ongoing: ${testData.seminars.filter(s => s.status === 'ongoing').length}`)
    console.log(`  - Scheduled: ${testData.seminars.filter(s => s.status === 'scheduled').length}`)
    console.log(`  - Completed: ${testData.seminars.filter(s => s.status === 'completed').length}`)
    
    console.log(`- Publications: ${testData.publications.length} items`)
    console.log(`  - Monthly reports: ${testData.publications.filter(p => p.category === 'monthly').length}`)
    console.log(`  - Books: ${testData.publications.filter(p => p.category === 'book').length}`)
    console.log(`  - Others: ${testData.publications.filter(p => !['monthly', 'book'].includes(p.category)).length}`)
    
    console.log(`- Notices: ${testData.notices.length} items`)
    console.log(`  - Important: ${testData.notices.filter(n => n.isImportant).length}`)
    console.log(`  - Regular: ${testData.notices.filter(n => !n.isImportant).length}`)
    
    console.log(`- Media files: ${testData.media.length} items`)
    console.log(`- Members: ${testData.members.length} items`)
    console.log(`- Pages: ${Object.keys(testData.pages).length} items`)
    
    console.log('\n🌐 You can now refresh your browser to see the updated data on HomePage.vue')
    console.log('📱 The HomePage should display:')
    console.log('  - Latest news items in the Information section')
    console.log('  - Updated seminar count')
    console.log('  - Updated publication count')
    console.log('  - Updated notice count')
    
    return testData
  } catch (error) {
    console.error('❌ Error adding test data:', error)
    throw error
  }
}

// Function to clear existing data
function clearData() {
  try {
    localStorage.removeItem('cms_mock_data')
    console.log('🗑️  Existing data has been cleared from localStorage')
  } catch (error) {
    console.error('❌ Error clearing data:', error)
    throw error
  }
}

// Function to view current data
function viewCurrentData() {
  try {
    const STORAGE_KEY = 'cms_mock_data'
    const currentData = localStorage.getItem(STORAGE_KEY)
    
    if (currentData) {
      const data = JSON.parse(currentData)
      console.log('📋 Current data in localStorage:')
      console.log(`- Seminars: ${data.seminars?.length || 0} items`)
      console.log(`- Publications: ${data.publications?.length || 0} items`)
      console.log(`- Notices: ${data.notices?.length || 0} items`)
      console.log(`- Media: ${data.media?.length || 0} items`)
      console.log(`- Members: ${data.members?.length || 0} items`)
      return data
    } else {
      console.log('📋 No data found in localStorage')
      return null
    }
  } catch (error) {
    console.error('❌ Error viewing current data:', error)
    throw error
  }
}

// Export functions for use in browser
if (typeof window !== 'undefined') {
  window.testDataManager = {
    addTestData,
    clearData,
    viewCurrentData,
    testData
  }
}

// If running as a script in Node.js
if (typeof require !== 'undefined' && require.main === module) {
  const command = process.argv[2]
  
  switch (command) {
    case 'add':
      addTestData()
      break
    case 'clear':
      clearData()
      break
    case 'view':
      viewCurrentData()
      break
    default:
      console.log('Usage:')
      console.log('  node add-test-data.js add    - Add test data')
      console.log('  node add-test-data.js clear  - Clear existing data')
      console.log('  node add-test-data.js view   - View current data')
  }
}

// Default export for ES modules
export default {
  addTestData,
  clearData,
  viewCurrentData,
  testData
}