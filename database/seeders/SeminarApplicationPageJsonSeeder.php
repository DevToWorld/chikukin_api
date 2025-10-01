<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class SeminarApplicationPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'seminar-application';

        $texts = [
            'page_title' => 'セミナー申し込み',
            'page_subtitle' => 'seminar application',
            'form_title' => 'セミナー申し込み',
            'form_label_subject' => '会員タイプ',
            'form_placeholder_select' => '選択してください',
            'form_option_standard' => 'スタンダード会員',
            'form_option_premium' => 'プレミアムネット会員',
            'form_option_inquiry' => '会員に関するお問い合わせ',
            'form_option_other' => 'その他',
            'form_label_name' => 'お名前',
            'form_label_kana' => 'ふりがな（全角ひらがな）',
            'form_label_company' => '会社名',
            'form_label_position' => '役職',
            'form_label_phone' => '電話番号',
            'form_label_email' => 'メールアドレス',
            'form_label_content' => '入会希望内容・特記事項',
            'terms_article1_title' => '第1条(目的）',
            'terms_article1_body' => '「ちくさん地域経済クラブ」（以下、「本会」という）は、株式会社ちくさん地域経済研究所（以下、「当社」という）が運営するサービスであり、産・食・学・金（金機関）のネットワーク等や会員相互の交流等を通じて、企業経営等に役立つ様々な情報や機会提供により、会員企業等がともに発展し、ひいては地域の振興・発展に貢献することを目的とします。',
            'terms_article2_title' => '第2条(会員）',
            'terms_article2_body' => '本規約を了承のうえ当社所定の形式により入会の手続きをされた法人およびそれに準ずる団体、個人事業主または個人のうち、当社が会員入会を承認した方を本会の会員とします（以下、会員入会を承認した法人およびそれに準ずる団体または個人事業主の方を「法人会員」、会員入会を承認した個人を「個人会員」という）。なお、法人会員は、複数口の入会が可能です。会員は、会員資格を第三者に利用させたり、貸与、譲渡、売買、質入等をすることはできないものとします。',
            'terms_article3_title' => '第3条(会員種別および会員サービス）',
            'terms_article3_intro' => '本会の会員は、スタンダード会員とプレミアムネット会員の2種類とし、その会員種別に応じた次のサービス（以下、「会員サービス」という）を利用できるものとします。',
            'standard_title' => '【スタンダード会員】',
            'standard_item1' => '機関誌「ちくぎん地域経済レポート」等、当社が発行する刊行物並びにダイレクトメール、E-Mail等による経済、産業、企業動向等、企業経営に役立つ情報サービス',
            'standard_item2' => '各種の経営相談に対する課題解決に向けた提案（相談の内容によっては有料）',
            'standard_item3' => '当社主催の各種セミナー、企画、イベント等の割引料金による案内',
            'standard_item4' => '当社が運営するインターネットサイト（スタンダード会員サイト）の利用',
            'premium_title' => '【プレミアムネット会員】',
            'premium_intro' => 'スタンダード会員が利用できる上記①から④までのサービスに加えて',
            'premium_item5' => 'プレミアムネット会員専用インターネットサイト（販路拡大等を目的としたビジネスマッチングサービスを含む）による企業経営に役立つ各種ビジネス情報の提供',
            'premium_item6' => '会員企業PR情報掲載サービス',
            'premium_item7' => '日経BP発刊「日経トップリーダー」の送付。同社主催の「日経トップリーダー 経営セミナー」の案内',
            'terms_download_label' => '会員規約をPDFでダウンロード',
            'form_privacy_note' => '弊社のプライバシーポリシー（個人情報保護方針）に同意をされる場合は、下記のチェックボックスにチェックを入れてください。',
            'form_privacy_agree' => '個人情報保護方針に同意します',
            'form_button_confirm' => '確認する',
        ];

        $page = PageContent::where('page_key', $key)->first();

        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => $texts['page_title'],
                'content' => ['texts' => $texts],
                'meta_description' => 'セミナー申し込みフォームです。',
                'meta_keywords' => 'セミナー,申し込み,参加',
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) {
            $content = ['html' => (string) $content];
        }

        $content['texts'] = array_merge($texts, $content['texts'] ?? []);

        $page->update([
            'title' => $page->title ?: $texts['page_title'],
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
