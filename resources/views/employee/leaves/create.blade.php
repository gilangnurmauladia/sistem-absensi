@extends('layouts.employee')
@section('title', 'Ajukan Izin')
@section('page-title', 'Ajukan Izin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-sb">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('employee.leaves.index') }}" class="btn-outline-emp" style="padding:6px 12px;"><i class="fa-solid fa-arrow-left"></i></a>
                <h5 style="margin:0; font-weight:700;">Form Pengajuan Izin</h5>
            </div>

            <form action="{{ route('employee.leaves.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label-sb">Jenis Izin <span class="text-danger">*</span></label>
                        <select name="type" class="form-control-sb form-select-sb" required>
                            <option value="">Pilih Jenis Izin</option>
                            <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="cuti" {{ old('type') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>Izin (Kepentingan Pribadi)</option>
                            <option value="keperluan_keluarga" {{ old('type') == 'keperluan_keluarga' ? 'selected' : '' }}>Keperluan Keluarga</option>
                            <option value="lainnya" {{ old('type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label-sb">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control-sb" value="{{ old('start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sb">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control-sb" value="{{ old('end_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <label class="form-label-sb">Alasan / Keterangan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control-sb" rows="4" required placeholder="Tuliskan alasan pengajuan izin secara detail...">{{ old('reason') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn-primary-emp"><i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
