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
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        {{ count($a->lampiran) }} File
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($a->lampiran as $path)
                                            <li><a class="dropdown-item small" href="{{ asset('storage/' . $path) }}" target="_blank">File {{ $loop->iteration }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted small">Tanpa Lampiran</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.aktivitas.edit', $a->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.aktivitas.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#adminAktivitasTable').DataTable();
    });
</script>
@endsection
