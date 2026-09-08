<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rows here are a SNAPSHOT written once, at alert-creation time, by
     * joining against farmer_profiles.municipality — never a live query
     * re-evaluated later. Per build plan §3: "municipality assignment
     * could change" and must not silently move who received a past alert.
     * See App\Models\Outbreak / whatever service triggers alert creation
     * in Phase 2 for where these rows actually get written.
     */
    public function up(): void
    {
        Schema::create('alert_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('alert_id')->constrained('alerts')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['alert_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_user');
    }
};
