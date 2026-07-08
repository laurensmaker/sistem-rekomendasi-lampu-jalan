{{-- resources/views/backend/dashboard/admin.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Dashboard Admin</h3>
    <div>
        <span class="badge bg-primary">{{ now()->format('d F Y') }}</span>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm hover-shadow transition">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total User</h6>
                        <h3 class="mb-0 fw-bold">{{ \App\Models\User::count() }}</h3>
                        <small class="text-muted">
                            <i data-feather="users" class="me-1" style="width: 14px;"></i>
                            Terdaftar
                        </small>
                    </div>
                    <div class="avatar bg-primary-soft rounded-10 p-3">
                        <i data-feather="users" class="text-primary" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm hover-shadow transition">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Lokasi</h6>
                        <h3 class="mb-0 fw-bold">{{ \App\Models\Lokasi::count() }}</h3>
                        <small class="text-muted">
                            <i data-feather="map-pin" class="me-1" style="width: 14px;"></i>
                            Tersedia
                        </small>
                    </div>
                    <div class="avatar bg-success-soft rounded-10 p-3">
                        <i data-feather="map-pin" class="text-success" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm hover-shadow transition">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Survei</h6>
                        <h3 class="mb-0 fw-bold">{{ \App\Models\HasilSurvei::count() }}</h3>
                        <small class="text-muted">
                            <i data-feather="clipboard" class="me-1" style="width: 14px;"></i>
                            Tercatat
                        </small>
                    </div>
                    <div class="avatar bg-warning-soft rounded-10 p-3">
                        <i data-feather="clipboard" class="text-warning" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm hover-shadow transition">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Rekomendasi</h6>
                        <h3 class="mb-0 fw-bold">{{ \App\Models\Rekomendasi::count() }}</h3>
                        <small class="text-muted">
                            <i data-feather="thumbs-up" class="me-1" style="width: 14px;"></i>
                            Dihasilkan
                        </small>
                    </div>
                    <div class="avatar bg-info-soft rounded-10 p-3">
                        <i data-feather="thumbs-up" class="text-info" style="width: 28px; height: 28px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Detail -->
<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Distribusi Role User</h6>
                @php
                    $roles = \App\Models\User::select('role', \DB::raw('count(*) as total'))
                        ->groupBy('role')
                        ->get();
                    $colors = ['admin' => 'danger', 'petugas_survei' => 'info', 'staf_perencana' => 'warning', 'kepala_bidang' => 'success'];
                @endphp
                @foreach($roles as $role)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst(str_replace('_', ' ', $role->role)) }}</span>
                            <span>{{ $role->total }} orang</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $colors[$role->role] ?? 'primary' }}" 
                                 style="width: {{ $role->total / \App\Models\User::count() * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Status Rekomendasi</h6>
                @php
                    $statuses = \App\Models\Rekomendasi::select('status', \DB::raw('count(*) as total'))
                        ->groupBy('status')
                        ->get();
                    $statusColors = ['draft' => 'secondary', 'diajukan' => 'warning', 'disetujui' => 'success', 'ditolak' => 'danger'];
                    $totalRekomendasi = \App\Models\Rekomendasi::count() ?: 1;
                @endphp
                @foreach($statuses as $status)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($status->status) }}</span>
                            <span>{{ $status->total }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $statusColors[$status->status] ?? 'primary' }}" 
                                 style="width: {{ $status->total / $totalRekomendasi * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Aktivitas Terakhir</h6>
                <div class="timeline">
                    @php
                        $latestSurvei = \App\Models\HasilSurvei::with('user')->latest()->take(3)->get();
                    @endphp
                    @foreach($latestSurvei as $survei)
                        <div class="timeline-item d-flex mb-3">
                            <div class="timeline-icon me-3">
                                <div class="avatar bg-info-soft rounded-circle p-2">
                                    <i data-feather="clipboard" class="text-info" style="width: 16px;"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0"><strong>{{ $survei->user->name }}</strong> melakukan survei</p>
                                <small class="text-muted">{{ $survei->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                    @php
                        $latestRekomendasi = \App\Models\Rekomendasi::with('hasilSurvei.lokasi')->latest()->take(3)->get();
                    @endphp
                    @foreach($latestRekomendasi as $rekom)
                        <div class="timeline-item d-flex mb-3">
                            <div class="timeline-icon me-3">
                                <div class="avatar bg-success-soft rounded-circle p-2">
                                    <i data-feather="thumbs-up" class="text-success" style="width: 16px;"></i>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0">Rekomendasi untuk <strong>{{ $rekom->hasilSurvei->lokasi->nama_jalan }}</strong></p>
                                <small class="text-muted">Status: {{ ucfirst($rekom->status) }} • {{ $rekom->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data Tables -->
<div class="row g-3">
    <div class="col-xl-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Lokasi Terbaru</h6>
                <a href="{{ route('lokasi.index') }}" class="btn btn-link btn-sm p-0">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nama Jalan</th>
                                <th>Distrik</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Lokasi::latest()->take(5)->get() as $lokasi)
                                <tr>
                                    <td>{{ $lokasi->nama_jalan }}</td>
                                    <td>{{ $lokasi->distrik }}</td>
                                    <td>{{ $lokasi->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Kriteria SAW</h6>
                <a href="{{ route('kriteria.index') }}" class="btn btn-link btn-sm p-0">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nama Kriteria</th>
                                <th>Bobot</th>
                                <th>Atribut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Kriteria::all() as $kriteria)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $kriteria->nama_kriteria)) }}</td>
                                    <td>{{ $kriteria->bobot }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $kriteria->atribut == 'benefit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($kriteria->atribut) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.transition {
    transition: all 0.3s ease;
}

.avatar.bg-primary-soft { background: rgba(13, 110, 253, 0.1); }
.avatar.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.avatar.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.avatar.bg-info-soft { background: rgba(13, 202, 240, 0.1); }

.timeline-item:not(:last-child) {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
}
</style>
@endpush