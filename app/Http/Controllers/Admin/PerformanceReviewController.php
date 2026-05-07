<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PerformanceReview;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;

        $reviews = PerformanceReview::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->sortByDesc(fn($r) => $r->total_score)
            ->values();

        // Karyawan yang belum dinilai bulan ini
        $reviewedIds = $reviews->pluck('employee_id');
        $unreviewed  = Employee::where('status', 'aktif')
            ->whereNotIn('id', $reviewedIds)
            ->get();

        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        return view('admin.performances.index', compact('reviews', 'month', 'year', 'unreviewed', 'months'));
    }

    public function create(Request $request)
    {
        $employees = Employee::where('status', 'aktif')->get();
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;
        $preEmployee = $request->employee_id ? Employee::find($request->employee_id) : null;

        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

        return view('admin.performances.create', compact('employees', 'month', 'year', 'preEmployee', 'months'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|integer|between:1,12',
            'year'        => 'required|integer|min:2020',
            'punctuality' => 'required|integer|between:1,3',
            'attendance'  => 'required|integer|between:1,3',
            'discipline'  => 'required|integer|between:1,3',
            'cleanliness' => 'required|integer|between:1,3',
            'friendliness'=> 'required|integer|between:1,3',
            'notes'       => 'nullable|string',
        ]);

        // Cek apakah sudah ada penilaian untuk bulan ini
        $existing = PerformanceReview::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($existing) {
            return back()->withErrors(['employee_id' => 'Karyawan ini sudah dinilai untuk bulan tersebut.'])->withInput();
        }

        PerformanceReview::create([
            'employee_id' => $request->employee_id,
            'reviewed_by' => auth()->id(),
            'month'       => $request->month,
            'year'        => $request->year,
            'punctuality' => $request->punctuality,
            'attendance'  => $request->attendance,
            'discipline'  => $request->discipline,
            'cleanliness' => $request->cleanliness,
            'friendliness'=> $request->friendliness,
            'notes'       => $request->notes,
        ]);

        return redirect()->route('admin.performances.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', 'Penilaian karyawan berhasil disimpan.');
    }

    public function show(PerformanceReview $performance)
    {
        $performance->load('employee', 'reviewedBy');
        return view('admin.performances.show', compact('performance'));
    }

    public function edit(PerformanceReview $performance)
    {
        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        return view('admin.performances.edit', compact('performance', 'months'));
    }

    public function update(Request $request, PerformanceReview $performance)
    {
        $request->validate([
            'punctuality' => 'required|integer|between:1,3',
            'attendance'  => 'required|integer|between:1,3',
            'discipline'  => 'required|integer|between:1,3',
            'cleanliness' => 'required|integer|between:1,3',
            'friendliness'=> 'required|integer|between:1,3',
            'notes'       => 'nullable|string',
        ]);

        $performance->update($request->only([
            'punctuality', 'attendance', 'discipline', 'cleanliness', 'friendliness', 'notes'
        ]));

        return redirect()->route('admin.performances.index')
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(PerformanceReview $performance)
    {
        $performance->delete();
        return back()->with('success', 'Penilaian berhasil dihapus.');
    }
}
