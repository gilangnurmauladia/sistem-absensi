@extends('layouts.employee')
@section('title', 'Jadwal Kerja')
@section('page-title', 'Jadwal Kerja')

@push('styles')
<style>
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; }
.cal-header { background: #F8F9FB; text-align: center; font-size: 12px; font-weight: 700; color: var(--sb-text-muted); padding: 12px; border-radius: 12px; }
.cal-cell { background: white; border: 1px solid var(--sb-border); border-radius: 12px; min-height: 100px; padding: 10px; display: flex; flex-direction: column; }
.cal-date { font-size: 14px; font-weight: 700; color: var(--sb-text-muted); margin-bottom: 8px; }
.cal-cell.empty { background: transparent; border: none; }
.cal-cell.today { border: 2px solid var(--sb-primary); box-shadow: 0 4px 12px rgba(193,125,60,0.15); }
.cal-cell.today .cal-date { color: var(--sb-primary); }

.shift-card { border-radius: 8px; padding: 8px; flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
.shift-card.pagi { background: #DBEAFE; color: #1E40AF; }
.shift-card.siang { background: #FEF9C3; color: #92400E; }
.shift-card.libur { background: #FEE2E2; color: #B91C1C; }

.shift-title { font-size: 12px; font-weight: 700; margin-bottom: 2px; }
.shift-time { font-size: 11px; opacity: 0.8; }
</style>
@endpush

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-sb d-flex align-items-center gap-3">
            <div class="stat-icon blue"><i class="fa-solid fa-sun"></i></div>
            <div>
                <div class="stat-value">{{ $pagiCount }}</div>
                <div class="stat-label">Shift Pagi Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-sb d-flex align-items-center gap-3">
            <div class="stat-icon amber"><i class="fa-solid fa-moon"></i></div>
            <div>
                <div class="stat-value">{{ $siangCount }}</div>
                <div class="stat-label">Shift Siang Bulan Ini</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-sb d-flex align-items-center gap-3">
            <div class="stat-icon red"><i class="fa-solid fa-bed"></i></div>
            <div>
                <div class="stat-value">{{ $liburCount }}</div>
                <div class="stat-label">Hari Libur Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

<div class="card-sb mb-4">
    <form action="{{ route('employee.schedules.index') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
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

<div class="card-sb p-4">
    <div class="cal-grid mb-2">
        <div class="cal-header">Senin</div>
        <div class="cal-header">Selasa</div>
        <div class="cal-header">Rabu</div>
        <div class="cal-header">Kamis</div>
        <div class="cal-header">Jumat</div>
        <div class="cal-header">Sabtu</div>
        <div class="cal-header">Minggu</div>
    </div>

    <div class="cal-grid">
        @php
            $startDayOfWeek = $firstDay->dayOfWeekIso;
            $totalCells = ceil(($daysInMonth + $startDayOfWeek - 1) / 7) * 7;
            $todayDay = (date('n') == $month && date('Y') == $year) ? date('j') : null;
        @endphp

        @for($i = 1; $i <= $totalCells; $i++)
            @if($i < $startDayOfWeek || $i >= $startDayOfWeek + $daysInMonth)
                <div class="cal-cell empty"></div>
            @else
                @php 
                    $day = $i - $startDayOfWeek + 1; 
                    $isToday = $day == $todayDay;
                    $sch = $schedules->get($day);
                @endphp
                <div class="cal-cell {{ $isToday ? 'today' : '' }}">
                    <div class="cal-date">{{ $day }}</div>
                    @if($sch)
                        <div class="shift-card {{ $sch->shift_type }}">
                            <div class="shift-title">{{ $sch->shift_label }}</div>
                            @if($sch->shift_type != 'libur')
                                <div class="shift-time">{{ substr($sch->start_time,0,5) }} - {{ substr($sch->end_time,0,5) }}</div>
                            @endif
                        </div>
                    @else
                        <div class="shift-card" style="background:#F8F9FB; color:var(--sb-text-muted);">
                            <div class="shift-title" style="font-size:10px;">Belum ada jadwal</div>
                        </div>
                    @endif
                </div>
            @endif
        @endfor
    </div>
</div>
@endsection
