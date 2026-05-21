<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $employee = auth()->user()->employee;
        $today    = Carbon::today();

        // Validasi: cek apakah sudah absen masuk hari ini
        $existing = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->check_in) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // Cek jadwal hari ini
        $schedule = Schedule::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();
 
        if (!$schedule || $schedule->shift_type === 'libur') {
            return back()->with('error', 'Anda tidak memiliki jadwal kerja hari ini.');
        }
 
        // Tentukan status (hadir/terlambat) dan validasi waktu shift
        $now    = Carbon::now();
        $status = 'hadir';
 
        if ($schedule->start_time) {
            $startTime = Carbon::parse($schedule->start_time);
            $endTime   = Carbon::parse($schedule->end_time);
            
            // Beri kelonggaran 30 menit sebelum shift mulai
            $earliestCheckIn = (clone $startTime)->subMinutes(30);
            
            if ($now->lt($earliestCheckIn) || $now->gt($endTime)) {
                return back()->with('error', "Bukan waktu shift Anda. Jadwal Anda: {$schedule->start_time} - {$schedule->end_time}");
            }
 
            // Terlambat jika lebih dari 15 menit
            if ($now->gt((clone $startTime)->addMinutes(15))) {
                $status = 'terlambat';
            }
        }

        if ($existing) {
            $existing->update([
                'check_in' => $now->format('H:i:s'),
                'status'   => $status,
            ]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'date'        => $today->toDateString(),
                'check_in'    => $now->format('H:i:s'),
                'status'      => $status,
            ]);
        }

        $statusMsg = $status === 'terlambat' ? ' (Terlambat)' : '';
        return back()->with('success', "Absen masuk berhasil pukul {$now->format('H:i')}{$statusMsg}.");
    }

    public function checkOut(Request $request)
    {
        $employee = auth()->user()->employee;
        $today    = Carbon::today();

        // Validasi: harus absen masuk dulu
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        $now = Carbon::now();
        $attendance->update([
            'check_out' => $now->format('H:i:s'),
        ]);

        return back()->with('success', "Absen pulang berhasil pukul {$now->format('H:i')}.");
    }
}
