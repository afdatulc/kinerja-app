@extends('layouts.dashboard')

@section('title', 'Buat Notulen Kinerja')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-file-word text-primary me-2"></i> Form Notulen Kinerja</h5>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('notulen.download') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nama_satker" class="form-label fw-bold">Nama Satker</label>
                        <input type="text" name="nama_satker" id="nama_satker" class="form-control" 
                               placeholder="Contoh: BPS Kabupaten Tapin" required>
                        <div class="form-text">Masukkan nama satuan kerja yang akan dicantumkan dalam notulen.</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="triwulan" class="form-label fw-bold">Periode Triwulan</label>
                            <select name="triwulan" id="triwulan" class="form-select" required>
                                <option value="" selected disabled>Pilih Triwulan</option>
                                <option value="1">Triwulan I</option>
                                <option value="2">Triwulan II</option>
                                <option value="3">Triwulan III</option>
                                <option value="4">Triwulan IV</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tahun" class="form-label fw-bold">Periode Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" required>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="fas fa-download me-2"></i> Generate & Download Notulen (.docx)
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light py-3 border-0">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i> Data kinerja dan analisis akan otomatis diambil dari database berdasarkan periode yang dipilih.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
