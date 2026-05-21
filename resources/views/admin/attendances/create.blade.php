@extends('layouts.admin')
@section('title', 'Tambah Absensi Manual')
@section('page-title', 'Tambah Absensi Manual')
 
@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.attendances.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Tambah Absensi Manual</h1>
            <p>Input data kehadiran karyawan secara manual</p>
        </div>
    </div>
</div>
 
<div class="card-sb">
    <form action="{{ route('admin.attendances.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-sb">Karyawan <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-control-sb form-select-sb" required>
                    <option value="">Pilih Karyawan</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->position }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label-sb">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control-sb" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-sb">Jam Masuk</label>
                <input type="time" name="check_in" class="form-control-sb">
            </div>
            <div class="col-md-6">
                <label class="form-label-sb">Jam Pulang</label>
                <input type="time" name="check_out" class="form-control-sb">
            </div>
            <div class="col-md-6">
                <label class="form-label-sb">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control-sb form-select-sb" required>
                    <option value="hadir">Hadir</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="izin">Izin</option>
                    <option value="alpha">Alpha</option>
                    <option value="libur">Libur</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label-sb">Catatan</label>
                <textarea name="notes" class="form-control-sb" rows="3" placeholder="Alasan input manual atau keterangan lainnya..."></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn-primary-sb">
                <i class="fa-solid fa-save"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
