{{-- resources/views/backend/hasil-survei/index.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Data Hasil Survei</h3>
    <div>
        @if(auth()->user()->isPetugasSurvei())
        <a href="{{ route('hasil-survei.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus"></i> Tambah Survei
        </a>
        @endif
        <a href="{{ route('dashboard.' . auth()->user()->role) }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Survei</h6>
                <h3 class="mb-0">{{ $hasilSurvei->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Sudah Diperhitungkan</h6>
                <h3 class="mb-0">{{ $hasilSurvei->whereNotNull('nilai_preferensi')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Sudah Direkomendasikan</h6>
                <h3 class="mb-0">{{ $totalDenganRekomendasi  }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i data-feather="check-circle" class="me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i data-feather="alert-circle" class="me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lokasi</th>
                        <th>Distrik</th>
                        <th>Tanggal Survei</th>
                        <th>Surveyor</th>
                        <th>Nilai Preferensi</th>
                        <th>Rekomendasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasilSurvei as $key => $item)
                        <tr>
                            <td>{{ $hasilSurvei->firstItem() + $key }}</td>
                            <td>
                                <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                                {{ $item->lokasi->nama_jalan }}
                            </td>
                            <td>{{ $item->lokasi->distrik }}</td>
                            <td>{{ $item->tanggal_survei->format('d/m/Y') }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>
                                @if($item->nilai_preferensi !== null)
                                    <span class="badge bg-success">
                                        {{ number_format($item->nilai_preferensi, 4) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning">Belum dihitung</span>
                                @endif
                            </td>
                            <td>
                                @if($item->rekomendasi)
                                    <span class="badge bg-{{ $item->rekomendasi->status == 'disetujui' ? 'success' : ($item->rekomendasi->status == 'ditolak' ? 'danger' : ($item->rekomendasi->status == 'diajukan' ? 'warning' : 'info')) }}">
                                        {{ ucfirst($item->rekomendasi->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('hasil-survei.show', $item->id) }}" 
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i data-feather="eye" style="width: 16px;"></i>
                                    </a>
                                    @if(auth()->user()->isPetugasSurvei() && $item->user_id == auth()->id())
                                        <a href="{{ route('hasil-survei.edit', $item->id) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i data-feather="edit-2" style="width: 16px;"></i>
                                        </a>
                                        <form action="{{ route('hasil-survei.destroy', $item->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Yakin ingin menghapus?')">
                                                <i data-feather="trash-2" style="width: 16px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->isStafPerencana() && $item->nilai_preferensi === null)
                                        <form action="{{ route('hasil-survei.hitung-saw', $item->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" title="Hitung SAW">
                                                <i data-feather="bar-chart-2" style="width: 16px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->isStafPerencana() && $item->nilai_preferensi !== null && !$item->rekomendasi)
                                        <form action="{{ route('hasil-survei.generate-rekomendasi', $item->id) }}" 
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
                                <i data-feather="clipboard" style="width: 48px; height: 48px; color: #ccc;"></i>
                                <p class="mb-0">Tidak ada data hasil survei</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $hasilSurvei->links() }}
        </div>
    </div>
</div>

@endsection