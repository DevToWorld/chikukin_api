<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsV2Seeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('news')) {
            return; // テーブルが無い環境ではスキップ
        }

        $hasSlug = Schema::hasColumn('news', 'slug');
        $hasExcerpt = Schema::hasColumn('news', 'excerpt');
        $hasCategoryId = Schema::hasColumn('news', 'category_id');
        $hasIsPublished = Schema::hasColumn('news', 'is_published');
        $hasPublishedAt = Schema::hasColumn('news', 'published_at');
        $hasPublishedDate = Schema::hasColumn('news', 'published_date');
        $hasStatus = Schema::hasColumn('news', 'status');
        $hasMembershipRequirement = Schema::hasColumn('news', 'membership_requirement');
        $hasCreatedBy = Schema::hasColumn('news', 'created_by');
        $hasUpdatedBy = Schema::hasColumn('news', 'updated_by');

        $now = Carbon::now();

        $baseItems = [
            [
                'title' => 'お知らせ: システムメンテナンス完了',
                'content' => '本日未明に実施したシステムメンテナンスは正常に完了しました。',
                'excerpt' => 'メンテナンス完了のお知らせ',
                'category' => 'notice'
            ],
            [
                'title' => '地域経済動向 最新レポート公開',
                'content' => '最新の地域経済レポートを公開しました。会員の方は全文閲覧可能です。',
                'excerpt' => '最新レポート公開のご案内',
                'category' => 'research'
            ],
        ];

        foreach ($baseItems as $i => $item) {
            $row = [
                'title' => $item['title'],
                'content' => $item['content'],
                'view_count' => 0,
                'is_featured' => false,
                'featured_image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($hasSlug) {
                $row['slug'] = Str::slug($item['title']).'-'.($i+1);
            }
            if ($hasExcerpt) {
                $row['excerpt'] = $item['excerpt'];
            }
            if ($hasCategoryId) {
                $row['category_id'] = null; // カテゴリ未作成のためnull
            }
            if ($hasIsPublished) {
                $row['is_published'] = true;
            }
            if ($hasPublishedAt) {
                $row['published_at'] = $now->copy()->subDays($i*2);
            }
            if ($hasPublishedDate) {
                $row['published_date'] = $now->copy()->subDays($i*2)->toDateString();
            }
            if ($hasStatus) {
                $row['status'] = 'published';
            }
            if ($hasMembershipRequirement) {
                $row['membership_requirement'] = 'none';
            }
            if ($hasCreatedBy) {
                $adminId = DB::table('admins')->value('id');
                $row['created_by'] = $adminId;
            }
            if ($hasUpdatedBy) {
                $row['updated_by'] = $row['created_by'] ?? null;
            }

            // 重複チェック（slugがあればslugで、無ければtitleで）
            $query = DB::table('news');
            if ($hasSlug) {
                $query->where('slug', $row['slug']);
            } else {
                $query->where('title', $row['title']);
            }

            if (!$query->exists()) {
                DB::table('news')->insert($row);
            }
        }
    }
}

