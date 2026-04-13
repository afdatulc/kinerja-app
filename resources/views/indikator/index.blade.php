@extends('layouts.dashboard')

@section('title', 'Master Indikator')

@section('content')
    <div class="card border-0 shadow-sm rounded-4 text-dark">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                        data-bs-target="#modalIndikator">
                        <i class="fas fa-plus me-1"></i> Tambah Indikator
                    </button>
                    <a href="{{ route('indikator.template') }}" class="btn btn-outline-success rounded-pill px-3 ms-2 fw-bold">
                        <i class="fas fa-download me-1"></i> Template
                    </a>
                @else
                    <div class="fw-bold text-dark"><i class="fas fa-list-check me-2 text-primary"></i> Daftar Tanggung Jawab
                        Indikator Kinerja</div>
                @endif
            </div>
            @if(auth()->user()->isAdmin())
                <form action="{{ route('indikator.import') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex align-items-center">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="file" name="file" class="form-control rounded-start-pill border-success"
                            style="width: 250px;" required>
                        <button type="submit" class="btn btn-success rounded-end-pill px-3">
                            <i class="fas fa-upload me-1"></i> Import
                        </button>
                    </div>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="indikatorTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Kode</th>
                            <th>Sasaran & Indikator Kinerja</th>
                            <th width="120">Jenis / Periode</th>
                            <th width="100">Satuan</th>
                            <th width="100">Target</th>
                            <th width="150">PIC</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($indikators as $i)
                            <tr id="row-{{ $i->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="small fw-bold text-primary">{{ $i->kode ?: '-' }}</td>
                                <td>
                                    <div class="fw-bold text-dark mb-1">{{ $i->indikator_kinerja }}</div>
                                    <div class="small text-muted" style="font-size: 0.75rem;"><i
                                            class="fas fa-crosshairs me-1 text-secondary"></i>{{ $i->sasaran }}</div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-2 mb-1">{{ $i->jenis_indikator }}</span>
                                    <div class="extra-small text-muted ps-1">{{ $i->periode }} ({{ $i->tahun }})</div>
                                </td>
                                <td><span class="badge bg-light text-dark border fw-normal">{{ $i->satuan }}</span></td>
                                <td class="fw-bold text-primary">{{ $i->target_tahunan }}</td>
                                <td>
                                    @if($i->pic)
                                        <div class="small fw-bold text-dark">{{ $i->pic->nama }}</div>
                                        <div class="extra-small text-muted">{{ $i->pic->nip }}</div>
                                    @else
                                        <span class="text-muted small italic">- Belum diatur -</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('realisasi.entry', $i->id) }}"
                                            class="btn btn-sm btn-outline-success rounded-3" title="Input Progress">
                                            <i class="fas fa-chart-line"></i>
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <button class="btn btn-sm btn-outline-primary rounded-3 edit-indikator"
                                                data-id="{{ $i->id }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger rounded-3 delete-indikator"
                                                data-id="{{ $i->id }}" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

    <!-- Modal Tambah/Edit Indikator -->
    <div class="modal fade" id="modalIndikator" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Indikator Kinerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formIndikator">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" id="indikator_id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Kode Indikator</label>
                                <input type="text" name="kode" id="kode"
                                    class="form-control rounded-3 shadow-none border-light-subtle"
                                    placeholder="Contoh: 1.1.1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Jenis</label>
                                <select name="jenis_indikator" id="jenis_indikator"
                                    class="form-select rounded-3 shadow-none border-light-subtle" required>
                                    <option value="IKU">IKU</option>
                                    <option value="Proksi">Proksi</option>
                                    <option value="IK">IK</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Tahun</label>
                                <input type="number" name="tahun" id="tahun"
                                    class="form-control rounded-3 shadow-none border-light-subtle" value="{{ date('Y') }}"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Tujuan Strategis</label>
                                <textarea name="tujuan" id="tujuan"
                                    class="form-control rounded-3 shadow-none border-light-subtle" rows="2"
                                    placeholder="Deskripsi tujuan strategis..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Sasaran</label>
                                <input type="text" name="sasaran" id="sasaran"
                                    class="form-control rounded-3 shadow-none border-light-subtle"
                                    placeholder="Deskripsi sasaran..." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small">Indikator Kinerja</label>
                                <input type="text" name="indikator_kinerja" id="indikator_kinerja"
                                    class="form-control rounded-3 shadow-none border-light-subtle"
                                    placeholder="Nama indikator kinerja..." required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Periode</label>
                                <select name="periode" id="periode"
                                    class="form-select rounded-3 shadow-none border-light-subtle" required>
                                    <option value="Tahunan">Tahunan</option>
                                    <option value="Bulanan">Bulanan</option>
                                    <option value="Triwulanan">Triwulanan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Tipe Data</label>
                                <select name="tipe" id="tipe" class="form-select rounded-3 shadow-none border-light-subtle"
                                    required>
                                    <option value="Persen">Persen</option>
                                    <option value="Dokumen">Dokumen</option>
                                    <option value="Nilai">Nilai</option>
                                    <option value="Rasio">Rasio</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small">Satuan</label>
                                <input type="text" name="satuan" id="satuan"
                                    class="form-control rounded-3 shadow-none border-light-subtle"
                                    placeholder="Contoh: Persen" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Target Tahunan</label>
                                <input type="number" step="0.01" name="target_tahunan" id="target_tahunan"
                                    class="form-control rounded-3 shadow-none border-light-subtle" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Penanggung Jawab (PIC)</label>
                                <select name="pic_id" id="pic_id"
                                    class="form-select rounded-3 shadow-none border-light-subtle">
                                    <option value="">-- Tanpa PIC --</option>
                                    @foreach($pegawais as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->nip }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnSimpan">Simpan
                            Indikator</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#indikatorTable').DataTable({
                language: window.DATATABLES_ID
            });

            // Reset Modal on Close
            $('#modalIndikator').on('hidden.bs.modal', function () {
                $('#formIndikator')[0].reset();
                $('#modalTitle').text('Tambah Indikator Kinerja');
                $('#formMethod').val('POST');
                $('#indikator_id').val('');
                $('#pic_id').val('').trigger('change');
            });

            // Edit Button Click (Event Delegation)
            $(document).on('click', '.edit-indikator', function () {
                const id = $(this).data('id');
                $('#modalTitle').text('Edit Indikator Kinerja');
                $('#formMethod').val('PUT');
                $('#indikator_id').val(id);
                $('#modalIndikator').modal('show');

                $.get(`{{ url('indikator') }}/${id}`, function (data) {
                    $('#kode').val(data.kode);
                    $('#tujuan').val(data.tujuan);
                    $('#sasaran').val(data.sasaran);
                    $('#indikator_kinerja').val(data.indikator_kinerja);
                    $('#jenis_indikator').val(data.jenis_indikator);
                    $('#periode').val(data.periode);
                    $('#tipe').val(data.tipe);
                    $('#satuan').val(data.satuan);
                    $('#target_tahunan').val(data.target_tahunan);
                    $('#tahun').val(data.tahun);
                    $('#pic_id').val(data.pic_id).trigger('change');
                });
            });

            // Form Submit (AJAX)
            $('#formIndikator').on('submit', function (e) {
                e.preventDefault();
                const id = $('#indikator_id').val();
                const method = $('#formMethod').val();
                const url = method === 'POST' ? "{{ route('indikator.store') }}" : `{{ url('indikator') }}/${id}`;
                const btn = $('#btnSimpan');

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        toastr.success(response.message);
                        $('#modalIndikator').modal('hide');

                        if (method === 'PUT') {
                            const data = response.data;
                            const row = $(`#row-${id}`);

                            // Update Columns Manually
                            row.find('td:nth-child(2)').html(`<span class="small fw-bold text-primary">${data.kode || '-'}</span>`);
                            row.find('td:nth-child(3)').html(`
                                <div class="fw-bold text-dark mb-1">${data.indikator_kinerja}</div>
                                <div class="small text-muted" style="font-size: 0.75rem;"><i class="fas fa-crosshairs me-1 text-secondary"></i>${data.sasaran}</div>
                            `);
                            row.find('td:nth-child(4)').html(`
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill px-2 mb-1">${data.jenis_indikator}</span>
                                <div class="extra-small text-muted ps-1">${data.periode} (${data.tahun})</div>
                            `);
                            row.find('td:nth-child(5)').html(`<span class="badge bg-light text-dark border fw-normal">${data.satuan}</span>`);
                            row.find('td:nth-child(6)').text(data.target_tahunan);

                            // Update PIC (Assuming we need to fetch name if only ID returned, 
                            // but let's try to get it from the select text)
                            const picName = $('#pic_id option:selected').text().split(' (')[0];
                            const picNip = $('#pic_id option:selected').text().split(' (')[1]?.replace(')', '') || '';

                            if ($('#pic_id').val()) {
                                row.find('td:nth-child(7)').html(`
                                    <div class="small fw-bold text-dark">${picName}</div>
                                    <div class="extra-small text-muted">${picNip}</div>
                                `);
                            } else {
                                row.find('td:nth-child(7)').html('<span class="text-muted small italic">- Belum diatur -</span>');
                            }

                            // Invalidate and draw (keep paging)
                            const table = $('#indikatorTable').DataTable();
                            table.row(row).invalidate().draw(false);
                        } else {
                            // For Create, it's easier to reload or add row, 
                            // but user specifically asked for edit state retention.
                            setTimeout(() => location.reload(), 1000);
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html('Simpan Indikator');
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            Object.values(errors).forEach(err => toastr.error(err[0]));
                        } else {
                            toastr.error('Terjadi kesalahan saat menyimpan data.');
                        }
                    }
                });
            });

            // Delete Button Click (Event Delegation)
            $(document).on('click', '.delete-indikator', function () {
                if (!confirm('Hapus indikator ini?')) return;
                const id = $(this).data('id');
                const row = $(`#row-${id}`);

                $.ajax({
                    url: `{{ url('indikator') }}/${id}`,
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
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.02);
        }

        .extra-small {
            font-size: 0.7rem;
        }
    </style>
@endsection