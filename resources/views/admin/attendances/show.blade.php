@extends('layouts.admin')
@section('title', 'Detail Absensi - ' . $employee->name)
@section('page-title', 'Detail Absensi')
 
@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.attendances.recap') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Detail Absensi: {{ $employee->name }}</h1>
            <p>Log kehadiran periode {{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}</p>
        </div>
    </div>
</div>
 
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-sb h-100">
            <div class="text-center mb-4">
                <div class="user-avatar mx-auto mb-3" style="width:100px; height:100px; font-size:32px;">
                    {{ substr($employee->name, 0, 2) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $employee->name }}</h5>
                <p class="text-muted small mb-0">{{ $employee->position }}</p>
                <div class="mt-2 text-muted small">ID: {{ $employee->employee_id }}</div>
            </div>
            
            <hr style="border-color:var(--sb-border);">
            
            <h6 class="fw-bold mb-3" style="font-size:14px;">Ringkasan Bulan Ini</h6>
            <div class="d-flex flex-column gap-2">
                @php
                    $stats = [
                        'hadir'     => ['label' => 'Hadir', 'color' => 'success'],
                        'terlambat' => ['label' => 'Terlambat', 'color' => 'warning'],
                        'izin'      => ['label' => 'Izin', 'color' => 'info'],
                        'alpha'     => ['label' => 'Alpha', 'color' => 'danger'],
                    ];
                @endphp
                @foreach($stats as $status => $cfg)
                    <div class="d-flex justify-content-between align-items-center p-2 rounded border" style="background:#FAF7F2; border-color:var(--sb-border)!important;">
                        <span class="small fw-semibold">{{ $cfg['label'] }}</span>
                        <span class="status-badge bg-{{ $cfg['color'] }} text-white">
                            {{ $attendances->where('status', $status)->count() }} Hari
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
 
    <div class="col-lg-8">
        <div class="card-sb">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Log Kehadiran</h6>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.attendances.show', $employee) }}" method="GET" class="d-flex gap-2">
                        <select name="month" class="form-control-sb form-select-sb py-1" style="font-size:12px;" onchange="this.form.submit()">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                        <select name="year" class="form-control-sb form-select-sb py-1" style="font-size:12px;" onchange="this.form.submit()">
                            @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>
 
            <div class="table-responsive">
                <table class="table table-sb">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Durasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                        <tr>
                            <td class="fw-bold">{{ $att->date->format('d M Y') }}</td>
                            <td>{{ $att->check_in ?? '--:--' }}</td>
                            <td>{{ $att->check_out ?? '--:--' }}</td>
                            <td>
                                <span class="status-badge bg-{{ $att->status_color }} text-white">
                                    {{ $att->status_label }}
                                </span>
                            </td>
                            <td>{{ $att->duration ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-calendar-xmark fa-2x mb-2 opacity-20"></i><br>
                                Tidak ada data absensi untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
