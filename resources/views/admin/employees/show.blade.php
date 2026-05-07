@extends('layouts.admin')
@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.employees.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Detail Karyawan</h1>
            <p>Informasi lengkap {{ $employee->name }}</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Profil Card -->
    <div class="col-lg-4">
        <div class="card-sb text-center mb-4">
            <img src="{{ $employee->photo_url }}" style="width:110px; height:110px; border-radius:18px; object-fit:cover; border:3px solid var(--sb-border); margin:0 auto 16px;">
            <h5 style="font-size:18px; font-weight:800; margin-bottom:4px;">{{ $employee->name }}</h5>
            <div style="font-size:13px; color:var(--sb-text-muted); margin-bottom:12px;">{{ $employee->employee_code }}</div>
            <span class="status-badge badge-hadir">{{ $employee->position }}</span>
            
            <div class="mt-4 pt-4 border-top" style="border-color:var(--sb-border) !important;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px; color:var(--sb-text-muted);">Status</span>
                    @if($employee->status === 'aktif')
                        <span class="status-badge badge-hadir">Aktif</span>
                    @else
                        <span class="status-badge badge-alpha">Nonaktif</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px; color:var(--sb-text-muted);">Bergabung</span>
                    <span style="font-size:13px; font-weight:600;">{{ $employee->join_date->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px; color:var(--sb-text-muted);">No. HP</span>
                    <span style="font-size:13px; font-weight:600;">{{ $employee->phone ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:13px; color:var(--sb-text-muted);">Email</span>
                    <span style="font-size:13px; font-weight:600;">{{ $employee->user->email }}</span>
                </div>
                <div class="text-start mt-3">
                    <span style="font-size:13px; color:var(--sb-text-muted); display:block; margin-bottom:4px;">Alamat</span>
                    <span style="font-size:13px; font-weight:500;">{{ $employee->address ?? '-' }}</span>
                </div>
            </div>
            
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn-primary-sb w-100 justify-content-center mt-4">
                <i class="fa-solid fa-pen"></i> Edit Karyawan
            </a>
        </div>
    </div>

    <!-- Right Content -->
    <div class="col-lg-8">
        <!-- Tabs -->
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist" style="gap:10px;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-absen-tab" data-bs-toggle="pill" data-bs-target="#pills-absen" type="button" role="tab" style="font-size:13.5px; font-weight:600; border-radius:10px; color:var(--sb-text);">Absensi Terakhir</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-izin-tab" data-bs-toggle="pill" data-bs-target="#pills-izin" type="button" role="tab" style="font-size:13.5px; font-weight:600; border-radius:10px; color:var(--sb-text);">Riwayat Izin</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-nilai-tab" data-bs-toggle="pill" data-bs-target="#pills-nilai" type="button" role="tab" style="font-size:13.5px; font-weight:600; border-radius:10px; color:var(--sb-text);">Penilaian</button>
            </li>
        </ul>
        
        <style>
            .nav-pills .nav-link.active { background: var(--sb-primary); color: white !important; }
            .nav-pills .nav-link { background: white; border: 1px solid var(--sb-border); padding: 8px 16px; }
        </style>

        <div class="tab-content" id="pills-tabContent">
            <!-- Tab Absensi -->
            <div class="tab-pane fade show active" id="pills-absen" role="tabpanel">
                <div class="card-sb">
                    <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">10 Absensi Terakhir</h6>
                    <div class="table-responsive">
                        <table class="table table-sb mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->attendances as $att)
                                <tr>
                                    <td style="font-size:13px; font-weight:500;">{{ $att->date->format('d M Y') }}</td>
                                    <td><span class="status-badge {{ $att->status === 'terlambat' ? 'badge-terlambat' : 'badge-hadir' }}">{{ $att->check_in ?? '-' }}</span></td>
                                    <td><span class="status-badge badge-hadir">{{ $att->check_out ?? '-' }}</span></td>
                                    <td style="font-size:13px;">{{ $att->duration ?? '-' }}</td>
                                    <td><span class="status-badge badge-{{ $att->status }}">{{ $att->status_label }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4" style="color:var(--sb-text-muted);">Belum ada riwayat absensi</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Izin -->
            <div class="tab-pane fade" id="pills-izin" role="tabpanel">
                <div class="card-sb">
                    <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">Riwayat Izin</h6>
                    <div class="table-responsive">
                        <table class="table table-sb mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Lama Izin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employee->leaves as $leave)
                                <tr>
                                    <td style="font-size:13px;">{{ $leave->start_date->format('d M Y') }}</td>
                                    <td style="font-size:13px; font-weight:500;">{{ $leave->type_label }}</td>
                                    <td style="font-size:13px;">{{ $leave->duration_days }} hari</td>
                                    <td><span class="status-badge badge-{{ $leave->status }}">{{ $leave->status_label }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4" style="color:var(--sb-text-muted);">Belum ada riwayat izin</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Penilaian -->
            <div class="tab-pane fade" id="pills-nilai" role="tabpanel">
                <div class="card-sb">
                    <h6 style="font-size:15px; font-weight:700; margin-bottom:16px;">Penilaian Kinerja Bulanan</h6>
                    <div class="row g-3">
                        @forelse($employee->performanceReviews as $rev)
                        <div class="col-md-6">
                            <div style="border:1px solid var(--sb-border); border-radius:10px; padding:16px;">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <div style="font-size:14px; font-weight:700;">Bulan {{ $rev->month_name }} {{ $rev->year }}</div>
                                        <div style="font-size:11px; color:var(--sb-text-muted);">Dinilai oleh: {{ $rev->reviewedBy->name }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div style="font-size:18px; font-weight:800; color:var(--sb-primary);">{{ $rev->total_score }}/15</div>
                                        <span class="status-badge bg-{{ $rev->grade_color }} text-white mt-1">{{ $rev->grade_label }}</span>
                                    </div>
                                </div>
                                <div class="progress" style="height:6px; margin-bottom:12px;">
                                    <div class="progress-bar bg-{{ $rev->grade_color }}" style="width: {{ $rev->score_percent }}%"></div>
                                </div>
                                <div style="font-size:12px; color:var(--sb-text-muted); font-style:italic;">
                                    "{{ $rev->notes ?? 'Tidak ada catatan.' }}"
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-4" style="color:var(--sb-text-muted);">Belum ada data penilaian</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
