@extends('layouts.admin')
@section('title', 'Edit Penilaian')
@section('page-title', 'Edit Penilaian')
 
@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.performances.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Edit Penilaian Kinerja</h1>
            <p>Perbarui hasil evaluasi karyawan</p>
        </div>
    </div>
</div>
 
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-sb">
            <form action="{{ route('admin.performances.update', $performance) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label-sb">Karyawan</label>
                        <input type="text" class="form-control-sb bg-light" readonly value="{{ $performance->employee->name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-sb">Bulan</label>
                        <input type="text" class="form-control-sb bg-light" readonly value="{{ $performance->month_name }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-sb">Tahun</label>
                        <input type="text" class="form-control-sb bg-light" readonly value="{{ $performance->year }}">
                    </div>
                </div>
 
                <hr style="border-color:var(--sb-border); margin:24px 0;">
                <h6 style="font-size:14px; font-weight:700; margin-bottom:16px; color:var(--sb-primary);">
                    <i class="fa-solid fa-list-check"></i> Kriteria Penilaian SAW
                </h6>
 
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-sb">Kehadiran (Benefit - 30%)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" class="form-control-sb bg-light" readonly 
                                value="{{ $attendanceData['attendance_count'] }} hari = {{ $performance->attendance_score }} poin">
                        </div>
                        <p class="small text-muted mt-1">Otomatis dihitung sistem (Readonly)</p>
                    </div>
 
                    <div class="col-md-6">
                        <label class="form-label-sb">Keterlambatan (Cost - 20%)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" class="form-control-sb bg-light" readonly 
                                value="{{ $attendanceData['tardiness_count'] }} kali = {{ $performance->tardiness_score }} poin">
                        </div>
                        <p class="small text-muted mt-1">Otomatis dihitung sistem (Readonly)</p>
                    </div>
 
                    <div class="col-md-4">
                        <label class="form-label-sb">Tanggung Jawab (Benefit - 20%) <span style="color:red">*</span></label>
                        <select name="responsibility_score" class="form-control-sb form-select-sb" required>
                            @foreach($scoreOptions as $val => $lbl)
                                <option value="{{ $val }}" {{ old('responsibility_score', $performance->responsibility_score) == $val ? 'selected' : '' }}>{{ $val }} - {{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <div class="col-md-4">
                        <label class="form-label-sb">Kebersihan (Benefit - 15%) <span style="color:red">*</span></label>
                        <select name="cleanliness_score" class="form-control-sb form-select-sb" required>
                            @foreach($scoreOptions as $val => $lbl)
                                <option value="{{ $val }}" {{ old('cleanliness_score', $performance->cleanliness_score) == $val ? 'selected' : '' }}>{{ $val }} - {{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <div class="col-md-4">
                        <label class="form-label-sb">Keramahan (Benefit - 15%) <span style="color:red">*</span></label>
                        <select name="friendliness_score" class="form-control-sb form-select-sb" required>
                            @foreach($scoreOptions as $val => $lbl)
                                <option value="{{ $val }}" {{ old('friendliness_score', $performance->friendliness_score) == $val ? 'selected' : '' }}>{{ $val }} - {{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <div class="col-12 mt-3">
                        <label class="form-label-sb">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control-sb" rows="3">{{ old('notes', $performance->notes) }}</textarea>
                    </div>
                </div>
 
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary-sb">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.performances.index') }}" class="btn-outline-sb">Batal</a>
                </div>
            </form>
        </div>
    </div>
 
    <div class="col-lg-4">
        <div class="card-sb" style="background: linear-gradient(135deg, #F5EDE4, #FAF7F2);">
            <h6 style="font-size:14px; font-weight:700; margin-bottom:12px;">📊 Rumus Ranking SAW</h6>
            <div class="mb-3">
                <p style="font-size:12px; color:var(--sb-text-muted);">
                    Data absensi (Kehadiran & Keterlambatan) bersifat <strong>Readonly</strong> karena ditarik langsung dari log absensi sistem pada periode tersebut.
                </p>
                <div class="d-flex flex-column gap-2">
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Kehadiran</span><span class="fw-bold">30%</span>
                    </div>
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Keterlambatan</span><span class="fw-bold">20%</span>
                    </div>
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Lainnya</span><span class="fw-bold">50%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
