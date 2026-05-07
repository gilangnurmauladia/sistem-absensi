<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'shift_type',
        'start_time',
        'end_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getShiftLabelAttribute(): string
    {
        return match($this->shift_type) {
            'pagi'  => 'Pagi (P)',
            'siang' => 'Siang (S)',
            'libur' => 'Libur',
            default => '-',
        };
    }

    public function getShiftCodeAttribute(): string
    {
        return match($this->shift_type) {
            'pagi'  => 'P',
            'siang' => 'S',
            'libur' => 'OFF',
            default => '-',
        };
    }

    public function getShiftColorAttribute(): string
    {
        return match($this->shift_type) {
            'pagi'  => 'primary',
            'siang' => 'warning',
            'libur' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Default start/end time based on shift type
     */
    public static function shiftDefaults(): array
    {
        return [
            'pagi'  => ['start' => '07:00', 'end' => '15:00'],
            'siang' => ['start' => '15:00', 'end' => '23:00'],
            'libur' => ['start' => null,    'end' => null],
        ];
    }
}
