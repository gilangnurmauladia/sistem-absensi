<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $employees = Employee::with([
            'attendances' => fn($q) => $q->whereDate('date', $date),
            'schedules'   => fn($q) => $q->whereDate('date', $date),
        ])->where('status', 'aktif')->get();

        $totalEmployees = $employees->count();
        $hadirCount     = 0;
        $terlambatCount = 0;
        $izinCount      = 0;
        $alphaCount     = 0;
        $liburCount     = 0;

        foreach ($employees as $emp) {
            $att = $emp->attendances->first();
            if ($att) {
                if (in_array($att->status, ['hadir', 'terlambat'])) {
                    $hadirCount++;
                    if ($att->status === 'terlambat') $terlambatCount++;
                } elseif ($att->status === 'izin') {
                    $izinCount++;
                } elseif ($att->status === 'libur') {
                    $liburCount++;
                } else {
                    $alphaCount++;
                }
            } else {
                // Cek apakah hari libur di jadwal
                $schedule = $emp->schedules->first();
                if ($schedule && $schedule->shift_type === 'libur') {
                    $liburCount++;
                } else {
                    $alphaCount++;
                }
            }
        }

        return view('admin.attendances.index', compact(
            'employees', 'date', 'totalEmployees',
            'hadirCount', 'terlambatCount', 'izinCount', 'alphaCount', 'liburCount'
        ));
    }

    public function recap(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;

        $employees = Employee::where('status', 'aktif')->get();
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $recapData = [];
        foreach ($employees as $emp) {
            $attendances = Attendance::where('employee_id', $emp->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->keyBy(fn($a) => Carbon::parse($a->date)->day);

            $hadir     = $attendances->whereIn('status', ['hadir', 'terlambat'])->count();
            $terlambat = $attendances->where('status', 'terlambat')->count();
            $izin      = $attendances->where('status', 'izin')->count();
            $alpha     = $attendances->where('status', 'alpha')->count();
            $libur     = $attendances->where('status', 'libur')->count();

            $recapData[] = [
                'employee'   => $emp,
                'hadir'      => $hadir,
                'terlambat'  => $terlambat,
                'izin'       => $izin,
                'alpha'      => $alpha,
                'libur'      => $libur,
                'attendances'=> $attendances,
            ];
        }

        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        return view('admin.attendances.recap', compact(
            'recapData', 'month', 'year', 'daysInMonth', 'months'
        ));
    }

    public function show(Employee $employee, Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();

        return view('admin.attendances.show', compact('employee', 'attendances', 'month', 'year'));
    }
}
