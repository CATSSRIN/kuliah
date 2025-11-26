<? php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'status',
        'balance', 'deposit', 'telegram_chat_id', 'whatsapp_number',
        'member_expired_at', 'avatar', 'address', 'postal_code',
        'kelurahan', 'kecamatan', 'city', 'province',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'member_expired_at' => 'date',
        'password' => 'hashed',
        'balance' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    // Relationships
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'seller_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function wonAuctions()
    {
        return $this->hasMany(Auction::class, 'winner_id');
    }

    public function memberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    public function activeMembership()
    {
        return $this->hasOne(UserMembership::class)
            ->where('status', 'active')
            ->where('expired_at', '>', now());
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    // Helpers
    public function isMember(): bool
    {
        return $this->role === 'member' || 
               ($this->activeMembership && $this->activeMembership->expired_at > now());
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canPostAd(): bool
    {
        if ($this->isMember()) return true;
        
        $weeklyAds = $this->vehicles()
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
            
        return $weeklyAds < 2;
    }

    public function getWeeklyAdsRemaining(): int
    {
        if ($this->isMember()) return -1; // unlimited
        
        $weeklyAds = $this->vehicles()
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
            
        return max(0, 2 - $weeklyAds);
    }
}