<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route-level backstop, e.g. Route::middleware('role:admin').
 * This is NOT a substitute for Policies — per build plan §2.2, "all of the
 * above must be enforced server-side (Laravel Policies/Gates + route
 * middleware)". Use this for coarse route-group gating (e.g. the entire
 * admin technician-management section); use Policies for per-model checks.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $allowed = array_map(fn (string $r) => UserRole::from($r), $roles);

        if (! in_array($user->role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
