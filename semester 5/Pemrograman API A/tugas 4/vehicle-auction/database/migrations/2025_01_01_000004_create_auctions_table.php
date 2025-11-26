<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('starting_price', 15, 2);
            $table->decimal('reserve_price', 15, 2)->nullable();
            $table->decimal('buy_now_price', 15, 2)->nullable();
            $table->decimal('min_bid_increment', 15, 2)->default(100000);
            $table->decimal('current_price', 15, 2)->nullable();
            $table->decimal('deposit_percentage', 5, 2)->default(10. 00);
            $table->integer('payment_deadline_hours')->default(24);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->datetime('original_end_time');
            $table->enum('status', ['scheduled', 'active', 'ended', 'sold', 'cancelled', 'reopened'])->default('scheduled');
            $table->foreignId('winner_id')->nullable()->constrained('users');
            $table->decimal('winning_amount', 15, 2)->nullable();
            $table->datetime('payment_deadline')->nullable();
            $table->integer('reopen_count')->default(0);
            $table->integer('max_reopen')->default(2);
            $table->integer('total_bids')->default(0);
            $table->integer('total_views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('deposit_amount', 15, 2);
            $table->enum('status', ['active', 'outbid', 'won', 'cancelled', 'forfeited'])->default('active');
            $table->boolean('is_auto_bid')->default(false);
            $table->decimal('max_auto_bid', 15, 2)->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index(['auction_id', 'amount']);
        });

        Schema::create('auction_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bid_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['held', 'released', 'forfeited', 'refunded'])->default('held');
            $table->datetime('forfeited_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('auction_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('deposit_used', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method');
            $table->string('payment_gateway');
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_payments');
        Schema::dropIfExists('auction_deposits');
        Schema::dropIfExists('bids');
        Schema::dropIfExists('auctions');
    }
};