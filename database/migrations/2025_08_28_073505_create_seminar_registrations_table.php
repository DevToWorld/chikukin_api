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
        Schema::create('seminar_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name', 100);
            $table->string('email');
            $table->string('company', 200)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('special_requests')->nullable();
            $table->enum('attendance_status', ['registered', 'attended', 'cancelled', 'no_show'])->default('registered');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->string('registration_number', 50)->unique();
            $table->timestamps();
            
            // インデックス
            $table->index(['seminar_id']);
            $table->index(['member_id']);
            $table->index(['email']);
            $table->index(['registration_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar_registrations');
    }
};
