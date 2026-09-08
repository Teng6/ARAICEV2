<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outbreak extends Model
{
    use HasUuids;

    protected $fillable = [
        'disease_id',
        'municipality',
        'status',
        'closed_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Manual close only — outbreaks never auto-resolve (§2.4). This does
     * NOT retract any alerts; they remain a permanent historical record.
     */
    public function close(User $closer): void
    {
        $this->update([
            'status' => 'resolved',
            'closed_by' => $closer->id,
            'closed_at' => now(),
        ]);
    }
}
