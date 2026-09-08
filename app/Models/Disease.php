<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disease extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'description', 'prevention_tips'];

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function outbreaks(): HasMany
    {
        return $this->hasMany(Outbreak::class);
    }
}
