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
    <div class="col-12">
        <div class="card-sb">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">
                    @if($step == 1) STEP 1: Matriks Keputusan Awal
                    @elseif($step == 2) STEP 2: Normalisasi Matriks
                    @else STEP 3: Perangkingan Akhir @endif
                </h6>
                <div class="d-flex gap-2">
                    @if($step == 1 && $reviews->isNotEmpty())
                        <a href="{{ route('admin.performances.index', ['month' => $month, 'year' => $year, 'step' => 2]) }}" class="btn-primary-sb">
                            <i class="fa-solid fa-calculator"></i> Proses Normalisasi
                        </a>
                    @elseif($step == 2)
                        <a href="{{ route('admin.performances.index', ['month' => $month, 'year' => $year, 'step' => 1]) }}" class="btn-outline-sb">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.performances.index', ['month' => $month, 'year' => $year, 'step' => 3]) }}" class="btn-primary-sb">
                            <i class="fa-solid fa-trophy"></i> Hitung Ranking
                        </a>
                    @elseif($step == 3)
                        <a href="{{ route('admin.performances.index', ['month' => $month, 'year' => $year, 'step' => 2]) }}" class="btn-outline-sb">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.performances.index', ['month' => $month, 'year' => $year, 'step' => 1]) }}" class="btn-primary-sb">
                            <i class="fa-solid fa-rotate"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
 
            <div class="table-responsive">
                <table class="table table-sb mb-0">
                    <thead>
                        @if($step == 3)
                            <tr>
                                <th width="80">Ranking</th>
                                <th>Karyawan</th>
                                @foreach($criteria as $key => $config)
                                    <th class="text-center">{{ $config['label'] }}</th>
                                @endforeach
                                <th class="text-center">Nilai Akhir (Vi)</th>
                                <th width="100">Aksi</th>
                            </tr>
                        @else
                            <tr>
                                <th width="50">No</th>
                                <th>Karyawan</th>
                                @foreach($criteria as $key => $config)
                                    <th class="text-center">
                                        {{ $config['label'] }}
                                        <div class="small fw-normal text-muted">
                                            ({{ $config['type'] }} | {{ $config['weight']*100 }}%)
                                        </div>
                                    </th>
                                @endforeach
                                <th width="100">Aksi</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @forelse($reviews as $idx => $rev)
                        <tr>
                            <td>
                                @if($step == 3)
                                    @if($idx == 0) 🥇 @elseif($idx == 1) 🥈 @elseif($idx == 2) 🥉 @else #{{ $idx + 1 }} @endif
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width:30px; height:30px; font-size:11px;">{{ substr($rev->employee->name, 0, 2) }}</div>
                                    <div>
                                        <div class="fw-bold" style="font-size:13px;">{{ $rev->employee->name }}</div>
                                        <div class="text-muted" style="font-size:11px;">{{ $rev->employee->position }}</div>
                                    </div>
                                </div>
                            </td>
                            @foreach($criteria as $key => $config)
                                <td class="text-center">
                                    @if($step == 1)
                                        <span class="fw-bold">{{ $rev->$key }}</span>
                                    @else
                                        <span class="text-primary fw-bold">{{ number_format($matrix[$rev->id][$key], 3) }}</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            @if($step == 3)
                                <td class="text-center">
                                    <div class="status-badge badge-hadir px-3" style="font-size:14px;">
                                        {{ number_format($rev->final_score, 3) }}
                                    </div>
                                </td>
                            @endif
 
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.performances.edit', $rev) }}" class="btn-outline-sb py-1 px-2" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($criteria) + ($step == 3 ? 4 : 3) }}" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa-solid fa-clipboard-list fa-3x mb-3 opacity-20"></i><br>
                                    Belum ada data penilaian untuk periode ini.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
 
    @if($step == 1)
    <div class="col-12 mt-4">
        <div class="card-sb">
            <h6 class="fw-bold mb-4">Karyawan Belum Dinilai</h6>
            @if($unreviewed->count() > 0)
                <div class="row g-3">
                    @foreach($unreviewed as $emp)
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded-3" style="background:#FAF7F2;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width:36px; height:36px; background:var(--sb-primary-light);">{{ substr($emp->name, 0, 2) }}</div>
                                <div>
                                    <div class="fw-bold" style="font-size:13px;">{{ $emp->name }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $emp->position }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.performances.create', ['employee_id' => $emp->id, 'month' => $month, 'year' => $year]) }}" class="btn-primary-sb py-1 px-3" style="font-size:12px;">
                                Nilai
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-success">
                    <i class="fa-solid fa-check-circle fa-2x mb-2"></i><br>
                    Semua karyawan sudah dinilai.
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
