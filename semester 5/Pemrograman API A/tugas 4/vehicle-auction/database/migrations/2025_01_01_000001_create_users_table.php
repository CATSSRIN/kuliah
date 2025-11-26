<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'member', 'anggota'])->default('anggota');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('deposit', 15, 2)->default(0);
            $table->string('telegram_chat_id')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->date('member_expired_at')->nullable();
            $table->string('avatar')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};