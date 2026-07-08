{{-- resources/views/backend/dashboard/kepala.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Dashboard Kepala Bidang</h3>
    <div>
        <span class="badge bg-{{ $perluValidasi > 0 ? 'danger' : 'success' }} fs-6">
            <i data-feather="bell" style="width: 16px;"></i>
            {{ $perluValidasi }} Perlu Validasi
        </span>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    {{-- <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Rekomendasi</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalRekomendasi ?? 0 }}</h3>
                        <small class="text-muted">Semua data</small>
                    </div>
                    <div class="avatar bg-primary-soft rounded-10 p-3">
                        <i data-feather="thumbs-up" class="text-primary" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Perlu Validasi</h6>
                        <h3 class="mb-0 fw-bold text-warning">{{ $perluValidasi ?? 0 }}</h3>
                        <small class="text-muted">Menunggu persetujuan</small>
                    </div>
                    <div class="avatar bg-warning-soft rounded-10 p-3">
                        <i data-feather="clock" class="text-warning" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Disetujui</h6>
                        <h3 class="mb-0 fw-bold text-success">{{ $disetujui ?? 0 }}</h3>
                        <small class="text-muted">Telah disetujui</small>
                    </div>
                    <div class="avatar bg-success-soft rounded-10 p-3">
                        <i data-feather="check-circle" class="text-success" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Ditolak</h6>
                        <h3 class="mb-0 fw-bold text-danger">{{ $ditolak ?? 0 }}</h3>
                        <small class="text-muted">Tidak disetujui</small>
                    </div>
                    <div class="avatar bg-danger-soft rounded-10 p-3">
                        <i data-feather="x-circle" class="text-danger" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Ringkasan Rekomendasi -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Ringkasan Rekomendasi</h6>
                <div class="row">
                    <div class="col-md-6">
                        @php
                            $totalRekomendasi = $totalRekomendasi ?? 0;
                            $disetujui = $disetujui ?? 0;
                            $perluValidasi = $perluValidasi ?? 0;
                            $ditolak = $ditolak ?? 0;
                            $draft = \App\Models\Rekomendasi::where('status', 'draft')->count();
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Disetujui</span>
                                <span>{{ $disetujui }} ({{ $totalRekomendasi > 0 ? round($disetujui / $totalRekomendasi * 100) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-success" style="width: {{ $totalRekomendasi > 0 ? $disetujui / $totalRekomendasi * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Perlu Validasi</span>
                                <span>{{ $perluValidasi }} ({{ $totalRekomendasi > 0 ? round($perluValidasi / $totalRekomendasi * 100) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-warning" style="width: {{ $totalRekomendasi > 0 ? $perluValidasi / $totalRekomendasi * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Ditolak</span>
                                <span>{{ $ditolak }} ({{ $totalRekomendasi > 0 ? round($ditolak / $totalRekomendasi * 100) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-danger" style="width: {{ $totalRekomendasi > 0 ? $ditolak / $totalRekomendasi * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- <div class="mb-0">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Draft</span>
                                <span>{{ $draft }} ({{ $totalRekomendasi > 0 ? round($draft / $totalRekomendasi * 100) : 0 }}%)</span>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-secondary" style="width: {{ $totalRekomendasi > 0 ? $draft / $totalRekomendasi * 100 : 0 }}%"></div>
                            </div>
                        </div> --}}
                    </div>
                    {{-- <div class="col-md-6 text-center">
                        @php
                            $totalLampu = \App\Models\Rekomendasi::sum('jumlah_lampu');
                            $totalBiaya = \App\Models\Rekomendasi::sum('total_biaya');
                        @endphp
                        <div class="bg-light rounded-10 p-3 mb-2">
                            <h5 class="text-muted">Total Kebutuhan Lampu</h5>
                            <h2 class="fw-bold text-primary">{{ number_format($totalLampu) }} Unit</h2>
                        </div>
                        <div class="bg-light rounded-10 p-3">
                            <h5 class="text-muted">Total Anggaran</h5>
                            <h3 class="fw-bold text-success">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h3>
                        </div>
                    </div> --}}
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
                    <a href="{{ route('rekomendasi.index', ['status' => 'diajukan']) }}" class="btn btn-warning">
                        <i data-feather="clock"></i> Validasi Rekomendasi
                    </a>
                    <a href="{{ route('rekomendasi.index') }}" class="btn btn-primary">
                        <i data-feather="list"></i> Semua Rekomendasi
                    </a>
                    <a href="{{ route('rekomendasi.index', ['status' => 'disetujui']) }}" class="btn btn-success">
                        <i data-feather="check-circle"></i> Rekomendasi Disetujui
                    </a>
                    <a href="{{ route('rekomendasi.index', ['status' => 'ditolak']) }}" class="btn btn-danger">
                        <i data-feather="x-circle"></i> Rekomendasi Ditolak
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Rekomendasi yang Perlu Validasi (Status: Diajukan) -->
<div class="row g-3">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-warning-soft">
                <h6 class="mb-0">
                    <i data-feather="clock" class="me-1 text-warning"></i>
                    Rekomendasi Perlu Validasi ({{ $rekomendasiTerbaru->count() }})
                </h6>
                <a href="{{ route('rekomendasi.index', ['status' => 'diajukan']) }}" class="btn btn-link btn-sm p-0">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if(isset($rekomendasiTerbaru) && $rekomendasiTerbaru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lokasi</th>
                                <th>Distrik</th>
                                <th>Surveyor</th>
                                <th>Jumlah Lampu</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekomendasiTerbaru as $key => $rekom)
                                <tr class="table-warning">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                                        {{ $rekom->hasilSurvei->lokasi->nama_jalan ?? 'Tidak Diketahui' }}
                                    </td>
                                    <td>{{ $rekom->hasilSurvei->lokasi->distrik ?? '-' }}</td>
                                    <td>{{ $rekom->hasilSurvei->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $rekom->jumlah_lampu }}</span>
                                        <small class="text-muted">unit</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            Rp {{ number_format($rekom->total_biaya, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i data-feather="clock" style="width: 12px;"></i>
                                            {{ ucfirst($rekom->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('rekomendasi.show', $rekom->id) }}" 
                                               class="btn btn-info btn-sm" title="Detail">
                                                <i data-feather="eye" style="width: 16px;"></i>
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#setujuiModal{{ $rekom->id }}"
                                                    title="Setujui">
                                                <i data-feather="check" style="width: 16px;"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#tolakModal{{ $rekom->id }}"
                                                    title="Tolak">
                                                <i data-feather="x" style="width: 16px;"></i>
                                            </button>
                                            <a href="{{ route('rekomendasi.cetak', $rekom->id) }}" 
                                               target="_blank" class="btn btn-secondary btn-sm" title="Cetak">
                                                <i data-feather="printer" style="width: 16px;"></i>
                                            </a>
                                        </div>

                                        <!-- Modal Setujui -->
                                        <div class="modal fade" id="setujuiModal{{ $rekom->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('rekomendasi.validasi', $rekom->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="disetujui">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Setujui Rekomendasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Anda akan menyetujui rekomendasi untuk lokasi:</p>
                                                            <h6><strong>{{ $rekom->hasilSurvei->lokasi->nama_jalan ?? 'Tidak Diketahui' }}</strong></h6>
                                                            <div class="row mt-3">
                                                                <div class="col-6">
                                                                    <small class="text-muted">Jumlah Lampu</small>
                                                                    <p><strong>{{ $rekom->jumlah_lampu }} unit</strong></p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted">Total Biaya</small>
                                                                    <p><strong>Rp {{ number_format($rekom->total_biaya, 0, ',', '.') }}</strong></p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="catatan_setujui{{ $rekom->id }}" class="form-label">Catatan (Opsional)</label>
                                                                <textarea name="catatan" id="catatan_setujui{{ $rekom->id }}" class="form-control" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i data-feather="check"></i> Setujui
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Tolak -->
                                        <div class="modal fade" id="tolakModal{{ $rekom->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('rekomendasi.validasi', $rekom->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="ditolak">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Rekomendasi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Anda akan menolak rekomendasi untuk lokasi:</p>
                                                            <h6><strong>{{ $rekom->hasilSurvei->lokasi->nama_jalan ?? 'Tidak Diketahui' }}</strong></h6>
                                                            <div class="mb-3">
                                                                <label for="catatan_tolak{{ $rekom->id }}" class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                                                                <textarea name="catatan" id="catatan_tolak{{ $rekom->id }}" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i data-feather="x"></i> Tolak
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i data-feather="check-circle" style="width: 48px; height: 48px; color: #28a745;"></i>
                                        <h5 class="mt-2 text-success">Tidak Ada Rekomendasi Perlu Validasi</h5>
                                        <p class="text-muted">Semua rekomendasi sudah divalidasi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i data-feather="check-circle" style="width: 48px; height: 48px; color: #28a745;"></i>
                    <h5 class="mt-2 text-success">Tidak Ada Rekomendasi Perlu Validasi</h5>
                    <p class="text-muted">Semua rekomendasi sudah divalidasi</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.avatar.bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
.avatar.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.avatar.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.avatar.bg-danger-soft { background: rgba(220, 53, 69, 0.1); }
.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.rounded-10 { border-radius: 10px; }
.table-warning {
    background-color: #fff3cd !important;
}
</style>
@endpush