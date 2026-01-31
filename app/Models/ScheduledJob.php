<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ScheduledJob extends Model
{
    protected $fillable = [
        'name',
        'command',
        'parameters',
        'schedule_type',
        'run_date',
        'run_time',
        'days_of_week',
        'day_of_month',
        'last_run_at',
        'next_run_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'parameters'     => 'array',
        'days_of_week'   => 'array',
        'run_date'       => 'date',
        'last_run_at'    => 'datetime',
        'next_run_at'    => 'datetime',
        'is_active'      => 'boolean',
    ];

    /**
     * Calculate next execution datetime
     */
    public function calculateNextRun(): ?Carbon
    {
        $now = now();

        return match ($this->schedule_type) {
            'once' => null,

            'daily' => $now->copy()
                ->addDay()
                ->setTimeFromTimeString($this->run_time),

            'weekly' => $this->nextWeeklyRun($now),

            'monthly' => $now->copy()
                ->addMonth()
                ->day($this->day_of_month ?? 1)
                ->setTimeFromTimeString($this->run_time),
        };
    }

    protected function nextWeeklyRun(Carbon $now): Carbon
    {
        $days = $this->days_of_week ?? [];

        for ($i = 0; $i <= 7; $i++) {
            $date = $now->copy()->addDays($i);
            if (in_array($date->dayOfWeekIso, $days)) {
                return $date->setTimeFromTimeString($this->run_time);
            }
        }

        return $now->copy()->addWeek()->setTimeFromTimeString($this->run_time);
    }
}
