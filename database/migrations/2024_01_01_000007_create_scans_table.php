<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // NULLABLE per build plan §3 / §2.3: a technician or admin can
            // submit a scan for a field with no farmer account attached.
            // No retroactive linking, ever — see §2.3.
            $table->foreignUuid('farmer_id')->nullable()->constrained('farmer_profiles');

            // Nullable: whether variety is farmer-selected at submission or
            // model-detected is an open methodology question
            // (ARAICE_PAPER_AND_ERD_CHANGES.md §6.6). Nullable keeps the
            // submission form from being blocked on that decision.
            $table->foreignUuid('variety_id')->nullable()->constrained('rice_varieties');

            // Nullable: classification may still be pending (ViT model
            // integration point is undecided — build plan §4).
            $table->foreignUuid('disease_id')->nullable()->constrained('diseases');

            $table->string('image_url');
            $table->decimal('confidence_score', 5, 4)->nullable();

            // ASSUMPTION — not locked anywhere in the docs. The ERD has this
            // column with no defined values (ARAICE_PAPER_AND_ERD_CHANGES.md
            // §6.4), flagged as a prerequisite for the Phase 1 mock-
            // classification stub. Confirm this enum with whoever owns the
            // ViT integration before Phase 1 ships.
            $table->enum('status', ['pending', 'classified', 'failed'])->default('pending');

            $table->decimal('gps_lat', 10, 7);
            $table->decimal('gps_long', 10, 7);
            $table->timestamp('scan_date');

            // NEVER NULL per build plan §3 — this is the sole accountability
            // trail for technician/admin-submitted scans. Always the
            // authenticated user who performed the submission, regardless
            // of whether farmer_id is set.
            $table->foreignUuid('uploaded_by')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
