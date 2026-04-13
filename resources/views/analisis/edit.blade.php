@extends('layouts.dashboard')

@section('title', 'Edit Analisis Kinerja')

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('analisis.update', $analisi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold">Indikator</label>
                    <select name="indikator_id" class="form-select select2" required>
                        @foreach($indikators as $i)
                        <option value="{{ $i->id }}" {{ $analisi->indikator_id == $i->id ? 'selected' : '' }}>{{ $i->indikator_kinerja }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Triwulan</label>
                    <select name="triwulan" class="form-select" required>
                        @foreach([1, 2, 3, 4] as $tw)
                        <option value="{{ $tw }}" {{ $analisi->triwulan == $tw ? 'selected' : '' }}>TW {{ $tw }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kendala</label>
                    <textarea name="kendala" class="form-control" rows="3">{{ old('kendala', $analisi->kendala) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Solusi</label>
                    <textarea name="solusi" class="form-control" rows="3">{{ old('solusi', $analisi->solusi) }}</textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Rencana Tindak Lanjut</label>
                    <textarea name="rencana_tindak_lanjut" class="form-control" rows="2">{{ old('rencana_tindak_lanjut', $analisi->rencana_tindak_lanjut) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">PIC Tindak Lanjut</label>
                    <select name="pic_tindak_lanjut" class="form-select select2">
                        <option value="">-- Pilih PIC --</option>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->nama }}" {{ old('pic_tindak_lanjut', $analisi->pic_tindak_lanjut) == $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Batas Waktu</label>
                    <input type="date" name="batas_waktu" class="form-control" value="{{ old('batas_waktu', $analisi->batas_waktu) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Severity</label>
                    <select name="severity" class="form-select">
                        <option value="Low" {{ $analisi->severity == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ $analisi->severity == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ $analisi->severity == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Link Bukti Kinerja</label>
                    <input type="url" name="link_bukti_kinerja" class="form-control" placeholder="https://..." value="{{ old('link_bukti_kinerja', $analisi->link_bukti_kinerja) }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">File Bukti Kinerja</label>
                    @if($analisi->file_bukti_kinerja)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $analisi->file_bukti_kinerja) }}" target="_blank" class="btn btn-sm btn-light">
                                <i class="fas fa-file-download me-1"></i> Preview File Saat Ini
                            </a>
                        </div>
                    @endif
                    <input type="file" name="file_bukti_kinerja" class="form-control">
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4">Update Analisis</button>
                <a href="{{ route('analisis.index') }}" class="btn btn-light ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
