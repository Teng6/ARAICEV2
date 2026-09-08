<?php

namespace App\Policies;

use App\Models\Outbreak;
use App\Models\User;

class OutbreakPolicy
{
    /**
     * §2.2 "2D map" row: farmers see outbreaks in their own municipality
     * only; technician/admin see all, province-wide.
     */
    public function view(User $user, Outbreak $outbreak): bool
    {
        if ($user->isTechnician() || $user->isAdmin()) {
            return true;
        }

        if ($user->isFarmer()) {
            return $user->farmerProfile?->municipality === $outbreak->municipality;
        }

        return false;
    }

    /**
     * Outbreaks are created by the automated threshold detector (§2.4),
     * not directly by a user action — there's no manual "create outbreak"
     * UI per the locked decisions. This exists so the policy surface is
     * complete and explicit, matching the "no role has this" pattern in
     * ScanPolicy.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * §2.2 "Close an outbreak" row: farmer ❌, technician ✅, admin ✅.
     */
    public function close(User $user, Outbreak $outbreak): bool
    {
        return $user->isTechnician() || $user->isAdmin();
    }
}
