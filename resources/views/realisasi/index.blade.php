@extends('layouts.dashboard')

@section('title', 'Realisasi Kinerja Triwulanan')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <form action="{{ route('realisasi.index') }}" method="GET" class="d-flex align-items-center">
                    <label class="me-3 text-nowrap">Filter Triwulan:</label>
                    <select name="triwulan" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Semua Triwulan</option>
                        <option value="1" {{ request('triwulan') == 1 ? 'selected' : '' }}>Triwulan 1</option>
                        <option value="2" {{ request('triwulan') == 2 ? 'selected' : '' }}>Triwulan 2</option>
                        <option value="3" {{ request('triwulan') == 3 ? 'selected' : '' }}>Triwulan 3</option>
                        <option value="4" {{ request('triwulan') == 4 ? 'selected' : '' }}>Triwulan 4</option>
                    </select>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('export.realisasi') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Excel
                </a>
                <a href="{{ route('realisasi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Realisasi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover border">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Indikator</th>
                        <th class="text-center">Triwulan</th>
                        <th class="text-center">Target TW</th>
                        <th class="text-center">Realisasi Kumulatif</th>
                        <th class="text-center">Capaian TW (%)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($realisasis as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->indikator->indikator_kinerja }}</td>
                        <td class="text-center"><span class="badge bg-secondary">TW {{ $r->triwulan }}</span></td>
                        @php
                            $targetField = 'target_tw' . $r->triwulan;
                            $targetVal = $r->indikator->target ? $r->indikator->target->$targetField : 0;
                        @endphp
                        <td class="text-center fw-bold">{{ $targetVal }}</td>
                        <td class="text-center fw-bold text-primary">{{ $r->realisasi_kumulatif }}</td>
                        <td class="text-center">
                            @php $capaian = $r->capaian_triwulan; @endphp
                            <span class="fw-bold text-{{ $capaian >= 100 ? 'success' : ($capaian >= 80 ? 'warning' : 'danger') }}">
                                {{ number_format($capaian, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('realisasi.edit', $r->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('realisasi.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus realisasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
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
