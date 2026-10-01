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
        // 1. Groups Table (Gugus PKKMB)
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('pj_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Students Table (Mahasiswa Baru)
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('study_program');
            $table->string('faculty')->default('Umum');
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Event Days Table (Hari/Sesi Kegiatan PKKMB)
        Schema::create('event_days', function (Blueprint $table) {
            $table->id();
            $table->string('day_name'); // e.g. Hari 1, Hari 2
            $table->string('session_name'); // e.g. Sesi Pagi, Sesi Sore
            $table->date('date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Attendances Table (Catatan Presensi Mahasiswa)
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('event_day_id')->constrained('event_days')->cascadeOnDelete();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('alpa');
            $table->string('proof_file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Unique index for [student_id, event_day_id]
            $table->unique(['student_id', 'event_day_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('event_days');
        Schema::dropIfExists('students');
        Schema::dropIfExists('groups');
    }
};
