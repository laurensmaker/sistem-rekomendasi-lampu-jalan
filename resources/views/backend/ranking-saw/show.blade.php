{{-- resources/views/backend/ranking-saw/show.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Detail Ranking SAW</h3>
    <div>
        <a href="{{ route('ranking-saw.index') }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Lokasi -->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Lokasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="150">Ranking</th>
                        <td>
                            @if($position == 1)
                                <span class="badge bg-warning text-dark fs-6">
                                    <i data-feather="award"></i> #{{ $position }}
                                </span>
                            @elseif($position == 2)
                                <span class="badge bg-secondary fs-6">#{{ $position }}</span>
                            @elseif($position == 3)
                                <span class="badge bg-danger fs-6">#{{ $position }}</span>
                            @else
                                <span class="badge bg-light text-dark">#{{ $position }}</span>
                            @endif
                            <small class="text-muted">dari {{ $totalData }} data</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Nama Jalan</th>
                        <td>{{ $hasilSurvei->lokasi->nama_jalan }}</td>
                    </tr>
                    <tr>
                        <th>Distrik</th>
                        <td>{{ $hasilSurvei->lokasi->distrik }}</td>
                    </tr>
                    <tr>
                        <th>Surveyor</th>
                        <td>{{ $hasilSurvei->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Survei</th>
                        <td>{{ $hasilSurvei->tanggal_survei->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Preferensi</th>
                        <td>
                            <span class="fw-bold text-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                                {{ number_format($hasilSurvei->nilai_preferensi, 4) }}
                            </span>
                            <div class="progress mt-1" style="height: 8px; width: 200px;">
                                <div class="progress-bar bg-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $hasilSurvei->nilai_preferensi * 100 }}%;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Informasi Rekomendasi -->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Rekomendasi</h5>
            </div>
            <div class="card-body">
                @if($hasilSurvei->rekomendasi)
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">Status</th>
                            <td>
                                <span class="badge bg-{{ $hasilSurvei->rekomendasi->status == 'disetujui' ? 'success' : ($hasilSurvei->rekomendasi->status == 'ditolak' ? 'danger' : ($hasilSurvei->rekomendasi->status == 'diajukan' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($hasilSurvei->rekomendasi->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah Lampu</th>
                            <td><strong>{{ $hasilSurvei->rekomendasi->jumlah_lampu }} unit</strong></td>
                        </tr>
                        <tr>
                            <th>Jarak Antar Lampu</th>
                            <td>{{ number_format($hasilSurvei->rekomendasi->jarak_antar_lampu, 2) }} meter</td>
                        </tr>
                        <tr>
                            <th>Total Biaya</th>
                            <td><strong>Rp {{ number_format($hasilSurvei->rekomendasi->total_biaya, 0, ',', '.') }}</strong></td>
                        </tr>
                        @if($hasilSurvei->rekomendasi->catatan)
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $hasilSurvei->rekomendasi->catatan }}</td>
                            </tr>
                        @endif
                    </table>
                @else
                    <div class="alert alert-warning">
                        <i data-feather="alert-circle" class="me-1"></i>
                        Belum ada rekomendasi untuk lokasi ini
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Perhitungan SAW -->
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Detail Perhitungan SAW</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Atribut</th>
                                <th>Nilai</th>
                                <th>Max/Min</th>
                                <th>Normalisasi</th>
                                <th>Nilai Terbobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalNormalisasi = 0;
                                $totalTerbobot = 0;
                            @endphp
                            @foreach($hasilSurvei->rankingSaw as $index => $ranking)
                                @php
                                    $totalNormalisasi += $ranking->nilai_normalisasi;
                                    $totalTerbobot += $ranking->nilai_terbobot;
                                    $kriteria = $ranking->kriteria;
                                    
                                    // Ambil nilai max/min untuk kriteria ini
                                    $field = $kriteria->nama_kriteria;
                                    $allValues = \App\Models\HasilSurvei::pluck($field)->toArray();
                                    $max = max($allValues);
                                    $min = min($allValues);
                                @endphp
                                <tr>
                                    <td><strong>C{{ $index + 1 }}</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $kriteria->nama_kriteria)) }}</td>
                                    <td>{{ $kriteria->bobot }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $kriteria->atribut == 'benefit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($kriteria->atribut) }}
                                        </span>
                                    </td>
                                    <td class="text-center"><strong>{{ $hasilSurvei->{$field} }}</strong></td>
                                    <td class="text-center">
                                        @if($kriteria->atribut == 'benefit')
                                            Max: {{ number_format($max, 0) }}
                                        @else
                                            Min: {{ number_format($min, 0) }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ number_format($ranking->nilai_normalisasi, 4) }}</td>
                                    <td class="text-center"><strong>{{ number_format($ranking->nilai_terbobot, 4) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-success">
                            <tr>
                                <td colspan="6" class="text-end"><strong>Total</strong></td>
                                <td class="text-center"><strong>{{ number_format($totalNormalisasi, 4) }}</strong></td>
                                <td class="text-center">
                                    <strong class="text-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                                        {{ number_format($totalTerbobot, 4) }}
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Fisik Lokasi -->
    <div class="col-12 mt-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Data Fisik Lokasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Panjang Jalan</h6>
                            <h4>{{ number_format($hasilSurvei->panjang_jalan, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Lebar Jalan</h6>
                            <h4>{{ number_format($hasilSurvei->lebar_jalan, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Tinggi Tiang</h6>
                            <h4>{{ number_format($hasilSurvei->tinggi_tiang, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Jarak Lampu</h6>
                            <h4>
                                @if($hasilSurvei->rekomendasi)
                                    {{ number_format($hasilSurvei->rekomendasi->jarak_antar_lampu, 0) }} m
                                @else
                                    -
                                @endif
                            </h4>
                        </div>
                    </div>
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
</style>
@endpush