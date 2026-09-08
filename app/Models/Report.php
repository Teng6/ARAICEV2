<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasUuids;

    protected $fillable = [
        'generated_by',
        'municipality',
        'range_start',
        'range_end',
        'summary_data',
    ];

    protected function casts(): array
    {
        return [
            'range_start' => 'date',
            'range_end' => 'date',
        ];
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
