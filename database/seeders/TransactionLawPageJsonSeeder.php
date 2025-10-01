<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class TransactionLawPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'transaction-law';

        $texts = [
            'page_title' => '特定商取引法に関する表記',
            'page_subtitle' => 'transaction law',
        ];

        // 本ページは表形式が多く、管理しやすいよう HTML として保存
        $html = <<<HTML
<h3>事業者情報</h3>
<table>
  <tr><th>販売業者</th><td>株式会社 ちくぎん地域経済研究所</td></tr>
  <tr><th>代表者名</th><td>代表取締役社長　空閑 重信</td></tr>
  <tr><th>住所</th><td>〒839-0864 福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階</td></tr>
  <tr><th>電話番号</th><td>0942-46-5081 (平日9:00～17:00)</td></tr>
  <tr><th>FAX番号</th><td>0942-38-7631</td></tr>
  <tr><th>メール</th><td>info@chikugin-ri.co.jp</td></tr>
</table>

<h3>料金・支払い</h3>
<p>会費は会員種別により異なります。支払いは口座振替または年会費の一括納入となります。</p>

<h3>返品・キャンセル等</h3>
<p>サービスの性質上、原則としてお支払い後の返金はいたしません。</p>
HTML;

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '特定商取引法に関する表記',
                'content' => [ 'texts' => $texts, 'html' => $html, 'htmls' => ['body' => $html] ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        // 非破壊マージ（既存優先、不足のみ補完）
        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $existingHtml  = (isset($content['html']) && is_string($content['html']) && trim($content['html']) !== '') ? $content['html'] : '';
        $existingBody  = (isset($content['htmls']['body']) && is_string($content['htmls']['body']) && trim($content['htmls']['body']) !== '') ? $content['htmls']['body'] : '';
        $content['texts'] = array_replace($texts, $content['texts'] ?? []);
        $content['html'] = $existingHtml !== '' ? $existingHtml : $html;
        $content['htmls'] = array_replace($content['htmls'] ?? [], ['body' => ($existingBody !== '' ? $existingBody : ($existingHtml !== '' ? $existingHtml : $html))]);

        $page->update([
            'title' => $page->title ?: '特定商取引法に関する表記',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
