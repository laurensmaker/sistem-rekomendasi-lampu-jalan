{{-- resources/views/backend/ranking-saw/index.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">3 Prioritas Utama Pemasangan LPJU</h3>
    <div>
        <a href="{{ route('ranking-saw.export-excel') }}" class="btn btn-success btn-sm">
            <i data-feather="file-text"></i> Export Excel
        </a>
        <a href="{{ route('ranking-saw.export-pdf') }}" class="btn btn-danger btn-sm">
            <i data-feather="printer"></i> Export PDF
        </a>
        <form action="{{ route('ranking-saw.recalculate') }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Yakin ingin menghitung ulang semua data SAW?')">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
                <i data-feather="refresh-cw"></i> Hitung Ulang
            </button>
        </form>
        {{-- <a href="{{ route('dashboard.' . auth()->user()->role) }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
        </a> --}}
    </div>
</div>

<!-- Statistik Cards -->
{{-- <div class="row g-3 mb-4">
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Data</h6>
                <h3 class="mb-0">{{ $statistik['total'] }}</h3>
                <small class="text-muted">Lokasi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Rata-rata</h6>
                <h3 class="mb-0">{{ number_format($statistik['rata_rata'], 4) }}</h3>
                <small class="text-muted">Nilai Preferensi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Tertinggi</h6>
                <h3 class="mb-0 text-success">{{ number_format($statistik['tertinggi'], 4) }}</h3>
                <small class="text-muted">Nilai Preferensi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Terendah</h6>
                <h3 class="mb-0 text-danger">{{ number_format($statistik['terendah'], 4) }}</h3>
                <small class="text-muted">Nilai Preferensi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Sudah Rekomendasi</h6>
                <h3 class="mb-0 text-success">{{ $statistik['sudah_rekomendasi'] }}</h3>
                <small class="text-muted">Lokasi</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-lg-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Belum Rekomendasi</h6>
                <h3 class="mb-0 text-warning">{{ $statistik['belum_rekomendasi'] }}</h3>
                <small class="text-muted">Lokasi</small>
            </div>
        </div>
    </div>
</div> --}}

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" class="me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i data-feather="alert-circle" class="me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- 3 Prioritas Teratas -->
        <div class="row g-4">
            @forelse($ranking as $key => $item)
                @php
                    $rank = $key + 1;
                    $colors = [
                        1 => ['bg' => 'gold', 'text' => 'dark', 'icon' => 'award'],
                        2 => ['bg' => 'silver', 'text' => 'dark', 'icon' => 'award'],
                        3 => ['bg' => 'bronze', 'text' => 'dark', 'icon' => 'award']
                    ];
                    $color = $colors[$rank] ?? ['bg' => 'light', 'text' => 'dark', 'icon' => 'circle'];
                @endphp
                <div class="col-md-4">
                    <div class="card border-0 rounded-10 shadow-sm hover-shadow transition">
                        <div class="card-header bg-{{ $color['bg'] }} text-{{ $color['text'] }} text-center py-3">
                            <h4 class="mb-0">
                                <i data-feather="{{ $color['icon'] }}" class="me-1"></i>
                                Peringkat #{{ $rank }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar avatar-lg bg-primary-soft rounded-circle mx-auto p-3">
                                    <i data-feather="map-pin" class="text-primary" style="width: 40px; height: 40px;"></i>
                                </div>
                                <h4 class="mt-2">{{ $item->lokasi->nama_jalan }}</h4>
                                <p class="text-muted">{{ $item->lokasi->distrik }}</p>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="bg-light rounded-10 p-2 text-center">
                                        <small class="text-muted">Nilai SAW</small>
                                        <h5 class="mb-0 text-{{ $item->nilai_preferensi >= 0.8 ? 'success' : ($item->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                                            {{ number_format($item->nilai_preferensi, 4) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-10 p-2 text-center">
                                        <small class="text-muted">Panjang Jalan</small>
                                        <h5 class="mb-0">{{ number_format($item->panjang_jalan, 0) }} m</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-10 p-2 text-center">
                                        <small class="text-muted">Jumlah Lampu</small>
                                        <h5 class="mb-0 text-primary">
                                            {{ $item->rekomendasi ? $item->rekomendasi->jumlah_lampu : '-' }}
                                            <small>unit</small>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-10 p-2 text-center">
                                        <small class="text-muted">Jarak Lampu</small>
                                        <h5 class="mb-0">
                                            {{ $item->rekomendasi ? number_format($item->rekomendasi->jarak_antar_lampu, 0) : '-' }} m
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bg-light rounded-10 p-2 text-center">
                                        <small class="text-muted">Total Biaya</small>
                                        <h5 class="mb-0 text-success">
                                            {{ $item->rekomendasi ? 'Rp ' . number_format($item->rekomendasi->total_biaya, 0, ',', '.') : '-' }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $item->nilai_preferensi >= 0.8 ? 'success' : ($item->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}" 
                                         role="progressbar" 
                                         style="width: {{ $item->nilai_preferensi * 100 }}%;" 
                                         aria-valuenow="{{ $item->nilai_preferensi * 100 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format($item->nilai_preferensi * 100, 0) }}%
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                <a href="{{ route('ranking-saw.show', $item->id) }}" 
                                   class="btn btn-info btn-sm">
                                    <i data-feather="eye"></i> Detail
                                </a>
                                @if($item->rekomendasi)
                                    <a href="{{ route('rekomendasi.show', $item->rekomendasi->id) }}" 
                                       class="btn btn-success btn-sm">
                                        <i data-feather="thumbs-up"></i> Rekomendasi
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-{{ $color['bg'] }} text-center">
                            <small class="text-{{ $color['text'] }}">
                                <i data-feather="calendar" style="width: 14px;"></i>
                                Survei: {{ $item->tanggal_survei->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <i data-feather="bar-chart-2" style="width: 64px; height: 64px; color: #ccc;"></i>
                    <h4 class="mt-3">Belum Ada Data Ranking</h4>
                    <p class="text-muted">Silakan lakukan perhitungan SAW terlebih dahulu</p>
                    <form action="{{ route('ranking-saw.recalculate') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="bar-chart-2"></i> Hitung SAW Sekarang
                        </button>
                    </form>
                </div>
            @endforelse
        </div>

        <!-- Informasi Prioritas -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i data-feather="info" class="me-1"></i>
                    <strong>Keterangan Peringkat:</strong>
                    <ul class="mb-0 mt-1">
                        <li>
                            <span class="badge bg-warning text-dark">🏆 #1</span> - Prioritas Tertinggi (Sangat Mendesak)
                        </li>
                        <li>
                            <span class="badge bg-secondary text-white">#2</span> - Prioritas Kedua (Mendesak)
                        </li>
                        <li>
                            <span class="badge bg-danger text-white">#3</span> - Prioritas Ketiga (Perlu Diperhatikan)
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-success">
                    <i data-feather="bar-chart-2" class="me-1"></i>
                    <strong>Interpretasi Nilai SAW:</strong>
                    <ul class="mb-0 mt-1">
                        <li>
                            <span class="text-success">≥ 0.80</span> - Prioritas Sangat Tinggi
                        </li>
                        <li>
                            <span class="text-warning">0.60 - 0.79</span> - Prioritas Tinggi
                        </li>
                        <li>
                            <span class="text-danger">≤ 0.59</span> - Prioritas Sedang/Rendah
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.rounded-10 {
    border-radius: 10px;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    transition: all 0.3s ease;
}
.transition {
    transition: all 0.3s ease;
}
.avatar.bg-primary-soft { background: rgba(13, 110, 253, 0.1); }

.bg-gold { background: linear-gradient(135deg, #ffd700, #f5a623); }
.bg-silver { background: linear-gradient(135deg, #c0c0c0, #a8a8a8); }
.bg-bronze { background: linear-gradient(135deg, #cd7f32, #a0522d); }

.text-gold { color: #f5a623; }
.text-silver { color: #a8a8a8; }
.text-bronze { color: #a0522d; }
</style>
@endpush