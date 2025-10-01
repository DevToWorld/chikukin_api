<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsV2Page;
use App\Models\CmsV2Override;

class CmsV2RenameAboutusSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Remove existing 'aboutus' entry if it exists (cleanup duplicates)
        $existingAboutUs = CmsV2Page::where('slug', 'aboutus')->first();
        if ($existingAboutUs) {
            // Detach overrides that point to this slug (we will recreate/retarget to the unified page below)
            CmsV2Override::where('slug', 'aboutus')->delete();
            $existingAboutUs->delete();
        }

        // 2) Rename 'about-institute' to 'aboutus'
        $aboutInstitute = CmsV2Page::where('slug', 'about-institute')->first();
        if ($aboutInstitute) {
            // Update overrides first to avoid unique conflicts
            CmsV2Override::where('slug', 'about-institute')->update([
                'slug' => 'aboutus',
                'page_id' => $aboutInstitute->id,
            ]);

            $aboutInstitute->slug = 'aboutus';
            $aboutInstitute->save();
        }
    }
}

