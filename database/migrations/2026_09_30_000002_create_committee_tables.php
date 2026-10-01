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
        // 1. Committee Sections Table (Seksi / Divisi Panitia)
        Schema::create('committee_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Committee Attendances Table (Catatan Presensi Panitia)
        Schema::create('committee_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nim')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('committee_section_id')->constrained('committee_sections')->cascadeOnDelete();
            $table->foreignId('event_day_id')->nullable()->constrained('event_days')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_attendances');
        Schema::dropIfExists('committee_sections');
    }
};
