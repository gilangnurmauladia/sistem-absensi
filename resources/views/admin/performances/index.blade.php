@extends('layouts.admin')
@section('title', 'Penilaian Kinerja')
@section('page-title', 'Penilaian Kinerja')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1>Penilaian Kinerja</h1>
            <p>Evaluasi dan ranking karyawan bulanan</p>
        </div>
        <a href="{{ route('admin.performances.create') }}" class="btn-primary-sb">
            <i class="fa-solid fa-star"></i> Input Penilaian
        </a>
    </div>
</div>

<div class="card-sb mb-4">
    <form action="{{ route('admin.performances.index') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
        <div>
            <label class="form-label-sb">Bulan</label>
            <select name="month" class="form-control-sb form-select-sb" onchange="this.form.submit()">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label-sb">Tahun</label>
            <select name="year" class="form-control-sb form-select-sb" onchange="this.form.submit()">
                @for($y = date('Y')-1; $y <= date('Y')+1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-sb h-100">
            <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">Ranking Bulan {{ $months[$month] ?? '' }} {{ $year }}</h6>
            <div class="table-responsive">
                <table class="table table-sb mb-0">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Karyawan</th>
                            <th>Total Nilai</th>
                            <th>Grade</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $idx => $rev)
                        <tr>
                            <td>
                                @if($idx == 0)
                                    <span style="font-size:20px;">🥇</span>
                                @elseif($idx == 1)
                                    <span style="font-size:20px;">🥈</span>
                                @elseif($idx == 2)
                                    <span style="font-size:20px;">🥉</span>
                                @else
                                    <span style="font-size:14px; font-weight:700; color:var(--sb-text-muted); padding-left:8px;">#{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $rev->employee->photo_url }}" style="width:32px; height:32px; border-radius:8px; object-fit:cover;">
                                    <div>
                                        <div style="font-size:13px; font-weight:600;">{{ $rev->employee->name }}</div>
                                        <div style="font-size:11px; color:var(--sb-text-muted);">{{ $rev->employee->position }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:15px; font-weight:800; color:var(--sb-primary);">{{ $rev->total_score }}/15</div>
                            </td>
                            <td><span class="status-badge bg-{{ $rev->grade_color }} text-white">{{ $rev->grade_label }}</span></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.performances.show', $rev) }}" class="btn-outline-sb" style="padding:4px 8px; font-size:11px;">Detail</a>
                                    <a href="{{ route('admin.performances.edit', $rev) }}" class="btn-outline-sb" style="padding:4px 8px; font-size:11px;"><i class="fa-solid fa-pen"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4" style="color:var(--sb-text-muted);">Belum ada penilaian untuk bulan ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-sb h-100">
            <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">Belum Dinilai</h6>
            @if($unreviewed->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($unreviewed as $emp)
                    <div class="d-flex justify-content-between align-items-center p-2 border rounded-3" style="border-color:var(--sb-border)!important;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $emp->photo_url }}" style="width:28px; height:28px; border-radius:6px; object-fit:cover;">
                            <div style="font-size:12px; font-weight:600;">{{ $emp->name }}</div>
                        </div>
                        <a href="{{ route('admin.performances.create', ['employee_id' => $emp->id, 'month' => $month, 'year' => $year]) }}" class="btn-primary-sb" style="padding:4px 10px; font-size:11px;">Nilai</a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-success" style="font-size:13px; font-weight:500;">
                    <i class="fa-solid fa-check-circle fa-2x mb-2 opacity-50"></i><br>
                    Semua karyawan sudah dinilai bulan ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
