@extends('layouts.admin')
@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.employees.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Edit Karyawan</h1>
            <p>Perbarui data {{ $employee->name }}</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-sb">
            <form action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <h6 style="font-size:14px; font-weight:700; margin-bottom:16px; color:var(--sb-primary);">
                    <i class="fa-solid fa-user"></i> Informasi Pribadi
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-sb">Nama Lengkap <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control-sb" value="{{ old('name', $employee->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Jabatan <span style="color:red">*</span></label>
                        <select name="position" class="form-control-sb form-select-sb" required>
                            @foreach($positions as $pos)
                            <option value="{{ $pos }}" {{ old('position', $employee->position) == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">No. HP</label>
                        <input type="text" name="phone" class="form-control-sb" value="{{ old('phone', $employee->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Tanggal Bergabung <span style="color:red">*</span></label>
                        <input type="date" name="join_date" class="form-control-sb" value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Status</label>
                        <select name="status" class="form-control-sb form-select-sb">
                            <option value="aktif"    {{ old('status', $employee->status) == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $employee->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sb">Alamat</label>
                        <textarea name="address" class="form-control-sb" rows="2">{{ old('address', $employee->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sb">Foto Baru (biarkan kosong jika tidak diganti)</label>
                        <input type="file" name="photo" class="form-control-sb" accept="image/*" id="photoInput">
                        <div class="mt-2">
                            <img id="previewImg" src="{{ $employee->photo_url }}" style="width:70px; height:70px; border-radius:10px; object-fit:cover; border:2px solid var(--sb-border);">
                        </div>
                    </div>
                </div>

                <hr style="border-color:var(--sb-border); margin:24px 0;">
                <h6 style="font-size:14px; font-weight:700; margin-bottom:16px; color:var(--sb-primary);">
                    <i class="fa-solid fa-lock"></i> Akun Login
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-sb">Email <span style="color:red">*</span></label>
                        <input type="email" name="email" class="form-control-sb" value="{{ old('email', $employee->user->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Password Baru (biarkan kosong jika tidak diganti)</label>
                        <input type="password" name="password" class="form-control-sb" placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary-sb">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn-outline-sb">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-sb text-center mb-3">
            <img src="{{ $employee->photo_url }}" style="width:90px; height:90px; border-radius:14px; object-fit:cover; border:3px solid var(--sb-border); margin:0 auto 12px;">
            <div style="font-size:15px; font-weight:700;">{{ $employee->name }}</div>
            <div style="font-size:12px; color:var(--sb-text-muted);">{{ $employee->employee_code }}</div>
            <span class="status-badge badge-hadir mt-2" style="margin:auto;">{{ $employee->position }}</span>
        </div>
        <div class="card-sb">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="font-size:13px; color:var(--sb-text-muted);">Bergabung</span>
                <span style="font-size:13px; font-weight:600;">{{ $employee->join_date->format('d M Y') }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span style="font-size:13px; color:var(--sb-text-muted);">Total Absensi</span>
                <span style="font-size:13px; font-weight:600;">{{ $employee->attendances->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = ev => document.getElementById('previewImg').src = ev.target.result;
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
