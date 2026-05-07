<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('login')->with('error', 'Data karyawan tidak ditemukan.');
        }

        $today          = Carbon::today();
        $currentMonth   = $today->month;
        $currentYear    = $today->year;

        // Absensi hari ini
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        // Jadwal hari ini
        $todaySchedule = $employee->schedules()
            ->whereDate('date', $today)
            ->first();

        // Statistik bulan ini
        $monthAttendances = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        $hariHadir   = $monthAttendances->whereIn('status', ['hadir', 'terlambat'])->count();
        $terlambat   = $monthAttendances->where('status', 'terlambat')->count();
        $izinApproved = Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->count();
        $alpha = $monthAttendances->where('status', 'alpha')->count();

        // Riwayat absensi terbaru (10 terakhir)
        $recentAttendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        // Jadwal bulan ini
        $monthSchedules = $employee->schedules()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->get();

        // Total hari kerja bulan ini (bukan libur)
        $totalWorkDays = $monthSchedules->whereNotIn('shift_type', ['libur'])->count();

        return view('employee.dashboard', compact(
            'employee', 'today', 'todayAttendance', 'todaySchedule',
            'hariHadir', 'terlambat', 'izinApproved', 'alpha',
            'recentAttendances', 'totalWorkDays', 'currentMonth', 'currentYear'
        ));
    }
}
