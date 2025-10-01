<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class RemoveHeroImagesFromMediaSeeder extends Seeder
{
    public function run(): void
    {
        $page = PageContent::where('page_key', 'media')->first();
        if (!$page) return;

        $content = $page->content ?? [];
        if (!is_array($content)) return;
        $images = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];
        if (!$images) return;

        $filtered = [];
        foreach ($images as $k => $v) {
            if (strpos((string)$k, 'hero_') === 0) {
                // drop hero_* keys
                continue;
            }
            $filtered[$k] = $v;
        }

        $content['images'] = $filtered;
        $page->update(['content' => $content]);
    }
}

