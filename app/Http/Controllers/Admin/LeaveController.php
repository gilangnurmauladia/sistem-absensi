<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }

        $leaves = $query->paginate(15)->withQueryString();
        $pendingCount = Leave::where('status', 'pending')->count();

        return view('admin.leaves.index', compact('leaves', 'pendingCount'));
    }

    public function show(Leave $leave)
    {
        $leave->load('employee', 'approvedBy');
        return view('admin.leaves.show', compact('leave'));
    }

    public function approve(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Izin ini sudah diproses.');
        }

        $leave->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Buat record attendance sebagai izin untuk setiap hari yang dimohon
        $start = Carbon::parse($leave->start_date);
        $end   = Carbon::parse($leave->end_date);

        while ($start->lte($end)) {
            \App\Models\Attendance::updateOrCreate(
                ['employee_id' => $leave->employee_id, 'date' => $start->toDateString()],
                ['status' => 'izin', 'notes' => "Izin: {$leave->type_label}"]
            );
            $start->addDay();
        }

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Izin karyawan telah disetujui.');
    }

    public function reject(Request $request, Leave $leave)
    {
        $request->validate([
            'rejection_note' => 'required|string|max:500',
        ]);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Izin ini sudah diproses.');
        }

        $leave->update([
            'status'         => 'rejected',
            'approved_by'    => auth()->id(),
            'rejection_note' => $request->rejection_note,
        ]);

        return redirect()->route('admin.leaves.index')
            ->with('success', 'Izin karyawan ditolak.');
    }
}
