<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nim',
        'phone',
        'committee_section_id',
        'event_day_id',
        'notes',
    ];

    /**
     * Relasi ke Seksi Panitia
     */
    public function committeeSection(): BelongsTo
    {
        return $this->belongsTo(CommitteeSection::class, 'committee_section_id');
    }

    /**
     * Relasi ke Sesi / Event Day
     */
    public function eventDay(): BelongsTo
    {
        return $this->belongsTo(EventDay::class, 'event_day_id');
    }
}
