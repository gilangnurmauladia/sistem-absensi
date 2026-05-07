@extends('layouts.admin')
@section('title', 'Rekap Absensi Bulanan')
@section('page-title', 'Rekap Bulanan')

@push('styles')
<style>
.cell-date { width: 30px; text-align: center; padding: 6px !important; font-size: 11px; border: 1px solid var(--sb-border) !important; }
.cell-h { background: #E8F5EE; color: #2D7A51; font-weight: 700; }
.cell-t { background: #FEF3E2; color: #D4860A; font-weight: 700; }
.cell-i { background: #E3F0FF; color: #1A6B8A; font-weight: 700; }
.cell-a { background: #FDEAEA; color: #C0392B; font-weight: 700; }
.cell-l { background: #F0EEF5; color: #5B4E72; font-weight: 700; }
.cell-empty { background: #F8F9FB; color: #C8D0D8; }
.table-recap th { border: 1px solid var(--sb-border) !important; background: #F5EDE4; text-align: center; font-size:11px; padding:8px 4px !important; }
.table-recap td { border: 1px solid var(--sb-border) !important; padding:8px !important; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('admin.attendances.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1>Rekap Absensi</h1>
                <p>Rekapitulasi kehadiran karyawan per bulan</p>
            </div>
        </div>
    </div>
</div>

<div class="card-sb mb-4">
    <form action="{{ route('admin.attendances.recap') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
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
                @for($y = date('Y')-2; $y <= date('Y')+1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="ms-auto">
            <div class="d-flex gap-3 text-end" style="font-size:12px;">
                <div><span class="cell-date cell-h d-inline-block rounded-1 me-1">H</span> Hadir</div>
                <div><span class="cell-date cell-t d-inline-block rounded-1 me-1">T</span> Terlambat</div>
                <div><span class="cell-date cell-i d-inline-block rounded-1 me-1">I</span> Izin/Cuti</div>
                <div><span class="cell-date cell-a d-inline-block rounded-1 me-1">A</span> Alpha</div>
                <div><span class="cell-date cell-l d-inline-block rounded-1 me-1">L</span> Libur</div>
            </div>
        </div>
    </form>
</div>

<div class="card-sb" style="padding:0; overflow:hidden;">
    <div class="table-responsive">
        <table class="table table-recap mb-0" style="min-width: 1200px;">
            <thead>
                <tr>
                    <th rowspan="2" style="min-width:200px; text-align:left; padding-left:16px !important;">Karyawan</th>
                    <th colspan="{{ $daysInMonth }}">Tanggal</th>
                    <th colspan="4" style="background:#E8A96A; color:white;">Total</th>
                </tr>
                <tr>
                    @for($i = 1; $i <= $daysInMonth; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                    <th style="background:#E8F5EE; color:#2D7A51;">H</th>
                    <th style="background:#FEF3E2; color:#D4860A;">T</th>
                    <th style="background:#E3F0FF; color:#1A6B8A;">I</th>
                    <th style="background:#FDEAEA; color:#C0392B;">A</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recapData as $row)
                <tr>
                    <td style="padding-left:16px !important;">
                        <div class="d-flex align-items-center gap-2">
                            <div style="font-size:13px; font-weight:600;">{{ $row['employee']->name }}</div>
                        </div>
                    </td>
                    @for($i = 1; $i <= $daysInMonth; $i++)
                        @php
                            $att = $row['attendances']->get($i);
                            $class = 'cell-empty';
                            $text = '-';
                            if ($att) {
                                if ($att->status == 'hadir') { $class = 'cell-h'; $text = 'H'; }
                                elseif ($att->status == 'terlambat') { $class = 'cell-t'; $text = 'T'; }
                                elseif ($att->status == 'izin') { $class = 'cell-i'; $text = 'I'; }
                                elseif ($att->status == 'alpha') { $class = 'cell-a'; $text = 'A'; }
                                elseif ($att->status == 'libur') { $class = 'cell-l'; $text = 'L'; }
                            }
                        @endphp
                        <td class="cell-date {{ $class }}">{{ $text }}</td>
                    @endfor
                    <td class="cell-date cell-h">{{ $row['hadir'] }}</td>
                    <td class="cell-date cell-t">{{ $row['terlambat'] }}</td>
                    <td class="cell-date cell-i">{{ $row['izin'] }}</td>
                    <td class="cell-date cell-a">{{ $row['alpha'] }}</td>
                </tr>
                @empty
                <tr><td colspan="{{ $daysInMonth + 5 }}" class="text-center py-4">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
