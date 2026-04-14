@extends('layouts.dashboard')

@section('title', 'Master Kegiatan')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 text-dark">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                @if(auth()->user()->isAdmin() || $indikators->count() > 0)
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                        data-bs-target="#modalKegiatan">
                        <i class="fas fa-plus me-1"></i> Tambah Kegiatan
                    </button>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('kegiatan-master.template') }}"
                            class="btn btn-outline-success rounded-pill px-3 ms-2 fw-bold">
                            <i class="fas fa-download me-1"></i> Template
                        </a>
                    @endif
                @else
                    <div class="fw-bold text-dark"><i class="fas fa-tasks me-2 text-primary"></i> Daftar Kegiatan & Tim Anda
                    </div>
                @endif
            </div>
            @if(auth()->user()->isAdmin())
                <form action="{{ route('kegiatan-master.import') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex align-items-center">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="file" name="file" class="form-control rounded-start-pill border-success"
                            style="width: 150px;" required>
                        <button type="submit" class="btn btn-success rounded-end-pill px-3">
                            <i class="fas fa-upload me-1"></i> Import
                        </button>
                    </div>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="kegiatanTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">No</th>
                            <th width="90">Kode IKU</th>
                            <th style="min-width: 200px;">Nama Kegiatan</th>
                            <th width="180">Ketua Tim</th>
                            <th>Milestone Pekerjaan</th>
                            <th width="110" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kegiatans as $k)
                            <tr id="row-{{ $k->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="small fw-bold text-primary">{{ $k->indikator->kode ?: '-' }}</td>
                                <td class="fw-bold text-dark">{{ $k->nama_kegiatan }}</td>
                                <td>
                                    @if($k->ketuaTim)
                                        <div class="small fw-bold text-dark">{{ $k->ketuaTim->nama }}</div>
                                        <div class="extra-small text-muted">{{ $k->ketuaTim->nip }}</div>
                                    @else
                                        <span class="text-muted small italic">- Belum ditunjuk -</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($k->tahapan_json as $t)
                                        <span
                                            class="badge bg-light text-primary border border-primary-subtle rounded-pill small px-2 py-1 mb-1 me-1">{{ $t }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        @if(auth()->user()->isAdmin() || (auth()->user()->pegawai_id == $k->ketua_tim_id) || (auth()->user()->pegawai_id == $k->indikator->pic_id))
                                            <button class="btn btn-sm btn-outline-info rounded-3 manage-anggota"
                                                data-id="{{ $k->id }}" title="Kelola Anggota">
                                                <i class="fas fa-users"></i>
                                            </button>
                                        @endif

                                        @if(auth()->user()->isAdmin() || (auth()->user()->pegawai_id == $k->indikator->pic_id))
                                            <button class="btn btn-sm btn-outline-primary rounded-3 edit-kegiatan"
                                                data-id="{{ $k->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if(auth()->user()->isAdmin())
                                                <button class="btn btn-sm btn-outline-danger rounded-3 delete-kegiatan"
                                                    data-id="{{ $k->id }}" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Kegiatan -->
    <div class="modal fade" id="modalKegiatan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Kegiatan Master</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formKegiatan">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" id="kegiatan_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Indikator Terkait</label>
                            <select name="indikator_id" id="indikator_id"
                                class="form-select rounded-3 shadow-none border-light-subtle" required>
                                <option value="">-- Pilih Indikator --</option>
                                @foreach($indikators as $i)
                                    <option value="{{ $i->id }}">{{ $i->kode }} - {{ $i->indikator_kinerja }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Kegiatan Master</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                                class="form-control rounded-3 shadow-none border-light-subtle"
                                placeholder="Contoh: Pengumpulan Data Lapangan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Ketua Tim Kegiatan</label>
                            <select name="ketua_tim_id" id="ketua_tim_id"
                                class="form-select rounded-3 shadow-none border-light-subtle">
                                <option value="">-- Pilih Ketua Tim --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nip }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted extra-small"><i class="fas fa-info-circle me-1"></i>Ketua tim dapat
                                menentukan anggota timnya sendiri.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small d-flex justify-content-between">
                                Tahapan Kegiatan
                                <button type="button" class="btn btn-xs btn-primary rounded-pill py-0 px-2"
                                    style="font-size: 0.65rem;" onclick="addTahapanRow()">
                                    <i class="fas fa-plus me-1"></i> Tambah Tahapan
                                </button>
                            </label>
                            <div id="tahapan-container" class="mt-2">
                                <!-- Baris tahapan akan ditambahkan di sini -->
                            </div>
                            <small class="text-muted extra-small mt-1"><i class="fas fa-info-circle me-1"></i>Tahapan ini
                                akan muncul sebagai progres yang bisa dipilih oleh petugas.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnSimpan">Simpan
                            Kegiatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Anggota -->
    <div class="modal fade" id="modalAnggota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Kelola Anggota Tim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnggota">
                    @csrf
                    <input type="hidden" id="manage_kegiatan_id">
                    <div class="modal-body p-4">
                        <div class="mb-4 text-center">
                            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-2"
                                id="kegiatan_name_label">Kegiatan Name</div>
                            <p class="small text-muted mb-0">Silahkan pilih pegawai yang akan dilibatkan sebagai anggota tim
                                dalam kegiatan ini.</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Pilih Anggota Tim</label>
                            <select name="anggotas[]" id="select_anggotas" class="form-select" multiple="multiple">
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nip }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"
                            id="btnSimpanAnggota">Simpan Anggota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addTahapanRow(value = '') {
            const container = $('#tahapan-container');
            const html = `
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" name="tahapan[]" class="form-control rounded-start-3" value="${value}" placeholder="Nama tahapan..." required>
                        <button type="button" class="btn btn-outline-danger rounded-end-3" onclick="$(this).closest('.input-group').remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            container.append(html);
        }

        $(document).ready(function () {
            $('#kegiatanTable').DataTable({
                language: window.DATATABLES_ID
            });

            // Reset Modal on Close
            $('#modalKegiatan').on('hidden.bs.modal', function () {
                $('#formKegiatan')[0].reset();
                $('#modalTitle').text('Tambah Kegiatan Master');
                $('#formMethod').val('POST');
                $('#kegiatan_id').val('');
                $('#tahapan-container').empty();
            });

            // Tambahkan satu baris tahapan saat modal tambah dibuka pertama kali
            $('#modalKegiatan').on('shown.bs.modal', function () {
                if ($('#tahapan-container').is(':empty')) {
                    addTahapanRow();
                }

                // Initialize Select2 for modal elements to fix search focus
                $('#indikator_id, #ketua_tim_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#modalKegiatan')
                });
            });

            // Edit Button Click
            $(document).on('click', '.edit-kegiatan', function () {
                const id = $(this).data('id');
                $('#modalTitle').text('Edit Kegiatan Master');
                $('#formMethod').val('PUT');
                $('#kegiatan_id').val(id);
                $('#tahapan-container').empty();
                $('#modalKegiatan').modal('show');

                $.get(`{{ url('kegiatan-master') }}/${id}`, function (data) {
                    $('#indikator_id').val(data.indikator_id).trigger('change');
                    $('#nama_kegiatan').val(data.nama_kegiatan);
                    $('#ketua_tim_id').val(data.ketua_tim_id).trigger('change');
                    if (data.tahapan_json) {
                        data.tahapan_json.forEach(t => addTahapanRow(t));
                    }
                });
            });

            // Manage Anggota Click
            $(document).on('click', '.manage-anggota', function () {
                const id = $(this).data('id');
                $('#manage_kegiatan_id').val(id);
                $('#select_anggotas').val(null).trigger('change');

                $.get(`{{ url('kegiatan-master') }}/${id}`)
                    .done(function (data) {
                        $('#kegiatan_name_label').text(data.nama_kegiatan);
                        // Pre-select current members
                        if (data.anggotas && Array.isArray(data.anggotas)) {
                            const memberIds = data.anggotas.map(a => a.id);
                            $('#select_anggotas').val(memberIds).trigger('change');
                        }
                        $('#modalAnggota').modal('show');

                        // Initialize Select2 if not already done or to fix width
                        $('#select_anggotas').select2({
                            theme: 'bootstrap-5',
                            width: '100%',
                            dropdownParent: $('#modalAnggota')
                        });
                    })
                    .fail(function () {
                        toastr.error('Gagal mengambil data kegiatan.');
                    });
            });

            // Sync Anggota Submit
            $('#formAnggota').on('submit', function (e) {
                e.preventDefault();
                const id = $('#manage_kegiatan_id').val();
                const btn = $('#btnSimpanAnggota');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.ajax({
                    url: `{{ url('kegiatan-master') }}/${id}/sync-anggota`,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.message);
                        $('#modalAnggota').modal('hide');
                        btn.prop('disabled', false).text('Simpan Anggota');
                    },
                    error: function () {
                        btn.prop('disabled', false).text('Simpan Anggota');
                        toastr.error('Gagal memperbarui anggota tim.');
                    }
                });
            });

            // Form Submit
            $('#formKegiatan').on('submit', function (e) {
                e.preventDefault();
                const id = $('#kegiatan_id').val();
                const method = $('#formMethod').val();
                const url = method === 'POST' ? "{{ route('kegiatan-master.store') }}" : `{{ url('kegiatan-master') }}/${id}`;
                const btn = $('#btnSimpan');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.message);
                        $('#modalKegiatan').modal('hide');
                        btn.prop('disabled', false).html('Simpan Kegiatan');

                        if (method === 'PUT') {
                            const data = response.data;
                            const row = $(`#row-${id}`);

                            // Update Columns Manually
                            const kodeIKU = $('#indikator_id option:selected').text().split(' - ')[0];
                            row.find('td:nth-child(2)').text(kodeIKU);
                            row.find('td:nth-child(3)').text(data.nama_kegiatan);

                            const ketuaTim = $('#ketua_tim_id option:selected').text();
                            if ($('#ketua_tim_id').val()) {
                                const name = ketuaTim.split(' (')[0];
                                const nip = ketuaTim.split(' (')[1]?.replace(')', '');
                                row.find('td:nth-child(4)').html(`<div class="small fw-bold text-dark">${name}</div><div class="extra-small text-muted">${nip}</div>`);
                            } else {
                                row.find('td:nth-child(4)').html('<span class="text-muted small italic">- Belum ditunjuk -</span>');
                            }

                            let tahapanHtml = '';
                            if (data.tahapan_json) {
                                data.tahapan_json.forEach(t => {
                                    tahapanHtml += `<span class="badge bg-light text-primary border border-primary-subtle rounded-pill small px-2 py-1 mb-1 me-1">${t}</span>`;
                                });
                            }
                            row.find('td:nth-child(5)').html(tahapanHtml);

                            // Invalidate and draw (keep paging)
                            const table = $('#kegiatanTable').DataTable();
                            table.row(row).invalidate().draw(false);
                        } else {
                            setTimeout(() => location.reload(), 1000);
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('Simpan Kegiatan');
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            Object.values(errors).forEach(err => toastr.error(err[0]));
                        } else {
                            toastr.error('Terjadi kesalahan saat menyimpan data.');
                        }
                    }
                });
            });

            // Delete Button Click
            $(document).on('click', '.delete-kegiatan', function () {
                if (!confirm('Hapus kegiatan ini?')) return;
                const id = $(this).data('id');
                const row = $(`#row-${id}`);

                $.ajax({
                    url: `{{ url('kegiatan-master') }}/${id}`,
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        toastr.success(response.message);
                        row.fadeOut(function () { $(this).remove(); });
                    },
                    error: function () {
                        toastr.error('Gagal menghapus data.');
                    }
                });
            });
        });
    </script>
    <style>
        .btn-xs {
            padding: 0.1rem 0.4rem;
            font-size: 0.75rem;
        }
    </style>
@endsection