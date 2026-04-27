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
                            @php
                                $filesData = collect($aktivitas->lampiran)->map(function($path, $index) {
                                    return [
                                        'url' => asset('storage/' . $path),
                                        'name' => 'File ' . ($index + 1),
                                        'ext' => strtolower(pathinfo($path, PATHINFO_EXTENSION))
                                    ];
                                });
                            @endphp
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($aktivitas->lampiran as $path)
                                    @php
                                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                                        $icon = 'fa-file';
                                        $color = 'secondary';
                                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { $icon = 'fa-file-image'; $color = 'success'; }
                                        elseif (strtolower($ext) === 'pdf') { $icon = 'fa-file-pdf'; $color = 'danger'; }
                                    @endphp
                                    <div class="p-2 border rounded bg-light d-flex align-items-center attachment-item" id="file-{{ $loop->index }}">
                                        <i class="fas {{ $icon }} me-2 text-{{ $color }}"></i>
                                        <span class="small me-3">File {{ $loop->iteration }}</span>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-xs btn-outline-info" 
                                                    onclick='showGallery({{ $loop->index }}, @json($filesData))'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-danger" 
                                                    onclick="markForDeletion('{{ $path }}', 'file-{{ $loop->index }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div id="deleted-inputs"></div>
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
                        <a href="{{ route('admin.aktivitas.index') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="modalPreview" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="previewTitle">File Preview</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="carouselPreview" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner" id="previewContent">
                        <!-- Content will be injected here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPreview" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselPreview" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-xs { padding: 0.1rem 0.3rem; font-size: 0.7rem; }
    .attachment-item { transition: all 0.3s; }
    #previewContent img { max-height: 80vh; object-fit: contain; }
    .carousel-control-prev, .carousel-control-next { width: 5%; }
    .carousel-item { min-height: 400px; }
</style>
@endsection

@section('scripts')
<script>
    function showGallery(startIndex, files) {
        let html = '';
        
        files.forEach((file, index) => {
            const isActive = index === startIndex ? 'active' : '';
            let content = '';
            
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(file.ext)) {
                content = `<img src="${file.url}" class="d-block mx-auto img-fluid rounded shadow-sm" alt="${file.name}">`;
            } else if (file.ext === 'pdf') {
                content = `<iframe src="${file.url}" width="100%" height="600px" style="border: none; border-radius: 8px;"></iframe>`;
            } else {
                content = `
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fs-1 text-muted mb-3"></i>
                        <p>Format file <b>.${file.ext}</b> tidak mendukung preview langsung.</p>
                        <a href="${file.url}" target="_blank" class="btn btn-primary px-4 rounded-pill">Download / Buka File</a>
                    </div>
                `;
            }
            
            html += `
                <div class="carousel-item ${isActive}">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-3 badge bg-light text-dark border px-3 py-2 rounded-pill">${file.name}.${file.ext}</div>
                        ${content}
                    </div>
                </div>
            `;
        });

        $('#previewContent').html(html);
        
        // Hide controls if only one file
        if (files.length <= 1) {
            $('.carousel-control-prev, .carousel-control-next').hide();
        } else {
            $('.carousel-control-prev, .carousel-control-next').show();
        }

        const modal = new bootstrap.Modal(document.getElementById('modalPreview'));
        modal.show();
        
        // Ensure carousel starts at correct index
        const carousel = new bootstrap.Carousel(document.getElementById('carouselPreview'));
        carousel.to(startIndex);
    }

    function markForDeletion(path, elementId) {
        if (confirm('Apakah Anda yakin ingin menghapus lampiran ini?')) {
            $(`#${elementId}`).fadeOut(300, function() {
                $(this).remove();
            });
            $('#deleted-inputs').append(`<input type="hidden" name="deleted_lampiran[]" value="${path}">`);
        }
    }
</script>
@endsection
