<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->enum('platform', ['facebook', 'instagram']);
            $table->string('account_name');
            $table->string('account_id');
            $table->string('access_token', 1000);
            $table->string('page_id')->nullable();
            $table->string('group_id')->nullable();
            $table->datetime('token_expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('social_account_id')->constrained()->onDelete('cascade');
            $table->enum('platform', ['facebook', 'instagram']);
            $table->string('post_id')->nullable();
            $table->text('content');
            $table->json('media_urls')->nullable();
            $table->enum('status', ['pending', 'posted', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->datetime('posted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_posts');
        Schema::dropIfExists('social_accounts');
    }
};