{{-- resources/views/backend/rekomendasi/index.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Hasil Rekomendasi Prioritas LPJU</h3>
    <div>
        <a href="{{ route('rekomendasi.export-excel') }}" class="btn btn-success btn-sm">
            <i data-feather="file-text"></i> Export Excel
        </a>
        <a href="{{ route('dashboard.' . auth()->user()->role) }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
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
                        <h6 class="text-muted mb-1">Total Lokasi</h6>
                        <h3 class="mb-0 fw-bold">{{ $rekomendasi->total() }}</h3>
                        <small class="text-muted">Yang direkomendasikan</small>
                    </div>
                    <div class="avatar bg-primary-soft rounded-10 p-3">
                        <i data-feather="map-pin" class="text-primary" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Total Lampu</h6>
                        <h3 class="mb-0 fw-bold text-primary">{{ number_format(\App\Models\Rekomendasi::sum('jumlah_lampu')) }}</h3>
                        <small class="text-muted">Unit yang dibutuhkan</small>
                    </div>
                    <div class="avatar bg-success-soft rounded-10 p-3">
                        <i data-feather="sun" class="text-success" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Total Anggaran</h6>
                        <h5 class="mb-0 fw-bold text-success">Rp {{ number_format(\App\Models\Rekomendasi::sum('total_biaya'), 0, ',', '.') }}</h5>
                        <small class="text-muted">Estimasi biaya</small>
                    </div>
                    <div class="avatar bg-warning-soft rounded-10 p-3">
                        <i data-feather="dollar-sign" class="text-warning" style="width: 28px; height: 28px;"></i>
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
                        <h6 class="text-muted mb-1">Rata-rata Nilai SAW</h6>
                        <h3 class="mb-0 fw-bold">
                            {{ number_format(\App\Models\HasilSurvei::whereNotNull('nilai_preferensi')->avg('nilai_preferensi'), 4) }}
                        </h3>
                        <small class="text-muted">Nilai preferensi</small>
                    </div>
                    <div class="avatar bg-info-soft rounded-10 p-3">
                        <i data-feather="bar-chart-2" class="text-info" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

        <!-- Filter dan Pencarian -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('rekomendasi.index') }}" method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="filter"></i> Filter
                    </button>
                    @if(request('status') || request('search'))
                        <a href="{{ route('rekomendasi.index') }}" class="btn btn-secondary btn-sm">
                            <i data-feather="x"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="col-md-6 text-end">
                <form action="{{ route('rekomendasi.index') }}" method="GET" class="d-flex gap-2 justify-content-end">
                    <input type="text" 
                           name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Cari lokasi..." 
                           value="{{ request('search') }}" 
                           style="width: 200px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="search"></i> Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Ranking Prioritas -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">Rank</th>
                        <th>Lokasi</th>
                        <th>Distrik</th>
                        <th>Nilai SAW</th>
                        <th>Panjang Jalan</th>
                        <th>Jarak Lampu</th>
                        <th>Jumlah Lampu</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekomendasi as $key => $item)
                        @php
                            $rank = $rekomendasi->firstItem() + $key;
                            $nilaiPreferensi = $item->hasilSurvei->nilai_preferensi ?? 0;
                            $statusColors = [
                                'draft' => 'secondary',
                                'diajukan' => 'warning',
                                'disetujui' => 'success',
                                'ditolak' => 'danger'
                            ];
                            $statusIcons = [
                                'draft' => 'file-text',
                                'diajukan' => 'send',
                                'disetujui' => 'check-circle',
                                'ditolak' => 'x-circle'
                            ];
                        @endphp
                        <tr class="{{ $rank <= 3 ? 'table-success' : ($rank <= 5 ? 'table-info' : '') }}">
                            <td>
                                @if($rank == 1)
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i data-feather="award"></i> #1
                                    </span>
                                @elseif($rank == 2)
                                    <span class="badge bg-secondary fs-6">#2</span>
                                @elseif($rank == 3)
                                    <span class="badge bg-danger fs-6">#3</span>
                                @else
                                    <span class="badge bg-light text-dark">#{{ $rank }}</span>
                                @endif
                            </td>
                            <td>
                                <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                                <strong>{{ $item->hasilSurvei->lokasi->nama_jalan }}</strong>
                            </td>
                            <td>{{ $item->hasilSurvei->lokasi->distrik }}</td>
                            <td>
                                <span class="fw-bold text-{{ $nilaiPreferensi >= 0.8 ? 'success' : ($nilaiPreferensi >= 0.6 ? 'warning' : 'danger') }}">
                                    {{ number_format($nilaiPreferensi, 4) }}
                                </span>
                                <div class="progress mt-1" style="height: 4px; width: 80px;">
                                    <div class="progress-bar bg-{{ $nilaiPreferensi >= 0.8 ? 'success' : ($nilaiPreferensi >= 0.6 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $nilaiPreferensi * 100 }}%;"></div>
                                </div>
                            </td>
                            <td>{{ number_format($item->hasilSurvei->panjang_jalan, 0) }} m</td>
                            <td>{{ number_format($item->jarak_antar_lampu, 0) }} m</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $item->jumlah_lampu }}</span>
                                <small class="text-muted">unit</small>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                    <i data-feather="{{ $statusIcons[$item->status] ?? 'file-text' }}" style="width: 12px;"></i>
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('rekomendasi.show', $item->id) }}" 
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i data-feather="eye" style="width: 16px;"></i>
                                    </a>
                                    @if(auth()->user()->isStafPerencana() && $item->status == 'draft')
                                        <form action="{{ route('rekomendasi.ajukan', $item->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" 
                                                    title="Ajukan Validasi"
                                                    onclick="return confirm('Yakin ingin mengajukan rekomendasi ini?')">
                                                <i data-feather="send" style="width: 16px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->isKepalaBidang() && $item->status == 'diajukan')
                                        <button type="button" class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#setujuiModal{{ $item->id }}"
                                                title="Setujui">
                                            <i data-feather="check" style="width: 16px;"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#tolakModal{{ $item->id }}"
                                                title="Tolak">
                                            <i data-feather="x" style="width: 16px;"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('rekomendasi.cetak', $item->id) }}" 
                                       target="_blank" class="btn btn-secondary btn-sm" title="Cetak">
                                        <i data-feather="printer" style="width: 16px;"></i>
                                    </a>
                                </div>

                                <!-- Modal Setujui -->
                                <div class="modal fade" id="setujuiModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('rekomendasi.validasi', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="disetujui">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Setujui Rekomendasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Anda akan menyetujui rekomendasi untuk lokasi:</p>
                                                    <h6><strong>{{ $item->hasilSurvei->lokasi->nama_jalan }}</strong></h6>
                                                    <div class="row mt-3">
                                                        <div class="col-6">
                                                            <small class="text-muted">Nilai SAW</small>
                                                            <p><strong>{{ number_format($nilaiPreferensi, 4) }}</strong></p>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Jumlah Lampu</small>
                                                            <p><strong>{{ $item->jumlah_lampu }} unit</strong></p>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted">Total Biaya</small>
                                                            <p><strong>Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</strong></p>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="catatan_setujui{{ $item->id }}" class="form-label">Catatan (Opsional)</label>
                                                        <textarea name="catatan" id="catatan_setujui{{ $item->id }}" class="form-control" rows="2"></textarea>
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
                                <div class="modal fade" id="tolakModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('rekomendasi.validasi', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ditolak">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Rekomendasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Anda akan menolak rekomendasi untuk lokasi:</p>
                                                    <h6><strong>{{ $item->hasilSurvei->lokasi->nama_jalan }}</strong></h6>
                                                    <div class="mb-3">
                                                        <label for="catatan_tolak{{ $item->id }}" class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="catatan" id="catatan_tolak{{ $item->id }}" class="form-control" rows="3" required></textarea>
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
                            <td colspan="10" class="text-center py-4">
                                <i data-feather="thumbs-up" style="width: 48px; height: 48px; color: #ccc;"></i>
                                <p class="mb-0">Tidak ada data rekomendasi</p>
                                <small class="text-muted">Silakan generate rekomendasi dari data survei</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Informasi Prioritas -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="alert alert-info">
                    <i data-feather="info" class="me-1"></i>
                    <strong>Keterangan Prioritas:</strong>
                    <ul class="mb-0 mt-1">
                        <li>
                            <span class="badge bg-warning text-dark">🏆 #1</span> - Prioritas Tertinggi
                        </li>
                        <li>
                            <span class="badge bg-secondary">#2</span> - Prioritas Kedua
                        </li>
                        <li>
                            <span class="badge bg-danger">#3</span> - Prioritas Ketiga
                        </li>
                        <li>
                            <span class="badge bg-light text-dark">#4 dst</span> - Prioritas Berikutnya
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

        <div class="d-flex justify-content-end mt-3">
            {{ $rekomendasi->appends(request()->all())->links() }}
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.rounded-10 {
    border-radius: 10px;
}
.avatar.bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
.avatar.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.avatar.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.avatar.bg-info-soft { background: rgba(13, 202, 240, 0.1); }
.table-success { background-color: #d4edda !important; }
.table-info { background-color: #d1ecf1 !important; }
</style>
@endpush