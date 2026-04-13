@extends('layouts.dashboard')

@section('title', 'Edit Aktivitas Pegawai')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Detail Aktivitas & Tambah Lampiran</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.aktivitas.update', $aktivitas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6 text-muted small">Indikator:</div>
                        <div class="col-md-6 text-muted small">Triwulan:</div>
                        <div class="col-md-6 fw-bold text-dark">{{ $aktivitas->indikator->kode }}</div>
                        <div class="col-md-6 fw-bold text-dark">TW {{ $aktivitas->triwulan }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Uraian Aktivitas</label>
                        <textarea name="uraian" class="form-control" rows="4" required>{{ old('uraian', $aktivitas->uraian) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tahapan</label>
                        <input type="text" name="tahapan" class="form-control" value="{{ old('tahapan', $aktivitas->tahapan) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small d-block">Lampiran Saat Ini</label>
                        @if($aktivitas->lampiran && count($aktivitas->lampiran) > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($aktivitas->lampiran as $path)
                                    <div class="p-2 border rounded bg-light d-flex align-items-center">
                                        <i class="fas fa-file-alt me-2 text-primary"></i>
                                        <span class="small me-3">File {{ $loop->iteration }}</span>
                                        <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-info small"><i class="fas fa-eye"></i></a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted small italic">Belum ada lampiran.</span>
                        @endif
                    </div>

                    <div class="mb-4 p-3 bg-primary-subtle rounded-3 border border-primary-subtle">
                        <label class="form-label fw-bold small mb-2 text-primary"><i class="fas fa-plus-circle me-1"></i> Tambah Lampiran Baru</label>
                        <input type="file" name="lampiran[]" class="form-control" multiple>
                        <div class="form-text small">Format: PDF, JPG, PNG. Maks 10MB per file. Lampiran baru akan ditambahkan tanpa menghapus yang lama.</div>
                    </div>

                    <div class="border-top pt-3 d-flex justify-content-between">
                        <a href="{{ route('admin.aktivitas.index') }}" class="btn btn-light px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-5">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
