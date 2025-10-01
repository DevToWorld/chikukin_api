<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class StandardMembershipPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'standard-membership';

        $texts = [
            'page_title' => 'スタンダード会員',
            'page_subtitle' => 'standard membership',

            // Intro
            'intro_title' => 'スタンダード会員について',
            'intro_text' => 'スタンダード会員は、各種情報やセミナー割引など、ビジネスに役立つサービスを手軽にご利用いただけるプランです。',

            // Services
            'services_title' => 'スタンダード会員の主なサービス',
            'services_label' => 'services',
            'standard_category_title' => 'スタンダードサービス',
            'standard_service1_tag' => 'seminar',
            'standard_service1_name' => 'ビジネスセミナー割引',
            'standard_service2_tag' => 'matching',
            'standard_service2_name' => 'マッチング支援',
            'standard_service3_tag' => 'report',
            'standard_service3_name' => '経済統計指標DL',
            'standard_service4_tag' => 'report',
            'standard_service4_name' => '地域経済レポート閲覧',
            'standard_service5_tag' => 'consultation',
            'standard_service5_name' => '経営相談',

            // Membership info
            'membership_info_title' => '会員サービスについて',
            'membership_benefits_label' => '会員のメリット',
            'membership_benefits_text' => '企業経営に必要な各種情報、人材育成のための各種サービスをご提供します。各種サービスは無料または会員価格でご利用になれます。',
            'membership_fee_label' => '月会費',
            'membership_fee_text' => 'スタンダード 1,000円（税別）',

            // Flow
            'flow_title' => '入会までの流れ',
            'flow_label' => 'flow of joining',
            'step1_title' => 'お申し込み',
            'step1_desc' => '入会申込書に必要事項をご記入の上、当研究所・筑邦銀行の窓口にてご提出ください。',
            'step2_title' => '審査',
            'step2_desc' => '審査完了後、メールで結果をご連絡します。',
            'step3_title' => '会費のお支払い',
            'step4_title' => '会員IDの発行（メール連絡）',

            // CTA
            'cta_primary' => '会員についてお問い合わせ',
            'cta_secondary' => '入会はこちら',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'スタンダード会員',
                'content' => [ 'texts' => $texts ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);

        $page->update([
            'title' => $page->title ?: 'スタンダード会員',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}

