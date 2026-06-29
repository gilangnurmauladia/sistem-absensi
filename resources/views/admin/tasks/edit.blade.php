@extends('layouts.admin')

@section('content')
<div class="container">

    <h2>Edit Tugas Karyawan</h2>

    <form action="{{ route('admin.tasks.update', $task->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Karyawan</label>

            <select name="employee_id"
                    class="form-control">

                @foreach($employees as $employee)

                    <option value="{{ $employee->id }}"
                        {{ $task->employee_id == $employee->id ? 'selected' : '' }}>

                        {{ $employee->name }}

                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label>Nama Tugas</label>

            <input type="text"
                   name="task_name"
                   value="{{ $task->task_name }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>

            <select name="status"
                    class="form-control">

                <option value="belum"
                    {{ $task->status == 'belum' ? 'selected' : '' }}>
                    Belum
                </option>

                <option value="selesai"
                    {{ $task->status == 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>

            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal</label>

            <input type="date"
                   name="task_date"
                   value="{{ $task->task_date }}"
                   class="form-control">
        </div>

        <button class="btn btn-primary">
            Update
        </button>

    </form>

</div>
@endsection