@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.ranking-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--sb-border); }
.ranking-row:last-child { border-bottom: none; }
.rank-badge { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0; }
.rank-1 { background: #FEF9C3; color: #92400E; }
.rank-2 { background: #F0F0F0; color: #374151; }
.rank-3 { background: #FEF3E2; color: #92400E; }
.rank-other { background: var(--sb-bg); color: var(--sb-text-muted); }
.score-bar { height: 6px; border-radius: 3px; background: #E8ECF0; overflow: hidden; }
.score-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, var(--sb-primary), var(--sb-accent)); }
.emp-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--sb-border); }
.emp-row:last-child { border-bottom: none; }
.emp-avatar { width: 34px; height: 34px; border-radius: 9px; background: var(--sb-primary); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 700; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-user-check"></i></div>
            <div>
                <div class="stat-value">{{ $hadirCount }}</div>
                <div class="stat-label">Hadir Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fa-solid fa-clock"></i></div>
            <div>
                <div class="stat-value">{{ $terlambatCount }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-file-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ $izinCount }}</div>
                <div class="stat-label">Izin Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fa-solid fa-user-xmark"></i></div>
            <div>
                <div class="stat-value">{{ $alphaCount }}</div>
                <div class="stat-label">Alpha/Tidak Hadir</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Chart Kehadiran 7 Hari -->
    <div class="col-lg-8">
        <div class="card-sb h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0" style="font-size:15px; font-weight:700;">Grafik Kehadiran 7 Hari Terakhir</h6>
                <a href="{{ route('admin.attendances.recap') }}" class="btn-outline-sb" style="font-size:12px; padding:5px 12px;">Lihat Rekap</a>
            </div>
            <div style="height:260px;">
    <canvas id="attendanceChart"></canvas>
</div>
        </div>
    </div>

    <!-- Ranking Performa -->
    <div class="col-lg-4">
        <div class="card-sb h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-700 mb-0" style="font-size:15px; font-weight:700;">Top Performa Bulan Ini</h6>
                @role('Super Admin')
                <a href="{{ route('admin.performances.index') }}" class="btn-outline-sb" style="font-size:12px; padding:5px 12px;">Lihat Semua</a>
                @endrole('Super Admin')
            </div>
            @forelse($rankingData as $idx => $review)
            <div class="ranking-row">
                <div class="rank-badge {{ $idx == 0 ? 'rank-1' : ($idx == 1 ? 'rank-2' : ($idx == 2 ? 'rank-3' : 'rank-other')) }}">
                    {{ $idx == 0 ? '🥇' : ($idx == 1 ? '🥈' : ($idx == 2 ? '🥉' : $idx + 1)) }}
                </div>
                <div class="flex-grow-1">
                    <div style="font-size:13px; font-weight:600;">{{ $review->employee->name }}</div>
                    <div style="font-size:11px; color:var(--sb-text-muted);">{{ $review->employee->position }}</div>
                    <div class="score-bar mt-1">
                        <div class="score-fill" style="width:{{ $review->score_percent }}%"></div>
                    </div>
                </div>
                <div style="font-size:14px; font-weight:800; color:var(--sb-primary);">{{ $review->total_score }}/15</div>
            </div>
            @empty
            <div class="text-center py-4" style="color:var(--sb-text-muted); font-size:13px;">
                <i class="fa-solid fa-chart-bar fa-2x mb-2 d-block" style="opacity:0.3;"></i>
                Belum ada penilaian bulan ini
            </div>
            @endforelse

            @if($pendingLeaves > 0)
            <div class="mt-3 p-3 rounded-3" style="background:#FEF3E2;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock" style="color:#D4860A;"></i>
                    <span style="font-size:13px; font-weight:600; color:#D4860A;">{{ $pendingLeaves }} izin menunggu persetujuan</span>
                </div>
                @role('Super Admin')
                <a href="{{ route('admin.leaves.index', ['status'=>'pending']) }}" class="btn-primary-sb mt-2 w-100 justify-content-center" style="font-size:12px; padding:7px;">Proses Sekarang</a>
                @endrole('Super Admin')
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Absensi Hari Ini -->
<div class="card-sb">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 style="font-size:15px; font-weight:700; margin:0;">Status Kehadiran Hari Ini</h6>
        <a href="{{ route('admin.attendances.index') }}" class="btn-outline-sb" style="font-size:12px; padding:5px 12px;">
            <i class="fa-solid fa-arrow-right"></i> Detail Lengkap
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-sb table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Shift</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employeesWithAttendance->take(8) as $emp)
                @php
                    $att      = $emp->todayAttendance;
                    $schedule = $emp->todaySchedule;
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $emp->photo_url }}" alt="" width="32" height="32"
                                 style="border-radius:8px; object-fit:cover;">
                            <div>
                                <div style="font-size:13px; font-weight:600;">{{ $emp->name }}</div>
                                <div style="font-size:11px; color:var(--sb-text-muted);">{{ $emp->employee_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $emp->position }}</td>
                    <td>
                        @if($att && $att->check_in)
                            <span class="status-badge {{ $att->status === 'terlambat' ? 'badge-terlambat' : 'badge-hadir' }}" style="font-size:12px;">
                                {{ $att->check_in }}
                            </span>
                        @else
                            <span style="color:#C8D0D8; font-size:13px;">--:--</span>
                        @endif
                    </td>
                    <td>
                        @if($att && $att->check_out)
                            <span class="status-badge badge-hadir" style="font-size:12px;">{{ $att->check_out }}</span>
                        @else
                            <span style="color:#C8D0D8; font-size:13px;">--:--</span>
                        @endif
                    </td>
                    <td>
                        @if($schedule)
                            <span class="shift-chip shift-{{ $schedule->shift_type }}">{{ $schedule->shift_code }}</span>
                        @else
                            <span style="font-size:12px; color:var(--sb-text-muted);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($att)
                            <span class="status-badge badge-{{ $att->status }}">{{ $att->status_label }}</span>
                        @elseif($schedule && $schedule->shift_type === 'libur')
                            <span class="status-badge" style="background:#F0EEF5; color:#5B4E72;">Libur</span>
                        @else
                            <span class="status-badge" style="background:#F0F2F5; color:#9CA3AF;">Belum Absen</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4" style="color:var(--sb-text-muted);">Tidak ada data karyawan aktif</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const canvas = document.getElementById('attendanceChart');

const hadirData = {!! json_encode(array_column($last7Days, 'hadir')) !!};
const izinData  = {!! json_encode(array_column($last7Days, 'izin')) !!};
const alphaData = {!! json_encode(array_column($last7Days, 'alpha')) !!};
const terlambatData = {!! json_encode(array_column($last7Days, 'terlambat')) !!};

const totalHadir = hadirData.reduce((a, b) => a + Number(b), 0);
const totalIzin  = izinData.reduce((a, b) => a + Number(b), 0);
const totalAlpha = alphaData.reduce((a, b) => a + Number(b), 0);
const totalTerlambat = terlambatData.reduce((a, b) => a + Number(b), 0);

new Chart(canvas, {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Terlambat', 'Izin', 'Alpha'],
        datasets: [{
            data: [
    totalHadir,
    totalTerlambat,
    totalIzin,
    totalAlpha
],
            backgroundColor: [
    '#4CAF50',
    '#FACC15',
    '#2196F3',
    '#F44336'
],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
