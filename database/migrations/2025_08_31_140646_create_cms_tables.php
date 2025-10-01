<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // セミナーテーブル
        if (!Schema::hasTable('seminars')) {
        Schema::create('seminars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('detailed_description')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->integer('capacity')->default(0);
            $table->integer('current_participants')->default(0);
            $table->decimal('fee', 10, 2)->default(0);
            $table->enum('status', ['draft', 'scheduled', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->string('featured_image')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        }

        // 刊行物カテゴリテーブル
        if (!Schema::hasTable('publication_categories')) {
        Schema::create('publication_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }

        // 刊行物テーブル
        if (!Schema::hasTable('publications')) {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('publication_categories')->nullOnDelete();
            $table->date('publication_date');
            $table->string('author')->default('ちくぎん地域経済研究所');
            $table->integer('pages')->default(0);
            $table->string('file_url')->nullable();
            $table->decimal('file_size', 8, 2)->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('membership_level', ['free', 'basic', 'standard', 'premium'])->default('free');
            $table->integer('download_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->text('tags')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
        }

        // お知らせカテゴリテーブル
        if (!Schema::hasTable('news_categories')) {
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('color', 7)->default('#da5761');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }

        // お知らせテーブル
        if (!Schema::hasTable('news')) {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('news_categories')->nullOnDelete();
            $table->string('featured_image')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->text('tags')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
        }

        // 管理者テーブル
        if (!Schema::hasTable('admins')) {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('full_name', 100);
            $table->enum('role', ['super_admin', 'admin', 'editor'])->default('admin');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
        }

        // 会員テーブル
        if (!Schema::hasTable('members')) {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('company_name');
            $table->string('representative_name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('address')->nullable();
            $table->enum('membership_type', ['basic', 'standard', 'premium'])->default('basic');
            $table->enum('status', ['pending', 'active', 'suspended', 'cancelled'])->default('pending');
            $table->date('joined_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_categories');
        Schema::dropIfExists('publications');
        Schema::dropIfExists('publication_categories');
        Schema::dropIfExists('seminars');
    }
};
