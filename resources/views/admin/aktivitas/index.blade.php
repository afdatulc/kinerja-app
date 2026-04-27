@extends('layouts.dashboard')

@section('title', 'Daftar Aktivitas Pegawai')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.aktivitas.index') }}" method="GET" class="d-flex align-items-center">
            <label class="me-3">Filter Triwulan:</label>
            <select name="triwulan" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Semua Triwulan</option>
                <option value="1" {{ request('triwulan') == 1 ? 'selected' : '' }}>Triwulan 1</option>
                <option value="2" {{ request('triwulan') == 2 ? 'selected' : '' }}>Triwulan 2</option>
                <option value="3" {{ request('triwulan') == 3 ? 'selected' : '' }}>Triwulan 3</option>
                <option value="4" {{ request('triwulan') == 4 ? 'selected' : '' }}>Triwulan 4</option>
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="adminAktivitasTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pegawai</th>
                        <th>Indikator</th>
                        <th>Aktivitas & Tahapan</th>
                        <th>Waktu</th>
                        <th>Lampiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aktivitas as $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold">{{ $a->pegawai->nama ?? 'NIP: '.$a->pegawai_nip }}</div>
                            <small class="text-muted">{{ $a->pegawai_nip }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary mb-1">TW {{ $a->triwulan }}</span>
                            <div class="small fw-bold">{{ $a->indikator->kode }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $a->kegiatan->nama_kegiatan ?? '-' }}</div>
                            <div class="mb-2 mt-1">
                                <span class="badge bg-light text-primary border border-primary-subtle rounded-pill small me-1 mb-1">{{ $a->tahapan }}</span>
                            </div>
                            <div class="p-2 bg-light rounded small" style="max-width: 300px;">{{ Str::limit($a->uraian, 100) }}</div>
                        </td>
                        <td>
                            <div class="small"><i class="far fa-calendar-alt me-1 text-success"></i> {{ $a->tanggal_mulai->format('d/m/Y') }}</div>
                            <div class="small"><i class="far fa-calendar-check me-1 text-danger"></i> {{ $a->tanggal_selesai->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            @if($a->lampiran && count($a->lampiran) > 0)
                                @php
                                    $filesData = collect($a->lampiran)->map(function($path, $index) {
                                        return [
                                            'url' => asset('storage/' . $path),
                                            'name' => 'File ' . ($index + 1),
                                            'ext' => strtolower(pathinfo($path, PATHINFO_EXTENSION))
                                        ];
                                    });
                                @endphp
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($a->lampiran as $path)
                                        @php
                                            $ext = pathinfo($path, PATHINFO_EXTENSION);
                                            $icon = 'fa-file';
                                            $color = 'secondary';
                                            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { $icon = 'fa-file-image'; $color = 'success'; }
                                            elseif (strtolower($ext) === 'pdf') { $icon = 'fa-file-pdf'; $color = 'danger'; }
                                            elseif (in_array(strtolower($ext), ['doc', 'docx'])) { $icon = 'fa-file-word'; $color = 'primary'; }
                                            elseif (in_array(strtolower($ext), ['xls', 'xlsx', 'csv'])) { $icon = 'fa-file-excel'; $color = 'success'; }
                                        @endphp
                                        <button class="btn btn-xs btn-outline-{{ $color }} rounded-3 p-1" 
                                                title="File {{ $loop->iteration }} (.{{ $ext }})"
                                                onclick='showGallery({{ $loop->index }}, @json($filesData))'>
                                            <i class="fas {{ $icon }} fa-fw"></i>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted extra-small">Tanpa Lampiran</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="{{ route('admin.aktivitas.edit', $a->id) }}" class="btn btn-sm btn-outline-primary rounded-3 d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.aktivitas.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    .extra-small { font-size: 0.75rem; }
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

    $(document).ready(function() {
        $('#adminAktivitasTable').DataTable({
            language: window.DATATABLES_ID
        });
    });
</script>
@endsection
