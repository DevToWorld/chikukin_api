<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class AboutPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'about';

        $texts = [
            'page_title' => 'ちくぎん地域経済研究所について',
            'page_subtitle' => 'ABOUT US',
            'mission_title' => '産学官金のネットワークを活かした地域創生',
            // component expects mission_text (not mission_body)
            'mission_text' => '私たちは、産・官・学・金（金融機関）のネットワークを活用し、地域経済の持続的な発展と地域企業の成長を支援することを使命としています。',
            'philosophy_title' => '経営理念',
            'philosophy_subtitle' => 'OUR MISSION',
            'message_title' => 'ご挨拶',
            'message_subtitle' => 'MESSAGE',
            // 署名/ラベル（CompanyProfileの実装に準拠）
            'message_signature' => '株式会社ちくぎん地域経済研究所 代表取締役社長',
            'message_label' => 'MESSAGE',
            'company_title' => '会社概要',
            'company_subtitle' => 'COMPANY PROFILE',
            'history_title' => '沿革',
            'history_subtitle' => 'HISTORY',
            'staff_title' => '所属紹介',
            'staff_subtitle' => 'OUR TEAM',
            // CTA block
            'cta_block_title' => '株式会社ちくぎん地域経済研究所',
            'cta_block_body' => '地域の未来を共に考え、企業の成長をサポートします。',
            'cta_block_button' => 'お問い合わせはこちら',
            // Company table labels/values
            'company_label_name' => '会社名',
            'company_value_name' => '株式会社 ちくぎん地域経済研究所',
            'company_label_established' => '設立',
            'company_value_established' => '平成3年4月1日（1991年）',
            'company_label_capital' => '資本金',
            'company_value_capital' => '30,000千円',
            'company_label_address' => '所在地',
            'company_value_address' => '〒839-0864 福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階',
            'company_label_tel' => '電話番号',
            'company_value_tel' => 'TEL：0942-46-5081',
            'company_label_business_hours' => '営業時間',
            'company_value_business_hours' => '平日 9:00～17:00',
            // History
            'history_more' => 'さらに表示',
        ];
        
        $defaultHtmls = [
            'message_body' => '<p>変化の激しい経済環境の中で、企業が持続的な成長を遂げるためには、正確な情報と深い洞察に基づく戦略的な意思決定が不可欠です。</p><p>私たちちくぎん地域経済研究所は、長年培ってきた調査研究の知見と幅広いネットワークを活用し、地域の皆様の課題解決と成長を全力でサポートしてまいります。</p><p>産・官・学・金の連携を深め、地域経済の活性化に貢献することで、九州地域の明るい未来を共に創造していきたいと考えております。</p><div class="message-signature"><p>株式会社ちくぎん地域経済研究所</p><p>代表取締役社長</p></div>'
        ];

        $content = [ 'texts' => $texts, 'images' => [], 'htmls' => $defaultHtmls ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '会社概要',
                'content' => $content,
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $existing = $page->content ?? [];
        if (!is_array($existing)) $existing = ['html' => (string)$existing];
        $existing['texts'] = array_merge($texts, $existing['texts'] ?? []);
        $htmls = isset($existing['htmls']) && is_array($existing['htmls']) ? $existing['htmls'] : [];
        $existing['htmls'] = array_merge($defaultHtmls, $htmls);

        // privacy型との整合: content.html が空なら簡易本文を合成
        $existingHtml = isset($existing['html']) && is_string($existing['html']) ? trim($existing['html']) : '';
        if ($existingHtml === '') {
            $t = $existing['texts'];
            $h = $existing['htmls'];
            $parts = [];
            $msgTitle = isset($t['message_title']) && $t['message_title'] !== '' ? $t['message_title'] : 'ご挨拶';
            $parts[] = '<h2>' . htmlspecialchars($msgTitle, ENT_QUOTES, 'UTF-8') . '</h2>';
            if (!empty($h['message_body'])) { $parts[] = '<div>' . $h['message_body'] . '</div>'; }
            $compiled = implode("\n\n", $parts);
            $existing['html'] = $compiled;
            $existing['htmls'] = array_merge($existing['htmls'] ?? [], ['body' => $compiled]);
        }

        $page->update(['title' => $page->title ?: '会社概要', 'content' => $existing]);
    }
}
