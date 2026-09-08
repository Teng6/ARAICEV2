<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NEW TABLE — not in the original ERD. Required per
     * ARAICE_WEB_BUILD_PLAN.md §3: "Nothing in the current ERD tracks
     * outbreak state."
     *
     * The actual detection query (dedup-by-farmer, threshold=3, per
     * disease/municipality) is Phase 2 work — this migration only lays
     * the foundation table so Phase 0 schema work is complete up front.
     */
    public function up(): void
    {
        Schema::create('outbreaks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('disease_id')->constrained('diseases');

            // Same granularity as alerts/map per §2.4-§2.6. Deliberately a
            // plain string, not a municipalities FK table — no such table
            // exists in the ERD and none was decided.
            $table->string('municipality');

            $table->enum('status', ['active', 'resolved'])->default('active');

            // Outbreaks never auto-resolve (§2.4) — closed_by/closed_at stay
            // null until a technician or admin manually closes it.
            $table->foreignUuid('closed_by')->nullable()->constrained('users');
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbreaks');
    }
};
