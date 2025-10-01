<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PrivacyPolicyFullPageSeeder extends Seeder
{
    public function run(): void
    {
        // 既存フロントは pageKey='privacy' を参照（必要なら環境変数で上書き）
        $key = env('PRIVACY_PAGE_KEY', 'privacy');

        // 本文（HTML） — フロントの既存CSSで装飾されるセマンティックタグ中心
        $html = <<<'HTML'
<div class="privacy-policy-content">
  <h1>プライバシーポリシー</h1>

  <p>株式会社ちくぎん地域経済研究所（以下「当研究所」といいます）は、お客様の個人情報の重要性を認識し、その保護に関して以下のとおり定めます。</p>

  <h2>1. 個人情報の収集</h2>
  <p>当研究所は、以下の場合に個人情報を収集することがあります：</p>
  <ul>
    <li>お問い合わせフォームからのご連絡時</li>
    <li>セミナー・イベントへのお申し込み時</li>
    <li>会員登録時</li>
    <li>各種サービスのご利用時</li>
  </ul>
  <p>収集する個人情報には、お名前、メールアドレス、電話番号、所属組織、住所などが含まれます。</p>

  <h2>2. 個人情報の利用目的</h2>
  <p>収集した個人情報は、以下の目的で利用いたします：</p>
  <ul>
    <li>お問い合わせへの回答</li>
    <li>サービスの提供および運営</li>
    <li>セミナー・イベントのご案内</li>
    <li>新サービスや重要なお知らせの通知</li>
    <li>アンケート調査の実施</li>
    <li>統計データの作成（個人を特定できない形で処理）</li>
  </ul>

  <h2>3. 個人情報の第三者提供</h2>
  <p>当研究所は、以下の場合を除き、お客様の同意なく第三者に個人情報を提供することはありません：</p>
  <ul>
    <li>法令に基づく場合</li>
    <li>人の生命、身体または財産の保護のために必要な場合</li>
    <li>公衆衛生の向上または児童の健全な育成の推進のために特に必要な場合</li>
    <li>国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合</li>
  </ul>

  <h2>4. 個人情報の管理</h2>
  <p>当研究所は、個人情報の正確性を保ち、安全に管理するため、以下の措置を講じています：</p>
  <ul>
    <li>セキュリティシステムの維持・管理</li>
    <li>アクセス権限の制限</li>
    <li>個人情報取扱いに関する従業員教育</li>
    <li>委託先の適切な監督</li>
  </ul>

  <h2>5. Cookieの使用について</h2>
  <p>当サイトでは、お客様により良いサービスを提供するため、Cookieを使用することがあります。Cookieの使用を希望されない場合は、ブラウザの設定により無効にすることができます。ただし、Cookieを無効にした場合、一部のサービスがご利用いただけなくなる可能性があります。</p>

  <h2>6. SSLの使用について</h2>
  <p>当サイトでは、個人情報を送信いただく際の安全性を確保するため、SSL（Secure Socket Layer）による暗号化通信を使用しています。</p>

  <h2>7. 個人情報の開示・訂正・削除</h2>
  <p>お客様ご本人から個人情報の開示、訂正、削除のご要望があった場合は、ご本人であることを確認の上、速やかに対応いたします。</p>

  <h2>8. プライバシーポリシーの変更</h2>
  <p>当研究所は、必要に応じて本プライバシーポリシーを変更することがあります。変更した場合は、当サイト上でお知らせいたします。</p>

  <h2>9. お問い合わせ</h2>
  <p>個人情報の取り扱いに関するお問い合わせは、以下までご連絡ください：</p>
  <address>
    株式会社ちくぎん地域経済研究所<br>
    〒830-0032 福岡県久留米市東町33-7<br>
    TEL: 0942-33-0915<br>
    E-mail: info@cri-chikugin.co.jp
  </address>

  <p class="update-date">最終更新日：2024年3月25日</p>
</div>
HTML;

        // 子コンポーネント文言（既存のCmsText参照キーに揃える）
        $texts = [
            'page_title'      => 'プライバシーポリシー',
            'page_subtitle'   => 'Privacy Policy',
            'intro'           => '株式会社ちくぎん地域経済研究所（以下「当研究所」といいます）は、お客様の個人情報の重要性を認識し、その保護に関して以下のとおり定めます。',

            'collection_title' => '個人情報の収集',
            'collection_body'  => '当研究所では、当研究所の各種サービスをご利用いただくにあたって、個人の情報をお伺いする場合があります。お伺いする情報としては、会社名、役職、氏名、Ｅメールアドレス、電話番号などの個人情報が主なものとなります。また、それ以外にもアンケート調査のため、質問させていただくこともございます。これらの情報は、すべて下記の収集目的に従って、適法かつ公正な手段により収集されます。',

            'purpose_title' => '個人情報の利用目的',
            'purpose_intro' => '当研究所が個人情報を収集または利用する目的は以下のとおりです。',
            'purpose_list' => 'サービスの提供、キャンペーンなどを行うため<br>サービスに関する情報またはキャンペーン情報を提供するため<br>お客様から寄せられたご意見、ご要望にお答えするため<br>サービスの開発・改善を目的とした調査・検討を行うため<br>サービスに関する統計的資料を作成するため',

            'disclosure_title' => '個人情報の第三者提供',
            'disclosure_list'  => '法令により情報の開示が求められる場合<br>人の生命、身体または財産の保護のために必要があると当研究所が判断した場合<br>国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力することその他公共の利益のために特に必要があると当研究所が判断した場合<br>お客様または当研究所の権利の確保のために必要であると当研究所が判断した場合<br>業務遂行に必要な限度で個人情報の取扱いを委託する場合',

            'correction_title' => '個人情報の開示・訂正・削除',
            'correction_body'  => 'お客様は、当研究所所定の手続きにより、以下の請求を行うことができます。(1) 当研究所の保有する自己の個人情報が誤った情報でないことを確認すること (2) 当研究所の保有する自己の個人情報が誤った情報である場合に、それを訂正または削除すること<br>当研究所は、前項(２)の個人情報の訂正または削除の可否を決定した場合には、遅滞なく、当該お客様に通知します。<br>これらの請求を希望される場合には、お問い合わせフォームよりご連絡ください。',

            'disclaimer_title' => '免責事項',
            'disclaimer_body1' => '当研究所では、お客様の個人情報を厳格に管理し、改ざん、漏えいや不正アクセス等の危険性に対して予防策を実施します。 個人情報の登録が発生するWebページではデータ送信の際にSSL(Secure Socket Layer)暗号化技術を使用しております。',
            'disclaimer_body2' => 'ただし、各種申込フォームにお客様が入力されたメールアドレスが間違っている場合は責任を負いかねますのでご理解下さい。',
            'disclaimer_body3' => '※各種申込フォームでは内容をお客様に確認していただくために、登録されたメールアドレスに申込情報を自動的に配信する仕組みになっています。そのため間違ったメールアドレスであっても、そのメールアドレスに申込情報が自動的に配信されます。',

            // 任意：変更告知（既存キーがあれば上書き）
            'changes_title' => 'プライバシーポリシーの変更',
            'changes_body'  => '当研究所サイトを利用された場合、お客様はプライバシーポリシーに同意して頂いたものと判断いたします。諸事情により、上記のプライバシーポリシーを部分的に変更、修正、追加、削除させて頂くことがございます。加えて、プライバシーポリシーについては、変更等のご連絡をお客様にご連絡することはありません。随時、このページにてご確認ください。',
        ];

        $page = PageContent::where('page_key', $key)->first();

        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'プライバシーポリシー',
                'content' => [
                    'html'  => $html,
                    'htmls' => [ 'body' => $html ],
                    'texts' => $texts,
                ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        // 既存を尊重しつつ、不足のみ補完（既存本文や文言は上書きしない）
        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }

        $existingTexts = isset($content['texts']) && is_array($content['texts']) ? $content['texts'] : [];
        $existingHtml  = (isset($content['html']) && is_string($content['html']) && trim($content['html']) !== '') ? $content['html'] : '';
        $existingBody  = (isset($content['htmls']['body']) && is_string($content['htmls']['body']) && trim($content['htmls']['body']) !== '') ? $content['htmls']['body'] : '';

        // texts: 既存を優先し、足りないキーだけ今回のデフォルトで補完
        $content['texts'] = array_replace($texts, $existingTexts);

        // html: 既存があれば維持、無ければ今回の全文を設定
        $newHtml = $existingHtml !== '' ? $existingHtml : $html;
        $content['html'] = $newHtml;

        // htmls.body: 既存があれば維持、無ければ html と同じ内容で補完
        $newBody = $existingBody !== '' ? $existingBody : $newHtml;
        $content['htmls'] = array_replace($content['htmls'] ?? [], ['body' => $newBody]);

        $page->update([
            'title' => $page->title ?: 'プライバシーポリシー',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
