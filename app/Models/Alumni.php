<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'nim', 'name', 'faculty', 'department',
        'place_of_birth', 'date_of_birth',
        'graduation_year', 'ipk', 'email', 'phone', 'address', 'photo', 'is_active',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'graduation_year' => 'integer',
        'date_of_birth'   => 'date',
        'ipk'             => 'decimal:2',
    ];

    public function voter()
    {
        return $this->hasOne(\App\Models\Voter::class);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = strtoupper(substr($words[0], 0, 1));
        if (isset($words[1])) {
            $initials .= strtoupper(substr($words[1], 0, 1));
        }
        return $initials;
    }
}
