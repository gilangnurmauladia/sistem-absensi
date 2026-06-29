@extends('layouts.admin')
 
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
 
@section('content')

<style>
    .users-pagination {
    margin-top: 24px;
}

.users-pagination nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.users-pagination nav p {
    margin: 0;
    color: #6b7280;
}

.users-pagination nav a,
.users-pagination nav span span {
    text-decoration: none;
    border-radius: 8px;
}

.users-pagination svg {
    width: 18px;
    height: 18px;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card-sb">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-0 fw-bold">Daftar Pengguna</h5>
                    <p class="text-muted small mb-0">Kelola akses pengguna ke sistem panel admin.</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn-primary-sb">
                    <i class="fa-solid fa-plus"></i> Tambah User
                </a>
            </div>
 
            <div class="table-responsive">
                <table class="table table-sb">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->firstItem() - 1) }}</td>
                            <td>
                                <div class="fw-bold">{{ $user->name }}</div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="status-badge badge-izin">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline-sb py-1 px-2" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-sb py-1 px-2" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
 
            <div class="mt-4 users-pagination
            ">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
