<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir'    => 'Hadir',
            'terlambat'=> 'Terlambat',
            'izin'     => 'Izin',
            'alpha'    => 'Alpha',
            'libur'    => 'Libur',
            default    => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'hadir'    => 'success',
            'terlambat'=> 'warning',
            'izin'     => 'info',
            'alpha'    => 'danger',
            'libur'    => 'secondary',
            default    => 'secondary',
        };
    }

    public function getDurationAttribute(): ?string
    {
        if ($this->check_in && $this->check_out) {
            $in  = Carbon::parse($this->check_in);
            $out = Carbon::parse($this->check_out);
            $diff = $in->diff($out);
            return $diff->h . 'j ' . $diff->i . 'm';
        }
        return null;
    }
}
