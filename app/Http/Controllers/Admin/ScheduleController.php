<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;

        $employees   = Employee::where('status', 'aktif')->get();
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $firstDay    = Carbon::createFromDate($year, $month, 1);

        // Ambil semua jadwal bulan ini
        $schedules = Schedule::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy('employee_id')
            ->map(fn($group) => $group->keyBy(fn($s) => Carbon::parse($s->date)->day));

        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        return view('admin.schedules.index', compact(
            'employees', 'schedules', 'month', 'year', 'daysInMonth', 'firstDay', 'months'
        ));
    }

    public function create(Request $request)
    {
        $employees = Employee::where('status', 'aktif')->get();
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // Existing jadwal for pre-fill
        $existing = Schedule::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy('employee_id')
            ->map(fn($g) => $g->keyBy(fn($s) => Carbon::parse($s->date)->day));

        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        return view('admin.schedules.create', compact('employees', 'month', 'year', 'daysInMonth', 'existing', 'months'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month'       => 'required|integer|between:1,12',
            'year'        => 'required|integer|min:2020',
            'schedules'   => 'required|array',
        ]);

        $month = $request->month;
        $year  = $request->year;
        $shiftDefaults = Schedule::shiftDefaults();

        foreach ($request->schedules as $employeeId => $days) {
            foreach ($days as $day => $shiftType) {
                if (empty($shiftType)) continue;

                $date = Carbon::createFromDate($year, $month, $day)->toDateString();
                $defaults = $shiftDefaults[$shiftType] ?? ['start' => null, 'end' => null];

                Schedule::updateOrCreate(
                    ['employee_id' => $employeeId, 'date' => $date],
                    [
                        'shift_type' => $shiftType,
                        'start_time' => $defaults['start'],
                        'end_time'   => $defaults['end'],
                    ]
                );
            }
        }

        return redirect()->route('admin.schedules.index', ['month' => $month, 'year' => $year])
            ->with('success', 'Jadwal shift berhasil disimpan.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
