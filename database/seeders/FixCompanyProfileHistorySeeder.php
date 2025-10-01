<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\PageContent;

class FixCompanyProfileHistorySeeder extends Seeder
{
    public function run(): void
    {
        // Normalize page_key to 'company-profile'
        $company = PageContent::where('page_key', 'company')->first();
        $profile = PageContent::where('page_key', 'company-profile')->first();

        if ($company && !$profile) {
            // rename old 'company' record to 'company-profile'
            $company->page_key = 'company-profile';
            $company->save();
            $profile = $company;
        } elseif ($company && $profile) {
            // merge: prefer existing 'company-profile', but migrate missing fields from 'company'
            $pc = $profile->content ?? [];
            $cc = $company->content ?? [];
            if (!is_array($pc)) $pc = [];
            if (!is_array($cc)) $cc = [];
            // texts
            $pc['texts'] = array_replace($cc['texts'] ?? [], $pc['texts'] ?? []);
            // htmls
            $pc['htmls'] = array_replace($cc['htmls'] ?? [], $pc['htmls'] ?? []);
            // html (body)
            if (empty(Arr::get($pc, 'html'))) {
                $pc['html'] = Arr::get($cc, 'html', '');
            }
            // tentative merge of images/media_keys
            foreach (['images','media_keys'] as $k) {
                $pc[$k] = array_replace($cc[$k] ?? [], $pc[$k] ?? []);
            }
            $profile->content = $pc;
            $profile->save();
        }

        // Ensure content.history is present and normalized
        $page = PageContent::where('page_key', 'company-profile')->first();
        if (!$page) return;

        $content = $page->content ?? [];
        if (!is_array($content)) $content = [];

        // Try to read history from possible alternate paths
        $history = [];
        // standard
        if (isset($content['history']) && is_array($content['history'])) {
            $history = $content['history'];
        }
        // alternates
        $candidates = [
            Arr::get($content, 'histories'),
            Arr::get($content, 'company.history'),
            Arr::get($content, 'timeline'),
        ];
        foreach ($candidates as $cand) {
            if (is_array($cand) && count($cand) && !count($history)) { $history = $cand; }
        }

        // Normalize entries
        $normalized = [];
        if (is_array($history)) {
            foreach ($history as $idx => $h) {
                if (!is_array($h)) continue;
                $year = (string)($h['year'] ?? $h['date'] ?? '');
                $date = (string)($h['date'] ?? '');
                $body = (string)($h['body'] ?? $h['title'] ?? $h['text'] ?? '');
                $title = (string)($h['title'] ?? '');
                if ($year || $date || $body || $title) {
                    $normalized[] = [
                        'year' => $year,
                        'date' => $date,
                        'body' => $body ?: $title,
                    ];
                }
            }
        }

        // Only update if we discovered something or key missing
        $existingHasKey = array_key_exists('history', $content);
        if (!$existingHasKey || count($normalized)) {
            $content['history'] = $normalized;
            $page->update(['content' => $content]);
        }
    }
}

