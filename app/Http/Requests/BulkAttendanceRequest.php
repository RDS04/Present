<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'event_day_id' => ['required', 'exists:event_days,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'attendances' => ['required', 'array'],
            'attendances.*.status' => ['required', 'in:hadir,izin,sakit,alpa'],
            'attendances.*.notes' => ['nullable', 'string', 'max:500'],
            'attendances.*.proof_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_day_id.required' => 'Sesi acara harus dipilih.',
            'group_id.required' => 'Gugus harus dipilih.',
            'attendances.required' => 'Data presensi tidak boleh kosong.',
            'attendances.*.status.in' => 'Status presensi harus salah satu dari: Hadir, Izin, Sakit, atau Alpa.',
            'attendances.*.proof_file.mimes' => 'Bukti file harus berformat JPG, PNG, atau PDF.',
            'attendances.*.proof_file.max' => 'Ukuran bukti file maksimal 2MB.',
        ];
    }
}
