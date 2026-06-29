@extends('layouts.admin')

@section('content')
<div class="container">

    <h2>Tambah Tugas Karyawan</h2>

    <form action="{{ route('admin.tasks.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">
            <label>Karyawan</label>

            <select name="employee_id"
                    class="form-control">

                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }}
                    </option>
                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label>Nama Tugas</label>

            <input type="text"
                   name="task_name"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>

            <select name="status"
                    class="form-control">

                <option value="belum">Belum</option>
                <option value="selesai">Selesai</option>

            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>

            <input type="date"
                   name="task_date"
                   class="form-control">
        </div>

        <button class="btn btn-success">
            Simpan
        </button>

    </form>

</div>
@endsection