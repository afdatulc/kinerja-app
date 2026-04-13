@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 p-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <div class="card mb-3 p-3 shadow-sm border-0">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold mb-1">Tahun</label>
                        <select class="form-select form-select-sm bg-light" id="filterTahun">
                            <option value="2026" {{ $tahun == 2026 ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ $tahun == 2025 ? 'selected' : '' }}>2025</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold mb-1">Triwulan</label>
                        <select class="form-select form-select-sm bg-light" id="filterTriwulan">
                            <option value="1" {{ $triwulan == 1 ? 'selected' : '' }}>Triwulan I</option>
                            <option value="2" {{ $triwulan == 2 ? 'selected' : '' }}>Triwulan II</option>
                            <option value="3" {{ $triwulan == 3 ? 'selected' : '' }}>Triwulan III</option>
                            <option value="4" {{ $triwulan == 4 ? 'selected' : '' }}>Triwulan IV</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold mb-1">Wilayah</label>
                        <select class="form-select form-select-sm bg-light" disabled>
                            <option>[6305] Tapin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted fw-bold mb-1">Unit Kerja</label>
                        <select class="form-select form-select-sm bg-light" disabled>
                            <option>[92800] BPS Kabupaten/Kota</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label class="small text-muted fw-bold mb-1">Pilih Indikator Kinerja Utama (IKU)</label>
                        <select class="form-select select2-iku border-2" id="selectIKU">
                            <option value="">-- Pilih Indikator --</option>
                            @foreach($indikators as $i)
                                <option value="{{ $i->id }}">{{ $i->kode }} — {{ $i->indikator_kinerja }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row g-3 d-none" id="actionCards">
                <div class="col-md-6">
                    <div class="card h-100 p-3 border-start border-4 border-success shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                <i class="fas fa-tasks text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Input Aktivitas</h6>
                                <p class="text-muted extra-small mb-0">Catat tahapan kegiatan.</p>
                            </div>
                        </div>
                        <button class="btn btn-accent btn-sm w-100 py-2 mt-2" data-bs-toggle="modal" data-bs-target="#modalAktivitas">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Aktivitas
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-3 border-start border-4 border-danger shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                                <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">Input Kendala</h6>
                                <p class="text-muted extra-small mb-0">Laporkan hambatan.</p>
                            </div>
                        </div>
                        <button class="btn btn-accent btn-sm w-100 py-2 mt-2" data-bs-toggle="modal" data-bs-target="#modalKendala">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Kendala
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 py-4" id="placeHolder">
                <img src="https://illustrations.popsy.co/gray/work-from-home.svg" alt="select" width="180" class="mb-3 opacity-50">
                <h6 class="text-muted">Silakan pilih IKU untuk memulai pengisian</h6>
            </div>

            </div>
        </div>
    </div>

    <!-- Modal Aktivitas -->
    <div class="modal fade" id="modalAktivitas" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('public.aktivitas.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content border-0 rounded-4">
                @csrf
                <input type="hidden" name="indikator_id" class="hidden-iku">
                <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah / Edit Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">NIP / Email BPS</label>
                        <input type="text" name="nip" class="form-control form-control-sm" placeholder="Masukkan NIP atau Email Anda" required>
                    </div>

                    <div class="mb-2" id="kegiatan_wrapper" style="display:none;">
                        <label class="form-label small fw-bold mb-1">Pilih Kegiatan</label>
                        <select name="kegiatan_id" id="kegiatan_select" class="form-select form-select-sm select2-modal" required>
                            <option value="">-- Pilih Kegiatan --</option>
                        </select>
                    </div>

                    <div class="mb-2" id="tahapan_wrapper" style="display:none;">
                        <label class="form-label small fw-bold mb-1">Tahap yang Sedang Dikerjakan</label>
                        <select name="tahapan" id="tahapan_select" class="form-select form-select-sm select2-modal" required>
                            <option value="">-- Pilih Tahapan --</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Uraian Aktivitas</label>
                        <textarea name="uraian" class="form-control" rows="4"
                            placeholder="Deskripsikan pekerjaan yang dilakukan..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lampiran Bukti (Boleh lebih dari 1)</label>
                        <input type="file" name="lampiran[]" class="form-control" multiple>
                        <small class="text-muted">Format: PDF, JPG, PNG, DOCX, XLSX. Maks 10MB per file.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-accent px-4">Simpan Aktivitas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Kendala -->
    <div class="modal fade" id="modalKendala" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('public.kendala.store') }}" method="POST" class="modal-content border-0 rounded-4">
                @csrf
                <input type="hidden" name="indikator_id" class="hidden-iku">
                <input type="hidden" name="triwulan" value="{{ $triwulan }}">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Laporkan Kendala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">NIP / Email BPS</label>
                        <input type="text" name="nip" class="form-control form-control-sm" placeholder="Masukkan NIP atau Email Anda" required>
                    </div>

                    <div class="mb-2" id="kegiatan_wrapper_kendala" style="display:none;">
                        <label class="form-label small fw-bold mb-1">Pilih Kegiatan</label>
                        <select name="kegiatan_id" id="kegiatan_select_kendala" class="form-select form-select-sm select2-modal" required>
                            <option value="">-- Pilih Kegiatan --</option>
                        </select>
                    </div>

                    <div class="mb-2" id="tahapan_wrapper_kendala" style="display:none;">
                        <label class="form-label small fw-bold mb-1">Tahap yang Sedang Dikerjakan</label>
                        <select name="tahapan" id="tahapan_select_kendala" class="form-select form-select-sm select2-modal" required>
                            <option value="">-- Pilih Tahapan --</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Kendala yang Dihadapi</label>
                        <textarea name="kendala" class="form-control form-control-sm" rows="3"
                            placeholder="Jelaskan hambatan secara spesifik..." required></textarea>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Solusi yang Dilakukan</label>
                            <textarea name="solusi" class="form-control form-control-sm" rows="2"
                                placeholder="Apa yang sudah dilakukan?"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Rencana Tindak Lanjut</label>
                            <textarea name="rencana_tindak_lanjut" class="form-control form-control-sm" rows="2"
                                placeholder="Apa rencana ke depan?"></textarea>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">PIC Tindak Lanjut</label>
                            <select name="pic_tindak_lanjut" class="form-select form-select-sm select2-modal">
                                <option value="">-- Pilih PIC --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->nama }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Batas Waktu RTL</label>
                            <input type="date" name="batas_waktu" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold mb-1">Tingkat Keparahan (Severity)</label>
                        <div class="d-flex gap-2">
                            <div class="form-check border p-2 rounded-3 px-3 flex-grow-1 clickable-card severity-card">
                                <input class="form-check-input" type="radio" name="severity" id="sevLow" value="Low" checked>
                                <label class="form-check-label ms-1 small" for="sevLow">Low</label>
                            </div>
                            <div class="form-check border p-2 rounded-3 px-3 flex-grow-1 clickable-card severity-card">
                                <input class="form-check-input" type="radio" name="severity" id="sevMed" value="Medium">
                                <label class="form-check-label ms-1 small" for="sevMed">Medium</label>
                            </div>
                            <div class="form-check border p-2 rounded-3 px-3 flex-grow-1 clickable-card severity-card border-danger text-danger">
                                <input class="form-check-input" type="radio" name="severity" id="sevHigh" value="High">
                                <label class="form-check-label ms-1 small fw-bold" for="sevHigh">High</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-accent px-4">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // General Select2 Init
            $('.select2-iku').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Reusable Fetch Logic
            function loadKegiatan(ikuId, $kegiatanSelect, $kegiatanWrapper, $tahapanSelect, $tahapanWrapper) {
                $kegiatanWrapper.hide();
                $tahapanWrapper.hide();
                $kegiatanSelect.html('<option value="">-- Pilih Kegiatan --</option>');
                $tahapanSelect.html('<option value="">-- Pilih Tahapan --</option>');

                if (ikuId) {
                    $.get(`/api/kegiatan/${ikuId}`, function (data) {
                        if (data.length > 0) {
                            data.forEach(function (k) {
                                const option = $('<option></option>')
                                    .attr('value', k.id)
                                    .text(k.nama_kegiatan)
                                    .attr('data-tahapan', JSON.stringify(k.tahapan_json));
                                $kegiatanSelect.append(option);
                            });
                            $kegiatanWrapper.show();
                            // Re-init Select2 for modal selects if needed
                            $kegiatanSelect.select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                dropdownParent: $kegiatanSelect.closest('.modal')
                            });
                        }
                    });
                }
            }

            // Sync Modals on Open
            $('#modalAktivitas, #modalKendala').on('show.bs.modal', function () {
                const ikuId = $('#selectIKU').val();
                const isAktivitas = $(this).attr('id') === 'modalAktivitas';
                
                const $kegS = isAktivitas ? $('#kegiatan_select') : $('#kegiatan_select_kendala');
                const $kegW = isAktivitas ? $('#kegiatan_wrapper') : $('#kegiatan_wrapper_kendala');
                const $tahS = isAktivitas ? $('#tahapan_select') : $('#tahapan_select_kendala');
                const $tahW = isAktivitas ? $('#tahapan_wrapper') : $('#tahapan_wrapper_kendala');

                $('.hidden-iku').val(ikuId);
                loadKegiatan(ikuId, $kegS, $kegW, $tahS, $tahW);
            });

            // Tahapan Populate Logic
            $('.select2-modal').on('change', function() {
                if ($(this).attr('id').includes('kegiatan_select')) {
                    const $selected = $(this).find(':selected');
                    const isAktivitas = $(this).attr('id') === 'kegiatan_select';
                    const $tahS = isAktivitas ? $('#tahapan_select') : $('#tahapan_select_kendala');
                    const $tahW = isAktivitas ? $('#tahapan_wrapper') : $('#tahapan_wrapper_kendala');
                    
                    $tahS.html('<option value="">-- Pilih Tahapan --</option>');
                    
                    if ($selected.val()) {
                        const tahapan = JSON.parse($selected.attr('data-tahapan'));
                        tahapan.forEach(function (t) {
                            $tahS.append($('<option></option>').attr('value', t).text(t));
                        });
                        $tahW.show();
                        $tahS.select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            dropdownParent: $tahS.closest('.modal')
                        });
                    } else {
                        $tahW.hide();
                    }
                }
            });

            $('#selectIKU').on('change', function () {
                const val = $(this).val();
                if (val) {
                    $('#actionCards').removeClass('d-none');
                    $('#placeHolder').addClass('d-none');
                } else {
                    $('#actionCards').addClass('d-none');
                    $('#placeHolder').removeClass('d-none');
                }
            });

            $('#filterTahun, #filterTriwulan').on('change', function () {
                window.location.href = `/?tahun=${$('#filterTahun').val()}&triwulan=${$('#filterTriwulan').val()}`;
            });

            // Clickable Severity Cards
            $('.clickable-card').on('click', function() {
                $(this).find('input[type="radio"]').prop('checked', true);
                $('.severity-card').removeClass('bg-light shadow-sm border-primary');
                $(this).addClass('bg-light shadow-sm border-primary');
            });
        });
    </script>
@endsection