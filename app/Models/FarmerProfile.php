<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmerProfile extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'full_name',
        'barangay',
        'municipality',
        'contact_number',
        'farm_lat',
        'farm_long',
    ];

    protected function casts(): array
    {
        return [
            'farm_lat' => 'decimal:7',
            'farm_long' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Every farmer-scoped query in the app should go through this
     * relationship or an equivalent explicit `farmer_id` filter — never a
     * query that "forgets" the scope. See build plan §2.2 enforcement rule.
     */
    public function scans(): HasMany
    {
        return $this->hasMany(Scan::class, 'farmer_id');
    }
}
