<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isFarmer(): bool
    {
        return $this->role === UserRole::Farmer;
    }

    public function isTechnician(): bool
    {
        return $this->role === UserRole::Technician;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Only present for farmers. Technicians/admins have no profile row —
     * ERD has no such table for them (build plan §2.1, §2.2).
     */
    public function farmerProfile(): HasOne
    {
        return $this->hasOne(FarmerProfile::class);
    }

    /**
     * Scans this user personally submitted — always populated regardless
     * of role, per §3 (uploaded_by is never null).
     */
    public function uploadedScans(): HasMany
    {
        return $this->hasMany(Scan::class, 'uploaded_by');
    }

    /**
     * Outbreaks this user (technician/admin) manually closed. §2.4:
     * outbreaks never auto-resolve.
     */
    public function closedOutbreaks(): HasMany
    {
        return $this->hasMany(Outbreak::class, 'closed_by');
    }

    /**
     * Alerts targeted at this user, via the snapshot join table. For
     * farmers this is populated by municipality at alert-creation time
     * (§2.5) — not filtered live here.
     */
    public function alerts(): BelongsToMany
    {
        return $this->belongsToMany(Alert::class, 'alert_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function reportsGenerated(): HasMany
    {
        return $this->hasMany(Report::class, 'generated_by');
    }
}
