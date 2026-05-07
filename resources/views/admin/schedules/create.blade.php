@extends('layouts.admin')
@section('title', 'Atur Jadwal Shift')
@section('page-title', 'Atur Jadwal Shift')

@push('styles')
<style>
.table-schedule th { text-align: center; font-size: 11px; padding: 10px 4px !important; background: #F8F9FB; color: var(--sb-text-muted); border: 1px solid var(--sb-border) !important; }
.table-schedule td { padding: 4px !important; vertical-align: middle; border: 1px solid var(--sb-border) !important; }
.shift-select { width: 100%; border: none; background: transparent; padding: 4px; font-size: 11px; font-weight: 600; text-align: center; cursor: pointer; outline: none; appearance: none; }
.shift-select:focus { background: #F5EDE4; }
.shift-wrapper { position: relative; border-radius: 4px; overflow: hidden; }
.shift-wrapper.pagi { background: #DBEAFE; color: #1E40AF; }
.shift-wrapper.siang { background: #FEF9C3; color: #92400E; }
.shift-wrapper.libur { background: #FEE2E2; color: #B91C1C; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('admin.schedules.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1>Atur Jadwal Shift</h1>
                <p>Pilih bulan dan tentukan shift harian karyawan</p>
            </div>
        </div>
    </div>
</div>

<div class="card-sb mb-4">
    <form action="{{ route('admin.schedules.create') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
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
        <div class="ms-auto">
            <button form="scheduleForm" type="submit" class="btn-primary-sb">
                <i class="fa-solid fa-save"></i> Simpan Jadwal
            </button>
        </div>
    </form>
</div>

<div class="card-sb p-0" style="overflow:hidden;">
    <form id="scheduleForm" action="{{ route('admin.schedules.store') }}" method="POST">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">
        
        <div class="table-responsive">
            <table class="table table-schedule mb-0" style="min-width:1200px;">
                <thead>
                    <tr>
                        <th style="min-width:150px; text-align:left; padding-left:16px !important;">Karyawan</th>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php 
                                $date = \Carbon\Carbon::createFromDate($year, $month, $i);
                                $isWeekend = $date->isWeekend();
                            @endphp
                            <th style="{{ $isWeekend ? 'background:#FEE2E2; color:#B91C1C;' : '' }}">
                                {{ $i }}<br><span style="font-size:9px; font-weight:normal;">{{ $date->locale('id')->isoFormat('dd') }}</span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td style="padding-left:16px !important; font-size:13px; font-weight:600;">{{ $emp->name }}</td>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $currentShift = '';
                                if(isset($existing[$emp->id][$i])) {
                                    $currentShift = $existing[$emp->id][$i]->shift_type;
                                }
                            @endphp
                            <td>
                                <div class="shift-wrapper {{ $currentShift }}" id="wrap_{{ $emp->id }}_{{ $i }}">
                                    <select name="schedules[{{ $emp->id }}][{{ $i }}]" class="shift-select" onchange="updateColor(this, 'wrap_{{ $emp->id }}_{{ $i }}')">
                                        <option value="">-</option>
                                        <option value="pagi"  {{ $currentShift == 'pagi' ? 'selected' : '' }}>P</option>
                                        <option value="siang" {{ $currentShift == 'siang' ? 'selected' : '' }}>S</option>
                                        <option value="libur" {{ $currentShift == 'libur' ? 'selected' : '' }}>L</option>
                                    </select>
                                </div>
                            </td>
                        @endfor
                    </tr>
                    @empty
                    <tr><td colspan="{{ $daysInMonth + 1 }}" class="text-center py-4">Belum ada karyawan aktif</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updateColor(select, wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    wrapper.className = 'shift-wrapper ' + select.value;
}
</script>
@endpush
