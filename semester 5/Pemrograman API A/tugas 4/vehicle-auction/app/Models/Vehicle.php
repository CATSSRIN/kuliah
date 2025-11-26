<? php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'brand_id', 'model_id', 'type', 'title', 'slug',
        'description', 'year', 'mileage', 'color', 'transmission',
        'fuel_type', 'engine_capacity', 'plate_number', 'stnk_expired',
        'condition', 'postal_code', 'kelurahan', 'kecamatan', 'city',
        'province', 'latitude', 'longitude', 'status', 'rejection_reason',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function images()
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(VehicleImage::class)->where('is_primary', true);
    }

    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    public function activeAuction()
    {
        return $this->hasOne(Auction::class)->where('status', 'active');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByArea($query, $postalCode)
    {
        return $query->where('postal_code', $postalCode);
    }

    public function scopeSearch($query, $filters)
    {
        return $query
            ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($filters['brand_id'] ?? null, fn($q, $brand) => $q->where('brand_id', $brand))
            ->when($filters['model_id'] ?? null, fn($q, $model) => $q->where('model_id', $model))
            ->when($filters['year_from'] ?? null, fn($q, $year) => $q->where('year', '>=', $year))
            ->when($filters['year_to'] ?? null, fn($q, $year) => $q->where('year', '<=', $year))
            ->when($filters['city'] ?? null, fn($q, $city) => $q->where('city', $city))
            ->when($filters['province'] ?? null, fn($q, $province) => $q->where('province', $province));
    }
}