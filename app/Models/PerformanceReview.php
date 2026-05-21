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
        'attendance_score',
        'tardiness_score',
        'responsibility_score',
        'cleanliness_score',
        'friendliness_score',
        'final_score',
        'rank',
        'notes',
    ];

    /**
     * SAW Criteria Definition
     */
    public static function getCriteria(): array
    {
        return [
            'attendance_score'   => ['weight' => 0.30, 'type' => 'benefit', 'label' => 'Kehadiran'],
            'tardiness_score'    => ['weight' => 0.20, 'type' => 'cost',    'label' => 'Keterlambatan'],
            'responsibility_score' => ['weight' => 0.20, 'type' => 'benefit', 'label' => 'Tanggung Jawab'],
            'cleanliness_score'  => ['weight' => 0.15, 'type' => 'benefit', 'label' => 'Kebersihan'],
            'friendliness_score' => ['weight' => 0.15, 'type' => 'benefit', 'label' => 'Keramahan'],
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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

    /**
     * Map manual input labels to scores
     */
    public static function scoreOptions(): array
    {
        return [
            10 => 'Baik',
            15 => 'Cukup',
            20 => 'Sangat Baik',
        ];
    }
}
