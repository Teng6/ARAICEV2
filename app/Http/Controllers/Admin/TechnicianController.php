<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

/**
 * §2.1: "Technician — Created by Admin only." This route is expected to sit
 * behind Route::middleware(['auth', 'role:admin']) — see routes/web.php.
 * Technicians have no profile table (unlike farmers), so this is a plain
 * User::create with role=technician; no second write needed.
 */
class TechnicianController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Admin/CreateTechnician');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::Technician,
        ]);

        return redirect()
            ->route('admin.technicians.index')
            ->with('success', 'Technician account created.');
    }
}
