<? php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id', 'user_id', 'amount', 'deposit_amount',
        'status', 'is_auto_bid', 'max_auto_bid', 'ip_address',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'max_auto_bid' => 'decimal:2',
        'is_auto_bid' => 'boolean',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->hasOne(AuctionDeposit::class);
    }
}