<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PrivacyPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'privacy';

        $texts = [
            'page_title' => 'プライバシーポリシー',
            'page_subtitle' => 'privacy policy',
        ];

        // 既存のコンポーネントに合わせ、本文は content.html に格納
        $html = <<<HTML
<h3>適切な取得</h3>
<p>当研究所では、当研究所の各種サービスをご利用いただくにあたって、個人の情報をお伺いする場合があります。お伺いする情報としては、会社名、役職、氏名、Ｅメールアドレス、電話番号などの個人情報が主なものとなります。また、それ以外にもアンケート調査のため、質問させていただくこともございます。これらの情報は、すべて下記の収集目的に従って、適法かつ公正な手段により収集されます。</p>

<h3>個人情報を収集・利用する目的</h3>
<ul>
  <li>サービスの提供、キャンペーンなどを行うため</li>
  <li>サービスに関する情報またはキャンペーン情報を提供するため</li>
  <li>お客様から寄せられたご意見・ご要望にお答えするため</li>
  <li>サービスの開発・改善を目的とした調査・検討を行うため</li>
  <li>サービスに関する統計的資料を作成するため</li>
  <li>各種お問い合わせ対応のため</li>
</ul>

<h3>情報の第三者への開示について</h3>
<p>法令に基づく場合、生命・身体・財産の保護が必要な場合、公共の利益に必要な場合、権利の保護が必要な場合、業務委託先に必要な範囲で開示する場合に限り、第三者へ開示することがあります。</p>

<h3>個人情報の訂正および削除</h3>
<p>当研究所所定の手続きにより、保有個人情報の確認・訂正・削除を請求できます。詳細はお問い合わせフォームよりご連絡ください。</p>

<h3>免責事項</h3>
<p>当研究所は、当ウェブサイトに掲載された情報の正確性・有用性について保証するものではなく、当該情報の利用によって生じたいかなる損害についても一切の責任を負いません。</p>
HTML;

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'プライバシーポリシー',
                'content' => [ 'texts' => $texts, 'html' => $html ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $content['html'] = $content['html'] ?? $html;

        $page->update([
            'title' => $page->title ?: 'プライバシーポリシー',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}

