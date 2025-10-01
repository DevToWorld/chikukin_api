<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

/**
 * Populate/normalize per-page non-KV images in PageContent->content['images'].
 *
 * This ensures Admin > メディア管理 で各ページ（media以外）に
 * 適切な初期URLが表示され、画像置換フローに載せられます。
 */
class PageNonKvImagesSeeder extends Seeder
{
    public function run(): void
    {
        // ABOUT: hero + mission section image (component reads content.images['hero'|'content'])
        $this->mergeImages('about', [
            'hero' => '/img/hero-image.png',
            // Used in AboutPage.vue: getImageUrl('content')
            'content' => '/img/image-1.png',
            // Optionally keep a message image slot for future wiring
            'message' => '/img/image-2.png',
        ]);

        // CRI Consulting: register page-managed images for all sections
        $this->mergeImages('cri-consulting', [
            // What / Duties main images
            'what_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/f016525f0cc061901e592a57545785e894630484?width=940',
            'duties_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/25a28ba9ea089f902a21c4d02c416034111f837a?width=1304',
            // Support section (FREE)
            'support_free_consultation' => 'https://api.builder.io/api/v1/image/assets/TEMP/33cee256926cd28b9a1a674d35fd9166a56602c3?width=590',
            'support_free_problem'      => 'https://api.builder.io/api/v1/image/assets/TEMP/31768edf86418d36cb69d49cceebe7329e674e2b?width=590',
            // Support section (PAID)
            'support_paid_strategy'     => 'https://api.builder.io/api/v1/image/assets/TEMP/3ae2d367ee53d3693e55ab25762bbc22b1e79db3?width=590',
            'support_paid_improvement'  => 'https://api.builder.io/api/v1/image/assets/TEMP/314b38f7051166e9dfefd653b917881f2e188fb0?width=590',
            'support_paid_analysis'     => 'https://api.builder.io/api/v1/image/assets/TEMP/e92448f8521df04fdda36093ecdc836ecfdcbdbb?width=590',
            'support_paid_newbiz'       => 'https://api.builder.io/api/v1/image/assets/TEMP/ccca71dcedbebcbeb9188be2f5b67ca86225155f?width=590',
        ]);

        // HOME: top banners (currently set via local data; reserved here for CMS migration)
        $this->mergeImages('home', [
            'banner_seminar' => '/img/---2.png',
            'banner_publications' => '/img/--.png',
            'banner_info' => '/img/---1.png',
            'banner_membership' => '/img/---7.png',
            'hero_slide_1' => '/img/Image_fx1.jpg',
            'hero_slide_2' => '/img/Image_fx6.jpg',
            'hero_slide_3' => '/img/Image_fx3.jpg',
            'about_card1_image' => '/img/image.png',
            'about_card2_image' => '/img/image-1.png',
            'about_card3_image' => '/img/image-2.png',
            'home_partner_logo1' => '/img/---3.png',
            'home_partner_logo2' => '/img/---4.png',
            'home_partner_logo3' => '/img/---5.png',
            'home_partner_logo4' => '/img/---6.png',
        ]);

        // ABOUT-INSTITUTE: main image + service images
        $this->mergeImages('about-institute', [
            'about_image'   => 'https://api.builder.io/api/v1/image/assets/TEMP/ec1204f76835f4d00fb62a46530330165ae1b65e?width=1340',
            'service1_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/a3c7e188d6e1f00b84c70555aa606fd2ebe0cc5b?width=870',
            'service2_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/f92b01ad91c11f2d63f1c851a4991b7316f2365d?width=870',
            'service3_image' => 'https://api.builder.io/api/v1/image/assets/TEMP/9de269531d1458bd155965ea0ad95d1a7f468fe4?width=870',
        ]);
    }

    private function mergeImages(string $pageKey, array $images): void
    {
        $page = PageContent::where('page_key', $pageKey)->first();
        if (!$page) {
            // Create a minimal page so media admin can manage immediately
            $page = PageContent::create([
                'page_key' => $pageKey,
                'title' => ucfirst(str_replace('-', ' ', $pageKey)),
                'content' => [ 'images' => $images ],
                'is_published' => true,
                'published_at' => now(),
            ]);
            return;
        }

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = [ 'html' => (string)$content ]; }
        $existing = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];
        // Our seeder values should override placeholders (e.g. '/img/hero-image.png') if present
        $content['images'] = array_replace($existing, $images);
        $page->update(['content' => $content]);
    }
}
