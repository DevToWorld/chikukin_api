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
        if (!Schema::hasTable('seminars')) {
            Schema::create('seminars', function (Blueprint $table) {
                $table->id();
                $table->string('title', 200);
                $table->text('description')->nullable();
                $table->longText('detailed_description')->nullable();
                $table->date('date');
                $table->time('start_time');
                $table->time('end_time');
                $table->string('location', 200)->nullable();
                $table->integer('capacity')->default(0);
                $table->integer('current_participants')->default(0);
                $table->decimal('fee', 10, 2)->default(0.00);
                $table->enum('status', ['draft', 'scheduled', 'ongoing', 'completed', 'cancelled'])->default('draft');
                $table->enum('membership_requirement', ['none', 'basic', 'standard', 'premium'])->default('none');
                $table->string('featured_image', 500)->nullable();
                $table->date('application_deadline')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('contact_phone', 20)->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->constrained('admins')->onDelete('set null');
                $table->timestamps();
                
                // インデックス
                $table->index(['date']);
                $table->index(['status']);
                $table->index(['membership_requirement']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminars');
    }
};
