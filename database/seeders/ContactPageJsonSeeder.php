<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class ContactPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'contact';
        $texts = [
            'page_title' => 'お問い合わせ',
            'page_subtitle' => 'contact',
            'form_title' => 'お問い合わせ',
            // ContactSection (default cmsPageKey fallback is 'contact')
            'contact_title' => '株式会社ちくぎん地域経済研究所',
            'contact_label' => 'About us',
            'contact_subtitle' => '様々な分野の調査研究を通じ、企業活動などをサポートします。',
            'contact_cta' => 'お問い合わせはこちら',
            // Form labels/options/buttons
            'form_label_subject' => '件名',
            'form_placeholder_select' => '選択してください',
            'form_option_inquiry' => 'サービスに関するお問い合わせ',
            'form_option_membership' => '会員に関するお問い合わせ',
            'form_option_seminar' => 'セミナーに関するお問い合わせ',
            'form_option_other' => 'その他',
            'form_label_name' => 'お名前',
            'form_label_kana' => 'ふりがな（全角ひらがな）',
            'form_label_company' => '会社名',
            'form_label_position' => '役職',
            'form_label_phone' => '電話番号',
            'form_label_email' => 'メールアドレス',
            'form_label_content' => 'お問い合わせ内容',
            'form_privacy_note' => '弊社のプライバシーポリシー（個人情報保護方針）に同意をされる場合は、下記のチェックボックスにチェックを入れてください。',
            'form_privacy_agree' => '個人情報保護方針に同意します',
            'form_button_confirm' => '確認する',
            // Terms (Contact dedicated)
            'article1_title' => '第1条(目的）',
            'article1_body' => '「ちくぎん地域経済クラブ」（以下、「本会」という）は、株式会社ちくぎん地域経済研究所（以下、「当社」という）が運営するサービスであり、産・官・学・金（金融機関）のネットワーク構築や会員相互の交流等を通じて、企業経営に役立つ情報や機会提供により、会員企業等がともに発展し、地域の振興・発展に寄与することを目的とします。',
            'article2_title' => '第2条(会員）',
            'article3_title' => '第3条(会員種別および会員サービス）',
            'article3_body' => '本会の会員は、スタンダード会員とプレミアムネット会員の2種類とし、その会員種別に応じた次のサービス（以下、「会員サービス」という）を利用できます。',
            'standard_title' => '【スタンダード会員】',
            'standard_item1' => '機関誌「ちくぎん地域経済レポート」等…',
            'standard_item2' => '各種の経営相談に対する課題解決の提案（相談内容により有料）',
            'standard_item3' => '当社主催の各種セミナー等の割引料金による案内',
            'standard_item4' => '当社が運営するインターネットサイト（スタンダード会員サイト）の利用',
            'premium_title' => '【プレミアムネット会員】',
            'premium_intro' => 'スタンダード会員が利用できる上記①から④までのサービスに加えて',
            'premium_item5' => 'プレミアムネット会員専用インターネットサイト…各種ビジネス情報の提供',
            'premium_item6' => '会員企業PR情報掲載サービス',
            'premium_item7' => '日経BP発刊「日経トップリーダー」の送付。同社主催の「日経トップリーダー 経営セミナー」の案内',
            'terms_download_label' => '会員規約をPDFでダウンロード',
        ];

        $htmls = [
            'article2_body' => '本規約を了承のうえ当社所定の形式により入会の手続きをされた法人…<br>会員は、会員資格を第三者に利用させたり、貸与、譲渡、売買、質入等をすることはできないものとします。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'お問い合わせ',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $content['htmls'] = array_merge($htmls, $content['htmls'] ?? []);
        $page->update(['title' => $page->title ?: 'お問い合わせ', 'content' => $content]);
    }
}
