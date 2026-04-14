@extends('layouts.dashboard')

@section('title', 'Monitoring Evidence (Galeri Bukti Dukung)')

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.evidence.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">Tahun</label>
                <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for($y = date('Y'); $y >= 2025; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Triwulan</label>
                <select name="triwulan" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @foreach([1, 2, 3, 4] as $tw)
                        <option value="{{ $tw }}" {{ request('triwulan') == $tw ? 'selected' : '' }}>TW {{ $tw }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">Filter Indikator</label>
                <select name="indikator_id" class="form-select form-select-sm select2" onchange="this.form.submit()">
                    <option value="">Semua Indikator</option>
                    @foreach($indikators as $i)
                        <option value="{{ $i->id }}" {{ request('indikator_id') == $i->id ? 'selected' : '' }}>{{ $i->kode }} - {{ $i->indikator_kinerja }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.evidence.index') }}" class="btn btn-light btn-sm w-100">Reset Filter</a>
            </div>
        </form>
    </div>
</div>

@if($evidences->count() > 0)
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4" id="evidenceGrid">
        @foreach($evidences as $e)
            @foreach($e->lampiran as $l)
                @php
                    $fileName = basename($l);
                    $ext = pathinfo($l, PATHINFO_EXTENSION);
                    $url = asset('storage/' . $l);
                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                @endphp
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm evidence-card">
                        <div class="evidence-preview-wrapper bg-light d-flex align-items-center justify-content-center" style="height: 180px; overflow: hidden; cursor: pointer;" onclick="showPreview('{{ $url }}', '{{ $fileName }}')">
                            @if($isImage)
                                <img src="{{ $url }}" class="img-fluid" alt="Evidence">
                            @else
                                <div class="text-center">
                                    <i class="fas fa-file-pdf text-danger fs-1 mb-2"></i>
                                    <div class="extra-small text-muted">{{ $ext }}</div>
                                </div>
                            @endif
                            <div class="preview-overlay">
                                <i class="fas fa-search-plus text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle extra-small">TW {{ $e->triwulan }}</span>
                                <span class="extra-small text-muted">{{ $e->tanggal_mulai->format('d/m/Y') }}</span>
                            </div>
                            <h6 class="card-title extra-small fw-bold mb-1 text-truncate" title="{{ $e->pegawai->nama ?? $e->pegawai_nip }}">
                                {{ $e->pegawai->nama ?? $e->pegawai_nip }}
                            </h6>
                            <p class="card-text extra-small text-muted mb-0 lh-sm line-clamp-2">
                                {{ $e->uraian }}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                            <div class="extra-small fw-bold text-primary text-truncate">{{ $e->indikator->kode }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
@else
    <div class="col-12 text-center py-5 mt-5">
        <i class="fas fa-folder-open fs-1 text-muted mb-3 d-block" style="font-size: 4rem !important;"></i>
        <h5 class="text-muted fw-bold">Tidak ada bukti dukung yang ditemukan.</h5>
    </div>
@endif

<!-- Modal Preview (Reused from Entry) -->
<div class="modal fade" id="modalPreview" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="previewTitle">File Preview</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="previewContent" style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .evidence-card {
        transition: transform 0.2s;
    }
    .evidence-card:hover {
        transform: translateY(-5px);
    }
    .evidence-preview-wrapper {
        position: relative;
    }
    .preview-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .evidence-preview-wrapper:hover .preview-overlay {
        opacity: 1;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .extra-small {
        font-size: 0.75rem;
    }
</style>
@endsection

@section('scripts')
<script>
    function showPreview(url, fileName) {
        const ext = fileName.split('.').pop().toLowerCase();
        let html = '';
        
        $('#previewTitle').text(fileName);
        $('#previewContent').html('<div class="spinner-border text-primary"></div>');
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
            html = `<img src="${url}" class="img-fluid rounded shadow-sm" style="max-height: 80vh;">`;
        } else if (ext === 'pdf') {
            html = `<iframe src="${url}" width="100%" height="600px" style="border: none; border-radius: 8px;"></iframe>`;
        } else {
            html = `
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fs-1 text-muted mb-3"></i>
                    <p>Format file <b>.${ext}</b> tidak mendukung preview langsung.</p>
                    <a href="${url}" target="_blank" class="btn btn-primary px-4">Download / Buka File</a>
                </div>
            `;
        }

        setTimeout(() => {
            $('#previewContent').html(html);
        }, 300);

        const modal = new bootstrap.Modal(document.getElementById('modalPreview'));
        modal.show();
    }

    $(document).ready(function() {
        if ($('.select2').length) {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        }
    });
</script>
@endsection
