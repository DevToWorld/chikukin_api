<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class NewsPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'news';
        $texts = [
            'page_title' => 'お知らせ',
            'page_subtitle' => 'news',
            'breadcrumb_label' => 'お知らせ',
            'detail_title' => 'お知らせ詳細',
            'detail_subtitle' => 'news detail',
            'back_to_list_label' => '一覧に戻る',
            'error_not_found' => 'お知らせが見つかりませんでした',
            'loading' => '読み込み中...'
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => $texts['page_title'],
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) {
            $content = ['texts' => []];
        }
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update([
            'title' => $page->title ?: $texts['page_title'],
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}
