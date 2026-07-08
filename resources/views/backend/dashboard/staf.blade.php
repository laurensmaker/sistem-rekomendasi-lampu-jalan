{{-- resources/views/backend/dashboard/staf.blade.php --}}
@extends('backend.layouts.main')

@section('content')

{{-- <div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Dashboard Staf Perencana</h3>
    <div>
        <span class="badge bg-{{ $rekomendasiDraft > 0 ? 'warning' : 'success' }}">
            {{ $rekomendasiDraft }} Draft Rekomendasi
        </span>
    </div>
</div> --}}

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Survei</h6>
                        {{-- <h3 class="mb-0 fw-bold">{{ $totalSurvei }}</h3> --}}
                        <small class="text-muted">Tersedia</small>
                    </div>
                    <div class="avatar bg-primary-soft rounded-10 p-3">
                        <i data-feather="clipboard" class="text-primary" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Sudah Dihitung SAW</h6>
                        <h3 class="mb-0 fw-bold text-success">
                            {{ \App\Models\HasilSurvei::whereNotNull('nilai_preferensi')->count() }}
                        </h3>
                        <small class="text-muted">Telah diproses</small>
                    </div>
                    <div class="avatar bg-success-soft rounded-10 p-3">
                        <i data-feather="bar-chart-2" class="text-success" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Rekomendasi</h6>
                        {{-- <h3 class="mb-0 fw-bold">{{ $totalRekomendasi }}</h3> --}}
                        <small class="text-muted">Dihasilkan</small>
                    </div>
                    <div class="avatar bg-info-soft rounded-10 p-3">
                        <i data-feather="thumbs-up" class="text-info" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Draft Rekomendasi</h6>
                        {{-- <h3 class="mb-0 fw-bold text-warning">{{ $rekomendasiDraft }}</h3> --}}
                        <small class="text-muted">Perlu diajukan</small>
                    </div>
                    <div class="avatar bg-warning-soft rounded-10 p-3">
                        <i data-feather="file-text" class="text-warning" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Aksi Cepat</h6>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('hasil-survei.index') }}" class="btn btn-primary">
                        <i data-feather="clipboard"></i> Kelola Survei
                    </a>
                    <a href="{{ route('rekomendasi.index') }}" class="btn btn-success">
                        <i data-feather="thumbs-up"></i> Lihat Rekomendasi
                    </a>
                    <a href="{{ route('rekomendasi.index', ['status' => 'draft']) }}" class="btn btn-warning">
                        <i data-feather="file-text"></i> Draft Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Perhitungan Terbaru -->
{{-- <div class="row g-3">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Perhitungan SAW Terbaru</h6>
                <a href="{{ route('hasil-survei.index') }}" class="btn btn-link btn-sm p-0">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lokasi</th>
                                <th>Distrik</th>
                                <th>Surveyor</th>
                                <th>Nilai Preferensi</th>
                                <th>Ranking</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perhitunganTerbaru as $key => $survei)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                                        {{ $survei->lokasi->nama_jalan }}
                                    </td>
                                    <td>{{ $survei->lokasi->distrik }}</td>
                                    <td>{{ $survei->user->name }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format($survei->nilai_preferensi, 4) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $rank = \App\Models\HasilSurvei::whereNotNull('nilai_preferensi')
                                                ->where('nilai_preferensi', '>', $survei->nilai_preferensi)
                                                ->count() + 1;
                                        @endphp
                                        <span class="badge bg-{{ $rank <= 3 ? 'danger' : ($rank <= 5 ? 'warning' : 'info') }}">
                                            #{{ $rank }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($survei->rekomendasi)
                                            <span class="badge bg-{{ $survei->rekomendasi->status == 'disetujui' ? 'success' : ($survei->rekomendasi->status == 'ditolak' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($survei->rekomendasi->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('hasil-survei.show', $survei->id) }}" 
                                               class="btn btn-info btn-sm" title="Detail">
                                                <i data-feather="eye" style="width: 16px;"></i>
                                            </a>
                                            @if(!$survei->rekomendasi)
                                                <form action="{{ route('hasil-survei.generate-rekomendasi', $survei->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Generate Rekomendasi">
                                                        <i data-feather="thumbs-up" style="width: 16px;"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i data-feather="bar-chart-2" style="width: 48px; height: 48px; color: #ccc;"></i>
                                        <p class="mb-0">Belum ada perhitungan SAW</p>
                                        <small class="text-muted">Silakan lakukan perhitungan SAW pada data survei</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Rekomendasi Draft -->
@if($rekomendasi_draft > 0)
<div class="row g-3 mt-2">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm border border-warning">
            <div class="card-header bg-warning-soft">
                <h6 class="mb-0 text-warning">
                    <i data-feather="alert-triangle"></i> Draft Rekomendasi Perlu Diajukan
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Lokasi</th>
                                <th>Jumlah Lampu</th>
                                <th>Total Biaya</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Rekomendasi::where('status', 'draft')
                                ->with('hasilSurvei.lokasi')
                                ->latest()
                                ->take(5)
                                ->get() as $rekom)
                                <tr>
                                    <td>{{ $rekom->hasilSurvei->lokasi->nama_jalan }}</td>
                                    <td>{{ $rekom->jumlah_lampu }} unit</td>
                                    <td>Rp {{ number_format($rekom->total_biaya, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('rekomendasi.ajukan', $rekom->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i data-feather="send"></i> Ajukan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
.avatar.bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
.avatar.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.avatar.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.avatar.bg-info-soft { background: rgba(13, 202, 240, 0.1); }
</style>
@endpush