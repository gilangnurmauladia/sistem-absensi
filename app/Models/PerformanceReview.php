<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reviewed_by',
        'month',
        'year',
        'punctuality',
        'attendance',
        'discipline',
        'cleanliness',
        'friendliness',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Calculate total score dynamically (in case stored column not available)
     */
    public function getTotalScoreAttribute(): int
    {
        return $this->punctuality + $this->attendance + $this->discipline + $this->cleanliness + $this->friendliness;
    }

    /**
     * Max possible score = 5 indicators × 3 (Tinggi) = 15
     */
    public function getMaxScoreAttribute(): int
    {
        return 15;
    }

    public function getScorePercentAttribute(): float
    {
        return round(($this->total_score / $this->max_score) * 100, 1);
    }

    public function getGradeLabelAttribute(): string
    {
        $pct = $this->score_percent;
        if ($pct >= 87) return 'Sangat Baik';
        if ($pct >= 67) return 'Baik';
        if ($pct >= 47) return 'Cukup';
        return 'Kurang';
    }

    public function getGradeColorAttribute(): string
    {
        $pct = $this->score_percent;
        if ($pct >= 87) return 'success';
        if ($pct >= 67) return 'primary';
        if ($pct >= 47) return 'warning';
        return 'danger';
    }

    public static function scoreLabel(int $score): string
    {
        return match($score) {
            1 => 'Rendah',
            2 => 'Sedang',
            3 => 'Tinggi',
            default => '-',
        };
    }

    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $months[$this->month] ?? '-';
    }
}
