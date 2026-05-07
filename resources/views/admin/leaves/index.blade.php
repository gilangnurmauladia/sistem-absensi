@extends('layouts.admin')
@section('title', 'Manajemen Izin')
@section('page-title', 'Manajemen Izin')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h1>Manajemen Izin</h1>
            <p>Persetujuan pengajuan izin, cuti, dan sakit karyawan</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-sb d-flex align-items-center gap-3">
            <div class="stat-icon amber"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div>
                <div class="stat-value">{{ $pendingCount }}</div>
                <div class="stat-label">Menunggu Persetujuan</div>
            </div>
        </div>
    </div>
</div>

<div class="card-sb">
    <form method="GET" action="{{ route('admin.leaves.index') }}" class="d-flex gap-3 flex-wrap align-items-end mb-4">
        <div>
            <label class="form-label-sb">Status</label>
            <select name="status" class="form-control-sb form-select-sb" style="width:150px;">
                <option value="">Semua</option>
                <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}>Menunggu</option>
                <option value="approved" {{ request('status')=='approved' ? 'selected':'' }}>Disetujui</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected':'' }}>Ditolak</option>
            </select>
        </div>
        <button type="submit" class="btn-outline-sb">Filter</button>
        @if(request('status'))
            <a href="{{ route('admin.leaves.index') }}" class="btn-outline-sb" style="border-color:#C8D0D8; color:var(--sb-text-muted);">Reset</a>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-sb table-hover mb-0">
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jenis Izin</th>
                    <th>Tanggal</th>
                    <th>Durasi</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $leave->employee->photo_url }}" style="width:30px; height:30px; border-radius:6px; object-fit:cover;">
                            <div style="font-size:13px; font-weight:600;">{{ $leave->employee->name }}</div>
                        </div>
                    </td>
                    <td style="font-size:13px; font-weight:600; color:var(--sb-primary-dark);">{{ $leave->type_label }}</td>
                    <td style="font-size:12px;">
                        @if($leave->start_date->eq($leave->end_date))
                            {{ $leave->start_date->format('d M Y') }}
                        @else
                            {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                        @endif
                    </td>
                    <td style="font-size:13px;">{{ $leave->duration_days }} hari</td>
                    <td style="font-size:12px; color:var(--sb-text-muted); max-width:200px;" class="text-truncate">
                        {{ $leave->reason }}
                    </td>
                    <td>
                        <span class="status-badge badge-{{ $leave->status }}">{{ $leave->status_label }}</span>
                    </td>
                    <td>
                        @if($leave->status === 'pending')
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" onsubmit="return confirm('Setujui izin ini?')">
                                    @csrf
                                    <button class="btn btn-sm btn-success text-white" style="font-size:11px; padding:4px 8px; border-radius:6px;"><i class="fa-solid fa-check"></i> Setujui</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger text-white" style="font-size:11px; padding:4px 8px; border-radius:6px;" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="border-radius:12px;">
                                        <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST">
                                            @csrf
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title" style="font-weight:700; font-size:16px;">Tolak Izin Karyawan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p style="font-size:13px; color:var(--sb-text-muted);">Berikan alasan penolakan untuk izin <strong>{{ $leave->employee->name }}</strong>.</p>
                                                <textarea name="rejection_note" class="form-control-sb" rows="3" required placeholder="Alasan ditolak..."></textarea>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn-outline-sb" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn-danger-sb">Tolak Izin</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button type="button" class="btn btn-sm btn-light" style="font-size:11px; padding:4px 8px; border-radius:6px;" data-bs-toggle="modal" data-bs-target="#detailModal{{ $leave->id }}">
                                Detail
                            </button>
                            <!-- Detail Modal -->
                            <div class="modal fade" id="detailModal{{ $leave->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content" style="border-radius:12px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title" style="font-weight:700; font-size:16px;">Detail Keputusan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="font-size:12px; margin-bottom:8px;"><span class="text-muted">Diproses Oleh:</span><br><strong>{{ $leave->approvedBy->name ?? '-' }}</strong></div>
                                            @if($leave->status === 'rejected')
                                                <div style="font-size:12px;"><span class="text-muted">Alasan Penolakan:</span><br><strong>{{ $leave->rejection_note }}</strong></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5" style="color:var(--sb-text-muted);">Belum ada data pengajuan izin</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $leaves->links() }}</div>
</div>
@endsection
