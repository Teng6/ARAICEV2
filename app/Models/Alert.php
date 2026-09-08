<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alert extends Model
{
    use HasUuids;

    protected $fillable = [
        'outbreak_id',
        'disease_id',
        'municipality',
        'message',
        'severity',
    ];

    public function outbreak(): BelongsTo
    {
        return $this->belongsTo(Outbreak::class);
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Snapshotted at creation time (build plan §3) — this is a historical
     * record of who was notified, not a live re-computation. Do not join
     * against farmer_profiles.municipality here as a shortcut.
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'alert_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }
}
