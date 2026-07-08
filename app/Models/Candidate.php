<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model
{
    protected $fillable = [
        'election_period_id', 'alumni_id', 'number', 'name',
        'photo', 'vision', 'mission', 'profile',
        'faculty', 'department', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class, 'election_period_id');
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/candidate-placeholder.png');
    }

    public function deletePhoto(): void
    {
        if ($this->photo) {
            Storage::disk('public')->delete($this->photo);
        }
    }
}
