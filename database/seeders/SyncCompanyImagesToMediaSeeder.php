<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class SyncCompanyImagesToMediaSeeder extends Seeder
{
    public function run(): void
    {
        $company = PageContent::where('page_key', 'company-profile')->first();
        $media = PageContent::where('page_key', 'media')->first();
        if (!$company || !$media) return;

        $src = $company->content ?? [];
        $dst = $media->content ?? [];
        if (!is_array($src)) $src = ['html' => (string)$src];
        if (!is_array($dst)) $dst = ['html' => (string)$dst];

        $srcImgs = isset($src['images']) && is_array($src['images']) ? $src['images'] : [];
        $dstImgs = isset($dst['images']) && is_array($dst['images']) ? $dst['images'] : [];

        $changed = false;
        foreach ($srcImgs as $k => $v) {
            if (strpos((string)$k, 'company_profile_') === 0) {
                $url = is_string($v) ? $v : (is_array($v) ? ($v['url'] ?? '') : '');
                if ($url && (!isset($dstImgs[$k]) || $dstImgs[$k] !== $url)) {
                    $dstImgs[$k] = $url;
                    $changed = true;
                }
            }
        }

        if ($changed) {
            $dst['images'] = $dstImgs;
            $media->update(['content' => $dst]);
        }
    }
}

