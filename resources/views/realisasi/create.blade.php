@extends('layouts.dashboard')

@section('title', 'Input Realisasi Kinerja')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('realisasi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Indikator</label>
                        <select name="indikator_id" class="form-select" required>
                            @foreach($indikators as $i)
                            <option value="{{ $i->id }}" {{ old('indikator_id') == $i->id ? 'selected' : '' }}>{{ $i->indikator_kinerja }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Triwulan</label>
                        <select name="triwulan" class="form-select" required>
                            <option value="1" {{ old('triwulan') == 1 ? 'selected' : '' }}>Triwulan 1</option>
                            <option value="2" {{ old('triwulan') == 2 ? 'selected' : '' }}>Triwulan 2</option>
                            <option value="3" {{ old('triwulan') == 3 ? 'selected' : '' }}>Triwulan 3</option>
                            <option value="4" {{ old('triwulan') == 4 ? 'selected' : '' }}>Triwulan 4</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Realisasi Kumulatif</label>
                        <input type="number" step="0.01" name="realisasi_kumulatif" class="form-control form-control-lg" value="{{ old('realisasi_kumulatif') }}" required>
                        <small class="text-muted text-info"><i class="fas fa-info-circle me-1"></i> Masukkan nilai kumulatif sampai akhir triwulan tersebut.</small>
                    </div>
                    
                    <div class="pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">Simpan Realisasi</button>
                        <a href="{{ route('realisasi.index') }}" class="btn btn-light ms-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
