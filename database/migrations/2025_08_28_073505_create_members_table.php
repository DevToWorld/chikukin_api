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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('company_name', 200);
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
            
            // インデックス
            $table->index(['email']);
            $table->index(['membership_type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
