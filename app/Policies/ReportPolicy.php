<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    /**
     * §2.2 "Reports" row: farmer ❌, technician ✅, admin ✅.
     * viewAny rather than per-model view, since reports aren't
     * farmer-scoped at all — access is role-gated, full stop.
     */
    public function viewAny(User $user): bool
    {
        return $user->isTechnician() || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isTechnician() || $user->isAdmin();
    }
}
