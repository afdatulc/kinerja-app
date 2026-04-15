@extends('layouts.dashboard')

@section('title', 'Input Analisis Kinerja')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('analisis.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold">Pilih Indikator</label>
                    <select name="indikator_id" class="form-select select2" required>
                        @foreach($indikators as $i)
                        <option value="{{ $i->id }}" {{ old('indikator_id') == $i->id ? 'selected' : '' }}>{{ $i->indikator_kinerja }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Triwulan</label>
                    <select name="triwulan" class="form-select" required>
                        <option value="1">TW 1</option>
                        <option value="2">TW 2</option>
                        <option value="3">TW 3</option>
                        <option value="4">TW 4</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Narasi Analisis Capaian Kinerja</label>
                    <textarea name="narasi_analisis" class="form-control" rows="3" placeholder="Jelaskan detail capaian kinerja triwulan ini...">{{ old('narasi_analisis') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kendala</label>
                    <textarea name="kendala" class="form-control" rows="3">{{ old('kendala') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Solusi</label>
                    <textarea name="solusi" class="form-control" rows="3">{{ old('solusi') }}</textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Rencana Tindak Lanjut</label>
                    <textarea name="rencana_tindak_lanjut" class="form-control" rows="2">{{ old('rencana_tindak_lanjut') }}</textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Penjelasan Lainnya</label>
                    <textarea name="penjelasan_lainnya" class="form-control" rows="2" placeholder="Keterangan tambahan jika diperlukan...">{{ old('penjelasan_lainnya') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">PIC Tindak Lanjut</label>
                    <select name="pic_tindak_lanjut" class="form-select select2">
                        <option value="">-- Pilih PIC --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->nama }}" {{ old('pic_tindak_lanjut') == $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Batas Waktu</label>
                    <input type="date" name="batas_waktu" class="form-control" value="{{ old('batas_waktu') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Link Bukti Kinerja</label>
                    <input type="url" name="link_bukti_kinerja" class="form-control" placeholder="https://..." value="{{ old('link_bukti_kinerja') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">File Bukti Kinerja</label>
                    <input type="file" name="file_bukti_kinerja" class="form-control">
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">Simpan Analisis</button>
                <a href="{{ route('analisis.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
