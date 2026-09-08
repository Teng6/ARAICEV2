<?php

namespace App\Policies;

use App\Models\Alert;
use App\Models\User;

class AlertPolicy
{
    /**
     * §2.2 "Alerts" row: all roles receive. For farmers, "receive" means
     * scoped to their own municipality (§2.5) — enforced by only ever
     * loading alerts through $user->alerts() (the alert_user snapshot),
     * never by a live municipality filter here.
     */
    public function view(User $user, Alert $alert): bool
    {
        if ($user->isTechnician() || $user->isAdmin()) {
            return true; // "visibility into alerts is all of them" — §2.5
        }

        return $user->alerts()->where('alerts.id', $alert->id)->exists();
    }

    /**
     * Alerts are never authored directly — they're auto-generated when an
     * outbreak crosses threshold (§2.4). "Trigger via outbreak" in the
     * access matrix means triggering the outbreak event, not calling an
     * alert-creation endpoint directly. See OutbreakPolicy::close() /
     * the Phase 2 detection service for the actual trigger path.
     */
    public function create(User $user): bool
    {
        return false;
    }
}
