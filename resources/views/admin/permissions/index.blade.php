@extends('layouts.admin')
 
@section('title', 'Permissions')
@section('page-title', 'Permissions')
 
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card-sb mb-4">
            <h5 class="fw-bold mb-3">Tambah Permission</h5>
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label-sb">Nama Permission</label>
                    <input type="text" name="name" class="form-control-sb" placeholder="Contoh: view-reports" required>
                </div>
                <button type="submit" class="btn-primary-sb w-100 justify-content-center">
                    <i class="fa-solid fa-plus"></i> Simpan Permission
                </button>
            </form>
        </div>
    </div>
 
    <div class="col-md-8">
        <div class="card-sb">
            <h5 class="fw-bold mb-4">Daftar Permission</h5>
            <div class="table-responsive">
                <table class="table table-sb">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Permission</th>
                            <th width="100" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $loop->iteration + ($permissions->firstItem() - 1) }}</td>
                            <td>{{ $permission->name }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-sb py-1 px-2" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
