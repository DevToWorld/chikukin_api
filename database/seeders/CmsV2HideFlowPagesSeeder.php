<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsV2Page;

class CmsV2HideFlowPagesSeeder extends Seeder
{
    /**
     * Mark flow (confirm/complete) pages as hidden in meta_json so admin listings can exclude them.
     */
    public function run(): void
    {
        $slugs = [
            'contact-confirm', 'contact-complete',
            'membership-application-confirm', 'membership-application-complete',
            'seminar-application-confirm', 'seminar-application-complete',
            // listing page not intended for Block CMS editing
            'seminars-past',
        ];

        foreach ($slugs as $slug) {
            $page = CmsV2Page::where('slug', $slug)->first();
            if (!$page) continue;
            $meta = is_array($page->meta_json) ? $page->meta_json : [];
            // Idempotent: always set hidden=true
            $meta['hidden'] = true;
            $page->update(['meta_json' => $meta]);
        }
    }
}
