<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class MembershipPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'membership';
        $texts = [
            'page_title' => '会員サービス',
            'page_subtitle' => 'membership',
            'cta_primary' => 'お問い合わせ',
            'cta_secondary' => '入会はこちら',
            // Intro section
            'intro_title' => 'ご入会に際しまして',
            'intro_text' => 'ちくぎん地域経済研究所では各種サービスを気軽にご利用いただけるよう、会員制度「ちくぎん地域経済クラブ」を設けております。会員の皆さまには地域経済の情報誌「ちくぎん地域経済レポート」をお届けするとともに、企業経営に関する各種サービスの提供や講演会・セミナーの案内など、御社のビジネスをバックアップします。',
            // Services section headings
            'services_title' => '会員が受けられる主なサービス内容',
            'services_label' => 'service',
            // Categories
            'premium_category_title' => 'プレミアサービス',
            'standard_premium_category_title' => 'スタンダード　&　プレミアサービス',
            // Premium service 1
            'premium_service1_tag' => 'consultation',
            'premium_service1_name' => '日経トップリーダー',
            // Standard services (1..5)
            'standard_service1_tag' => 'consultation',
            'standard_service1_name' => 'ビジネスセミナー',
            'standard_service2_tag' => 'problem',
            'standard_service2_name' => 'マッチング',
            'standard_service3_tag' => 'problem',
            'standard_service3_name' => '経済統計指標DL',
            'standard_service4_tag' => 'consultation',
            'standard_service4_name' => '地域経済統計レポート閲覧',
            'standard_service5_tag' => 'problem',
            'standard_service5_name' => '経営相談',
            // Membership info (table)
            'membership_info_title' => '会員サービスについて',
            'membership_benefits_label' => '会員のメリット',
            'membership_benefits_text' => '当研究所では、会員の皆さまに、企業経営に必要な各種情報、人材育成のための各種サービスを提供するとともに、経営相談に応じています。会員の皆さまは、各種サービスを無料または会員価格でご利用になれます。',
            'membership_fee_label' => '月会費',
            'membership_fee_text' => '・スタンダード 1,000円（税別） ・プレミアム 3,000円（税別）',
            // Flow
            'flow_title' => '入会までの流れ',
            'flow_label' => 'flow of joining',
            'step1_title' => '当事務所、もしくは最寄りの筑邦銀行の窓口でお申し込み',
            'step1_desc' => '入会申込書に必要事項をご記入いただきましたら、当研究所・筑邦銀行の窓口にて直接ご提出ください。<br>・筑邦銀行とのお取引がない方もお申し込みいただけます。',
            'step2_title' => '入会に際しての簡単な審査',
            'step2_desc' => '※審査完了後、当事務所よりメールで審査結果が届きます。',
            'step3_title' => '審査完了後、当事務所指定の銀行口座へ月会費のお支払い',
            'step4_title' => '支払い完了後の確認後、会員IDとパスワードを発行しメールでご連絡します。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '会員サービス',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: '会員サービス', 'content' => $content]);
    }
}
