<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

/**
 * §2.1: Farmer is the only role with self-signup. Technician is admin-only
 * (see Admin\TechnicianController) and Admin is seed/other-admin-created —
 * neither goes through this controller.
 */
class FarmerRegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/RegisterFarmer');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'barangay' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:32'],
            'farm_lat' => ['required', 'numeric', 'between:-90,90'],
            'farm_long' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::Farmer,
            ]);

            FarmerProfile::create([
                'user_id' => $user->id,
                'full_name' => $validated['full_name'],
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'contact_number' => $validated['contact_number'],
                'farm_lat' => $validated['farm_lat'],
                'farm_long' => $validated['farm_long'],
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
