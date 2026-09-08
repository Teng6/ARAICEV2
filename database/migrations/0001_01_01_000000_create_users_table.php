<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // NOT in the original ERD (which only had email/password/role) —
            // added because technicians and admins have no profile table of
            // their own (only farmers get FARMER_PROFILES.full_name).
            // Nullable since it's not part of the locked schema decisions;
            // confirm with the team before relying on it being populated.
            $table->string('name')->nullable();

            $table->string('email')->unique();
            $table->string('password');

            // ERD had `role string`. Locked to an enum here since the three
            // roles (farmer/technician/admin) are fixed and known up front —
            // see build plan §2.1. Keep app/Enums/UserRole.php in sync if
            // this ever changes.
            $table->enum('role', ['farmer', 'technician', 'admin']);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
