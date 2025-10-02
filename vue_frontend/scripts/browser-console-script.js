// Run this script in your browser console on the HomePage
// Copy and paste this entire script into the browser console and press Enter

console.log('🎯 CMS Test Data Script Starting...');

// Test data
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
            description: 'デジタルトランスフォーメーションの基礎から実践まで学べるセミナーです。',
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
            description: '九州地域の経済動向と今後の展望について専門家が解説します。',
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
            description: 'AI技術を中小企業でも実践的に活用する方法を学べるセミナーです。',
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
            description: '事業承継の計画策定から実行まで、成功のポイントを解説します。',
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
            description: '女性従業員の活躍推進とダイバーシティ経営について学ぶセミナーです。',
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
            description: '地域金融機関の融資動向と中小企業の資金調達環境について特集。',
            publishedDate: '2025-07-28',
            category: 'monthly',
            fileUrl: '/files/hot-info-324.pdf'
        },
        {
            id: 3,
            title: 'HOT Information Vol.323',
            description: 'コロナ後の消費行動の変化と小売業への影響を分析。',
            publishedDate: '2025-06-30',
            category: 'monthly',
            fileUrl: '/files/hot-info-323.pdf'
        },
        {
            id: 4,
            title: '経営参考BOOK vol.58',
            description: '持続可能な経営戦略とSDGsの実践について解説。',
            publishedDate: '2025-08-15',
            category: 'book',
            fileUrl: '/files/management-book-58.pdf'
        },
        {
            id: 5,
            title: '経営参考BOOK vol.57',
            description: 'デジタルマーケティングの最新動向と実践手法を特集。',
            publishedDate: '2025-07-10',
            category: 'book',
            fileUrl: '/files/management-book-57.pdf'
        },
        {
            id: 6,
            title: '九州経済白書2025',
            description: '九州地域経済の包括的な分析レポート。',
            publishedDate: '2025-06-01',
            category: 'report',
            fileUrl: '/files/kyushu-economic-whitepaper-2025.pdf'
        },
        {
            id: 7,
            title: 'スタートアップ支援ガイド2025',
            description: '九州地域でのスタートアップ創業支援に関する総合ガイド。',
            publishedDate: '2025-05-20',
            category: 'guide',
            fileUrl: '/files/startup-support-guide-2025.pdf'
        }
    ],
    notices: [
        {
            id: 1,
            title: '年末年始休業のお知らせ',
            content: '2025年12月28日（土）から2026年1月5日（日）まで年末年始休業とさせていただきます。',
            date: '2025-08-20',
            category: 'notice',
            isImportant: true
        },
        {
            id: 2,
            title: 'ホームページリニューアルのお知らせ',
            content: '当研究所のホームページをより使いやすくリニューアルいたしました。',
            date: '2025-08-01',
            category: 'notice',
            isImportant: false
        },
        {
            id: 3,
            title: 'セミナー申込システムメンテナンスのお知らせ',
            content: '8月30日（金）午後11時から翌31日（土）午前6時まで、セミナー申込システムのメンテナンスを実施いたします。',
            date: '2025-08-25',
            category: 'maintenance',
            isImportant: true
        },
        {
            id: 4,
            title: '九州経済フォーラム2025 早期申込割引のご案内',
            content: '10月8日開催の九州経済フォーラム2025の早期申込割引を実施中です。',
            date: '2025-08-15',
            category: 'campaign',
            isImportant: false
        },
        {
            id: 5,
            title: '新刊「経営参考BOOK vol.58」発刊のお知らせ',
            content: '持続可能な経営戦略とSDGsの実践をテーマにした新刊を発刊いたしました。',
            date: '2025-08-16',
            category: 'publication',
            isImportant: false
        },
        {
            id: 6,
            title: 'レポートダウンロードサービス開始のお知らせ',
            content: '会員限定で、過去のレポートをPDFでダウンロードできるサービスを開始いたします。',
            date: '2025-07-30',
            category: 'service',
            isImportant: false
        },
        {
            id: 7,
            title: '夏季休業のお知らせ',
            content: '8月13日（火）から8月16日（金）まで夏季休業とさせていただきました。',
            date: '2025-08-05',
            category: 'notice',
            isImportant: true
        },
        {
            id: 8,
            title: 'AI活用セミナー追加開催のお知らせ',
            content: '10月15日開催の「中小企業のためのAI活用セミナー」は、おかげさまで満席となりました。',
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
            content: '筑邦銀行のシンクタンクとして設立された地域経済研究所です。',
            lastUpdated: '2025-08-01'
        },
        services: {
            title: 'サービス一覧',
            content: '経済調査レポートの発行、経営コンサルティング、各種セミナーの開催、企業研修など。',
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
};

// Add test data to localStorage
const STORAGE_KEY = 'cms_mock_data';
localStorage.setItem(STORAGE_KEY, JSON.stringify(testData));

// Summary
const seminarsOngoing = testData.seminars.filter(s => s.status === 'ongoing').length;
const seminarsScheduled = testData.seminars.filter(s => s.status === 'scheduled').length;
const seminarsCompleted = testData.seminars.filter(s => s.status === 'completed').length;
const noticesImportant = testData.notices.filter(n => n.isImportant).length;

console.log('✅ Test data has been successfully added to localStorage!');
console.log('');
console.log('📊 Added data summary:');
console.log(`- Seminars: ${testData.seminars.length} items`);
console.log(`  - Ongoing: ${seminarsOngoing}`);
console.log(`  - Scheduled: ${seminarsScheduled}`);
console.log(`  - Completed: ${seminarsCompleted}`);
console.log(`- Publications: ${testData.publications.length} items`);
console.log(`- Notices: ${testData.notices.length} items`);
console.log(`  - Important: ${noticesImportant}`);
console.log(`- Media files: ${testData.media.length} items`);
console.log(`- Members: ${testData.members.length} items`);
console.log(`- Pages: ${Object.keys(testData.pages).length} items`);
console.log('');
console.log('🔄 Please refresh the page to see the updated data on HomePage.vue');

// Function to reload the current page
console.log('🔄 Refreshing page in 3 seconds to show new data...');
setTimeout(() => {
    window.location.reload();
}, 3000);