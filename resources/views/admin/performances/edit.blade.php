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
                @csrf
                @method('PUT')
                
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
                                value="{{ $attendanceData ? ($attendanceData['attendance_count'].' hari = '.$attendanceData['attendance_score'].' poin') : 'Data tidak tersedia' }}">
                        </div>
                        <p class="small text-muted mt-1">Otomatis dihitung: &lt; 15 hari = 10, &gt;= 15 hari = 20</p>
                    </div>
 
                    <div class="col-md-6">
                        <label class="form-label-sb">Keterlambatan (Cost - 20%)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="text" class="form-control-sb bg-light" readonly
                                value="{{ $attendanceData ? ($attendanceData['tardiness_count'].' kali = '.$attendanceData['tardiness_score'].' poin') : 'Data tidak tersedia' }}">
                        </div>
                        <p class="small text-muted mt-1">Otomatis dihitung dari keterlambatan</p>
                    </div>
 
                    <div class="col-md-4">
                        <label class="form-label-sb">Tanggung Jawab (Benefit - 20%)</label>
                        <input type="text" class="form-control-sb bg-light" readonly
                            value="{{ $attendanceData ? ($attendanceData['responsibility_score'].' poin - Otomatis') : 'Data tidak tersedia' }}">
                        <p class="small text-muted mt-1">Nilai otomatis dari kehadiran dan keterlambatan</p>
                    </div>
 
                    <div class="col-md-4">
                        <label class="form-label-sb">
                            Kebersihan (Benefit - 15%) <span style="color:red">*</span>
                        </label>

                        @php
                            $oldCleanliness = old('cleanliness_score', $performance->cleanliness_score);
                            $checkedCount = $oldCleanliness >= 15 ? 4 : ($oldCleanliness >= 10 ? 3 : 1);
                        @endphp

                        <div class="p-3 rounded border bg-light">
                            <label class="d-block mb-2">
                                <input type="checkbox" name="meja_bersih" {{ old('meja_bersih') || $checkedCount >= 1 ? 'checked' : '' }}>
                                Meja bersih
                            </label>

                            <label class="d-block mb-2">
                                <input type="checkbox" name="lantai_bersih" {{ old('lantai_bersih') || $checkedCount >= 2 ? 'checked' : '' }}>
                                Lantai bersih
                            </label>

                            <label class="d-block mb-2">
                                <input type="checkbox" name="peralatan_bersih" {{ old('peralatan_bersih') || $checkedCount >= 3 ? 'checked' : '' }}>
                                Peralatan bersih
                            </label>

                            <label class="d-block">
                                <input type="checkbox" name="area_kerja_bersih" {{ old('area_kerja_bersih') || $checkedCount >= 4 ? 'checked' : '' }}>
                                Area kerja bersih
                            </label>
                        </div>

                        <p class="small text-muted mt-1">
                            4 checklist = 15 poin, 2-3 checklist = 10 poin, 0-1 checklist = 5 poin.
                        </p>
                    </div>
 
                    <div class="col-md-4">
                        <label class="form-label-sb">Keramahan (Benefit - 15%) <span style="color:red">*</span></label>
                        <select name="friendliness_score" class="form-control-sb form-select-sb" required>
                            <option value="">Pilih Nilai</option>
                            @foreach($scoreOptions as $val => $lbl)
                                <option value="{{ $val }}" {{ old('friendliness_score', $performance->friendliness_score) == $val ? 'selected' : '' }}>{{ $val }} - {{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
 
                    <div class="col-12 mt-3">
                        <label class="form-label-sb">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control-sb" rows="3" placeholder="Evaluasi kualitatif tentang kinerja karyawan...">{{ old('notes', $performance->notes) }}</textarea>
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
                    Ranking dihitung menggunakan metode <strong>Simple Additive Weighting (SAW)</strong> dengan bobot sebagai berikut:
                </p>
                <div class="d-flex flex-column gap-2">
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Kehadiran (Benefit)</span>
                        <span class="fw-bold">30%</span>
                    </div>
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Keterlambatan (Cost)</span>
                        <span class="fw-bold">20%</span>
                    </div>
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Tanggung Jawab (Benefit)</span>
                        <span class="fw-bold">20%</span>
                    </div>
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Kebersihan (Benefit)</span>
                        <span class="fw-bold">15%</span>
                    </div>
                    <div class="bg-white p-2 rounded border d-flex justify-content-between" style="font-size:11px;">
                        <span>Keramahan (Benefit)</span>
                        <span class="fw-bold">15%</span>
                    </div>
                </div>
            </div>
            <p style="font-size:12px; color:var(--sb-text-muted); margin:0;">
                Kriteria <strong>Benefit</strong> akan dinormalisasi dengan <i>x<sub>ij</sub> / max(x<sub>ij</sub>)</i>. <br>
                Kriteria <strong>Cost</strong> akan dinormalisasi dengan <i>min(x<sub>ij</sub>) / x<sub>ij</sub></i>.
            </p>
        </div>
    </div>
</div>
@endsection
