<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    use HasUuids;

    // Disease-level only — deliberately no farmer_id/scan_id. See
    // ARAICE_PAPER_AND_ERD_CHANGES.md §1.
    protected $fillable = ['disease_id', 'title', 'description', 'type'];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }
}
