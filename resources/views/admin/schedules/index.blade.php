@extends('layouts.admin')
@section('title', 'Jadwal Shift Bulanan')
@section('page-title', 'Jadwal Shift')

@push('styles')
<style>
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: var(--sb-border); border: 1px solid var(--sb-border); border-radius: 10px; overflow: hidden; }
.cal-header { background: #F8F9FB; text-align: center; font-size: 12px; font-weight: 700; color: var(--sb-text-muted); padding: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
.cal-cell { background: white; min-height: 100px; padding: 6px; display: flex; flex-direction: column; gap: 4px; }
.cal-date { font-size: 12px; font-weight: 700; color: var(--sb-text-muted); margin-bottom: 4px; text-align: right; }
.cal-cell.empty { background: #FAF7F2; }
.shift-item { font-size: 10px; font-weight: 600; padding: 3px 6px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
.shift-item.pagi { background: #DBEAFE; color: #1E40AF; }
.shift-item.siang { background: #FEF9C3; color: #92400E; }
.shift-item.libur { background: #FEE2E2; color: #B91C1C; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1>Jadwal Shift</h1>
            <p>Kalender jadwal kerja karyawan</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="btn-primary-sb">
            <i class="fa-solid fa-pen-to-square"></i> Atur Jadwal
        </a>
    </div>
</div>

<div class="card-sb mb-4">
    <form action="{{ route('admin.schedules.index') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
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
        <div class="ms-auto d-flex gap-3 align-items-center">
            <div style="font-size:12px; font-weight:600;"><span class="d-inline-block rounded-1 me-1" style="width:12px; height:12px; background:#DBEAFE;"></span> Pagi (P)</div>
            <div style="font-size:12px; font-weight:600;"><span class="d-inline-block rounded-1 me-1" style="width:12px; height:12px; background:#FEF9C3;"></span> Siang (S)</div>
            <div style="font-size:12px; font-weight:600;"><span class="d-inline-block rounded-1 me-1" style="width:12px; height:12px; background:#FEE2E2;"></span> Libur (L)</div>
        </div>
    </form>
</div>

<div class="card-sb p-0 border-0" style="background:transparent; box-shadow:none;">
    <div class="cal-grid">
        <div class="cal-header">Senin</div>
        <div class="cal-header">Selasa</div>
        <div class="cal-header">Rabu</div>
        <div class="cal-header">Kamis</div>
        <div class="cal-header">Jumat</div>
        <div class="cal-header">Sabtu</div>
        <div class="cal-header">Minggu</div>

        @php
            $startDayOfWeek = $firstDay->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
            $totalCells = ceil(($daysInMonth + $startDayOfWeek - 1) / 7) * 7;
        @endphp

        @for($i = 1; $i <= $totalCells; $i++)
            @if($i < $startDayOfWeek || $i >= $startDayOfWeek + $daysInMonth)
                <div class="cal-cell empty"></div>
            @else
                @php 
                    $day = $i - $startDayOfWeek + 1; 
                    // Ambil jadwal semua karyawan pada hari ini
                    $daySchedules = collect();
                    foreach($schedules as $empId => $empSchedules) {
                        if(isset($empSchedules[$day])) {
                            $daySchedules->push([
                                'employee' => $employees->firstWhere('id', $empId),
                                'schedule' => $empSchedules[$day]
                            ]);
                        }
                    }
                @endphp
                <div class="cal-cell">
                    <div class="cal-date">{{ $day }}</div>
                    <div class="d-flex flex-column gap-1" style="overflow-y:auto; max-height:80px;">
                        @foreach($daySchedules as $ds)
                            @if($ds['employee'])
                                <div class="shift-item {{ $ds['schedule']->shift_type }}" title="{{ $ds['employee']->name }}">
                                    <span class="text-truncate" style="max-width:80%;">{{ explode(' ', $ds['employee']->name)[0] }}</span>
                                    <span>{{ $ds['schedule']->shift_code }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endfor
    </div>
</div>
@endsection
