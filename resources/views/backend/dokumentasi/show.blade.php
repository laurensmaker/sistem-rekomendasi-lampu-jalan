{{-- resources/views/backend/dokumentasi/show.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Detail Dokumentasi</h3>
    <div>
        <a href="{{ route('dokumentasi.edit', $dokumentasi->id) }}" class="btn btn-warning btn-sm">
            <i data-feather="edit-2"></i> Edit
        </a>
        <a href="{{ route('dokumentasi.index') }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Dokumentasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>#{{ $dokumentasi->id }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                            {{ $dokumentasi->lokasi->nama_jalan }}
                        </td>
                    </tr>
                    <tr>
                        <th>Distrik</th>
                        <td>{{ $dokumentasi->lokasi->distrik }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $dokumentasi->keterangan }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $dokumentasi->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $dokumentasi->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($dokumentasi->hasilSurvei->count() > 0)
                                <span class="badge bg-success">
                                    <i data-feather="check-circle" style="width: 12px;"></i>
                                    Sudah Disurvei ({{ $dokumentasi->hasilSurvei->count() }} survei)
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i data-feather="clock" style="width: 12px;"></i>
                                    Belum Disurvei
                                </span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Gambar</h5>
            </div>
            <div class="card-body text-center">
                @if($dokumentasi->gambar)
                    <img src="{{ asset('storage/' . $dokumentasi->gambar) }}" 
                         alt="Gambar" 
                         class="img-fluid rounded-10 mb-2"
                         style="max-height: 300px; width: 100%; object-fit: cover;">
                    <a href="{{ asset('storage/' . $dokumentasi->gambar) }}" 
                       target="_blank" 
                       class="btn btn-primary btn-sm w-100">
                        <i data-feather="eye"></i> Lihat Gambar
                    </a>
                @else
                    <div class="text-muted py-4">
                        <i data-feather="image" style="width: 48px; height: 48px;"></i>
                        <p class="mb-0">Tidak ada gambar</p>
                    </div>
                @endif
            </div>
        </div>

        @if($dokumentasi->foto_survei)
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Foto Survei</h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $dokumentasi->foto_survei) }}" 
                     alt="Foto Survei" 
                     class="img-fluid rounded-10 mb-2"
                     style="max-height: 300px; width: 100%; object-fit: cover;">
                <a href="{{ asset('storage/' . $dokumentasi->foto_survei) }}" 
                   target="_blank" 
                   class="btn btn-primary btn-sm w-100">
                    <i data-feather="eye"></i> Lihat Foto Survei
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@if($dokumentasi->hasilSurvei->count() > 0)
<div class="row mt-3">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Data Hasil Survei Terkait</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Surveyor</th>
                                <th>Tanggal</th>
                                <th>Kondisi Jalan</th>
                                <th>Prioritas</th>
                                <th>Nilai Preferensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dokumentasi->hasilSurvei as $key => $survei)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $survei->user->name }}</td>
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
                                                {{ number_format($survei->nilai_preferensi, 4) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Belum dihitung</span>
                                        @endif
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
.rounded-10 {
    border-radius: 10px;
}
</style>
@endpush