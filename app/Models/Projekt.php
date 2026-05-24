<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projekt extends Model
{
    use SoftDeletes;

    protected $table = 'projekts';

    protected $fillable = [
        'project_name',
        'address',
        'description',
        'start_date',
        'end_date',
        'status',
        'planned_budget',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'planned_budget' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    public function records()
    {
        return $this->hasMany(ProjektRecord::class, 'projekt_id');
    }

    public function budgetItems()
    {
        return $this->hasMany(ProjektBudgetItem::class, 'projekt_id');
    }

    public function documents()
    {
        return $this->hasMany(ProjektDocument::class, 'projekt_id');
    }

    public function getTimelineProgressAttribute(): int
    {
        if (in_array($this->status, ['completed', 'cancelled'], true)) {
            return 100;
        }

        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $startDate = $this->start_date->copy()->startOfDay();
        $endDate = $this->end_date->copy()->endOfDay();
        $now = Carbon::now();

        if ($endDate->lessThanOrEqualTo($startDate)) {
            return $now->greaterThanOrEqualTo($endDate) ? 100 : 0;
        }

        if ($now->lessThanOrEqualTo($startDate)) {
            return 0;
        }

        if ($now->greaterThanOrEqualTo($endDate)) {
            return 100;
        }

        $totalSeconds = $startDate->diffInSeconds($endDate);
        $elapsedSeconds = $startDate->diffInSeconds($now);

        return max(0, min(100, (int) round(($elapsedSeconds / $totalSeconds) * 100)));
    }

    public function getTimelineStatusAttribute(): string
    {
        if ($this->status === 'completed') {
            return 'completed';
        }

        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        if (!$this->start_date) {
            return 'open';
        }

        return Carbon::today()->lt($this->start_date->copy()->startOfDay())
            ? 'open'
            : 'in_progress';
    }

    public function getTimelineStatusBadgeAttribute(): string
    {
        return match($this->timeline_status) {
            'open' => 'badge-warning',
            'in_progress' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getTimelineStatusLabelAttribute(): string
    {
        return match($this->timeline_status) {
            'open' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Done',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return $this->timeline_status_badge;
    }

    public function getStatusLabelAttribute()
    {
        return $this->timeline_status_label;
    }
}
