<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\AuctionDeposit;
use App\Models\User;
use App\Jobs\ProcessAuctionEnd;
use App\Jobs\SendNotification;
use App\Exceptions\AuctionException;
use Illuminate\Support\Facades\DB;

class AuctionService
{
    public function __construct(
        protected PaymentService $paymentService,
        protected NotificationService $notificationService
    ) {}

    public function placeBid(Auction $auction, User $user, float $amount): Bid
    {
        if (! $auction->isActive()) {
            throw new AuctionException('Lelang tidak aktif.');
        }

        if ($user->id === $auction->seller_id) {
            throw new AuctionException('Anda tidak bisa bid di lelang sendiri.');
        }

        $minBid = $auction->getNextMinBid();
        if ($amount < $minBid) {
            throw new AuctionException("Bid minimal adalah Rp " . number_format($minBid, 0, ',', '. '));
        }

        $depositAmount = $amount * ($auction->deposit_percentage / 100);
        
        if ($user->balance < $depositAmount) {
            throw new AuctionException("Saldo tidak cukup untuk deposit.  Diperlukan: Rp " .  number_format($depositAmount, 0, ',', '.'));
        }

        return DB::transaction(function () use ($auction, $user, $amount, $depositAmount) {
            // Hold deposit
            $user->decrement('balance', $depositAmount);
            $user->increment('deposit', $depositAmount);

            // Create bid
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'deposit_amount' => $depositAmount,
                'status' => 'active',
                'ip_address' => request()->ip(),
            ]);

            // Create deposit record
            AuctionDeposit::create([
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'bid_id' => $bid->id,
                'amount' => $depositAmount,
                'status' => 'held',
            ]);

            // Update auction
            $auction->update([
                'current_price' => $amount,
                'total_bids' => $auction->total_bids + 1,
            ]);

            // Mark previous highest bid as outbid
            Bid::where('auction_id', $auction->id)
                ->where('id', '!=', $bid->id)
                ->where('status', 'active')
                ->update(['status' => 'outbid']);

            // Send notifications
            $this->notifyOutbidUsers($auction, $bid);

            // Extend auction time if bid placed in last 5 minutes
            $this->extendAuctionIfNeeded($auction);

            return $bid;
        });
    }

    public function endAuction(Auction $auction): void
    {
        if ($auction->status !== 'active') {
            return;
        }

        DB::transaction(function () use ($auction) {
            $highestBid = $auction->highestBid;

            if ($highestBid) {
                // We have a winner
                $auction->update([
                    'status' => 'ended',
                    'winner_id' => $highestBid->user_id,
                    'winning_amount' => $highestBid->amount,
                    'payment_deadline' => now()->addHours($auction->payment_deadline_hours),
                ]);

                $highestBid->update(['status' => 'won']);

                // Notify winner
                $this->notificationService->sendWinNotification($auction);

                // Schedule payment deadline check
                ProcessAuctionEnd::dispatch($auction)
                    ->delay(now()->addHours($auction->payment_deadline_hours));

                // Release deposits of non-winners
                $this->releaseLoserDeposits($auction);
            } else {
                $auction->update(['status' => 'ended']);
            }
        });
    }

    public function handlePaymentTimeout(Auction $auction): void
    {
        if ($auction->status !== 'ended' || !$auction->winner_id) {
            return;
        }

        DB::transaction(function () use ($auction) {
            // Forfeit winner's deposit
            $winnerDeposit = AuctionDeposit::where('auction_id', $auction->id)
                ->where('user_id', $auction->winner_id)
                ->where('status', 'held')
                ->first();

            if ($winnerDeposit) {
                $winnerDeposit->update([
                    'status' => 'forfeited',
                    'forfeited_at' => now(),
                ]);

                $user = User::find($auction->winner_id);
                $user->decrement('deposit', $winnerDeposit->amount);
            }

            // Check if we can reopen
            if ($auction->canReopen()) {
                $this->reopenAuction($auction);
            } else {
                $auction->update(['status' => 'cancelled']);
            }

            // Notify about forfeit
            $this->notificationService->sendForfeitNotification($auction);
        });
    }

    public function reopenAuction(Auction $auction): void
    {
        $auction->update([
            'status' => 'reopened',
            'winner_id' => null,
            'winning_amount' => null,
            'payment_deadline' => null,
            'reopen_count' => $auction->reopen_count + 1,
            'start_time' => now(),
            'end_time' => now()->addDays(3),
        ]);

        // Reset to starting price if no other bids
        $secondHighestBid = $auction->bids()
            ->where('status', '!=', 'forfeited')
            ->orderByDesc('amount')
            ->skip(1)
            ->first();

        if ($secondHighestBid) {
            $auction->update(['current_price' => $secondHighestBid->amount]);
        } else {
            $auction->update(['current_price' => null]);
        }

        $auction->update(['status' => 'active']);

        // Notify about reopen
        $this->notificationService->sendReopenNotification($auction);
    }

    protected function notifyOutbidUsers(Auction $auction, Bid $newBid): void
    {
        $outbidUsers = Bid::where('auction_id', $auction->id)
            ->where('id', '!=', $newBid->id)
            ->where('status', 'outbid')
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id');

        foreach ($outbidUsers as $user) {
            SendNotification::dispatch($user, 'outbid', [
                'auction' => $auction,
                'new_amount' => $newBid->amount,
            ]);
        }
    }

    protected function releaseLoserDeposits(Auction $auction): void
    {
        $loserDeposits = AuctionDeposit::where('auction_id', $auction->id)
            ->where('user_id', '!=', $auction->winner_id)
            ->where('status', 'held')
            ->get();

        foreach ($loserDeposits as $deposit) {
            $user = User::find($deposit->user_id);
            $user->decrement('deposit', $deposit->amount);
            $user->increment('balance', $deposit->amount);
            
            $deposit->update(['status' => 'released']);
        }
    }

    protected function extendAuctionIfNeeded(Auction $auction): void
    {
        $remainingMinutes = now()->diffInMinutes($auction->end_time);
        
        if ($remainingMinutes <= 5) {
            $auction->update([
                'end_time' => $auction->end_time->addMinutes(5),
            ]);
        }
    }
}