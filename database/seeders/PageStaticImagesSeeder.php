<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageStaticImagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAboutLike('about', [
            'hero' => '/img/Image_fx10.jpg',
            'content' => '/img/image-1.png',
            'message' => '/img/image-2.png',
        ]);

        $this->seedAboutLike('about-institute', [
            'hero' => '/img/Image_fx10.jpg',
            'content' => '/img/image-1.png',
            'message' => '/img/image-2.png',
        ]);

        $this->seedHomeBanners([
            'banner_seminar' => '/img/---2.png',
            'banner_publications' => '/img/--.png',
            'banner_info' => '/img/---1.png',
            'banner_membership' => '/img/---7.png',
        ]);

        $this->seedConsulting([
            'hero' => '/img/Image_fx9.jpg',
            'what_image' => '/img/image-1.png',
            'duties_image' => '/img/image-2.png',
            'achievements_item1_image' => '/img/demo4.jpg',
            'achievements_item2_image' => '/img/demo4.jpg',
            'achievements_item3_image' => '/img/demo4.jpg',
            'achievements_item4_image' => '/img/demo4.jpg',
        ]);

        $this->seedCompanyProfile([
            'hero' => '/img/Image_fx10.jpg',
            'company_profile_philosophy' => '/img/Image_fx10.jpg',
            'company_profile_message' => '/img/Image_fx10.jpg',
            'company_profile_staff_morita' => '/img/demo4.jpg',
            'company_profile_staff_mizokami' => '/img/demo4.jpg',
            'company_profile_staff_kuga' => '/img/demo4.jpg',
            'company_profile_staff_takada' => '/img/demo4.jpg',
            'company_profile_staff_nakamura' => '/img/demo4.jpg',
        ]);
    }

    private function seedAboutLike(string $pageKey, array $images): void
    {
        $page = PageContent::where('page_key', $pageKey)->first();
        if (!$page) return;

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }
        $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

        foreach ($images as $k => $url) {
            $curr = $this->imageUrl($imgs[$k] ?? null);
            if ($curr === '' || $this->isPlaceholder($curr)) {
                $imgs[$k] = $url;
            }
        }

        $content['images'] = $imgs;
        $page->update(['content' => $content]);
    }

    private function seedHomeBanners(array $images): void
    {
        $page = PageContent::where('page_key', 'home')->first();
        if (!$page) return;

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }
        $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

        foreach ($images as $k => $url) {
            $curr = $this->imageUrl($imgs[$k] ?? null);
            if ($curr === '' || $this->isPlaceholder($curr)) {
                $imgs[$k] = $url;
            }
        }

        $content['images'] = $imgs;
        $page->update(['content' => $content]);
    }

    private function seedConsulting(array $images): void
    {
        $page = PageContent::where('page_key', 'cri-consulting')->first();
        if (!$page) return;

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }
        $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

        foreach ($images as $k => $url) {
            $curr = $this->imageUrl($imgs[$k] ?? null);
            if ($curr === '' || $this->isPlaceholder($curr)) {
                $imgs[$k] = $url;
            }
        }

        $content['images'] = $imgs;
        $page->update(['content' => $content]);
    }

    private function seedCompanyProfile(array $images): void
    {
        $page = PageContent::where('page_key', 'company-profile')->first();
        if (!$page) return;

        $content = $page->content ?? [];
        if (!is_array($content)) { $content = ['html' => (string)$content]; }
        $imgs = isset($content['images']) && is_array($content['images']) ? $content['images'] : [];

        foreach ($images as $k => $url) {
            $curr = $this->imageUrl($imgs[$k] ?? null);
            if ($curr === '' || $this->isPlaceholder($curr)) {
                $imgs[$k] = $url;
            }
        }

        $content['images'] = $imgs;
        $page->update(['content' => $content]);
    }

    private function imageUrl($val): string
    {
        if (is_string($val)) return $val;
        if (is_array($val) && isset($val['url'])) return (string) $val['url'];
        return '';
    }

    private function isPlaceholder(string $url): bool
    {
        $u = strtolower($url);
        if ($u === '') return true;
        if (str_starts_with($u, '/img/hero-image')) return true;
        if (str_contains($u, '/img/image-')) return true;
        if (str_contains($u, '/img/---')) return true || str_contains($u, '/img/--');
        if (str_contains($u, 'temp/')) return true; // builder temp
        return false;
    }
}
