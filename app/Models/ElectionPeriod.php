<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionPeriod extends Model
{
    protected $fillable = ['name', 'year', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'year' => 'integer'];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ElectionSchedule::class);
    }

    public function dptSchedule()
    {
        return $this->schedules()->where('type', 'dpt_registration')->first();
    }

    public function electionSchedule()
    {
        return $this->schedules()->where('type', 'election')->first();
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }

    public function activate(): void
    {
        static::where('is_active', true)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }
}
