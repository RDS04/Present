<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_name',
        'session_name',
        'date',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Catatan Presensi pada Sesi ini
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'event_day_id');
    }

    /**
     * Helper title untuk display
     */
    public function getFullSessionTitleAttribute(): string
    {
        return "{$this->day_name} - {$this->session_name}";
    }
}
