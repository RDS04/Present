<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventDayController;
use App\Http\Controllers\RecapController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\PublicAttendanceController;

Route::get('/', function () {
    return redirect()->route('present');
});

use App\Http\Controllers\GroupController;
use App\Http\Controllers\PublicPanitiaAttendanceController;
use App\Http\Controllers\CommitteeSectionController;

// Portal Presensi Mandiri Mahasiswa Baru (Public)
Route::get('/present', [PublicAttendanceController::class, 'index'])->name('present');
Route::post('/present', [PublicAttendanceController::class, 'store'])->name('present.store');

// Portal Presensi Mandiri Panitia (Public)
Route::get('/present-panitia', [PublicPanitiaAttendanceController::class, 'index'])->name('present.panitia');
Route::post('/present-panitia', [PublicPanitiaAttendanceController::class, 'store'])->name('present.panitia.store');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Presensi Massal (Bulk Attendance)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/input/{eventDay}/{group}', [AttendanceController::class, 'inputForm'])->name('attendance.input');
    Route::post('/attendance/bulk', [AttendanceController::class, 'bulkStore'])->name('attendance.bulk');

    // Update & Hapus Presensi (Hanya Admin Sekretariat)
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])
        ->middleware(CheckRole::class . ':admin_sekretariat')
        ->name('attendance.update');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])
        ->middleware(CheckRole::class . ':admin_sekretariat')
        ->name('attendance.destroy');

    // Modul Rekapitulasi Kehadiran & Kelulusan
    Route::get('/recap', [RecapController::class, 'index'])->name('recap.index');
    Route::get('/recap/export', [RecapController::class, 'export'])->name('recap.export');

    // Kelola Sesi Acara, Gugus & Seksi Panitia (Hanya Admin Sekretariat)
    Route::middleware(CheckRole::class . ':admin_sekretariat')->group(function () {
        Route::get('/event-days', [EventDayController::class, 'index'])->name('event-days.index');
        Route::get('/event-days/{eventDay}', [EventDayController::class, 'show'])->name('event-days.show');
        Route::get('/event-days/{eventDay}/export', [EventDayController::class, 'exportCsv'])->name('event-days.export');
        Route::post('/event-days', [EventDayController::class, 'store'])->name('event-days.store');
        Route::post('/event-days/{eventDay}/launch', [EventDayController::class, 'launch'])->name('event-days.launch');
        Route::patch('/event-days/{eventDay}/toggle', [EventDayController::class, 'toggleActive'])->name('event-days.toggle');
        Route::delete('/event-days/{eventDay}', [EventDayController::class, 'destroy'])->name('event-days.destroy');

        // Kelola Gugus PKKMB
        Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
        Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
        Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

        // Kelola Seksi Panitia & Data Absensi Panitia
        Route::get('/committee-sections', [CommitteeSectionController::class, 'index'])->name('committee-sections.index');
        Route::get('/committee-sections/export/excel', [CommitteeSectionController::class, 'exportExcel'])->name('committee-sections.export-excel');
        Route::get('/committee-sections/export/pdf', [CommitteeSectionController::class, 'exportPdf'])->name('committee-sections.export-pdf');
        Route::post('/committee-sections', [CommitteeSectionController::class, 'store'])->name('committee-sections.store');
        Route::delete('/committee-sections/{committeeSection}', [CommitteeSectionController::class, 'destroy'])->name('committee-sections.destroy');
        Route::delete('/committee-sections/attendance/{attendance}', [CommitteeSectionController::class, 'destroyAttendance'])->name('committee-sections.destroy-attendance');
    });
});

require __DIR__.'/auth.php';
