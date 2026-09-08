<?php

namespace App\Policies;

use App\Models\Scan;
use App\Models\User;

class ScanPolicy
{
    /**
     * Access matrix §2.2, "Submit scan" row: farmer ✅ own farm only,
     * technician ✅, admin ✅.
     */
    public function create(User $user): bool
    {
        return true; // all three roles can submit; farm-ownership for
                     // farmers is enforced in the form request / controller
                     // when farmer_id is set, not here.
    }

    /**
     * §2.2, "View scans" row: farmer ✅ own only, technician/admin ✅ all.
     */
    public function view(User $user, Scan $scan): bool
    {
        if ($user->isTechnician() || $user->isAdmin()) {
            return true;
        }

        if ($user->isFarmer()) {
            // Zero-tolerance scoping per §2.2 enforcement rule — compare
            // against the user's own farmer profile, never trust a
            // client-supplied farmer_id.
            return $user->farmerProfile?->id === $scan->farmer_id;
        }

        return false;
    }

    /**
     * No role is granted scan deletion/editing in the access matrix.
     * Leaving these explicit (rather than omitted) so a future addition
     * doesn't silently fall through to Laravel's default-deny in a way
     * that's easy to miss during review.
     */
    public function update(User $user, Scan $scan): bool
    {
        return false;
    }

    public function delete(User $user, Scan $scan): bool
    {
        return false;
    }
}
