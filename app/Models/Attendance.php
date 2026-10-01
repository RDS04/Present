<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    public const STATUS_HADIR = 'hadir';
    public const STATUS_IZIN = 'izin';
    public const STATUS_SAKIT = 'sakit';
    public const STATUS_ALPA = 'alpa';

    protected $fillable = [
        'student_id',
        'event_day_id',
        'recorded_by_user_id',
        'status',
        'proof_file_path',
        'notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function eventDay(): BelongsTo
    {
        return $this->belongsTo(EventDay::class, 'event_day_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}
