<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('regions')) {
            Schema::create('regions', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->string('name', 100);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            DB::table('regions')->insert([
                ['code' => 'fukuoka', 'name' => '福岡', 'sort_order' => 1],
                ['code' => 'saga',    'name' => '佐賀', 'sort_order' => 2],
                ['code' => 'nagasaki','name' => '長崎', 'sort_order' => 3],
                ['code' => 'oita',    'name' => '大分', 'sort_order' => 4],
                ['code' => 'kumamoto','name' => '熊本', 'sort_order' => 5],
                ['code' => 'miyazaki','name' => '宮崎', 'sort_order' => 6],
                ['code' => 'kagoshima','name' => '鹿児島', 'sort_order' => 7],
            ]);
        }

        if (!Schema::hasTable('industries')) {
            Schema::create('industries', function (Blueprint $table) {
                $table->id();
                $table->string('code', 80)->unique();
                $table->string('name', 150);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            DB::table('industries')->insert([
                ['code' => 'manufacturing', 'name' => '製造業', 'sort_order' => 1],
                ['code' => 'mining', 'name' => '鉱業', 'sort_order' => 2],
                ['code' => 'construction', 'name' => '建設業', 'sort_order' => 3],
                ['code' => 'transport', 'name' => '運輸交通業', 'sort_order' => 4],
                ['code' => 'government', 'name' => '官公署', 'sort_order' => 5],
                ['code' => 'cargo', 'name' => '貨物取扱業', 'sort_order' => 6],
                ['code' => 'agriculture', 'name' => '農林業', 'sort_order' => 7],
                ['code' => 'livestock_fishery', 'name' => '畜産・水産業', 'sort_order' => 8],
                ['code' => 'commerce', 'name' => '商業', 'sort_order' => 9],
                ['code' => 'finance_ad', 'name' => '金融・広告業', 'sort_order' => 10],
                ['code' => 'clean_slaughter', 'name' => '清掃・と畜業', 'sort_order' => 11],
                ['code' => 'film_theatre', 'name' => '映画・演劇業', 'sort_order' => 12],
                ['code' => 'telecom', 'name' => '通信業', 'sort_order' => 13],
                ['code' => 'education_research', 'name' => '教育・研究業', 'sort_order' => 14],
                ['code' => 'health', 'name' => '保健衛生業', 'sort_order' => 15],
                ['code' => 'hospitality', 'name' => '接客娯楽業', 'sort_order' => 16],
                ['code' => 'other', 'name' => 'その他の事業', 'sort_order' => 99],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('industries');
        Schema::dropIfExists('regions');
    }
};

