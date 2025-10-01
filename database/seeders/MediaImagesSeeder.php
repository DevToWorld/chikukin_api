<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

/**
 * Seed/override media registry (page_key = 'media') with
 * non-KV images referenced from static components.
 *
 * Note: Hero/KV images (keys beginning with `hero_`) are intentionally
 * not touched here. Those can be replaced from the admin UI separately.
 */
class MediaImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the media registry page exists
        $page = PageContent::where('page_key', 'media')->first();
        if (!$page) {
            $page = PageContent::create([
                'page_key' => 'media',
                'title' => 'メディアレジストリ',
                'content' => [ 'images' => [] ],
                'is_published' => true,
                'published_at' => now(),
            ]);
        }

        // Current content (normalize to array form)
        $content = $page->content ?? [];
        if (!is_array($content)) {
            $content = [ 'html' => (string)$content ];
        }
        $currentImages = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

        // Non-KV image keys used by static components
        // 1) Company Profile section images used via useMedia() in CompanyProfile.vue
        $companyProfileImages = [
            'company_profile_philosophy' => 'https://api.builder.io/api/v1/image/assets/TEMP/01501a28725d762f4b766643e9bcd235f2e43e2e?width=1340',
            'company_profile_message' => 'https://api.builder.io/api/v1/image/assets/TEMP/20aa75cfa1be4c2096a1f47bf126cf240173b231?width=1340',
            'company_profile_staff_morita' => 'https://api.builder.io/api/v1/image/assets/TEMP/013d1cd8a9cd502c97404091dee8168d1aa93903?width=452',
            'company_profile_staff_mizokami' => 'https://api.builder.io/api/v1/image/assets/TEMP/3eb35c11c5738cb9283fd65048f0db5c42dd1080?width=451',
            'company_profile_staff_kuga' => 'https://api.builder.io/api/v1/image/assets/TEMP/ce433d9c00a0ce68895c315df3a3c49aa626deff?width=451',
            'company_profile_staff_takada' => 'https://api.builder.io/api/v1/image/assets/TEMP/b21372a6aca15dfc189c6953aeb23f36f5d5e20b?width=451',
            'company_profile_staff_nakamura' => 'https://api.builder.io/api/v1/image/assets/TEMP/497e67c9baa8add863ab6c5cc32439cf23eea4c3?width=451',
        ];

        // Add responsive variants pointing to same URLs for now
        $withVariants = function (array $base): array {
            $out = [];
            foreach ($base as $k => $v) {
                $out[$k] = $v;               // desktop/default
                $out[$k . '_mobile'] = $v;   // mobile
                $out[$k . '_tablet'] = $v;   // tablet
            }
            return $out;
        };

        // 2) Contact section background image used in ContactSection.vue
        $sectionImages = [
            'contact_section_bg' => '/img/Image_fx1.jpg',
        ];

        $images = array_merge(
            $withVariants($companyProfileImages),
            $withVariants($sectionImages)
        );

        // Merge so that our values override placeholder '/img/hero-image.png'
        $merged = array_merge($currentImages, $images);
        $content['images'] = $merged;

        $page->update([
            'content' => $content,
            'is_published' => true,
            'published_at' => $page->published_at ?: now(),
        ]);
    }
}

