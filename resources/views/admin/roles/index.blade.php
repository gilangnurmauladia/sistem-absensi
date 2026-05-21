@extends('layouts.admin')
 
@section('title', 'Role & Permission')
@section('page-title', 'Role & Permission')
 
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card-sb mb-4">
            <h5 class="fw-bold mb-3">Tambah Role Baru</h5>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label-sb">Nama Role</label>
                    <input type="text" name="name" class="form-control-sb" placeholder="Contoh: Manager" required>
                </div>
                <button type="submit" class="btn-primary-sb w-100 justify-content-center">
                    <i class="fa-solid fa-plus"></i> Simpan Role
                </button>
            </form>
        </div>
    </div>
 
    <div class="col-md-8">
        <div class="card-sb">
            <h5 class="fw-bold mb-4">Pengaturan Role & Permission</h5>
            
            <div class="accordion" id="roleAccordion">
                @foreach($roles as $role)
                <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#role_{{ $role->id }}">
                            <i class="fa-solid fa-shield-halved me-2 text-primary"></i> {{ $role->name }}
                        </button>
                    </h2>
                    <div id="role_{{ $role->id }}" class="accordion-collapse collapse" data-bs-parent="#roleAccordion">
                        <div class="accordion-body">
                            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="row">
                                    @foreach($permissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                id="perm_{{ $role->id }}_{{ $permission->id }}"
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="perm_{{ $role->id }}_{{ $permission->id }}">
                                                {{ str_replace('-', ' ', ucfirst($permission->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn-primary-sb py-1 px-3">
                                        <i class="fa-solid fa-check"></i> Sync Permission
                                    </button>
                                    @if($role->name !== 'Super Admin')
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-sb py-1 px-3 bg-transparent border-0 text-danger p-0">
                                            <i class="fa-solid fa-trash"></i> Hapus Role
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
 
@push('styles')
<style>
    .accordion-button:not(.collapsed) {
        background-color: var(--sb-bg);
        color: var(--sb-primary);
        box-shadow: none;
    }
    .accordion-button:focus {
        border-color: rgba(193,125,60,0.12);
        box-shadow: 0 0 0 3px rgba(193,125,60,0.12);
    }
</style>
@endpush
