@extends('layouts.admin')
@section('title', 'Data Absensi Harian')
@section('page-title', 'Data Absensi Harian')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1>Data Absensi Harian</h1>
            <p>Monitoring kehadiran karyawan per tanggal</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.attendances.create') }}" class="btn-primary-sb">
                <i class="fa-solid fa-plus"></i> Input Manual
            </a>
            <a href="{{ route('admin.attendances.recap') }}" class="btn-outline-sb">
                <i class="fa-solid fa-calendar-check"></i> Rekap Bulanan
            </a>
        </div>
    </div>
</div>

<!-- Filter Date -->
<div class="card-sb mb-4">
    <form action="{{ route('admin.attendances.index') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
        <div>
            <label class="form-label-sb">Pilih Tanggal</label>
            <input type="date" name="date" class="form-control-sb" value="{{ $date->format('Y-m-d') }}" onchange="this.form.submit()">
        </div>
        <div style="flex:1;">
            <div class="d-flex gap-2 align-items-center h-100" style="padding-bottom:10px;">
                <span class="status-badge badge-hadir">Hadir: {{ $hadirCount }}</span>
                <span class="status-badge badge-terlambat">Terlambat: {{ $terlambatCount }}</span>
                <span class="status-badge badge-izin">Izin: {{ $izinCount }}</span>
                <span class="status-badge badge-alpha">Alpha: {{ $alphaCount }}</span>
                <span class="status-badge badge-libur">Libur: {{ $liburCount }}</span>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card-sb">
    <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">
        Absensi Tanggal: <span style="color:var(--sb-primary);">{{ $date->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
    </h6>
    <div class="table-responsive">
        <table class="table table-sb mb-0 table-hover align-middle">
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                @php
                    $att = $emp->attendances->first();
                    $sch = $emp->schedules->first();
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $emp->photo_url }}" style="width:34px; height:34px; border-radius:8px; object-fit:cover;">
                            <div>
                                <div style="font-size:13px; font-weight:600;">{{ $emp->name }}</div>
                                <div style="font-size:11px; color:var(--sb-text-muted);">{{ $emp->employee_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $emp->position }}</td>
                    <td>
                        @if($sch)
                            <span class="shift-chip shift-{{ $sch->shift_type }}" title="{{ $sch->shift_label }}">{{ $sch->shift_code }}</span>
                        @else
                            <span style="color:var(--sb-text-muted); font-size:12px;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($att && $att->check_in)
                            <span class="status-badge {{ $att->status === 'terlambat' ? 'badge-terlambat' : 'badge-hadir' }}">{{ $att->check_in }}</span>
                        @else
                            <span style="color:#C8D0D8; font-size:13px;">--:--</span>
                        @endif
                    </td>
                    <td>
                        @if($att && $att->check_out)
                            <span class="status-badge badge-hadir">{{ $att->check_out }}</span>
                        @else
                            <span style="color:#C8D0D8; font-size:13px;">--:--</span>
                        @endif
                    </td>
                    <td>
                        @if($att)
                            <span class="status-badge badge-{{ $att->status }}">{{ $att->status_label }}</span>
                        @elseif($sch && $sch->shift_type === 'libur')
                            <span class="status-badge badge-libur">Libur</span>
                        @else
                            <span class="status-badge badge-alpha">Belum Absen/Alpha</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.attendances.show', $emp) }}" class="btn-outline-sb" style="padding:4px 10px; font-size:11px;">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
