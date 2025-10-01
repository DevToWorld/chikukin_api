<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('economic_reports') && !Schema::hasColumn('economic_reports', 'file_size_bytes')) {
            Schema::table('economic_reports', function (Blueprint $table) {
                $table->unsignedBigInteger('file_size_bytes')->nullable()->after('file_size');
            });

            // 可能な限り既存データを移行（decimalのfile_sizeがバイト値で保存されている前提）
            try {
                // file_size が1以上の場合をbytesとしてfile_size_bytesへコピー
                DB::table('economic_reports')
                    ->whereNotNull('file_size')
                    ->where('file_size', '>', 0)
                    ->update([
                        'file_size_bytes' => DB::raw('CAST(file_size AS BIGINT)')
                    ]);
            } catch (\Throwable $e) {
                // 失敗しても処理は続行
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('economic_reports') && Schema::hasColumn('economic_reports', 'file_size_bytes')) {
            Schema::table('economic_reports', function (Blueprint $table) {
                $table->dropColumn('file_size_bytes');
            });
        }
    }
};

