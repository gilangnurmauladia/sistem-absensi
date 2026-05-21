@extends('layouts.admin')
@section('title', 'Edit Absensi')
@section('page-title', 'Edit Absensi')
 
@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.attendances.show', $attendance->employee_id) }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Edit Absensi: {{ $attendance->employee->name }}</h1>
            <p>Tanggal: {{ $attendance->date->format('d M Y') }}</p>
        </div>
    </div>
</div>
 
<div class="card-sb">
    <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sb">Jam Masuk</label>
                <input type="time" name="check_in" class="form-control-sb" value="{{ $attendance->check_in }}">
            </div>
            <div class="col-md-6">
                <label class="form-label-sb">Jam Pulang</label>
                <input type="time" name="check_out" class="form-control-sb" value="{{ $attendance->check_out }}">
            </div>
            <div class="col-md-6">
                <label class="form-label-sb">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control-sb form-select-sb" required>
                    <option value="hadir" {{ $attendance->status === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ $attendance->status === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="izin" {{ $attendance->status === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="alpha" {{ $attendance->status === 'alpha' ? 'selected' : '' }}>Alpha</option>
                    <option value="libur" {{ $attendance->status === 'libur' ? 'selected' : '' }}>Libur</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label-sb">Catatan</label>
                <textarea name="notes" class="form-control-sb" rows="3">{{ $attendance->notes }}</textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn-primary-sb">
                <i class="fa-solid fa-save"></i> Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
