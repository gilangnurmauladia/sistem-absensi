@extends('layouts.admin')
 
@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
 
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card-sb">
            <div class="mb-4">
                <h5 class="fw-bold">Buat Akun Baru</h5>
                <p class="text-muted small">Tambahkan pengguna baru dan tentukan hak aksesnya.</p>
            </div>
 
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label-sb">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control-sb" placeholder="Masukkan nama" required value="{{ old('name') }}">
                </div>
 
                <div class="mb-3">
                    <label class="form-label-sb">Alamat Email</label>
                    <input type="email" name="email" class="form-control-sb" placeholder="email@example.com" required value="{{ old('email') }}">
                </div>
 
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-sb">Password</label>
                        <input type="password" name="password" class="form-control-sb" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label-sb">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control-sb" placeholder="••••••••" required>
                    </div>
                </div>
 
                <div class="mb-4">
                    <label class="form-label-sb">Pilih Role Access</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}">
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
 
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-sb">
                        <i class="fa-solid fa-save"></i> Simpan User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-outline-sb">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
