<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\RecapService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecapController extends Controller
{
    public function __construct(
        protected RecapService $recapService
    ) {}

    /**
     * Halaman Rekapitulasi Kehadiran & Status Kelulusan
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Jika pendamping gugus, default filter ke gugus yang diampunya (atau boleh lihat per gugus)
        $groupId = $request->input('group_id');
        if ($user->isPendampingGugus() && !$groupId) {
            $userGroup = Group::where('pj_user_id', $user->id)->first();
            $groupId = $userGroup?->id;
        }

        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $recapResult = $this->recapService->getRecapData(
            $groupId ? (int) $groupId : null,
            $search,
            $statusFilter
        );

        $groups = Group::all();

        return view('recap.index', [
            'recap' => $recapResult['recap'],
            'summary' => $recapResult['summary'],
            'totalSessions' => $recapResult['total_sessions'],
            'groups' => $groups,
            'selectedGroupId' => $groupId,
            'search' => $search,
            'selectedStatus' => $statusFilter,
        ]);
    }

    /**
     * Export Rekapitulasi ke Excel / CSV
     */
    public function export(Request $request): StreamedResponse
    {
        $groupId = $request->input('group_id');
        return $this->recapService->exportCsv($groupId ? (int) $groupId : null);
    }
}
