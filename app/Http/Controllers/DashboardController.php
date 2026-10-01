<?php

namespace App\Http\Controllers;

use App\Models\EventDay;
use App\Models\Group;
use App\Models\Student;
use App\Services\RecapService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected RecapService $recapService
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        $totalStudents = Student::count();
        $totalCommittee = \App\Models\CommitteeAttendance::distinct('name')->count('name');
        $totalCommitteeSections = \App\Models\CommitteeSection::count();
        $totalGroups = Group::count();
        $totalEventDays = EventDay::count();
        $activeSession = EventDay::where('is_active', true)->first();
        $allSessions = EventDay::orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        // Calculate summary using RecapService
        $recapData = $this->recapService->getRecapData();

        $userGroup = null;
        if ($user->isPendampingGugus()) {
            $userGroup = Group::where('pj_user_id', $user->id)->withCount('students')->first();
        }

        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'totalCommittee' => $totalCommittee,
            'totalCommitteeSections' => $totalCommitteeSections,
            'totalGroups' => $totalGroups,
            'totalEventDays' => $totalEventDays,
            'activeSession' => $activeSession,
            'allSessions' => $allSessions,
            'summary' => $recapData['summary'],
            'userGroup' => $userGroup,
        ]);
    }
}
