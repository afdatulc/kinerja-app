@extends('layouts.dashboard')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);">
            <div class="card-body p-4 p-md-5 text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Halo, {{ auth()->user()->name }}! 👋</h2>
                        <p class="mb-0 opacity-75">Selamat datang kembali di KinerjaApp. Pantau progres indikator dan laporan aktivitas Anda di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Total Activities -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3">
                        <i class="fas fa-tasks fs-4"></i>
                    </div>
                    <h6 class="mb-0 fw-bold">Kontribusi Aktivitas</h6>
                </div>
                <div class="fs-2 fw-bold text-dark">{{ $summary['personal_activities'] }}</div>
                <p class="text-muted small mb-0">Total laporan aktivitas yang telah Anda kirimkan.</p>
            </div>
        </div>
    </div>
    
    <!-- Responsibility Stats -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success-subtle text-success p-3 me-3">
                        <i class="fas fa-bullseye fs-4"></i>
                    </div>
                    <h6 class="mb-0 fw-bold">Tanggung Jawab (PIC)</h6>
                </div>
                <div class="fs-2 fw-bold text-dark">{{ $summary['total_pic'] }}</div>
                <p class="text-muted small mb-0">Indikator yang menjadi tanggung jawab Anda.</p>
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info-subtle text-info p-3 me-3">
                        <i class="fas fa-chart-line fs-4"></i>
                    </div>
                    <h6 class="mb-0 fw-bold">Status Indikator Saya</h6>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-success px-2 py-2 w-100">{{ $summary['pic_hijau'] }} Oke</span>
                    <span class="badge bg-danger px-2 py-2 w-100">{{ $summary['pic_critical'] }} Kritis</span>
                </div>
                <p class="text-muted small mt-2 mb-0">Gambaran cepat performa indikator Anda.</p>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-4">Daftar Indikator PIC Anda (Tahun {{ $tahun }})</h5>

<div class="row g-4">
    @forelse($indikators as $i)
        @php
            $capaian = $i->capaian_tahunan;
            $statusWarna = $i->status_warna;
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 card-indicator">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-light text-muted border fw-normal">{{ $i->kode }}</span>
                        <div class="text-{{ $statusWarna }} fw-bold">{{ number_format($capaian, 1) }}%</div>
                    </div>
                    <h6 class="fw-bold mb-3 text-dark lh-base">{{ $i->indikator_kinerja }}</h6>
                    
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $statusWarna }}" role="progressbar" style="width: {{ $capaian }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between extra-small text-muted">
                        <span>Target: {{ $i->target_tahunan }} {{ $i->satuan }}</span>
                        <span>{{ $i->satuan }}</span>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0 pb-4 px-3">
                    <a href="{{ route('rekap.capaian') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">Lihat Rekap Seluruh</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center py-5">
                <i class="fas fa-info-circle fs-3 text-muted mb-3 d-block"></i>
                @if(isset($error) && $error)
                    <p class="text-danger fw-bold mb-1">{{ $error }}</p>
                    <p class="text-muted small">Hubungi Administrator untuk menautkan akun Anda dengan data profil pegawai.</p>
                @else
                    <p class="text-muted mb-0">Anda belum ditugaskan sebagai PIC untuk indikator kinerja mana pun di tahun {{ $tahun }}.</p>
                @endif
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('styles')
<style>
    .card-indicator {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-indicator:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .extra-small {
        font-size: 0.75rem;
    }
</style>
@endsection
