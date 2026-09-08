<?php

namespace App\Policies;

use App\Models\User;

/**
 * Shared by Disease, Treatment, and RiceVariety — the ERD's "reference
 * data" trio share one rule in the access matrix, so one policy class
 * (registered three times in AuthServiceProvider) instead of three
 * near-identical files.
 *
 * §2.2 "Manage reference data" row: farmer ❌, technician ❌, admin ✅ CRUD.
 * §2.2 "Resource/encyclopedia" row: farmer ✅ read (public, ungated),
 * technician ✅ read, admin ✅ full CRUD.
 */
class ReferenceDataPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // public/ungated read for every authenticated role — §2.7
    }

    public function view(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}
