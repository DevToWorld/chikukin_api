<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class ConsultingPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'cri-consulting';
        $alias = 'consulting'; // legacy key to migrate/sync from
        $texts = [
            'page_title' => 'CRI経営コンサルティング',
            'page_subtitle' => 'consulting',
            // What is section
            'what_title' => 'CRI 経営コンサルティングとは？',
            'what_subtitle' => 'consulting',
            'what_content_subtitle' => "What's CRI Management Consulting",
            'what_content_heading' => 'CRI経営コンサルティングとは、事業でお悩みの皆様に対し、アドバイス、サポートするサービスです',
            // Duties section
            'duties_title' => 'CRI経営コンサルティングの主な業務',
            'duties_subtitle' => 'main duties',
            'duties_label' => 'MAIN DUTIES',
            'duties_heading' => '事業にまつわる様々な悩みを一緒に解決します',
            // Support section
            'support_title' => 'サポート内容と費用に関して',
            'support_subtitle_en' => 'contents and costs',
            'support_free_title' => '無料 -FREE-',
            'support_paid_title' => '有料 -PAID-',
            // Achievements
            'achievements_title' => 'CRI経営コンサルティングの実績紹介',
            'achievements_subtitle' => 'achievements',
            // Achievements list (editable)
            'achievements_item1_date' => '2025.6.27',
            'achievements_item1_category' => 'カテゴリー',
            'achievements_item1_title' => '分析（新たな課題認識）',
            'achievements_item2_date' => '2025.6.27',
            'achievements_item2_category' => 'カテゴリー',
            'achievements_item2_title' => '分析（新たな課題認識）',
            'achievements_item3_date' => '2025.6.27',
            'achievements_item3_category' => 'カテゴリー',
            'achievements_item3_title' => '分析（新たな課題認識）',
            'achievements_item4_date' => '2025.6.27',
            'achievements_item4_category' => 'カテゴリー',
            'achievements_item4_title' => '分析（新たな課題認識）',
            'achievements_note' => 'このほか「Hot Information」の配信や「経営参考BOOK」の配布も定期的に行っております。調査・研究の受託にも応じますので、お気軽にお問い合わせください。',
            // CTA
            'cta_primary' => 'お問い合わせはこちら',
            'cta_secondary' => '入会はこちら',
            // Small labels (FREE/PAID cards)
            'free_consultation_label' => 'consultation',
            'free_problem_label' => 'problem',
            'paid_strategy_label' => 'solutions',
            'paid_improvement_label' => 'improvement',
            'paid_analysis_label' => 'analysis',
            'paid_newbiz_label' => 'new business',
        ];

        $htmls = [
            'what_content_body' => '日常の企業活動の中で、まず自社が抱える様々な問題や課題・顧客ニーズなどに気付き、それらを整理して対応策を講じることができれば、改善に向けて新たなスタートを切ったと言えましょう。<br>しかし、「何か変だ。」と思いながらもとりあえず一日の業務を無事に終えたことに安心していては、身近に存在する重要なことに気付くのが遅れることもあります。',
            'duties_intro' => '事業にまつわる様々をサポート行います。<br>当研究所のネットワークを活かし、御社の実情に合う専門家もご紹介できます。',
            'duties_list' => '<p>経営戦略策定のサポート</p><p>ビジネスマッチィングの支援</p><p>事業継承の支援（後継者育成支援）</p><p>社員研修、経営計画策定のための人材育成</p><p>税務、財務、事務など各業務の課題解決力の育成</p><p>事務省力化のサポート</p><p>人事制度の策定</p><p>ワンストップのよろず屋相談</p>',
            'support_intro' => '経営改善とは会社の中の「人」が変わることです。<br>ご相談と問題点・課題の整理は無料です。まずは、お気軽にご相談ください。',
            // Achievements item descriptions (HTML)
            'achievements_item1_desc' => '有限会社AliveCast 中村理氏による「誰もおしえてくれない、プロと一緒に考えるインターネットを使った"売れる仕組みづくり"」セミナーを開催。 ホームページの作成や運営に様々な疑問を抱えていた参加者様が多く、「非常に参考になった」「インターネットだけではなく自社の今後の戦略にも役立った」との声もあり、大変ご好評いただいたセミナーでした。',
            'achievements_item2_desc' => '在上海中国ビジネスウォッチャー姫田小夏氏による「当世上海事情」講演会を開催しました。 自らも上海に居住し、独自の目線で中国ビジネスをとらえる姫田氏は「中国中小企業の貪欲さ、すさまじい競争がある限り、中国はまだまだ発展の余地はある」と講演され、聴講者はメモを取りながら聞き入っていました。',
            'achievements_item3_desc' => 'Excel 2010 講座を開催。今回の講座は知っておくと実務が効率化できるような、中級者〜上級者向けの講座でしたが、受講者の理解度・満足度は高く、次回の講座の要望もありました。 今やパソコンスキルは企業では必須とされています。CRIでは今後も実務に役立つ講座を実施していく予定です。',
            'achievements_item4_desc' => '上海経済事情視察ツアーを実施。「上海開拓の糸口を探る」と題したこのツアーには、様々な業種の方々にご参加いただきました。 ツアー参加者様からは「今後のビジネスの参考にしたい」「中国から得るものは大きい」と、大変満足度の高いツアーとなりました。'
        ];

        // Images used in achievements grid (to allow admin replacement later)
        $images = [
            'achievements_item1_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/ec6122758f12e1bfbe99ee61905a31ea4d49a78c?width=574',
            'achievements_item2_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/95f39033c1a29bc732f48805d8362c58b24c766c?width=574',
            'achievements_item3_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/9c17e76e3ac6cf114e40472b8f2ce93b5bfa00f3?width=574',
            'achievements_item4_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/5dabcf000de42e14f9010cb47622d830cacdf698?width=574',
        ];

        // Load primary and alias pages
        $page = PageContent::where('page_key', $key)->first();
        $aliasPage = PageContent::where('page_key', $alias)->first();

        $content = $page ? ($page->content ?? []) : [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $existingTexts = isset($content['texts']) && is_array($content['texts']) ? $content['texts'] : [];
        $existingHtmls = isset($content['htmls']) && is_array($content['htmls']) ? $content['htmls'] : [];

        $aliasContent = $aliasPage ? ($aliasPage->content ?? []) : [];
        if (!is_array($aliasContent)) $aliasContent = ['html' => (string)$aliasContent];
        $aliasTexts = isset($aliasContent['texts']) && is_array($aliasContent['texts']) ? $aliasContent['texts'] : [];
        $aliasHtmls = isset($aliasContent['htmls']) && is_array($aliasContent['htmls']) ? $aliasContent['htmls'] : [];

        // Merge order: defaults -> existing main -> alias (prefer alias since recent edits likely saved there)
        $finalTexts = array_replace($texts, $existingTexts, $aliasTexts);
        $finalHtmls = array_replace($htmls, $existingHtmls, $aliasHtmls);
        $content['texts'] = $finalTexts;
        $content['htmls'] = $finalHtmls;
        // merge images registry (immutable URLs ok; can be replaced via media tool later)
        $existingImages = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];
        $content['images'] = array_replace($images, $existingImages);

        // content.html が空なら、セクションから合成
        $existingHtml = isset($content['html']) && is_string($content['html']) ? trim($content['html']) : '';
        if ($existingHtml === '') {
            $t = $content['texts'];
            $h = $content['htmls'];
            $parts = [];

            // What is
            $whatTitle = $t['what_title'] ?? 'CRI 経営コンサルティングとは？';
            $parts[] = '<h2>' . htmlspecialchars($whatTitle, ENT_QUOTES, 'UTF-8') . '</h2>';
            if (!empty($t['what_content_heading'])) {
                $parts[] = '<h3>' . htmlspecialchars($t['what_content_heading'], ENT_QUOTES, 'UTF-8') . '</h3>';
            }
            if (!empty($h['what_content_body'])) { $parts[] = '<div>' . $h['what_content_body'] . '</div>'; }

            // Duties
            $dutiesTitle = $t['duties_title'] ?? '主な業務';
            $parts[] = '<h2>' . htmlspecialchars($dutiesTitle, ENT_QUOTES, 'UTF-8') . '</h2>';
            if (!empty($t['duties_heading'])) {
                $parts[] = '<p>' . htmlspecialchars($t['duties_heading'], ENT_QUOTES, 'UTF-8') . '</p>';
            }
            if (!empty($h['duties_intro'])) { $parts[] = '<p>' . $h['duties_intro'] . '</p>'; }
            if (!empty($h['duties_list'])) { $parts[] = '<div>' . $h['duties_list'] . '</div>'; }

            // Support
            $supportTitle = $t['support_title'] ?? 'サポート内容と費用に関して';
            $parts[] = '<h2>' . htmlspecialchars($supportTitle, ENT_QUOTES, 'UTF-8') . '</h2>';
            if (!empty($h['support_intro'])) { $parts[] = '<p>' . $h['support_intro'] . '</p>'; }
            if (!empty($t['support_free_title'])) { $parts[] = '<h3>' . htmlspecialchars($t['support_free_title'], ENT_QUOTES, 'UTF-8') . '</h3>'; }
            if (!empty($t['support_paid_title'])) { $parts[] = '<h3>' . htmlspecialchars($t['support_paid_title'], ENT_QUOTES, 'UTF-8') . '</h3>'; }

            $compiled = implode("\n\n", $parts);
            $content['html'] = $compiled;
            $content['htmls'] = array_merge($content['htmls'] ?? [], ['body' => $compiled]);
        }

        if (!$page) {
            $page = PageContent::create([
                'page_key' => $key,
                'title' => 'CRI経営コンサルティング',
                'content' => $content,
                'is_published' => true,
                'published_at' => now(),
            ]);
        } else {
            $page->update([
                'title' => $page->title ?: 'CRI経営コンサルティング',
                'content' => $content,
                'is_published' => true,
                'published_at' => $page->published_at ?: now(),
            ]);
        }

        // Keep legacy alias in sync to avoid admin/editor confusion
        if ($aliasPage) {
            $aliasPage->update([
                'title' => $aliasPage->title ?: 'CRI経営コンサルティング',
                'content' => $content,
                'is_published' => true,
                'published_at' => $aliasPage->published_at ?: now(),
            ]);
        } else {
            PageContent::create([
                'page_key' => $alias,
                'title' => 'CRI経営コンサルティング',
                'content' => $content,
                'is_published' => true,
                'published_at' => now(),
            ]);
        }
    }
}
