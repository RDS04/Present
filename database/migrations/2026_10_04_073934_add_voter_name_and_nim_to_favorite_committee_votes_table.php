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
        Schema::table('favorite_committee_votes', function (Blueprint $table) {
            $table->string('voter_name')->nullable()->after('candidate_id');
            $table->string('voter_nim')->nullable()->after('voter_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorite_committee_votes', function (Blueprint $table) {
            $table->dropColumn(['voter_name', 'voter_nim']);
        });
    }
};
