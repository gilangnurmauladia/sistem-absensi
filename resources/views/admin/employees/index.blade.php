@extends('layouts.admin')
@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1>Data Karyawan</h1>
            <p>Kelola informasi dan akun karyawan</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="btn-primary-sb">
            <i class="fa-solid fa-plus"></i> Tambah Karyawan
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card-sb mb-4">
    <form method="GET" action="{{ route('admin.employees.index') }}" class="d-flex gap-3 flex-wrap align-items-end">
        <div style="flex:1; min-width:200px;">
            <label class="form-label-sb">Cari Karyawan</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control-sb" placeholder="Nama, kode, jabatan...">
        </div>
        <div>
            <label class="form-label-sb">Status</label>
            <select name="status" class="form-control-sb form-select-sb" style="width:140px;">
                <option value="">Semua</option>
                <option value="aktif"    {{ request('status')=='aktif'    ? 'selected':'' }}>Aktif</option>
                <option value="nonaktif" {{ request('status')=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="btn-primary-sb">
            <i class="fa-solid fa-search"></i> Cari
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.employees.index') }}" class="btn-outline-sb">Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="card-sb">
    <div class="table-responsive">
        <table class="table table-sb table-hover mb-0">
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th>No. HP</th>
                    <th>Bergabung</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $emp->photo_url }}" alt="{{ $emp->name }}"
                                 style="width:38px; height:38px; border-radius:10px; object-fit:cover; border:2px solid var(--sb-border);">
                            <div>
                                <div style="font-size:13.5px; font-weight:600;">{{ $emp->name }}</div>
                                <div style="font-size:11.5px; color:var(--sb-text-muted);">
                                    <i class="fa-solid fa-id-card" style="font-size:10px;"></i> {{ $emp->employee_code }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13px; background:var(--sb-bg); padding:3px 10px; border-radius:20px; font-weight:500;">
                            {{ $emp->position }}
                        </span>
                    </td>
                    <td style="font-size:13px;">{{ $emp->phone ?? '-' }}</td>
                    <td style="font-size:13px;">{{ $emp->join_date->format('d M Y') }}</td>
                    <td>
                        @if($emp->status === 'aktif')
                            <span class="status-badge badge-hadir">Aktif</span>
                        @else
                            <span class="status-badge badge-alpha">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.employees.show', $emp) }}" class="btn-outline-sb" style="padding:5px 10px; font-size:12px;">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.employees.edit', $emp) }}" class="btn-outline-sb" style="padding:5px 10px; font-size:12px;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Hapus karyawan {{ $emp->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sb" style="padding:5px 10px; font-size:12px;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fa-solid fa-users-slash fa-2x mb-3 d-block" style="opacity:0.3;"></i>
                        <div style="color:var(--sb-text-muted);">Belum ada data karyawan</div>
                        <a href="{{ route('admin.employees.create') }}" class="btn-primary-sb mt-3">Tambah Karyawan</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $employees->links() }}</div>
</div>
@endsection
