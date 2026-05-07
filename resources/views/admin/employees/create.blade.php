@extends('layouts.admin')
@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.employees.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Tambah Karyawan</h1>
            <p>Isi data karyawan baru dan buat akun login</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-sb">
            <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h6 style="font-size:14px; font-weight:700; margin-bottom:16px; color:var(--sb-primary);">
                    <i class="fa-solid fa-user"></i> Informasi Pribadi
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-sb">Nama Lengkap <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control-sb" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Jabatan <span style="color:red">*</span></label>
                        <select name="position" class="form-control-sb form-select-sb" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($positions as $pos)
                            <option value="{{ $pos }}" {{ old('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">No. HP</label>
                        <input type="text" name="phone" class="form-control-sb" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Tanggal Bergabung <span style="color:red">*</span></label>
                        <input type="date" name="join_date" class="form-control-sb" value="{{ old('join_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sb">Alamat</label>
                        <textarea name="address" class="form-control-sb" rows="2" placeholder="Alamat lengkap karyawan">{{ old('address') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sb">Foto (Opsional)</label>
                        <input type="file" name="photo" class="form-control-sb" accept="image/jpg,image/jpeg,image/png" id="photoInput">
                        <div class="mt-2" id="photoPreview" style="display:none;">
                            <img id="previewImg" src="" style="width:80px; height:80px; border-radius:10px; object-fit:cover;">
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
                        <input type="email" name="email" class="form-control-sb" value="{{ old('email') }}" required placeholder="email@sunsetbridge.id">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Password <span style="color:red">*</span></label>
                        <input type="password" name="password" class="form-control-sb" required placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary-sb">
                        <i class="fa-solid fa-save"></i> Simpan Karyawan
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn-outline-sb">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-sb" style="background: linear-gradient(135deg, #F5EDE4, #FAF7F2);">
            <h6 style="font-size:14px; font-weight:700; margin-bottom:12px;">📋 Catatan</h6>
            <ul style="font-size:13px; color:var(--sb-text-muted); padding-left:16px; line-height:1.8;">
                <li>Kode karyawan akan dibuat otomatis</li>
                <li>Email digunakan untuk login ke sistem</li>
                <li>Jabatan: Barista, Kasir, Waiters, Kitchen, Supervisor, Store Manager</li>
                <li>Foto maksimal 2MB (JPG/PNG)</li>
                <li>Karyawan baru langsung berstatus <strong>Aktif</strong></li>
            </ul>
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
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
