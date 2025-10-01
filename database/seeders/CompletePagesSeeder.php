<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompletePagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // 1. ホームページ
            [
                'page_key' => 'home',
                'title' => 'ホーム',
                'content' => [
                    'hero' => [
                        'title' => 'ちくぎん地域経済研究所',
                        'subtitle' => '地域経済の発展に貢献します',
                        'description' => '最新の経済データと分析レポートを提供し、地域の成長をサポートします'
                    ],
                    'features' => [
                        ['title' => '経済統計', 'description' => 'リアルタイムデータ'],
                        ['title' => '調査レポート', 'description' => '専門分析'],
                        ['title' => 'セミナー', 'description' => '知識共有']
                    ]
                ],
                'meta_description' => 'ちくぎん地域経済研究所のホームページ。地域経済の発展に貢献する研究機関です。',
                'meta_keywords' => '地域経済,経済研究所,ちくぎん,福岡',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 2. 会社概要
            [
                'page_key' => 'about',
                'title' => '会社概要',
                'content' => [
                    'company_info' => [
                        'name' => '株式会社ちくぎん地域経済研究所',
                        'established' => '1985年4月',
                        'capital' => '1億円',
                        'employees' => '25名',
                        'address' => '福岡県福岡市中央区天神2-13-1'
                    ],
                    'mission' => '地域経済の発展に貢献する研究機関として、正確で信頼性の高いデータと分析を提供します'
                ],
                'meta_description' => 'ちくぎん地域経済研究所の会社概要、理念、沿革などをご紹介します。',
                'meta_keywords' => '会社概要,理念,沿革,ちくぎん地域経済研究所',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 3. ちくぎん地域経済研究所について
            [
                'page_key' => 'about-institute',
                'title' => 'ちくぎん地域経済研究所について',
                'content' => [
                    'description' => '地域経済の発展に貢献する研究機関の詳細情報',
                    'sections' => ['設立の経緯', '研究方針', '組織体制', '活動実績']
                ],
                'meta_description' => 'ちくぎん地域経済研究所の詳細情報をご紹介します。',
                'meta_keywords' => '研究所,研究機関,地域経済,設立経緯',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 4. サービス案内
            [
                'page_key' => 'services',
                'title' => 'サービス案内',
                'content' => [
                    'services' => [
                        ['title' => '経済調査', 'description' => '地域経済の動向分析'],
                        ['title' => 'コンサルティング', 'description' => '経営課題の解決サポート'],
                        ['title' => 'データ分析', 'description' => '統計データの専門分析']
                    ]
                ],
                'meta_description' => 'ちくぎん地域経済研究所が提供するサービスの詳細をご紹介します。',
                'meta_keywords' => 'サービス,経済調査,コンサルティング,データ分析',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 5. お知らせ
            [
                'page_key' => 'news',
                'title' => 'お知らせ',
                'content' => [
                    'description' => '研究所からのお知らせや最新情報をお届けします',
                    'categories' => ['お知らせ', '重要', 'イベント', 'メディア']
                ],
                'meta_description' => 'ちくぎん地域経済研究所からのお知らせや最新情報をご覧いただけます。',
                'meta_keywords' => 'お知らせ,お知らせ,イベント,最新情報',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 6. よくある質問
            [
                'page_key' => 'faq',
                'title' => 'よくある質問',
                'content' => [
                    'description' => 'よくいただくご質問とその回答をまとめています',
                    'faqs' => [
                        ['question' => 'サービスの料金体系を教えてください', 'answer' => '各サービスによって料金体系が異なります'],
                        ['question' => 'セミナーの参加方法は？', 'answer' => 'セミナーページから参加申し込みが可能です']
                    ]
                ],
                'meta_description' => 'ちくぎん地域経済研究所に寄せられるよくある質問とその回答をまとめています。',
                'meta_keywords' => 'FAQ,よくある質問,Q&A',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 7. 刊行物（会員）
            [
                'page_key' => 'publications',
                'title' => '刊行物（会員）',
                'content' => [
                    'description' => '会員限定の刊行物をご覧いただけます',
                    'categories' => ['調査レポート', '定期刊行物', '特別企画', '統計資料']
                ],
                'meta_description' => 'ちくぎん地域経済研究所が発行する会員限定の刊行物の一覧をご覧いただけます。',
                'meta_keywords' => '刊行物,会員限定,調査レポート,定期刊行物',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 8. 刊行物（一般）
            [
                'page_key' => 'publications-public',
                'title' => '刊行物（一般）',
                'content' => [
                    'description' => '一般公開されている刊行物をご覧いただけます',
                    'categories' => ['一般公開レポート', '統計資料', '特集記事']
                ],
                'meta_description' => 'ちくぎん地域経済研究所が発行する一般公開の刊行物の一覧をご覧いただけます。',
                'meta_keywords' => '刊行物,一般公開,統計資料,特集記事',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 9. セミナー
            [
                'page_key' => 'seminars',
                'title' => 'セミナー',
                'content' => [
                    'description' => '経済・経営に関するセミナーを定期的に開催しています',
                    'types' => ['経済動向', '経営戦略', '地域活性化', 'DX推進']
                ],
                'meta_description' => 'ちくぎん地域経済研究所が開催するセミナーの情報をご覧いただけます。',
                'meta_keywords' => 'セミナー,経済,経営,地域活性化',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 10. 受付中のセミナー
            [
                'page_key' => 'seminars-current',
                'title' => '受付中のセミナー',
                'content' => [
                    'description' => '現在申し込み受付中のセミナー一覧です',
                    'status' => '受付中',
                    'features' => ['オンライン参加可能', '録画配信あり', '資料ダウンロード']
                ],
                'meta_description' => '現在申し込み受付中のセミナー一覧をご覧いただけます。',
                'meta_keywords' => 'セミナー,受付中,申し込み,オンライン',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 11. 過去のセミナー
            [
                'page_key' => 'seminars-past',
                'title' => '過去のセミナー',
                'content' => [
                    'description' => '過去に開催されたセミナーの一覧です',
                    'status' => '終了',
                    'features' => ['録画配信', '資料ダウンロード', '参加者限定']
                ],
                'meta_description' => '過去に開催されたセミナーの一覧をご覧いただけます。',
                'meta_keywords' => 'セミナー,過去,録画,資料',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 12. 用語集
            [
                'page_key' => 'glossary',
                'title' => '用語集',
                'content' => [
                    'description' => '経済・経営に関する専門用語の解説です',
                    'categories' => ['経済用語', '経営用語', '統計用語', '政策用語']
                ],
                'meta_description' => '経済・経営に関する専門用語の解説をご覧いただけます。',
                'meta_keywords' => '用語集,専門用語,経済用語,経営用語',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 13. 経済指標一覧
            [
                'page_key' => 'economic-indicators',
                'title' => '経済指標一覧',
                'content' => [
                    'description' => '地域経済の主要指標を一覧でご覧いただけます',
                    'categories' => ['GDP', '雇用統計', '物価指数', '景気動向']
                ],
                'meta_description' => '地域経済の主要指標を一覧でご覧いただけます。',
                'meta_keywords' => '経済指標,GDP,雇用統計,物価指数',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 14. 経済・調査統計
            [
                'page_key' => 'statistics',
                'title' => '経済・調査統計',
                'content' => [
                    'description' => '地域経済の各種統計データを提供しています',
                    'categories' => ['地域別統計', '産業別統計', '時系列データ', '比較分析']
                ],
                'meta_description' => '地域経済の各種統計データをご覧いただけます。',
                'meta_keywords' => '経済統計,統計データ,地域経済,分析',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 15. 特定商取引法に関する表記
            [
                'page_key' => 'transaction-law',
                'title' => '特定商取引法に関する表記',
                'content' => [
                    'description' => '特定商取引法に基づく表記です',
                    'sections' => ['事業者の名称', '代表者', '所在地', '連絡先', '取引条件']
                ],
                'meta_description' => '特定商取引法に基づく表記をご覧いただけます。',
                'meta_keywords' => '特定商取引法,表記,事業者情報,取引条件',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 16. プライバシーポリシー
            [
                'page_key' => 'privacy-policy',
                'title' => 'プライバシーポリシー',
                'content' => [
                    'description' => '個人情報の取り扱いについて定めています',
                    'sections' => ['個人情報の収集', '利用目的', '第三者提供', '安全管理']
                ],
                'meta_description' => 'ちくぎん地域経済研究所のプライバシーポリシーです。',
                'meta_keywords' => 'プライバシーポリシー,個人情報,利用規約',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 17. 利用規約
            [
                'page_key' => 'terms-of-service',
                'title' => '利用規約',
                'content' => [
                    'description' => 'サービスの利用に関する規約です',
                    'sections' => ['利用条件', '禁止事項', '免責事項', '規約変更']
                ],
                'meta_description' => 'ちくぎん地域経済研究所の利用規約です。',
                'meta_keywords' => '利用規約,利用条件,免責事項',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 18. CRI経営コンサルティング
            [
                'page_key' => 'cri-consulting',
                'title' => 'CRI経営コンサルティング',
                'content' => [
                    'description' => '経営課題の解決をサポートするコンサルティングサービス',
                    'services' => ['経営戦略', '組織改革', '業務改善', 'DX推進']
                ],
                'meta_description' => '経営課題の解決をサポートするCRI経営コンサルティングの詳細をご紹介します。',
                'meta_keywords' => 'コンサルティング,経営戦略,組織改革,業務改善',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 19. お問い合わせ
            [
                'page_key' => 'contact',
                'title' => 'お問い合わせ',
                'content' => [
                    'contact_info' => [
                        'phone' => '092-123-4567',
                        'email' => 'info@chikugin-cri.co.jp',
                        'address' => '福岡県福岡市中央区天神2-13-1',
                        'business_hours' => '平日9:00〜17:00'
                    ]
                ],
                'meta_description' => 'ちくぎん地域経済研究所へのお問い合わせはこちらからどうぞ。',
                'meta_keywords' => 'お問い合わせ,連絡先,フォーム',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 20. サイトマップ
            [
                'page_key' => 'sitemap',
                'title' => 'サイトマップ',
                'content' => [
                    'description' => 'サイト内のページ一覧です',
                    'sections' => ['メインページ', 'サービス', '情報', 'サポート']
                ],
                'meta_description' => 'ちくぎん地域経済研究所のサイトマップです。',
                'meta_keywords' => 'サイトマップ,ページ一覧,ナビゲーション',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 21. 決算報告
            [
                'page_key' => 'financial-reports',
                'title' => '決算報告',
                'content' => [
                    'description' => '研究所の決算報告書をご覧いただけます',
                    'categories' => ['年度別決算', '財務諸表', '事業報告', '監査報告']
                ],
                'meta_description' => 'ちくぎん地域経済研究所の決算報告書をご覧いただけます。',
                'meta_keywords' => '決算報告,財務諸表,事業報告,監査報告',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 22. 入会案内
            [
                'page_key' => 'membership',
                'title' => '入会案内',
                'content' => [
                    'description' => '会員制度の詳細と入会方法をご案内します',
                    'plans' => ['ベーシック', 'スタンダード', 'プレミアム'],
                    'benefits' => ['情報閲覧', 'セミナー割引', '優先案内']
                ],
                'meta_description' => 'ちくぎん地域経済研究所の会員制度についてご案内します。',
                'meta_keywords' => '会員制度,入会案内,会員特典,登録方法',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 23. プレミアム会員の特典
            [
                'page_key' => 'membership-premium',
                'title' => 'プレミアム会員の特典',
                'content' => [
                    'description' => 'プレミアム会員限定の特典をご紹介します',
                    'benefits' => ['全コンテンツ閲覧', '優先セミナー参加', '個別相談', '特別レポート']
                ],
                'meta_description' => 'プレミアム会員限定の特典をご紹介します。',
                'meta_keywords' => 'プレミアム会員,特典,限定サービス,個別相談',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 24. マイアカウント
            [
                'page_key' => 'my-account',
                'title' => 'マイアカウント',
                'content' => [
                    'description' => '会員様専用のアカウント管理ページです',
                    'features' => ['プロフィール管理', '会員情報更新', '利用履歴', '設定変更'],
                    'labels' => [
                        'tabs' => [
                            'profile' => 'アカウント情報',
                            'seminars' => 'セミナー',
                            'seminarFavorites' => 'セミナーお気に入り',
                            'registrations' => '申込状況',
                            'membership' => '会員プラン',
                            'downloads' => 'ダウンロード履歴',
                            'favorites' => 'お気に入り',
                            'settings' => '設定'
                        ],
                        'headings' => [
                            'profile' => 'アカウント情報',
                            'seminars' => 'セミナー一覧',
                            'seminarFavorites' => 'お気に入りセミナー',
                            'registrations' => '申込状況',
                            'membership' => '会員プラン',
                            'downloads' => 'ダウンロード履歴',
                            'favoriteMembers' => 'お気に入り会員',
                            'settings' => '設定'
                        ],
                        'buttons' => [
                            'editProfile' => 'プロフィールを編集',
                            'changePassword' => 'パスワード変更',
                            'save' => '保存する',
                            'saving' => '保存中...',
                            'cancel' => 'キャンセル',
                            'submit' => '送信する',
                            'submitting' => '送信中...',
                            'upgrade' => 'プランをアップグレード',
                            'redownload' => '再ダウンロード',
                            'viewDirectory' => '会員名簿を見る',
                            'goFavorites' => 'お気に入り一覧ページへ',
                            'detail' => '詳細',
                            'remove' => '削除'
                        ],
                        'texts' => [
                            'upgradeNotice' => 'より多くのコンテンツにアクセスしたい場合は、プランをアップグレードしてください。'
                        ],
                        'empty' => [
                            'favoriteMembersTitle' => 'お気に入り会員はまだありません',
                            'favoriteMembersText' => '会員名簿からお気に入りの会員を登録してみましょう。'
                        ],
                        'tables' => [
                            'seminars' => [
                                'thDate' => '日付', 'thTitle' => 'タイトル', 'thVenue' => '会場', 'thStatus' => 'ステータス', 'thActions' => '操作',
                                'btnReserve' => '予約する', 'empty' => '該当するセミナーがありません'
                            ],
                            'seminarFavorites' => [
                                'thDate' => '日付', 'thTitle' => 'タイトル', 'thStatus' => 'ステータス', 'thFavoritedAt' => 'お気に入り日時',
                                'btnRemove' => '削除', 'empty' => 'お気に入りセミナーはありません'
                            ],
                            'registrations' => [
                                'thDate' => '日付', 'thTitle' => 'タイトル', 'thNumber' => '申込番号', 'thApproval' => '承認状態', 'thSeminarStatus' => '開催状態',
                                'empty' => '申込履歴はありません'
                            ]
                        ]
                    ]
                ],
                'meta_description' => '会員様専用のアカウント管理ページです。',
                'meta_keywords' => 'マイアカウント,会員管理,プロフィール,利用履歴',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 25. プランアップグレード
            [
                'page_key' => 'upgrade',
                'title' => 'プランアップグレード',
                'content' => [
                    'description' => '会員プランのアップグレード手続きです',
                    'plans' => ['ベーシック→スタンダード', 'スタンダード→プレミアム'],
                    'benefits' => ['特典拡充', 'サービス向上', 'サポート強化']
                ],
                'meta_description' => '会員プランのアップグレード手続きをご案内します。',
                'meta_keywords' => 'プランアップグレード,会員プラン,特典拡充,サービス向上',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 26. 会員ログイン
            [
                'page_key' => 'login',
                'title' => '会員ログイン',
                'content' => [
                    'description' => '会員様専用のログインページです',
                    'features' => ['ログイン', 'パスワードリセット', '新規登録', 'ログアウト']
                ],
                'meta_description' => '会員様専用のログインページです。',
                'meta_keywords' => '会員ログイン,ログイン,パスワードリセット,新規登録',
                'is_published' => true,
                'published_at' => now(),
            ],

            // 27. メディアレジストリ（Hero等の画像キー保管用）
            [
                'page_key' => 'media',
                'title' => 'メディアレジストリ',
                'content' => [
                    'images' => [
                        // 共有される非KV画像のみを登録（Heroは各ページで管理）
                        'company_profile_philosophy' => '/img/hero-image.png',
                        'company_profile_message' => '/img/hero-image.png',
                        'company_profile_staff_morita' => '/img/hero-image.png',
                        'company_profile_staff_mizokami' => '/img/hero-image.png',
                        'company_profile_staff_kuga' => '/img/hero-image.png',
                        'company_profile_staff_takada' => '/img/hero-image.png',
                        'company_profile_staff_nakamura' => '/img/hero-image.png',
                        // CompanyProfile レスポンシブ
                        'company_profile_philosophy_mobile' => '/img/hero-image.png',
                        'company_profile_philosophy_tablet' => '/img/hero-image.png',
                        'company_profile_message_mobile' => '/img/hero-image.png',
                        'company_profile_message_tablet' => '/img/hero-image.png',
                        'company_profile_staff_morita_mobile' => '/img/hero-image.png',
                        'company_profile_staff_morita_tablet' => '/img/hero-image.png',
                        'company_profile_staff_mizokami_mobile' => '/img/hero-image.png',
                        'company_profile_staff_mizokami_tablet' => '/img/hero-image.png',
                        'company_profile_staff_kuga_mobile' => '/img/hero-image.png',
                        'company_profile_staff_kuga_tablet' => '/img/hero-image.png',
                        'company_profile_staff_takada_mobile' => '/img/hero-image.png',
                        'company_profile_staff_takada_tablet' => '/img/hero-image.png',
                        'company_profile_staff_nakamura_mobile' => '/img/hero-image.png',
                        'company_profile_staff_nakamura_tablet' => '/img/hero-image.png',
                        // その他のセクション背景など
                        'contact_section_bg' => '/img/-----1-1.png',
                        'contact_section_bg_mobile' => '/img/-----1-1.png',
                        'contact_section_bg_tablet' => '/img/-----1-1.png'
                    ]
                ],
                'meta_description' => 'サイト内で使用するメディア参照のレジストリ（非KV共有画像）。',
                'meta_keywords' => 'メディア,画像',
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($pages as $page) {
            // 既存のページがある場合は更新、ない場合は作成
            PageContent::updateOrCreate(
                ['page_key' => $page['page_key']],
                $page
            );
        }

        $this->command->info('26ページ分のデータが正常に登録されました。');
    }
}
