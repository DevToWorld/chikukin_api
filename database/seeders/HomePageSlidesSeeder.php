<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

/**
 * Seed home page hero slider (images, texts, links) and banner images
 * so they are manageable from PageContent (ページ管理).
 */
class HomePageSlidesSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'home';

        // Default images for three hero slides (use local static assets)
        $images = [
            'hero_slide_1' => '/img/Image_fx8.jpg',
            'hero_slide_2' => '/img/Image_fx9.jpg',
            'hero_slide_3' => '/img/Image_fx10.jpg',
            // Ensure top banner images exist as page-managed keys (other seeder may also set these)
            'banner_seminar' => '/img/---2.png',
            'banner_publications' => '/img/--.png',
            'banner_info' => '/img/---1.png',
            'banner_membership' => '/img/---7.png',
        ];

        // Texts for hero slides (title/subtitle/button)
        $texts = [
            'hero_slide_1_title' => '地域の企業活動を支える',
            'hero_slide_1_subtitle' => '産・官・学・金のネットワーク',
            'hero_slide_1_button' => 'お問い合わせはこちら',

            'hero_slide_2_title' => '経営に効くセミナー',
            'hero_slide_2_subtitle' => '最新の実務・制度を分かりやすく',
            'hero_slide_2_button' => 'セミナー一覧を見る',

            'hero_slide_3_title' => '会員サービスのご案内',
            'hero_slide_3_subtitle' => '情報とネットワークで事業を後押し',
            'hero_slide_3_button' => '入会案内はこちら',
        ];

        // Links for hero slide buttons
        $links = [
            'hero_slide_1_link' => '/contact',
            'hero_slide_2_link' => '/seminars',
            'hero_slide_3_link' => '/application-form',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            $page = PageContent::create([
                'page_key' => $key,
                'title' => 'ホーム',
                'content' => [ 'images' => $images, 'texts' => $texts, 'links' => $links ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }

        $existingImages = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];
        $existingTexts  = isset($content['texts']) && is_array($content['texts']) ? $content['texts'] : [];
        $existingLinks  = isset($content['links']) && is_array($content['links']) ? $content['links'] : [];

        // Keep existing values; provide defaults only for missing keys
        $content['images'] = array_merge($images, $existingImages);
        $content['texts']  = array_merge($texts, $existingTexts);
        $content['links']  = array_merge($links, $existingLinks);

        $page->update([
            'title' => $page->title ?: 'ホーム',
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}

