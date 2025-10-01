<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('email_campaigns', 'is_template')) {
                $table->boolean('is_template')->default(false)->after('status')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('email_campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('email_campaigns', 'is_template')) {
                $table->dropColumn('is_template');
            }
        });
    }
};

