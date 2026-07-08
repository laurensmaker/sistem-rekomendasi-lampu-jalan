{{-- resources/views/backend/dashboard/petugas.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Dashboard Petugas Survei</h3>
    <div>
        <a href="{{ route('hasil-survei.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus"></i> Input Survei Baru
        </a>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Survei</h6>
                        <h3 class="mb-0 fw-bold">{{ $total_survei }}</h3>
                        <small class="text-muted">Telah dilakukan</small>
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
                        <h6 class="text-muted mb-1">Total Lokasi</h6>
                        <h3 class="mb-0 fw-bold">{{ $total_lokasi }}</h3>
                        <small class="text-muted">Tersedia</small>
                    </div>
                    <div class="avatar bg-success-soft rounded-10 p-3">
                        <i data-feather="map-pin" class="text-success" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Survei Hari Ini</h6>
                        <h3 class="mb-0 fw-bold">
                            {{ \App\Models\HasilSurvei::where('user_id', auth()->id())
                                ->whereDate('created_at', today())
                                ->count() }}
                        </h3>
                        <small class="text-muted">Hari ini</small>
                    </div>
                    <div class="avatar bg-warning-soft rounded-10 p-3">
                        <i data-feather="calendar" class="text-warning" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Rekomendasi</h6>
                        <h3 class="mb-0 fw-bold">
                            {{ \App\Models\Rekomendasi::whereHas('hasilSurvei', function($q) {
                                $q->where('user_id', auth()->id());
                            })->count() }}
                        </h3>
                        <small class="text-muted">Dihasilkan</small>
                    </div>
                    <div class="avatar bg-info-soft rounded-10 p-3">
                        <i data-feather="thumbs-up" class="text-info" style="width: 28px; height: 28px;"></i>
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
                    <a href="{{ route('hasil-survei.create') }}" class="btn btn-primary">
                        <i data-feather="plus"></i> Input Survei Baru
                    </a>
                    <a href="{{ route('hasil-survei.index') }}" class="btn btn-outline-primary">
                        <i data-feather="list"></i> Lihat Semua Survei
                    </a>
                    <a href="{{ route('lokasi.index') }}" class="btn btn-outline-success">
                        <i data-feather="map-pin"></i> Cek Lokasi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Survei Terbaru -->
{{-- <div class="row g-3">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Survei Terbaru Saya</h6>
                <a href="{{ route('hasil-survei.index') }}" class="btn btn-link btn-sm p-0">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Kondisi Jalan</th>
                                <th>Prioritas</th>
                                <th>Status SAW</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surveiTerbaru as $key => $survei)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                                        {{ $survei->lokasi->nama_jalan }}
                                    </td>
                                    <td>{{ $survei->tanggal_survei->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $survei->kondisi_jalan == 'baik' ? 'success' : ($survei->kondisi_jalan == 'sedang' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($survei->kondisi_jalan) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $survei->prioritas == 'tinggi' ? 'danger' : ($survei->prioritas == 'sedang' ? 'warning' : 'info') }}">
                                            {{ ucfirst($survei->prioritas) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($survei->nilai_preferensi !== null)
                                            <span class="badge bg-success">
                                                <i data-feather="check-circle" style="width: 12px;"></i>
                                                Terhitung
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i data-feather="clock" style="width: 12px;"></i>
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('hasil-survei.show', $survei->id) }}" 
                                               class="btn btn-info btn-sm" title="Detail">
                                                <i data-feather="eye" style="width: 16px;"></i>
                                            </a>
                                            <a href="{{ route('hasil-survei.edit', $survei->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i data-feather="edit-2" style="width: 16px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i data-feather="clipboard" style="width: 48px; height: 48px; color: #ccc;"></i>
                                        <p class="mb-0">Belum ada data survei</p>
                                        <small class="text-muted">Silakan input survei baru</small>
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

@endsection

@push('styles')
<style>
.avatar.bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
.avatar.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.avatar.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.avatar.bg-info-soft { background: rgba(13, 202, 240, 0.1); }
</style>
@endpush