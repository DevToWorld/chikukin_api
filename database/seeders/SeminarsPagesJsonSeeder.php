<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class SeminarsPagesJsonSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            'seminars' => ['セミナー', 'seminar'],
            'seminars-current' => ['受付中のセミナー', 'current seminars'],
            'seminars-past' => ['過去のセミナー', 'past seminars'],
        ];

        foreach ($entries as $key => [$titleJa, $subtitle]) {
            $texts = [
                'page_title' => $titleJa,
                'page_subtitle' => $subtitle,
            ];
            $page = PageContent::where('page_key', $key)->first();
            if (!$page) {
                PageContent::create([
                    'page_key' => $key,
                    'title' => $titleJa,
                    'content' => ['texts' => $texts],
                    'is_published' => true,
                    'published_at' => now(),
                ]);
                continue;
            }
            $content = is_array($page->content) ? $page->content : [];
            $content['texts'] = array_merge($texts, $content['texts'] ?? []);
            $page->update(['title' => $page->title ?: $titleJa, 'content' => $content]);
        }
    }
}

