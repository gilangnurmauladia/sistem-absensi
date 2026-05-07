<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\PerformanceReview;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = $today->month;
        $currentYear  = $today->year;

        // Statistik absensi hari ini
        $totalEmployees = Employee::where('status', 'aktif')->count();
        $todayAttendances = Attendance::whereDate('date', $today)->get();

        $hadirCount    = $todayAttendances->whereIn('status', ['hadir', 'terlambat'])->count();
        $izinCount     = $todayAttendances->where('status', 'izin')->count();
        $liburCount    = $todayAttendances->where('status', 'libur')->count();
        $alphaCount    = $totalEmployees - $hadirCount - $izinCount - $liburCount;
        $terlambatCount = $todayAttendances->where('status', 'terlambat')->count();

        // Izin pending
        $pendingLeaves = Leave::where('status', 'pending')->count();

        // Ranking performa bulan ini
        $rankingData = PerformanceReview::with('employee')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get()
            ->sortByDesc(fn($r) => $r->total_score)
            ->take(5);

        // Absensi 7 hari terakhir untuk chart
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayAttendances = Attendance::whereDate('date', $date)->get();
            $last7Days[] = [
                'date'   => $date->format('d/m'),
                'hadir'  => $dayAttendances->whereIn('status', ['hadir', 'terlambat'])->count(),
                'izin'   => $dayAttendances->where('status', 'izin')->count(),
                'alpha'  => $dayAttendances->where('status', 'alpha')->count(),
            ];
        }

        // Absensi hari ini per karyawan
        $employeesWithAttendance = Employee::with(['todayAttendance', 'todaySchedule'])
            ->where('status', 'aktif')
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees', 'hadirCount', 'izinCount', 'liburCount',
            'alphaCount', 'terlambatCount', 'pendingLeaves',
            'rankingData', 'last7Days', 'employeesWithAttendance', 'today'
        ));
    }
}
