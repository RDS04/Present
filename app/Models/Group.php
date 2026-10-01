<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pj_user_id',
    ];

    /**
     * Penanggung Jawab (User - Pendamping Gugus)
     */
    public function pjUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pj_user_id');
    }

    /**
     * Mahasiswa Baru dalam Gugus
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'group_id');
    }
}
