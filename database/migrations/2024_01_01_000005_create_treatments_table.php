<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Disease-level only, never per-farmer/per-scan — see
            // ARAICE_PAPER_AND_ERD_CHANGES.md §1. There is deliberately no
            // farmer_id or scan_id on this table.
            $table->foreignUuid('disease_id')->constrained('diseases')->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
