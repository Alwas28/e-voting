<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionSchedule extends Model
{
    protected $fillable = ['election_period_id', 'type', 'name', 'start_date', 'end_date', 'description'];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date'   => 'datetime',
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(ElectionPeriod::class, 'election_period_id');
    }

    public function getStatusAttribute(): string
    {
        $now = Carbon::now();
        if (!$this->start_date || !$this->end_date) return 'belum_diatur';
        if ($now->lt($this->start_date))              return 'belum_dimulai';
        if ($now->gt($this->end_date))                return 'selesai';
        return 'berlangsung';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'berlangsung'   => 'Berlangsung',
            'selesai'       => 'Selesai',
            'belum_dimulai' => 'Belum Dimulai',
            default         => 'Belum Diatur',
        };
    }

    public static function forPeriodAndType(ElectionPeriod $period, string $type): self
    {
        return static::firstOrCreate(
            ['election_period_id' => $period->id, 'type' => $type],
            ['name' => match ($type) {
                'dpt_registration' => 'Pendaftaran DPT',
                'election'         => 'Pemilihan Calon',
                default            => $type,
            }]
        );
    }
}
