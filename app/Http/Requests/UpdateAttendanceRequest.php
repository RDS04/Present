<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya Admin Sekretariat yang boleh update individual dispensasi presensi
        return auth()->check() && auth()->user()->isAdminSekretariat();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:hadir,izin,sakit,alpa'],
            'notes' => ['nullable', 'string', 'max:500'],
            'proof_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }
}
