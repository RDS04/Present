<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommitteeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relasi ke presensi panitia
     */
    public function attendances(): HasMany
    {
        return $table = $this->hasMany(CommitteeAttendance::class, 'committee_section_id');
    }
}
