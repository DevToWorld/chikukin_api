<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class LoginPageJsonSeeder extends Seeder
{
    public function run(): void
    {
        $key = 'login';
        $texts = [
            'page_title' => 'ログイン',
            'page_subtitle' => 'login',
            'intro' => '会員機能をご利用いただくにはログインが必要です。',
            'button_login' => 'ログイン',
        ];

        $page = PageContent::where('page_key', $key)->first();
        if (!$page) {
            PageContent::create([
                'page_key' => $key,
                'title' => 'ログイン',
                'content' => ['texts' => $texts],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }
        $content = is_array($page->content) ? $page->content : [];
        $content['texts'] = array_merge($texts, $content['texts'] ?? []);
        $page->update(['title' => $page->title ?: 'ログイン', 'content' => $content]);
    }
}

