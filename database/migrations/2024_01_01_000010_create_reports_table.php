<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            // DECISION MADE HERE: ERD named this column `report_id`; every
            // other entity uses `id`. Normalized per
            // ARAICE_PAPER_AND_ERD_CHANGES.md §6.5 so Eloquent doesn't need
            // a one-off primary key override.
            $table->uuid('id')->primary();

            $table->foreignUuid('generated_by')->constrained('users');
            $table->string('municipality');
            $table->date('range_start');
            $table->date('range_end');
            $table->text('summary_data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
