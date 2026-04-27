@extends('layouts.dashboard')

@section('title', 'Analisis & Kendala')

@section('content')
<div class="card border-0 shadow-sm rounded-4 text-dark">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <form action="{{ route('analisis.index') }}" method="GET" class="d-flex align-items-center">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0 rounded-start-pill px-3"><i class="fas fa-filter text-muted"></i></span>
                <select name="triwulan" class="form-select border-0 bg-light rounded-end-pill px-3" onchange="this.form.submit()" style="width: 120px;">
                    <option value="">Semua TW</option>
                    @foreach([1, 2, 3, 4] as $tw)
                        <option value="{{ $tw }}" {{ request('triwulan') == $tw ? 'selected' : '' }}>TW {{ $tw }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        @unless(auth()->user()->isAdmin())
        <a href="{{ route('analisis.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fas fa-plus me-1"></i> Input Analisis
        </a>
        @endunless
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle border-0" id="analisisTable">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th width="80">TW</th>
                        <th>Indikator Kinerja</th>
                        <th width="120">Status</th>
                        <th>Kendala, Solusi & RTL</th>
                        <th width="150">PIC / Batas Waktu</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analisiss as $a)
                    <tr class="border-bottom">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill px-3">TW {{ $a->triwulan }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small mb-1">{{ $a->indikator->indikator_kinerja }}</div>
                            <div class="extra-small text-primary fw-bold">{{ $a->indikator->kode }}</div>
                        </td>
                        <td>
                            @php
                                $color = $a->severity == 'High' ? 'danger' : ($a->severity == 'Medium' ? 'warning' : 'info');
                                $icon = $a->severity == 'High' ? 'circle-exclamation' : ($a->severity == 'Medium' ? 'triangle-exclamation' : 'circle-info');
                            @endphp
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}-subtle rounded-pill px-2 py-1 w-100">
                                <i class="fas fa-{{ $icon }} me-1"></i> {{ $a->severity }}
                            </span>
                        </td>
                        <td>
                            <div class="mb-2">
                                <span class="badge bg-light text-dark fw-bold extra-small border me-1">KENDALA</span>
                                <span class="small text-muted text-truncate-2 d-inline-block align-middle" style="max-width: 350px;">{{ $a->kendala ?: '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="badge bg-light text-primary fw-bold extra-small border me-1">SOLUSI</span>
                                <span class="small text-dark text-truncate-2 d-inline-block align-middle" style="max-width: 350px;">{{ $a->solusi ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="badge bg-light text-success fw-bold extra-small border me-1">RTL</span>
                                <span class="small text-dark text-truncate-2 d-inline-block align-middle fw-bold" style="max-width: 350px;">{{ $a->rencana_tindak_lanjut ?: '-' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($a->pic_tindak_lanjut)
                                <div class="small fw-bold text-dark mb-1"><i class="fas fa-user-tag me-1 text-primary"></i>{{ $a->pic_tindak_lanjut }}</div>
                                <div class="extra-small text-muted"><i class="fas fa-calendar-alt me-1"></i>{{ $a->batas_waktu ? \Carbon\Carbon::parse($a->batas_waktu)->format('d/m/Y') : '-' }}</div>
                            @else
                                <span class="text-muted extra-small italic">Belum ada PIC RTL</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="{{ route('analisis.edit', $a->id) }}" class="btn btn-sm btn-outline-primary rounded-3 d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('analisis.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus analisis ini?')">
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#analisisTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
        });
    });
</script>
<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.8rem;
    }
    .text-truncate-2:hover {
        -webkit-line-clamp: unset;
    }
    .extra-small {
        font-size: 0.65rem;
    }
</style>
@endsection
