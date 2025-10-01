<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageRemoveResponsiveVariantsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = PageContent::all();
        foreach ($pages as $page) {
            $content = $page->content ?? [];
            if (!is_array($content)) { $content = ['html' => (string) $content]; }
            $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];
            if (!$imgs) continue;

            $changed = false;
            foreach (array_keys($imgs) as $k) {
                if (preg_match('/_(mobile|tablet)$/', (string)$k)) {
                    unset($imgs[$k]);
                    $changed = true;
                }
            }

            if ($changed) {
                $content['images'] = $imgs;
                $page->update(['content' => $content]);
            }
        }
    }
}

