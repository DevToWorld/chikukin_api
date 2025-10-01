<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('mail_groups')) {
            Schema::create('mail_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamps();
                $table->index('created_by');
            });
        }

        if (!Schema::hasTable('mail_group_members')) {
            Schema::create('mail_group_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_id')->constrained('mail_groups')->cascadeOnDelete();
                $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
                $table->timestamp('created_at')->nullable();
                $table->unique(['group_id', 'member_id'], 'uq_mail_group_member');
                $table->index('group_id');
                $table->index('member_id');
            });
        }

        if (!Schema::hasTable('email_campaigns')) {
            Schema::create('email_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('subject', 500);
                $table->longText('body_html')->nullable();
                $table->longText('body_text')->nullable();
                $table->string('status', 20)->default('draft'); // draft|scheduled|sending|sent|failed
                $table->timestamp('scheduled_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
                $table->timestamps();
                $table->index('status');
                $table->index('scheduled_at');
                $table->index('created_by');
            });
        }

        if (!Schema::hasTable('email_recipients')) {
            Schema::create('email_recipients', function (Blueprint $table) {
                $table->id();
                $table->foreignId('campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
                $table->string('email');
                $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
                $table->string('status', 20)->default('pending'); // pending|sent|failed
                $table->timestamp('sent_at')->nullable();
                $table->text('error')->nullable();
                $table->timestamps();
                $table->index('campaign_id');
                $table->index('status');
                $table->index('email');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_recipients');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('mail_group_members');
        Schema::dropIfExists('mail_groups');
    }
};

