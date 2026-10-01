<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\EventDay;
use App\Models\Group;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    /**
     * Halaman pilih Sesi Acara & Gugus untuk input presensi.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $eventDays = EventDay::orderBy('date', 'asc')->get();
        
        // Pendamping gugus hanya melihat gugus yang diampunya, admin melihat semua gugus
        if ($user->isPendampingGugus()) {
            $groups = Group::where('pj_user_id', $user->id)->get();
        } else {
            $groups = Group::with('pjUser')->get();
        }

        $activeEventDay = EventDay::where('is_active', true)->first() ?? $eventDays->first();
        $selectedGroupId = $request->input('group_id', $groups->first()->id ?? null);
        $selectedEventDayId = $request->input('event_day_id', $activeEventDay->id ?? null);

        return view('attendance.index', compact('eventDays', 'groups', 'selectedGroupId', 'selectedEventDayId'));
    }

    /**
     * Form Input Presensi Massal (Bulk Presensi) per Gugus dan Sesi Acara.
     */
    public function inputForm(EventDay $eventDay, Group $group, Request $request): View
    {
        $user = $request->user();

        // Validasi Otorisasi: Pendamping Gugus hanya boleh mengakses gugusnya sendiri
        if ($user->isPendampingGugus() && $group->pj_user_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengelola presensi untuk gugus yang Anda ampu.');
        }

        // Fetch mahasiswa di gugus tersebut beserta catatan presensinya pada sesi ini (Eager Loading)
        $students = $group->students()
            ->with(['attendances' => function ($q) use ($eventDay) {
                $q->where('event_day_id', $eventDay->id);
            }])
            ->orderBy('nim', 'asc')
            ->get();

        // Map existing attendance by student_id
        $attendancesMap = [];
        foreach ($students as $student) {
            $existing = $student->attendances->first();
            $attendancesMap[$student->id] = [
                'status' => $existing ? $existing->status : 'hadir', // Default hadir
                'notes' => $existing ? $existing->notes : '',
                'proof_file_path' => $existing ? $existing->proof_file_path : null,
            ];
        }

        return view('attendance.input', compact('eventDay', 'group', 'students', 'attendancesMap'));
    }

    /**
     * Submit Bulk Presensi
     */
    public function bulkStore(BulkAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $group = Group::findOrFail($validated['group_id']);
        if ($user->isPendampingGugus() && $group->pj_user_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengelola presensi untuk gugus yang Anda ampu.');
        }

        $count = $this->attendanceService->saveBulkAttendance(
            (int) $validated['event_day_id'],
            (int) $validated['group_id'],
            $validated['attendances'],
            $user
        );

        return redirect()
            ->route('attendance.index', ['group_id' => $group->id, 'event_day_id' => $validated['event_day_id']])
            ->with('success', "Berhasil menyimpan presensi untuk {$count} mahasiswa pada Gugus {$group->name}.");
    }

    /**
     * Admin Update Dispensasi Presensi Individu
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $validated = $request->validated();
        $this->attendanceService->updateSingleAttendance($attendance, $validated, $request->user());

        return redirect()->back()->with('success', 'Catatan presensi berhasil diperbarui oleh Admin.');
    }

    /**
     * Hapus Data Presensi Mahasiswa (Admin Only)
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        $studentName = $attendance->student->name ?? 'Mahasiswa';
        $attendance->delete();

        return redirect()->back()->with('success', "Data presensi '{$studentName}' berhasil dihapus.");
    }
}
