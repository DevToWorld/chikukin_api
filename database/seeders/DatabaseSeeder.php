<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Normalize and backfill encrypted member fields early to prevent decrypt errors
            EncryptMembersBackfillSeeder::class,
            AdminSeeder::class,
            AdminUserSeeder::class,
            EconomicStatisticsSeeder::class,
            // コンテンツ系シード（一覧が空にならないように含める）
            NewsArticlesSeeder::class,
            SeminarSeeder::class,
            NewsV2Seeder::class,
            PublicationsSeeder::class,
            EconomicReportsSeeder::class,
            FinancialReportsSeeder::class,
            ServicesSeeder::class,
            PageContentsSeeder::class,
            CompletePagesSeeder::class,
            CmsV2DefaultPagesSeeder::class,
            // Populate non-KV media registry images after base pages are created
            MediaImagesSeeder::class,
            // Put per-page non-KV images into their own pages
            PageNonKvImagesSeeder::class,
            // Remove hero_* entries from media registry (managed per page)
            RemoveHeroImagesFromMediaSeeder::class,
            // Ensure each page has a hero placeholder field to manage from the page editor
            PageHeroPlaceholdersSeeder::class,
            // Set initial real hero images per page to avoid identical placeholders
            PageHeroRealImagesSeeder::class,
            TermsPageJsonSeeder::class,
            PrivacyPageJsonSeeder::class,
            FaqPageJsonSeeder::class,
            TransactionLawPageJsonSeeder::class,
            AboutPageJsonSeeder::class,
            CompanyProfilePageJsonSeeder::class,
            SitemapPageJsonSeeder::class,
            ContactPageJsonSeeder::class,
            NewsPageJsonSeeder::class,
            NavigationPageJsonSeeder::class,
            FooterPageJsonSeeder::class,
            PublicationsPageJsonSeeder::class,
            ConsultingPageJsonSeeder::class,
            AboutInstitutePageJsonSeeder::class,
            MembershipPageJsonSeeder::class,
            GlossaryPageJsonSeeder::class,
            FinancialReportsPageJsonSeeder::class,
            MembershipApplicationPageJsonSeeder::class,
            SeminarApplicationPageJsonSeeder::class,
            SeminarsPagesJsonSeeder::class,
            EconomicIndicatorSeeder::class,
            // Home page defaults (texts + hero slides + links)
            HomePageJsonSeeder::class,
            HomePageSlidesSeeder::class,
        ]);
    }
}
