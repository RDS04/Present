<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    public const ROLE_ADMIN = 'admin_sekretariat';
    public const ROLE_PENDAMPING = 'pendamping_gugus';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Groups where this user is PJ (Penanggung Jawab)
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'pj_user_id');
    }

    /**
     * Attendances recorded by this user
     */
    public function recordedAttendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'recorded_by_user_id');
    }

    public function isAdminSekretariat(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPendampingGugus(): bool
    {
        return $this->role === self::ROLE_PENDAMPING;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
}
