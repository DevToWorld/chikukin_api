<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class HomePageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'home';

        $texts = [
            // HeroSection titles
            'page_title' => 'ちくぎん地域経済研究所',
            'page_subtitle' => 'for your business',

            // About section
            'about_title' => 'ちくぎん地域経済研究所について',
            'about_subtitle' => 'FOR YOU',
            // CTA button under about
            'cta_secondary' => '研究所について詳しく',

            // Top CTA tiles
            'tile_seminar_label' => 'セミナー',
            'tile_seminar_subtitle' => 'seminar',
            'tile_publications_label' => '刊行物',
            'tile_publications_subtitle' => 'publications',
            'tile_information_label' => '各種情報',
            'tile_information_subtitle' => 'information',
            'tile_membership_label' => '会員について',
            'tile_membership_subtitle' => 'membership',

            // Section headings
            'news_section_label' => 'お知らせ',
            'news_section_heading' => 'INFORMATION',
            'publications_section_label' => '刊行物',
            'publications_section_heading' => 'PUBLICATIONS',
            'other_info_section_label' => 'その他の情報',
            'other_info_section_heading' => 'OTHER Information',

            // Other info cards
            'other_info_economic_label' => '経済指標',
            'other_info_economic_cta' => 'View More .',
            'other_info_financial_label' => '決算報告',
            'other_info_financial_cta' => 'View More .',
            'other_info_glossary_label' => '用語集',
            'other_info_glossary_cta' => 'View More .',
        ];

        $htmls = [
            'about_body' => '当研究所は、産・官・学・金(金融機関)のネットワークによる様々な分野の調査研究を通じ、企業活動などをサポートします。<br><br>経済・社会・産業動向などに関する調査研究及び企業経営や県民の生活のお役に立つ情報をご提供するとともに、各種経済・文化団体の事務局活動等を通じて、地域社会に貢献することを目指しております。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'ホーム',
                'content' => ['texts' => $texts, 'htmls' => $htmls],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) $content = ['html' => (string)$content];
        $existingTexts = isset($content['texts']) && is_array($content['texts']) ? $content['texts'] : [];
        $existingHtmls = isset($content['htmls']) && is_array($content['htmls']) ? $content['htmls'] : [];
        // Prefer existing where present; provide defaults otherwise
        $content['texts'] = array_merge($texts, $existingTexts);
        $content['htmls'] = array_merge($htmls, $existingHtmls);

        $page->update([
            'title' => $page->title ?: 'ホーム',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
