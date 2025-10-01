<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

/**
 * Migrate Transaction Law page to section-based content (texts/htmls) without changing design.
 * - Fills known keys used by TransactionLawPage.vue
 * - Preserves existing values; only supplies defaults where missing
 * - Clears content.html so the page renders section-based content
 */
class TransactionLawSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'transaction-law';

        // Text keys (short strings)
        $defaultsTexts = [
            'page_title' => '特定商取引法に関する表記',
            'page_subtitle' => 'transaction law',

            'seller_label' => '販売業者',
            'seller_value' => '株式会社 ちくぎん地域経済研究所',

            'rep_label' => '代表者名',
            'rep_value' => '代表取締役社長　空閑 重信',

            'addr_label' => '住所',

            'tel_label' => '電話番号',
            'tel_value' => '0942-46-5081 (平日9:00～17:00)',

            'fax_label' => 'FAX番号',
            'fax_value' => '0942-38-7631',

            'mail_label' => 'メール',
            'mail_value' => 'info@chikugin-ri.co.jp',

            'contact_cta' => 'お問い合わせはこちら',

            'fee_label' => '料金',
            'fee_section_title' => '■ ちくぎん地域経済クラブ',
            'fee_standard_label' => 'スタンダード会員',
            'fee_standard_amount' => '月額 1,000円（消費税別）',
            'fee_premium_label' => 'プレミアムネット会員',
            'fee_premium_amount' => '月額 3,000円（消費税別）',

            'payment_label' => '支払い時期および方法',

            'otherfees_label' => 'その他料金',
            'otherfees_value' => '口座振込：振込手数料',

            'service_time_label' => '提供時間',
            'service_time_value' => '所定の手続きの終了後、直ちにご利用いただけます。',

            'cancel_label' => '退会について',
            'cancel_value' => 'ご利用の停止、退会については、退会希望月の前月末までに、お電話または筑邦銀行窓口に直接お申し出ください。',

            'refund_label' => '返金について',
            'terms_note' => '会員サービスについては、会員規約も合わせてご確認ください。',

            'article1_title' => '第1条(目的）',
            'article1_body'  => '「ちくぎん地域経済クラブ」（以下、「本会」という）は、株式会社ちくぎん地域経済研究所（以下、「当社」という）が運営するサービスであり、産・官・学・金（金融機関）のネットワーク構築や会員相互の交流等を通じて、企業経営等に役立つ様々な情報や機会提供により、会員企業等がともに発展し、ひいては地域の振興・発展に寄与することを目的とします。',

            'article2_title' => '第2条(会員）',
            'article3_title' => '第3条(会員種別および会員サービス）',
            'article3_body'  => '本会の会員は、スタンダード会員とプレミアムネット会員の2種類とし、その会員種別に応じた次のサービス（以下、「会員サービス」という）を利用できるものとします。',
        ];

        // HTML body chunks for rich areas
        $defaultsHtmls = [
            'addr_value'   => '〒839-0864<br>福岡県久留米市百年公園1番1号<br>久留米リサーチセンタービル6階',
            'fee_desc'     => '会費は下記に定める金額とします。<br>（法人会員については個人の登録１名あたりの金額）',
            'payment_body' => '会費の納入は入会月の翌月から口座振替によって、毎月20日（休日の場合は翌営業日）に会員種別ごとの月額会費を納入することとします。<br>口座振替を利用しない場合は、毎年4月20日（休日の場合は翌営業日）までに年会費（毎年4月1日～翌年3月31日分）を一括納入することとします。',
            'refund_body'  => '退会または除名された会員がすでに納入した会費は返還しないものとします。<br>ただし、会員規約の変更に伴い退会する会員で、退会月の翌月以降の先払分会費がある場合は、当該会費を返還することとします。',
            'article2_body' => '本規約を了承のうえ当社所定の形式により入会の手続きをされた法人およびそれに準ずる団体、個人事業主または個人のうち、当社が会員入会を承認した方を本会の会員とします（以下、会員入会を承認した法人およびそれに準ずる団体または個人事業主の方を「法人会員」、会員入会を承認した個人の方を「個人会員」という）。なお、法人会員は、複数口の入会が可能です。<br>会員は、会員資格を第三者に利用させたり、貸与、譲渡、売買、質入等をすることはできないものとします。',
        ];

        $page = PageContent::where('page_key', $key)->first();

        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => '特定商取引法に関する表記',
                'content' => [
                    'texts' => $defaultsTexts,
                    'htmls' => $defaultsHtmls,
                    // 本文は空にしてセクション表示を有効化
                    'html'  => '',
                ],
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
        // 本文を空にしてセクション表示を優先（デザインはVue側で同一）
        $content['html'] = '';

        $page->update([
            'title' => $page->title ?: '特定商取引法に関する表記',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}

