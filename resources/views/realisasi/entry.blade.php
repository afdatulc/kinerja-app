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
                            <input type="number" step="0.01" name="realisasi_kumulatif" id="inputRealisasi" class="form-control form-control-lg" required {{ !$isPIC ? 'disabled' : '' }}>
                            <span class="input-group-text">{{ $indikator->satuan }}</span>
                        </div>
                        <div class="form-text text-info" id="prevInfo">Realisasi sebelumnya: <span id="prevValue">0</span></div>
                        @error('realisasi_kumulatif')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="outputMonitoringSection" class="mb-4 d-none">
                        <h6 class="text-muted small fw-bold text-uppercase mb-3">Rincian Output (Volume & Progres)</h6>
                        <div id="outputContainer" class="bg-light p-3 rounded-4 border">
                            <!-- Output inputs will be injected here -->
                        </div>
                    </div>

                    @if($isPIC)
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-save me-1"></i> Simpan Realisasi & Output
                        </button>
                    @else
                        <div class="alert alert-warning border-0 small text-center rounded-3">
                            <i class="fas fa-lock me-1"></i> Mode Lihat Saja (Hanya PIC/Admin yang dapat mengubah data)
                        </div>
                    @endif
                    <a href="{{ route('rekap.capaian') }}" class="btn btn-link w-100 text-muted mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="bg-light p-1 rounded-4 d-flex">
                    <ul class="nav nav-pills nav-fill w-100" id="contextTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold small py-2 rounded-3" id="kendala-tab" data-bs-toggle="tab" data-bs-target="#kendala-pane" type="button" role="tab">
                                <i class="fas fa-exclamation-triangle me-1"></i> Hambatan dan Kendala
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold small py-2 rounded-3" id="aktivitas-tab" data-bs-toggle="tab" data-bs-target="#aktivitas-pane" type="button" role="tab">
                                <i class="fas fa-tasks me-1"></i> Aktivitas Pegawai
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4 pt-0">
                <div class="tab-content" id="contextTabsContent">
                    <div class="tab-pane fade show active py-3" id="kendala-pane" role="tabpanel">
                        <div id="kendalaContainer">
                            <div class="text-center py-4 opacity-50 small italic text-muted">Memuat data kendala...</div>
                        </div>
                    </div>
                    <div class="tab-pane fade py-3" id="aktivitas-pane" role="tabpanel">
                        <div id="activityContainer">
                            <div class="text-center py-4 opacity-50 small italic text-muted">Memuat data aktivitas...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden input for output file upload -->
