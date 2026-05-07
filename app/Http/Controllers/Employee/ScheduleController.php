<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $employee = auth()->user()->employee;
        $month    = $request->month ?? Carbon::now()->month;
        $year     = $request->year  ?? Carbon::now()->year;

        $schedules = Schedule::where('employee_id', $employee->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get()
            ->keyBy(fn($s) => Carbon::parse($s->date)->day);

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $firstDay    = Carbon::createFromDate($year, $month, 1);

        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        // Hitung ringkasan shift
        $pagiCount  = $schedules->where('shift_type', 'pagi')->count();
        $siangCount = $schedules->where('shift_type', 'siang')->count();
        $liburCount = $schedules->where('shift_type', 'libur')->count();

        return view('employee.schedules.index', compact(
            'employee', 'schedules', 'month', 'year', 'daysInMonth',
            'firstDay', 'months', 'pagiCount', 'siangCount', 'liburCount'
        ));
    }
}
