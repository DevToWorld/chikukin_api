<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PageContent;

class ReportPageImagesDiff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pages:images:diff {--only=* : Limit to specific page_keys}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report differences between expected image keys and current content.images for each PageContent';

    public function handle(): int
    {
        $only = (array) $this->option('only');
        $pages = PageContent::all();

        // Expected keys per page_key
        $map = [
            'company-profile' => [
                'company_profile_philosophy',
                'company_profile_message',
                'company_profile_staff_morita',
                'company_profile_staff_mizokami',
                'company_profile_staff_kuga',
                'company_profile_staff_takada',
                'company_profile_staff_nakamura',
            ],
            'about' => ['hero','content','message'],
            'about-institute' => ['hero','content','message'],
            'cri-consulting' => [
                'what_image',
                'duties_image',
                'achievements_item1_image',
                'achievements_item2_image',
                'achievements_item3_image',
                'achievements_item4_image',
            ],
            'home' => ['banner_seminar','banner_publications','banner_info','banner_membership'],
        ];

        // For most pages, at least the per-page hero should exist
        $atLeastHero = [
            'privacy','terms','transaction-law','contact','glossary','membership',
            'premium-membership','standard-membership','sitemap','faq','financial-reports',
            'economic-indicators','economic-statistics','publications','company','aboutus',
        ];

        $rows = [];
        foreach ($pages as $p) {
            $key = (string) $p->page_key;
            if (!empty($only) && !in_array($key, $only, true)) continue;

            $content = is_array($p->content) ? $p->content : [];
            $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

            $expected = $map[$key] ?? [];
            if (in_array($key, $atLeastHero, true) && !in_array('hero', $expected, true)) {
                $expected[] = 'hero';
            }

            // Build url map
            $urlMap = [];
            foreach ($imgs as $ik => $iv) {
                if (is_string($iv)) $urlMap[$ik] = $iv;
                elseif (is_array($iv) && isset($iv['url'])) $urlMap[$ik] = (string)$iv['url'];
                else $urlMap[$ik] = '';
            }

            // Check
            $missing = [];
            $placeholder = [];
            foreach ($expected as $ek) {
                $url = $urlMap[$ek] ?? '';
                if ($url === '' || $url === null) {
                    $missing[] = $ek;
                } elseif ($this->isPlaceholder($url)) {
                    $placeholder[] = $ek;
                }
            }

            // Extras: keys present but not expected
            $extras = array_values(array_diff(array_keys($urlMap), $expected));

            $rows[] = [
                'page' => $key,
                'title' => (string) ($p->title ?? ''),
                'missing' => implode(', ', $missing),
                'placeholder' => implode(', ', $placeholder),
                'extras' => implode(', ', $extras),
            ];
        }

        if (empty($rows)) {
            $this->info('No pages matched filter or no data.');
            return self::SUCCESS;
        }

        $this->table(['page_key','title','missing_keys','placeholder_keys','extra_keys'], $rows);
        return self::SUCCESS;
    }

    private function isPlaceholder(string $url): bool
    {
        $u = strtolower($url);
        // Treat only obvious placeholders as such
        if (str_contains($u, 'hero-image.png')) return true;
        if (str_contains($u, 'temp/')) return true; // builder temp
        // Local static assets under /img are considered valid
        return false;
    }
}
