<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsV2Page;
use Illuminate\Support\Str;

class CmsV2DefaultPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Core site pages
            ['slug' => 'home', 'title' => 'home'],
            ['slug' => 'company', 'title' => 'company'],
            ['slug' => 'company-profile', 'title' => 'company-profile'],
            ['slug' => 'about', 'title' => 'about'],
            ['slug' => 'aboutus', 'title' => 'aboutus'],
            ['slug' => 'about-institute', 'title' => 'about-institute'],
            ['slug' => 'services', 'title' => 'services'],
            ['slug' => 'contact', 'title' => 'contact'],

            // Membership related
            ['slug' => 'membership', 'title' => 'membership'],
            ['slug' => 'standard-membership', 'title' => 'standard-membership'],
            // support both slugs for premium
            ['slug' => 'premium-membership', 'title' => 'premium-membership'],
            ['slug' => 'membership-premium', 'title' => 'membership-premium'],

            // Legal/Policies
            ['slug' => 'terms', 'title' => 'terms'],
            ['slug' => 'transaction-law', 'title' => 'transaction-law'],
            ['slug' => 'privacy-policy', 'title' => 'privacy-policy'],
            ['slug' => 'navigation', 'title' => 'navigation'],
            ['slug' => 'footer', 'title' => 'footer'],

            // Misc
            ['slug' => 'glossary', 'title' => 'glossary'],
            ['slug' => 'faq', 'title' => 'faq'],
            ['slug' => 'sitemap', 'title' => 'sitemap'],
            ['slug' => 'cri-consulting', 'title' => 'cri-consulting'],
            // Applications
            ['slug' => 'membership-application', 'title' => 'membership-application'],
            ['slug' => 'seminar-application', 'title' => 'seminar-application'],
        ];

        foreach ($pages as $p) {
            $row = CmsV2Page::where('slug', $p['slug'])->first();
            if (!$row) {
                CmsV2Page::create([
                    'id' => (string) Str::uuid(),
                    'slug' => $p['slug'],
                    'title' => $p['title'],
                    'meta_json' => null,
                    'published_snapshot_json' => null,
                ]);
            } else {
                // Ensure title is at least set (do not overwrite if already customized)
                if (!$row->title) {
                    $row->update(['title' => $p['title']]);
                }
            }
        }
    }
}