<input type="file" id="outputFileInput" style="display: none;" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">

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
    const isPIC = {{ $isPIC ? 'true' : 'false' }};

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
        $.get(`{{ url('api/realisasi/context/' . $indikator->kode) }}/${triwulan}`, function(data) {
            $('#targetValue').text(data.target);
            $('#prevValue').text(data.previous_value);
            $('#inputRealisasi').val(data.current_value);

            // Reset Containers
            $('#kendalaContainer').empty();
            $('#activityContainer').empty();

            // Render Kendala
            if (data.analisis.length === 0) {
                $('#kendalaContainer').html('<div class="text-center py-4 opacity-50 small italic">Tidak ada kendala yang dilaporkan.</div>');
            } else {
                let kendalaHtml = '';
                data.analisis.forEach(an => {
                    const sevClass = an.severity === 'High' ? 'danger' : (an.severity === 'Medium' ? 'warning' : 'info');
                    kendalaHtml += `
                        <div class="mb-3 pb-3 border-bottom last-child-no-border" style="border-left: 4px solid var(--bs-${sevClass}); padding-left: 15px;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="fw-bold extra-small text-dark">${an.pegawai} <span class="badge bg-${sevClass} text-white ms-1" style="font-size: 0.55rem;">${an.severity} Issue</span></div>
                                <span class="badge bg-light text-muted fw-normal border extra-small px-2 py-1">${an.tanggal}</span>
                            </div>
                            <div class="text-dark small mb-2 lh-base fw-bold">${an.kendala}</div>
                            ${an.solusi ? `<div class="bg-light p-2 rounded extra-small mt-2 border-start border-warning border-3"><i class="fas fa-lightbulb text-warning me-1"></i> <b>Solusi/Saran:</b> ${an.solusi}</div>` : ''}
                        </div>
                    `;
                });
                $('#kendalaContainer').html(kendalaHtml);
            }

            // Render Aktivitas
            if (data.aktivitas.length === 0) {
                $('#activityContainer').html('<div class="text-center py-4 opacity-50 small italic">Belum ada aktivitas yang dicatat.</div>');
            } else {
                let aktivitasHtml = '';
                data.aktivitas.forEach(a => {
                    let lampiranHtml = '';
                    if (a.lampirans && a.lampirans.length > 0) {
                        a.lampirans.forEach(l => {
                            const url = `{{ asset('storage') }}/${l}`;
                            const fileName = l.split('/').pop();
                            lampiranHtml += `<button type="button" onclick="showPreview('${url}', '${fileName}')" class="btn btn-light btn-sm border extra-small me-1 mt-1 px-2 py-0"><i class="fas fa-eye me-1"></i> Preview</button>`;
                        });
                    }

                    aktivitasHtml += `
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
                $('#activityContainer').html(aktivitasHtml);
            }

            // Handle Outputs
            if (data.outputs && data.outputs.length > 0) {
                $('#outputMonitoringSection').removeClass('d-none');
                let outputHtml = '';
                data.outputs.forEach(o => {
                    const hasFile = o.file_path != null;
                    outputHtml += `
                        <div class="mb-3 pb-3 border-bottom last-child-no-border">
                            <div class="form-check mb-2">
                                <input class="form-check-input output-checkbox" type="checkbox" 
                                    id="output-${o.id}" data-id="${o.id}" ${o.is_achieved ? 'checked' : ''} ${!isPIC ? 'disabled' : ''}>
                                <label class="form-check-label text-dark fw-bold small" for="output-${o.id}">
                                    ${o.nama_output} <span class="text-muted extra-small fw-normal">(${o.jenis_output})</span>
                                </label>
                            </div>
                            
                            <div class="row g-2 mb-2 ps-4">
                                <div class="col-6">
                                    <label class="extra-small text-muted mb-1">Volume Capaian</label>
                                    <input type="number" step="0.01" name="output_data[${o.id}][volume]" 
                                        class="form-control form-control-sm" value="${o.volume || ''}" placeholder="0" ${!isPIC ? 'disabled' : ''}>
                                </div>
                                <div class="col-6">
                                    <label class="extra-small text-muted mb-1">Progres (%)</label>
                                    <input type="number" step="0.01" name="output_data[${o.id}][progres]" 
                                        class="form-control form-control-sm" value="${o.progres || ''}" placeholder="0" ${!isPIC ? 'disabled' : ''}>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 ps-4">
                                ${isPIC ? `
                                <button type="button" class="btn btn-sm btn-outline-secondary border-dashed upload-trigger" data-id="${o.id}">
                                    <i class="fas ${hasFile ? 'fa-sync' : 'fa-upload'} me-1"></i> 
                                    ${hasFile ? 'Perbarui' : 'Unggah'}
                                </button>
                                ` : ''}
                                
                                <span class="text-muted extra-small">
                                    <span id="file-info-${o.id}">
                                        ${hasFile ? 
                                            `<a href="javascript:void(0)" onclick="showPreview('{{ asset('storage') }}/${o.file_path}', '${o.file_path.split('/').pop()}')" class="text-primary text-decoration-none fw-bold">
                                                <i class="fas fa-eye me-1"></i> Lihat
                                            </a>` : 
                                            '<i class="fas fa-info-circle me-1"></i> Tidak ada file'
                                        }
                                    </span>
                                </span>
                            </div>
                        </div>
                    `;
                });
                $('#outputContainer').html(outputHtml);
            } else {
                $('#outputMonitoringSection').addClass('d-none');
            }
        });
    }

    $(document).on('change', '.output-checkbox', function() {
        const id = $(this).data('id');
        const checked = $(this).is(':checked');
        
        $.ajax({
            url: "{{ route('output-master.toggle-status', ['output_master' => '__ID__']) }}".replace('__ID__', id),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Gagal memperbarui status output.');
            }
        });
    });

    // File Upload Handling
    let activeOutputId = null;
    $(document).on('click', '.upload-trigger', function() {
        activeOutputId = $(this).data('id');
        $('#outputFileInput').click();
    });

    $('#outputFileInput').on('change', function() {
        if (!this.files || !this.files[0] || !activeOutputId) return;
        
        const formData = new FormData();
        formData.append('file', this.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        const btn = $(`.upload-trigger[data-id="${activeOutputId}"]`);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: "{{ route('output-master.upload', ['output_master' => ':id']) }}".replace(':id', activeOutputId),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success(response.message);
                btn.prop('disabled', false).html('<i class="fas fa-sync me-1"></i> Perbarui Dokumen');
                
                $(`#file-info-${activeOutputId}`).html(`
                    <a href="javascript:void(0)" onclick="showPreview('${response.file_url}', '${response.file_name}')" class="text-primary text-decoration-none fw-bold">
                        <i class="fas fa-eye me-1"></i> Lihat Dokumen
                    </a>
                `);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalHtml);
                toastr.error(xhr.responseJSON?.message || 'Gagal mengunggah file.');
            },
            complete: function() {
                $('#outputFileInput').val('');
            }
        });
    });

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const twParam = urlParams.get('triwulan');
        if (twParam && ['1','2','3','4'].includes(twParam)) {
            $('#selectTriwulan').val(twParam);
        }

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
    .nav-pills .nav-link {
        color: #6c757d;
        border: none;
        transition: all 0.2s;
    }
    .nav-pills .nav-link.active {
        color: #fff;
        background-color: var(--bs-primary);
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    }
    .nav-pills .nav-link:not(.active):hover {
        background-color: rgba(0,0,0,0.05);
        color: var(--bs-primary);
    }
</style>
@endsection
