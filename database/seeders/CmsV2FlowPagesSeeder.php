<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsV2Page;
use Illuminate\Support\Str;

class CmsV2FlowPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Contact flow
            ['slug' => 'contact-confirm', 'title' => 'contact-confirm'],
            ['slug' => 'contact-complete', 'title' => 'contact-complete'],
            // Membership application flow
            ['slug' => 'membership-application-confirm', 'title' => 'membership-application-confirm'],
            ['slug' => 'membership-application-complete', 'title' => 'membership-application-complete'],
            // Seminar application flow
            ['slug' => 'seminar-application-confirm', 'title' => 'seminar-application-confirm'],
            ['slug' => 'seminar-application-complete', 'title' => 'seminar-application-complete'],
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
