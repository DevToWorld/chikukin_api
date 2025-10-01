<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class MyAccountPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'my-account';
        $texts = [
            'page_title' => 'マイアカウント',
            'page_subtitle' => 'my account',
            'intro' => '会員情報の確認・変更、ダウンロード履歴の確認などが行えます。',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'マイアカウント',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }
        $content = is_array($page->content) ? $page->content : [];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: 'マイアカウント', 'content' => $content]);
    }
}

