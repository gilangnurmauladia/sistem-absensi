@extends('layouts.admin')
@section('title', 'Input Penilaian')
@section('page-title', 'Input Penilaian')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.performances.index') }}" class="btn-outline-sb" style="padding:7px 12px;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1>Input Penilaian Kinerja</h1>
            <p>Berikan penilaian bulanan untuk karyawan</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-sb">
            <form action="{{ route('admin.performances.store') }}" method="POST">
                @csrf
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label-sb">Karyawan <span style="color:red">*</span></label>
                        <select name="employee_id" class="form-control-sb form-select-sb" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ (old('employee_id') == $emp->id || ($preEmployee && $preEmployee->id == $emp->id)) ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->position }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-sb">Bulan <span style="color:red">*</span></label>
                        <select name="month" class="form-control-sb form-select-sb" required>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ old('month', $month) == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-sb">Tahun <span style="color:red">*</span></label>
                        <input type="number" name="year" class="form-control-sb" value="{{ old('year', $year) }}" required min="2020">
                    </div>
                </div>

                <hr style="border-color:var(--sb-border); margin:24px 0;">
                <h6 style="font-size:14px; font-weight:700; margin-bottom:16px; color:var(--sb-primary);">
                    <i class="fa-solid fa-list-check"></i> Indikator Penilaian (1: Rendah, 2: Sedang, 3: Tinggi)
                </h6>

                @php
                    $indicators = [
                        'punctuality'  => 'Ketepatan Waktu',
                        'attendance'   => 'Kehadiran',
                        'discipline'   => 'Kedisiplinan',
                        'cleanliness'  => 'Kebersihan',
                        'friendliness' => 'Keramahan'
                    ];
                @endphp

                <div class="row g-3">
                    @foreach($indicators as $key => $label)
                    <div class="col-md-6">
                        <label class="form-label-sb">{{ $label }} <span style="color:red">*</span></label>
                        <select name="{{ $key }}" class="form-control-sb form-select-sb" required>
                            <option value="">Pilih Nilai</option>
                            <option value="1" {{ old($key) == '1' ? 'selected' : '' }}>1 - Rendah</option>
                            <option value="2" {{ old($key) == '2' ? 'selected' : '' }}>2 - Sedang</option>
                            <option value="3" {{ old($key) == '3' ? 'selected' : '' }}>3 - Tinggi</option>
                        </select>
                    </div>
                    @endforeach
                    <div class="col-12 mt-3">
                        <label class="form-label-sb">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control-sb" rows="3" placeholder="Evaluasi kualitatif tentang kinerja karyawan...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-primary-sb">
                        <i class="fa-solid fa-save"></i> Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-sb" style="background: linear-gradient(135deg, #F5EDE4, #FAF7F2);">
            <h6 style="font-size:14px; font-weight:700; margin-bottom:12px;">📊 Sistem Grading</h6>
            <div class="d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border" style="font-size:12px;">
                    <span class="status-badge badge-hadir text-white bg-success">Sangat Baik</span>
                    <span class="fw-bold">13 - 15 Poin</span>
                </div>
                <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border" style="font-size:12px;">
                    <span class="status-badge badge-hadir text-white bg-primary">Baik</span>
                    <span class="fw-bold">10 - 12 Poin</span>
                </div>
                <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border" style="font-size:12px;">
                    <span class="status-badge badge-terlambat text-white bg-warning">Cukup</span>
                    <span class="fw-bold">7 - 9 Poin</span>
                </div>
                <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border" style="font-size:12px;">
                    <span class="status-badge badge-alpha text-white bg-danger">Kurang</span>
                    <span class="fw-bold">5 - 6 Poin</span>
                </div>
            </div>
            <p style="font-size:12px; color:var(--sb-text-muted); margin:0;">
                Satu karyawan hanya dapat dinilai satu kali per bulan. Total maksimal adalah 15 poin.
            </p>
        </div>
    </div>
</div>
@endsection
