<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

/**
 * Ensure each main page has a per-page hero image key in content.images.
 * This enables changing hero images from each page's management UI
 * and makes them visible in 「メディア管理」一覧（page側の images.hero）。
 */
class PageHeroPlaceholdersSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = '/img/hero-image.png';
        $pages = [
            // Common site pages
            'home', 'about', 'about-institute', 'privacy', 'terms', 'glossary', 'sitemap', 'contact',
            // Membership
            'membership', 'premium-membership',
            // Content
            'financial-reports', 'economic-indicators', 'cri-consulting',
        ];

        foreach ($pages as $key) {
            $page = PageContent::where('page_key', $key)->first();
            if (!$page) continue;

            $content = $page->content ?? [];
            if (!is_array($content)) { $content = ['html' => (string)$content]; }
            $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

            // Only set if missing to avoid overriding any existing hero
            $changed = false;
            if (!array_key_exists('hero', $imgs)) {
                $imgs['hero'] = $defaults;
                $changed = true;
            }
            if ($changed) {
                $content['images'] = $imgs;
                $page->update(['content' => $content]);
            }
        }
    }
}
