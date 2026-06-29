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
        $step = $request->step ?? 1; // 1: Matrix, 2: Normalization, 3: Ranking
 
        $reviews = PerformanceReview::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get();
 
        $matrix = [];
        $criteria = PerformanceReview::getCriteria();
 
        if ($reviews->isNotEmpty()) {
            if ($step >= 2) {
                // Step 2: Normalization
                $matrix = $this->calculateNormalization($reviews, $criteria);
            }
 
            if ($step == 3) {
                // Step 3: Ranking
                $reviews = $this->calculateRanking($reviews, $matrix, $criteria);
                $reviews = $reviews->sortByDesc('final_score')->values();
            }
        }
 
        $reviewedIds = $reviews->pluck('employee_id');
        $unreviewed  = Employee::where('status', 'aktif')
            ->whereNotIn('id', $reviewedIds)
            ->get();
 
        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
 
        return view('admin.performances.index', compact('reviews', 'month', 'year', 'unreviewed', 'months', 'step', 'matrix', 'criteria'));
    }

    public function create(Request $request)
    {
        $employees = Employee::where('status', 'aktif')->get();
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;
        $preEmployee = $request->employee_id ? Employee::find($request->employee_id) : null;
 
        $attendanceData = null;
        if ($preEmployee) {
            $attendanceData = $this->calculateAutoScores($preEmployee->id, $month, $year);
        }
 
        $months = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                   7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
        
        $scoreOptions = PerformanceReview::scoreOptions();
 
        return view('admin.performances.create', compact('employees', 'month', 'year', 'preEmployee', 'months', 'attendanceData', 'scoreOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|integer|between:1,12',
            'year'        => 'required|integer|min:2020',
            'friendliness_score'   => 'required|integer',
            'notes'       => 'nullable|string',
        ]);
 
        $existing = PerformanceReview::where('employee_id', $request->employee_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();
 
        if ($existing) {
            return back()->withErrors(['employee_id' => 'Karyawan ini sudah dinilai untuk bulan tersebut.'])->withInput();
        }
        
        $autoScores = $this->calculateAutoScores(
            $request->employee_id,
            $request->month,
            $request->year

        );

        $cleanlinessScore = $this->calculateCleanlinessScore($request);
 
        PerformanceReview::create([
            'employee_id' => $request->employee_id,
            'reviewed_by' => auth()->id(),
            'month'       => $request->month,
            'year'        => $request->year,
            'attendance_score'   => $autoScores['attendance_score'],
            'tardiness_score'    => $autoScores['tardiness_score'],
            'responsibility_score' => $autoScores['responsibility_score'],
            'cleanliness_score' => $cleanlinessScore,
            'friendliness_score'   => $request->friendliness_score,
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
        $scoreOptions = PerformanceReview::scoreOptions();
        
        $attendanceData = $this->calculateAutoScores($performance->employee_id, $performance->month, $performance->year);
 
        return view('admin.performances.edit', compact('performance', 'months', 'scoreOptions', 'attendanceData'));
    }

    public function update(Request $request, PerformanceReview $performance)
    {
        $request->validate([
            'friendliness_score' => 'required|integer',
            'notes'             => 'nullable|string',
        ]);

        // Hitung ulang nilai otomatis agar halaman edit sama seperti input penilaian.
        $autoScores = $this->calculateAutoScores(
            $performance->employee_id,
            $performance->month,
            $performance->year
        );

        $cleanlinessScore = $this->calculateCleanlinessScore($request);

        $performance->update([
            'attendance_score'     => $autoScores['attendance_score'],
            'tardiness_score'      => $autoScores['tardiness_score'],
            'responsibility_score' => $autoScores['responsibility_score'],
            'cleanliness_score'    => $cleanlinessScore,
            'friendliness_score'   => $request->friendliness_score,
            'notes'                => $request->notes,
        ]);
 
        return redirect()->route('admin.performances.index')
            ->with('success', 'Penilaian berhasil diperbarui.');
    }

    private function calculateAutoScores($employeeId, $month, $year)
{
    $attendanceCount = \App\Models\Attendance::where('employee_id', $employeeId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->whereIn('status', ['hadir', 'terlambat'])
        ->count();

    $tardinessCount = \App\Models\Attendance::where('employee_id', $employeeId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('status', 'terlambat')
        ->count();

    // AUTO RESPONSIBILITY SCORE
    $responsibilityScore = 10;

    if ($attendanceCount >= 15 && $tardinessCount < 3) {
        $responsibilityScore = 20;
    } elseif ($attendanceCount >= 10) {
        $responsibilityScore = 15;
    }

    // TOTAL HARI KERJA
    $workingDays = 24;

    // HITUNG NILAI KETERLAMBATAN
    $tardinessScore = 20 - (($tardinessCount / $workingDays) * 20);

    // BULATKAN
    $tardinessScore = round($tardinessScore);

    // JIKA MINUS JADI 0
    if ($tardinessScore < 0) {
    $tardinessScore = 0;
    }

    return [
        'attendance_count' => $attendanceCount,
        'tardiness_count'  => $tardinessCount,

        'attendance_score' => $attendanceCount >= 15 ? 20 : 10,

        'tardiness_score' => $tardinessScore,

        // NEW
        'responsibility_score' => $responsibilityScore,
    ];
}
 
    private function calculateCleanlinessScore($request)
{
    $total = 0;

    if ($request->has('meja_bersih')) {
        $total++;
    }

    if ($request->has('lantai_bersih')) {
        $total++;
    }

    if ($request->has('peralatan_bersih')) {
        $total++;
    }

    if ($request->has('area_kerja_bersih')) {
        $total++;
    }

    // MAX 15
    if ($total >= 4) {
        return 15;
    }

    if ($total >= 2) {
        return 10;
    }

    return 5;
}

    private function calculateNormalization($reviews, $criteria)
    {
        $matrix = [];
        $maxValues = [];
        $minValues = [];
 
        foreach ($criteria as $key => $config) {
            $scores = $reviews->pluck($key)->toArray();
            if (empty($scores)) continue;
            $maxValues[$key] = max($scores);
            $minValues[$key] = min($scores);
        }
 
        foreach ($reviews as $review) {
            $row = ['id' => $review->id];
            foreach ($criteria as $key => $config) {
                $val = $review->$key;
                if ($config['type'] == 'benefit') {
                    $row[$key] = $maxValues[$key] > 0 ? $val / $maxValues[$key] : 0;
                } else {
                    $row[$key] = $val > 0 ? $minValues[$key] / $val : 0;
                }
            }
            $matrix[$review->id] = $row;
        }
 
        return $matrix;
    }
 
    private function calculateRanking($reviews, $normalizedMatrix, $criteria)
    {
        foreach ($reviews as $review) {
            $preferenceValue = 0;
            $row = $normalizedMatrix[$review->id];
            foreach ($criteria as $key => $config) {
                $preferenceValue += ($config['weight'] * $row[$key]);
            }
            $review->final_score = $preferenceValue;
            $review->save();
        }
 
        // Update ranks
        $sorted = $reviews->sortByDesc('final_score')->values();
        foreach ($sorted as $idx => $review) {
            $review->rank = $idx + 1;
            $review->save();
        }
 
        return $reviews;
    }

    public function destroy(PerformanceReview $performance)
    {
        $performance->delete();
        return back()->with('success', 'Penilaian berhasil dihapus.');
    }
}
