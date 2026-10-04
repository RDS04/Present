<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteCommitteeVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'voter_name',
        'voter_nim',
        'voter_identifier',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relationship to Candidate
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(FavoriteCommitteeCandidate::class, 'candidate_id');
    }
}
