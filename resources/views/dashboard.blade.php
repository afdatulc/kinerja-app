@extends('layouts.dashboard')

@section('title', 'Dashboard Monitoring ' . $tahun)

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stat-card bg-blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="opacity-75 small">Total Indikator</div>
                    <div class="fs-2 fw-bold">{{ $summary['total'] }}</div>
                </div>
                <i class="fas fa-list-ol fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="opacity-75 small">Capaian &ge; 100%</div>
                    <div class="fs-2 fw-bold">{{ $summary['hijau'] }}</div>
                </div>
                <i class="fas fa-check-double fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-yellow">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="opacity-75 small">Capaian 80-99%</div>
                    <div class="fs-2 fw-bold">{{ $summary['kuning'] }}</div>
                </div>
                <i class="fas fa-triangle-exclamation fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-red">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="opacity-75 small">Capaian &lt; 80%</div>
                    <div class="fs-2 fw-bold">{{ $summary['merah'] }}</div>
                </div>
                <i class="fas fa-circle-exclamation fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Grafik Capaian Tahunan per Indikator</span>
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center">
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="2025" {{ $tahun == 2025 ? 'selected' : '' }}>2025</option>
                        <option value="2026" {{ $tahun == 2026 ? 'selected' : '' }}>2026</option>
                        <option value="2027" {{ $tahun == 2027 ? 'selected' : '' }}>2027</option>
                    </select>
                </form>
            </div>
            <div class="card-body">
                <canvas id="capaianChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Distribusi Status</div>
            <div class="card-body">
                <canvas id="statusChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Daftar Indikator & Capaian</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Indikator</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Target Tahunan</th>
                        <th class="text-center">Tahun</th>
                        <th>Progress Capaian</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($indikators as $i)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-bold">{{ $i->indikator_kinerja }}</div>
                            <small class="text-muted">{{ $i->sasaran }}</small>
                        </td>
                        <td class="text-center">{{ $i->satuan }}</td>
                        <td class="text-center">{{ $i->target_tahunan }}</td>
                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $i->tahun }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $i->status_warna }}" role="progressbar" 
                                         style="width: {{ min(100, $i->capaian_tahunan) }}%"></div>
                                </div>
                                <span class="ms-2 small fw-bold">{{ number_format($i->capaian_tahunan, 1) }}%</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <i class="fas fa-circle text-{{ $i->status_warna }}"></i>
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
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('capaianChart').getContext('2d');
        const statusCtx = document.getElementById('statusChart').getContext('2d');

        const codes = {!! json_encode($indikators->pluck('kode')) !!};
        const fullLabels = {!! json_encode($indikators->pluck('indikator_kinerja')) !!};
        const dataCapaian = {!! json_encode($indikators->pluck('capaian_tahunan')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: codes,
                datasets: [{
                    label: 'Capaian (%)',
                    data: dataCapaian,
                    backgroundColor: dataCapaian.map(v => v >= 100 ? '#28a745' : (v >= 80 ? '#ffc107' : '#dc3545')),
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return fullLabels[context[0].dataIndex];
                            },
                            label: function(context) {
                                return 'Capaian: ' + context.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        max: 120,
                        title: { display: true, text: 'Capaian (%)' }
                    },
                    x: {
                        title: { display: true, text: 'Kode Indikator' },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hijau', 'Kuning', 'Merah'],
                datasets: [{
                    data: [{{ $summary['hijau'] }}, {{ $summary['kuning'] }}, {{ $summary['merah'] }}],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    });
</script>
@endsection
