@extends('layouts.admin')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h2>Data Tugas Karyawan</h2>

        <a href="{{ route('admin.tasks.create') }}"
           class="btn btn-primary">
            Tambah Tugas
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Karyawan</th>
                <th>Tugas</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->employee->name }}</td>
                <td>{{ $task->task_name }}</td>
                <td>{{ ucfirst($task->status) }}</td>
                <td>{{ $task->task_date }}</td>

                <td>
                    <a href="{{ route('admin.tasks.edit', $task->id) }}"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('admin.tasks.destroy', $task->id) }}"
                          method="POST"
                          style="display:inline-block">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection