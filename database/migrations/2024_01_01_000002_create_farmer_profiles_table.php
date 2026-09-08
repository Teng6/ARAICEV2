<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // 1:1 with users, per ERD. unique() enforces that at the DB level.
            $table->foreignUuid('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->string('full_name');
            $table->string('barangay');

            // This is THE field alert targeting (§2.5) and map scoping (§2.6)
            // key off of. ALERT_USER population and the farmer's map view
            // both filter on this — do not rename without updating both.
            $table->string('municipality');

            $table->string('contact_number');
            $table->decimal('farm_lat', 10, 7);
            $table->decimal('farm_long', 10, 7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_profiles');
    }
};
