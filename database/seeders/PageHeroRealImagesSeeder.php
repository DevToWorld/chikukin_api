<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageHeroRealImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Map page_key => hero image path (desktop). Mobile/Tablet will mirror for now.
        $map = [
            'contact' => '/img/Image_fx2.jpg',
            'privacy' => '/img/Image_fx8.jpg',
            'terms' => '/img/Image_fx8.jpg',
            'transaction-law' => '/img/Image_fx8.jpg',
            'economic-statistics' => '/img/Image_fx6.jpg',
            'economic-indicators' => '/img/Image_fx5.jpg',
            'cri-consulting' => '/img/Image_fx9.jpg',
            'about-institute' => '/img/Image_fx10.jpg',
            'about' => '/img/Image_fx10.jpg',
            'company-profile' => '/img/Image_fx10.jpg',
            'publications' => '/img/Image_fx5.jpg',
            'publications-member' => '/img/Image_fx5.jpg',
            'financial-reports' => '/img/Image_fx5.jpg',
            'sitemap' => '/img/Image_fx8.jpg',
            'membership' => '/img/Image_fx7.jpg',
            'premium-membership' => '/img/Image_fx7.jpg',
            'seminars' => 'https://api.builder.io/api/v1/image/assets/TEMP/ab5db9916398054424d59236a434310786cb8146?width=2880',
            'seminars-current' => 'https://api.builder.io/api/v1/image/assets/TEMP/ab5db9916398054424d59236a434310786cb8146?width=2880',
            'news' => '/img/Image_fx3.jpg',
            'glossary' => '/img/Image_fx8.jpg',
        ];

        foreach ($map as $key => $url) {
            $page = PageContent::where('page_key', $key)->first();
            if (!$page) continue; // only update existing pages

            $content = $page->content ?? [];
            if (!is_array($content)) { $content = ['html' => (string)$content]; }
            $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

            // overwrite placeholders or set when missing (single hero only)
            $imgs['hero'] = $url;

            $content['images'] = $imgs;
            $page->update(['content' => $content]);
        }
    }
}
