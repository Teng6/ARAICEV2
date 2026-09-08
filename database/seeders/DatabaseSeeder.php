<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Bootstraps the one seed admin per §2.1 ("Admin — (seed/other admin)").
     * Every other admin and every technician must be created by an admin
     * through the app, not through further seeding — this is intentionally
     * the only account this seeder creates.
     *
     * CHANGE THE PASSWORD before this ever touches anything but a local
     * dev database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@araice.test'],
            [
                'name' => 'A-RAICE Admin',
                'password' => Hash::make('change-me-immediately'),
                'role' => UserRole::Admin,
            ]
        );

        // Reference data (diseases/treatments/varieties) is deliberately
        // NOT seeded here — build plan §4 flags real content as an open
        // item someone (likely admin) needs to own populating before
        // classification is demo-testable. Don't fill this with
        // placeholder rows that look real.
    }
}
