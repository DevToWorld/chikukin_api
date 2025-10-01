<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('news')) {
            // 既に存在する場合は何もしない
            return;
        }
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->text('content')->nullable(); // 詳細な内容
            $table->enum('category', ['seminar', 'publication', 'notice', 'research', 'general'])->default('general');
            $table->enum('type', ['news', 'seminar', 'publication', 'notice'])->default('news');
            $table->string('featured_image')->nullable();
            $table->date('published_date');
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->enum('membership_requirement', ['none', 'basic', 'standard', 'premium'])->default('none');
            $table->integer('view_count')->default(0);
            $table->boolean('is_featured')->default(false); // 注目記事フラグ
            $table->unsignedBigInteger('created_by'); // 作成者（Adminユーザー）
            $table->unsignedBigInteger('updated_by')->nullable(); // 更新者（Adminユーザー）
            $table->timestamps();

            // 外部キー制約
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('set null');

            // インデックス
            $table->index(['published_date', 'status']);
            $table->index(['category', 'status']);
            $table->index(['type', 'status']);
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
