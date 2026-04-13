@extends('layouts.dashboard')

@section('title', 'Edit Master Kegiatan')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('kegiatan-master.update', $kegiatanMaster->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Indikator (IKU/Proksi)</label>
                <select name="indikator_id" class="form-select" required>
                    @foreach($indikators as $i)
                        <option value="{{ $i->id }}" {{ $kegiatanMaster->indikator_id == $i->id ? 'selected' : '' }}>{{ $i->kode }} -- {{ $i->indikator_kinerja }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $kegiatanMaster->nama_kegiatan) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Tahapan yang Tersedia</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach(['Persiapan', 'Pengumpulan Data', 'Penghitungan', 'Pengolahan', 'Analisis', 'Diseminasi', 'Evaluasi'] as $tahap)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tahapan[]" value="{{ $tahap }}" id="tahap{{ $loop->index }}" {{ in_array($tahap, $kegiatanMaster->tahapan_json) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tahap{{ $loop->index }}">{{ $tahap }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Perbarui Master Kegiatan</button>
                <a href="{{ route('kegiatan-master.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
