<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class TermsPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'terms';

        $defaultsTexts = [
            'page_title' => '利用規約',
            'page_subtitle' => 'terms of service',
            'copyright_title' => '著作権等について',
            'link_title' => 'リンクについて',
            'disclaimer_title' => '免責事項',
            'security_title' => 'セキュリティについて',
            'cookie_title' => 'クッキー(Cookie)について',
            'environment_title' => 'ご利用環境について',
            'prohibited_title' => '禁止される行為',
            'article8_title' => '第8条（利用規約の変更）',
        ];

        $defaultsHtmls = [
            'intro' => 'このウェブサイトhttp://www.chikugin-ri.co.jp/（以下｢当ウェブサイト｣といいます）は、株式会社 ちくぎん地域経済研究所（以下｢当研究所｣といいます）が運営しています。<br>ご利用にあたっては、以下の注意事項等をお読みになり、ご了解いただいたうえでご利用ください。なお、当研究所は当ウェブサイトの利用条件を予告なしに変更することがありますので、最新の利用条件をご確認ください。',
            'copyright_body' => '当ウェブサイトに掲載された全ての内容（情報、商標、デザイン等）の著作権その他の知的財産権は、当研究所又は権限ある第三者に帰属します。したがって、それらを無断で使用、複製、改変することを禁じます。',
            'link_body' => '当ウェブサイトから、リンクやバナーによって他のウェブサイトへ移動できる場合があります。移動した先のウェブサイトは当研究所が運営するものではありません。したがって、その内容等について当研究所は責任を負いかねますのでご了承ください。',
            'disclaimer_body' => '当ウェブサイトに掲載された情報、また、当ウェブサイトを利用することで生じたいかなるトラブル、損失、損害に対して、当研究所は責任を負いません。<br>当ウェブサイトは、予告なしに運営の中断や、内容の変更を行うことがあります。これによって生じたいかなるトラブル、損失、損害に対しても、当研究所は責任を負いません。',
            'security_body' => '当ウェブサイトでは、不正アクセス、紛失、破壊、改ざん、漏えい等のトラブルを防止するために、セキュリティを高めるための取組みを継続的に実施していますが、かかる事故が一切発生しないことを保証するものではありません。',
            'cookie_body' => '当ウェブサイトでは、より良いサービス提供のためCookieを使用する場合があります。ブラウザの設定によりCookieの受け入れを拒否することも可能です。',
            'environment_body' => '当ウェブサイトの閲覧には、最新のブラウザ環境を推奨いたします。古いバージョンのブラウザでは正常に表示されない場合があります。',
            'prohibited_body' => '（1）営利の目的で（但し、当研究所との取引を除く）、当ウェブサイト又は当ウェブサイト上の情報を使用又は複製すること<br>（2）当ウェブサイト上の情報に何らかの改変を加えること<br>（3）当研究所の事前の同意を得ることなく当ウェブサイトへのリンクを行うこと<br>（4）当ウェブサイトのうち、一般からのアクセスが意図されていない部分にアクセスすること<br>（5）当ウェブサイトのコードを複製すること<br>（6）システムの脆弱性を試すこと又はセキュリティもしくは認証システムを破ること<br>（7）有害なコンピュータプログラム等を送信または書き込むこと<br>（8）当研究所または第三者を誹謗中傷し、またはその名誉・信用を害すること<br>（9）法令に違反する行為を行うこと<br>（10）その他当研究所が不適切と認める行為を行うこと',
            'article8_body' => '当研究所は、利用者に通知することなく、本サービスの内容を変更しまたは本サービスの提供を中止することができるものとし、これによって利用者に生じた損害について一切の責任を負いません。',
        ];

        $page = PageContent::where('page_key', $key)->first();

        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '利用規約',
                'content' => [
                    'texts' => $defaultsTexts,
                    'htmls' => $defaultsHtmls,
                ],
                'meta_description' => 'ちくぎん地域経済研究所の利用規約です。',
                'meta_keywords' => '利用規約,利用条件,免責事項',
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        // 非破壊マージ（既存優先、不足のみ補完）
        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }

        $texts = isset($content['texts']) && is_array($content['texts']) ? $content['texts'] : [];
        $htmls = isset($content['htmls']) && is_array($content['htmls']) ? $content['htmls'] : [];

        $content['texts'] = array_replace($defaultsTexts, $texts);
        $content['htmls'] = array_replace($defaultsHtmls, $htmls);

        // プライバシーポリシー型（全文HTML）への整合: content.html が空なら、セクションから本文を合成
        $existingHtml = isset($content['html']) && is_string($content['html']) ? trim($content['html']) : '';
        if ($existingHtml === '') {
            $t = $content['texts'];
            $h = $content['htmls'];
            // 合成の順序: intro -> 各セクション（h3 + 本文）
            $parts = [];
            if (!empty($h['intro'])) { $parts[] = '<p>' . $h['intro'] . '</p>'; }
            $sections = [
                ['copyright', '著作権等について'],
                ['link', 'リンクについて'],
                ['disclaimer', '免責事項'],
                ['security', 'セキュリティについて'],
                ['cookie', 'クッキー(Cookie)について'],
                ['environment', 'ご利用環境について'],
                ['prohibited', '禁止される行為'],
                ['article8', '第8条（利用規約の変更）'],
            ];
            foreach ($sections as [$key, $fallbackTitle]) {
                $titleKey = $key . '_title';
                $bodyKey  = $key . '_body';
                $title = isset($t[$titleKey]) && is_string($t[$titleKey]) && trim($t[$titleKey]) !== '' ? $t[$titleKey] : $fallbackTitle;
                $body  = isset($h[$bodyKey]) && is_string($h[$bodyKey]) ? $h[$bodyKey] : '';
                if ($title || $body) {
                    $parts[] = '<h3>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>';
                    if ($body !== '') { $parts[] = '<div>' . $body . '</div>'; }
                }
            }
            $compiled = implode("\n\n", $parts);
            $content['html'] = $compiled;
            // 互換: body キーでも参照できるように
            $content['htmls'] = array_replace($content['htmls'] ?? [], ['body' => $compiled]);
        }

        $page->update([
            'title' => $page->title ?: '利用規約',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
