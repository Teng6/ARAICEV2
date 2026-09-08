<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scan extends Model
{
    use HasUuids;

    protected $fillable = [
        'farmer_id',
        'variety_id',
        'disease_id',
        'image_url',
        'confidence_score',
        'status',
        'gps_lat',
        'gps_long',
        'scan_date',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'decimal:4',
            'gps_lat' => 'decimal:7',
            'gps_long' => 'decimal:7',
            'scan_date' => 'datetime',
        ];
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id');
    }

    public function variety(): BelongsTo
    {
        return $this->belongsTo(RiceVariety::class, 'variety_id');
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * REQUIRED scope for every farmer-facing query. §2.2 enforcement rule:
     * "there is zero tolerance for a query that forgets this scope."
     * Controllers should call this rather than filtering ad hoc.
     */
    public function scopeOwnedByFarmer(Builder $query, string $farmerProfileId): Builder
    {
        return $query->where('farmer_id', $farmerProfileId);
    }
}
