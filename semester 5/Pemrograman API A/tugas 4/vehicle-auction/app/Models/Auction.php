<? php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id', 'seller_id', 'starting_price', 'reserve_price',
        'buy_now_price', 'min_bid_increment', 'current_price',
        'deposit_percentage', 'payment_deadline_hours', 'start_time',
        'end_time', 'original_end_time', 'status', 'winner_id',
        'winning_amount', 'payment_deadline', 'reopen_count', 'max_reopen',
        'total_bids', 'total_views',
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'reserve_price' => 'decimal:2',
        'buy_now_price' => 'decimal:2',
        'min_bid_increment' => 'decimal:2',
        'current_price' => 'decimal:2',
        'deposit_percentage' => 'decimal:2',
        'winning_amount' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'original_end_time' => 'datetime',
        'payment_deadline' => 'datetime',
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class)->orderByDesc('amount');
    }

    public function highestBid()
    {
        return $this->hasOne(Bid::class)->orderByDesc('amount');
    }

    public function deposits()
    {
        return $this->hasMany(AuctionDeposit::class);
    }

    public function payments()
    {
        return $this->hasMany(AuctionPayment::class);
    }

    public function socialPosts()
    {
        return $this->hasMany(SocialPost::class);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               $this->start_time <= now() && 
               $this->end_time > now();
    }

    public function hasEnded(): bool
    {
        return $this->end_time <= now();
    }

    public function getRemainingTime(): int
    {
        if ($this->hasEnded()) return 0;
        return now()->diffInSeconds($this->end_time);
    }

    public function getNextMinBid(): float
    {
        $currentPrice = $this->current_price ?? $this->starting_price;
        return $currentPrice + $this->min_bid_increment;
    }

    public function getDepositAmount(): float
    {
        $currentPrice = $this->current_price ?? $this->starting_price;
        return $currentPrice * ($this->deposit_percentage / 100);
    }

    public function canReopen(): bool
    {
        return $this->reopen_count < $this->max_reopen;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now());
    }

    public function scopeEnding($query, $minutes = 60)
    {
        return $query->active()
            ->where('end_time', '<=', now()->addMinutes($minutes));
    }
}