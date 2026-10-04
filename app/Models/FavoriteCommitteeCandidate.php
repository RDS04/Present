<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FavoriteCommitteeCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'photo',
        'description',
        'votes_count',
    ];

    /**
     * Relationship to Votes
     */
    public function votes(): HasMany
    {
        return $this->hasMany(FavoriteCommitteeVote::class, 'candidate_id');
    }

    /**
     * Accessor for full photo URL
     */
    public function getPhotoUrlAttribute(): string
    {
        if (empty($this->photo)) {
            return asset('images/default-avatar.png');
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        return asset($this->photo);
    }
}
