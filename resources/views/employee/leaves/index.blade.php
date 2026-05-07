@extends('layouts.employee')
@section('title', 'Riwayat Izin')
@section('page-title', 'Riwayat Izin')

@section('content')
<div class="card-sb p-0" style="overflow:hidden;">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 style="font-size:15px; font-weight:700; margin:0;">Daftar Pengajuan Izin</h6>
        <a href="{{ route('employee.leaves.create') }}" class="btn-primary-emp">
            <i class="fa-solid fa-plus"></i> Ajukan Izin Baru
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-emp mb-0 align-middle">
            <thead>
                <tr>
                    <th>Tanggal Pengajuan</th>
                    <th>Jenis Izin</th>
                    <th>Periode</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td>{{ $leave->created_at->format('d M Y') }}</td>
                    <td style="font-weight:600; color:var(--sb-primary-dark);">{{ $leave->type_label }}</td>
                    <td>
                        @if($leave->start_date->eq($leave->end_date))
                            {{ $leave->start_date->format('d M Y') }}
                        @else
                            {{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}
                        @endif
                        <div style="font-size:11px; color:var(--sb-text-muted);">{{ $leave->duration_days }} hari</div>
                    </td>
                    <td style="max-width:200px;" class="text-truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                    <td><span class="badge-status badge-{{ $leave->status }}">{{ $leave->status_label }}</span></td>
                    <td style="font-size:12px; color:var(--sb-text-muted); max-width:200px;">
                        @if($leave->status === 'rejected')
                            <span class="text-danger">{{ $leave->rejection_note }}</span>
                        @elseif($leave->status === 'approved')
                            Disetujui oleh admin
                        @else
                            Menunggu persetujuan
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pengajuan izin</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $leaves->links() }}</div>
</div>
@endsection
