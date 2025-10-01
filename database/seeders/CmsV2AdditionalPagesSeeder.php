<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsV2Page;
use Illuminate\Support\Str;

class CmsV2AdditionalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Public listings and detail contexts
            ['slug' => 'publications', 'title' => 'publications'],
            ['slug' => 'publications-public', 'title' => 'publications-public'],
            ['slug' => 'news', 'title' => 'news'],
            ['slug' => 'seminars', 'title' => 'seminars'],
            ['slug' => 'seminars-current', 'title' => 'seminars-current'],
            ['slug' => 'seminars-past', 'title' => 'seminars-past'],
            // Economic pages
            ['slug' => 'economic-indicators', 'title' => 'economic-indicators'],
            ['slug' => 'economic-statistics', 'title' => 'economic-statistics'],
            // Corporate resources
            ['slug' => 'financial-reports', 'title' => 'financial-reports'],
            // Auth / Account related
            ['slug' => 'login', 'title' => 'login'],
            ['slug' => 'my-account', 'title' => 'my-account'],
            // Media registry (for reference)
            ['slug' => 'media', 'title' => 'media'],
            // Other policies (alias that some content may use)
            ['slug' => 'terms-of-service', 'title' => 'terms-of-service'],
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
            } else if (!$row->title) {
                $row->update(['title' => $p['title']]);
            }
        }
    }
}
