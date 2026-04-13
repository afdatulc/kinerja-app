@extends('layouts.dashboard')

@section('title', 'Edit Target: ' . $target->indikator->indikator_kinerja)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('target.update', $target->indikator_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4 text-center">
                        <div class="badge bg-primary px-3 py-2 mb-2">Target Tahunan: {{ $target->indikator->target_tahunan }} {{ $target->indikator->satuan }}</div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Target Triwulan 1</label>
                            <div class="input-group">
                                <span class="input-group-text">TW1</span>
                                <input type="number" step="0.01" name="target_tw1" class="form-control" value="{{ old('target_tw1', $target->target_tw1) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Target Triwulan 2</label>
                            <div class="input-group">
                                <span class="input-group-text">TW2</span>
                                <input type="number" step="0.01" name="target_tw2" class="form-control" value="{{ old('target_tw2', $target->target_tw2) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Target Triwulan 3</label>
                            <div class="input-group">
                                <span class="input-group-text">TW3</span>
                                <input type="number" step="0.01" name="target_tw3" class="form-control" value="{{ old('target_tw3', $target->target_tw3) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Target Triwulan 4 (Kumulatif Akhir)</label>
                            <div class="input-group border border-primary rounded">
                                <span class="input-group-text bg-primary text-white">TW4</span>
                                <input type="number" step="0.01" name="target_tw4" class="form-control" value="{{ old('target_tw4', $target->target_tw4) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 pt-3 border-top text-center">
                        <button type="submit" class="btn btn-primary px-4">Update Target</button>
                        <a href="{{ route('target.index') }}" class="btn btn-light ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
