<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * Tampilkan Daftar Gugus PKKMB & Form Tambah
     */
    public function index(): View
    {
        $groups = Group::withCount('students')->with('pjUser')->orderBy('id', 'asc')->get();
        $pendampings = User::where('role', User::ROLE_PENDAMPING)->get();

        return view('groups.index', compact('groups', 'pendampings'));
    }

    /**
     * Simpan Gugus Baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:groups,name'],
            'pj_user_id' => ['nullable', 'exists:users,id'],
        ], [
            'name.required' => 'Nama Gugus wajib diisi.',
            'name.unique' => 'Nama Gugus sudah ada.',
        ]);

        Group::create($validated);

        return redirect()->back()->with('success', "Gugus '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Hapus Gugus
     */
    public function destroy(Group $group): RedirectResponse
    {
        $groupName = $group->name;
        $group->delete();

        return redirect()->back()->with('success', "Gugus '{$groupName}' berhasil dihapus.");
    }
}
