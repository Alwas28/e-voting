<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id', 'nim', 'name', 'faculty', 'department',
        'email', 'phone',
        'face_descriptor', 'face_photo',
        'registered_at', 'has_voted', 'voted_at', 'is_active',
    ];

    protected $casts = [
        'face_descriptor' => 'array',
        'has_voted'       => 'boolean',
        'is_active'       => 'boolean',
        'registered_at'   => 'datetime',
        'voted_at'        => 'datetime',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    public function hasFace(): bool
    {
        return !is_null($this->face_descriptor);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $i = strtoupper(substr($words[0], 0, 1));
        if (isset($words[1])) {
            $i .= strtoupper(substr($words[1], 0, 1));
        }
        return $i;
    }

    public function getVoterCodeAttribute(): string
    {
        return 'DPT-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }
}
