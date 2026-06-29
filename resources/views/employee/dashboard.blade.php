@extends('layouts.employee')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Utama')

@push('styles')
<style>
.clock-display {
    font-size: 52px; font-weight: 800; letter-spacing: 2px;
    color: var(--sb-text); line-height: 1.1; margin-bottom: 4px;
    font-feature-settings: "tnum"; font-variant-numeric: tabular-nums;
}
.date-display { font-size: 14px; font-weight: 500; color: var(--sb-text-muted); }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Left Column: Clock & Check In -->
    <div class="col-lg-4">
        <div class="card-sb text-center mb-4 pt-5 pb-5" style="border:none; box-shadow:0 4px 20px rgba(0,0,0,0.04);">
            <div class="clock-display" id="realtimeClock">00:00:00</div>
            <div class="date-display">{{ $today->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
            
            <div class="mt-5 mb-4 px-3">
                <div class="d-flex justify-content-between text-start mb-2 pb-2 border-bottom">
                    <span style="font-size:12px; color:var(--sb-text-muted);">Status Hari Ini</span>
                    @if($todayAttendance)
                        <span class="status-badge badge-{{ $todayAttendance->status }} py-1">{{ $todayAttendance->status_label }}</span>
                    @elseif($todaySchedule && $todaySchedule->shift_type == 'libur')
                        <span class="status-badge badge-libur py-1">Libur</span>
                    @else
                        <span class="status-badge" style="background:#F0F2F5; color:#9CA3AF; padding:2px 8px;">Belum Absen</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between text-start mb-2 pb-2 border-bottom">
                    <span style="font-size:12px; color:var(--sb-text-muted);">Jam Masuk</span>
                    <span style="font-size:13px; font-weight:700;">{{ $todayAttendance->check_in ?? '--:--' }}</span>
                </div>
                <div class="d-flex justify-content-between text-start mb-2 pb-2 border-bottom">
                    <span style="font-size:12px; color:var(--sb-text-muted);">Jam Pulang</span>
                    <span style="font-size:13px; font-weight:700;">{{ $todayAttendance->check_out ?? '--:--' }}</span>
                </div>
                <div class="d-flex justify-content-between text-start mb-2 pb-2">
                    <span style="font-size:12px; color:var(--sb-text-muted);">Jadwal Shift</span>
                    <span style="font-size:13px; font-weight:700; color:var(--sb-primary);">
                        @if($todaySchedule)
                            {{ $todaySchedule->shift_label }} ({{ $todaySchedule->start_time ? substr($todaySchedule->start_time,0,5) : '-' }} - {{ $todaySchedule->end_time ? substr($todaySchedule->end_time,0,5) : '-' }})
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <div class="px-3 d-flex flex-column gap-3">
                @if(!$todayAttendance || !$todayAttendance->check_in)
                    <form action="{{ route('employee.attendance.check-in') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-absen-masuk" {{ ($todaySchedule && $todaySchedule->shift_type == 'libur') ? 'disabled' : '' }}>
                            Absen Masuk
                        </button>
                    </form>
                    <button class="btn-absen-pulang" disabled>Absen Pulang</button>
                @elseif(!$todayAttendance->check_out)
                    <button class="btn-absen-masuk done" disabled><i class="fa-solid fa-check"></i> Sudah Absen Masuk</button>
                    <form action="{{ route('employee.attendance.check-out') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-absen-pulang">Absen Pulang</button>
                    </form>
                @else
                    <button class="btn-absen-masuk done" disabled><i class="fa-solid fa-check"></i> Sudah Absen Masuk</button>
                    <button class="btn-absen-pulang done" disabled><i class="fa-solid fa-check"></i> Sudah Absen Pulang</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & History -->
    <div class="col-lg-8">
        <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">Absensi Bulan Ini</h6>
        <div class="stat-grid mb-4">
            <div class="stat-card-emp hadir">
                <div class="stat-value">{{ $hariHadir }}</div>
                <div class="stat-label">Hari Hadir</div>
                <div class="stat-sub">dari {{ $totalWorkDays }} hari kerja</div>
            </div>
            <div class="stat-card-emp terlambat">
                <div class="stat-value">{{ $terlambat }}</div>
                <div class="stat-label">Terlambat</div>
                <div class="stat-sub">sekali bulan ini</div>
            </div>
            <div class="stat-card-emp izin">
                <div class="stat-value">{{ $izinApproved }}</div>
                <div class="stat-label">Izin Disetujui</div>
                <div class="stat-sub">{{ $izinApproved }} hari diizinkan</div>
            </div>
            <div class="stat-card-emp alpha">
                <div class="stat-value">{{ $alpha }}</div>
                <div class="stat-label">Tanpa Keterangan</div>
                <div class="stat-sub">{{ $alpha == 0 ? 'catatan bersih' : 'perlu perhatian' }}</div>
            </div>
        </div>

        <div class="card-sb p-0" style="overflow:hidden;">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 style="font-size:14px; font-weight:700; margin:0;">Riwayat Absen Terbaru</h6>
                <a href="{{ route('employee.schedules.index') }}" style="font-size:12px; color:var(--sb-primary); text-decoration:none; font-weight:600;">Lihat Kalender Shift</a>
            </div>
            <div class="table-responsive">
                <table class="table table-emp mb-0">
                    <thead>
                        <tr>
                            <th>Hari/Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $att)
                        <tr>
                            <td>{{ $att->date->locale('id')->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>{{ $att->check_in ?? '-' }}</td>
                            <td>{{ $att->check_out ?? '-' }}</td>
                            <td><span class="badge-status badge-{{ $att->status }}">{{ $att->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada riwayat absensi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('realtimeClock').textContent = `${h}:${m}:${s}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush
