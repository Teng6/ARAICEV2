<?php

use App\Http\Controllers\Admin\TechnicianController;
use App\Http\Controllers\Auth\FarmerRegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

// §2.1: Farmer self-signup — no auth required to reach these, standard
// register-then-log-in flow.
Route::middleware('guest')->group(function () {
	Route::get('register', [FarmerRegisteredUserController::class, 'create'])
		->name('register');
	Route::post('register', [FarmerRegisteredUserController::class, 'store']);
});

// §2.1: Technician creation is admin-only. The 'role:admin' middleware
// alias must be registered — see bootstrap/app.php note below.
Route::middleware(['auth', 'role:admin'])
	->prefix('admin')
	->name('admin.')
	->group(function () {
		Route::get('technicians/create', [TechnicianController::class, 'create'])
			->name('technicians.create');
		Route::post('technicians', [TechnicianController::class, 'store'])
			->name('technicians.store');
		// technicians.index left for you to add once there's a listing
		// view — TechnicianController::store redirects there.
	});
