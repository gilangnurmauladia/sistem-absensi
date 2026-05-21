@extends('layouts.admin')
 
@section('title', 'Edit User')
@section('page-title', 'Edit User')
 
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card-sb">
            <div class="mb-4">
                <h5 class="fw-bold">Perbarui Akun</h5>
                <p class="text-muted small">Update informasi profil dan hak akses pengguna.</p>
            </div>
 
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label-sb">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control-sb" placeholder="Masukkan nama" required value="{{ old('name', $user->name) }}">
                </div>
 
                <div class="mb-3">
                    <label class="form-label-sb">Alamat Email</label>
                    <input type="email" name="email" class="form-control-sb" placeholder="email@example.com" required value="{{ old('email', $user->email) }}">
                </div>
 
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-sb">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control-sb" placeholder="Kosongkan jika tidak diubah">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-sb">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control-sb" placeholder="••••••••">
                    </div>
                </div>
 
                <div class="mb-4">
                    <label class="form-label-sb">Pilih Role Access</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
 
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-sb">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-outline-sb">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
