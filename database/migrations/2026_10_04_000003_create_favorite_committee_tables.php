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
        // Table for Candidates / Nominees (Kakak Panitia Terfavorit)
        Schema::create('favorite_committee_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section')->nullable(); // Seksi / Divisi (e.g. Acara, Humas, Pendamping)
            $table->string('photo'); // Path file foto
            $table->text('description')->nullable(); // Bio / Quote / Alasan
            $table->unsignedInteger('votes_count')->default(0);
            $table->timestamps();
        });

        // Table for Votes Log
        Schema::create('favorite_committee_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('favorite_committee_candidates')->cascadeOnDelete();
            $table->string('voter_identifier')->index(); // Hash cookie UUID / Session / IP
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique('voter_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_committee_votes');
        Schema::dropIfExists('favorite_committee_candidates');
    }
};
