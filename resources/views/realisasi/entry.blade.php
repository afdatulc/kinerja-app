@extends('layouts.dashboard')

@section('title', 'Input Realisasi: ' . $indikator->kode)

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="text-muted small fw-bold text-uppercase mb-3">Informasi Indikator</h6>
                <div class="mb-3">
                    <label class="text-muted small d-block">Indikator Kinerja</label>
                    <div class="fw-bold">{{ $indikator->indikator_kinerja }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted small d-block">Satuan</label>
                        <div class="fw-bold">{{ $indikator->satuan }}</div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small d-block">Target Tahunan</label>
                        <div class="fw-bold text-primary">{{ $indikator->target_tahunan }}</div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="text-muted small d-block">Penanggung Jawab (PIC)</label>
                    <div class="d-flex align-items-center mt-1">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                            {{ substr($indikator->pic->nama ?? 'A', 0, 1) }}
                        </div>
                        <div class="fw-bold">{{ $indikator->pic->nama ?? 'Admin' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted small fw-bold text-uppercase mb-3">Form Realisasi</h6>
                <form action="{{ route('realisasi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="indikator_id" value="{{ $indikator->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Triwulan</label>
                        <select name="triwulan" id="selectTriwulan" class="form-select" required>
                            <option value="1">Triwulan I</option>
                            <option value="2">Triwulan II</option>
                            <option value="3">Triwulan III</option>
                            <option value="4">Triwulan IV</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border border-dashed text-center" id="targetInfo">
                        <div class="text-muted small">Target Kumulatif Triwulan <span id="twLabel">I</span></div>
                        <div class="fs-4 fw-bold text-dark" id="targetValue">-</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nilai Realisasi Kumulatif</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="realisasi_kumulatif" id="inputRealisasi" class="form-control form-control-lg" required>
                            <span class="input-group-text">{{ $indikator->satuan }}</span>
                        </div>
                        <div class="form-text text-info" id="prevInfo">Realisasi sebelumnya: <span id="prevValue">0</span></div>
                        @error('realisasi_kumulatif')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-save me-1"></i> Simpan Realisasi
                    </button>
                    <a href="{{ route('rekap.capaian') }}" class="btn btn-link w-100 text-muted mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase mb-0">Contextual Evidence (Aktivitas Pegawai)</h6>
                    <p class="text-muted extra-small mb-0">Berikut adalah laporan aktivitas yang masuk untuk Triwulan ini.</p>
                </div>
            </div>
            <div class="card-body p-4" id="activityContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview -->
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

    function loadContext() {
        const triwulan = $('#selectTriwulan').val();
        $('#twLabel').text(['', 'I', 'II', 'III', 'IV'][triwulan]);
        
        $('#activityContainer').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

        $.get(`{{ url('api/realisasi/context/' . $indikator->id) }}/${triwulan}`, function(data) {
            $('#targetValue').text(data.target);
            $('#prevValue').text(data.previous_value);
            $('#inputRealisasi').val(data.current_value);

            let html = '';
            if (data.aktivitas.length === 0) {
                html = `
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-folder-open fs-1 mb-3"></i>
                        <p>Belum ada aktivitas yang dicatat untuk triwulan ini.</p>
                    </div>
                `;
            } else {
                data.aktivitas.forEach(a => {
                    let lampiranHtml = '';
                    if (a.lampirans && a.lampirans.length > 0) {
                        a.lampirans.forEach(l => {
                            const url = `{{ asset('storage') }}/${l}`;
                            const fileName = l.split('/').pop();
                            lampiranHtml += `<button type="button" onclick="showPreview('${url}', '${fileName}')" class="btn btn-light btn-sm border me-1 mt-1"><i class="fas fa-eye me-1"></i> Preview</button>`;
                        });
                    }

                    html += `
                        <div class="mb-4 pb-4 border-bottom last-child-no-border">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="fw-bold fs-6 text-dark">${a.pegawai}</div>
                                <span class="badge bg-light text-muted fw-normal border px-2 py-1">${a.tanggal}</span>
                            </div>
                            <div class="small text-primary mb-2 fw-medium"><i class="fas fa-tag me-1"></i> Tahap: ${a.tahapan}</div>
                            <div class="text-muted small mb-3 lh-base">${a.uraian}</div>
                            <div class="d-flex flex-wrap">${lampiranHtml}</div>
                        </div>
                    `;
                });
            }
            $('#activityContainer').html(html);
        });
    }

    $(document).ready(function() {
        $('#selectTriwulan').on('change', loadContext);
        loadContext();
    });
</script>

<style>
    .last-child-no-border:last-child {
        border-bottom: none !important;
    }
    .extra-small {
        font-size: 0.75rem;
    }
</style>
@endsection
