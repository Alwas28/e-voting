<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = ['voter_id', 'candidate_id', 'election_period_id', 'voted_at'];

    protected function casts(): array
    {
        return ['voted_at' => 'datetime'];
    }

    public function voter(): BelongsTo
    {
        return $this->belongsTo(Voter::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class, 'election_period_id');
    }
}
