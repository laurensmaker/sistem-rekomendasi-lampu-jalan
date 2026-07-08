{{-- resources/views/backend/hasil-survei/show.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Detail Hasil Survei</h3>
    <div>
        @if(auth()->user()->isPetugasSurvei() && $hasilSurvei->user_id == auth()->id())
            <a href="{{ route('hasil-survei.edit', $hasilSurvei->id) }}" class="btn btn-warning btn-sm">
                <i data-feather="edit-2"></i> Edit
            </a>
        @endif
        @if(auth()->user()->isStafPerencana() && $hasilSurvei->nilai_preferensi === null)
            <form action="{{ route('hasil-survei.hitung-saw', $hasilSurvei->id) }}" 
                  method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    <i data-feather="bar-chart-2"></i> Hitung SAW
                </button>
            </form>
        @endif
        @if(auth()->user()->isStafPerencana() && $hasilSurvei->nilai_preferensi !== null && !$hasilSurvei->rekomendasi)
            <form action="{{ route('hasil-survei.generate-rekomendasi', $hasilSurvei->id) }}" 
                  method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">
                    <i data-feather="thumbs-up"></i> Generate Rekomendasi
                </button>
            </form>
        @endif
        <a href="{{ route('hasil-survei.index') }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Data Survei -->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Survei</h5>
                <span class="badge bg-{{ $hasilSurvei->nilai_preferensi !== null ? 'success' : 'warning' }}">
                    {{ $hasilSurvei->nilai_preferensi !== null ? 'Sudah Dihitung' : 'Belum Dihitung' }}
                </span>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td>#{{ $hasilSurvei->id }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                            {{ $hasilSurvei->lokasi->nama_jalan }}
                        </td>
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
                            @if($hasilSurvei->nilai_preferensi !== null)
                                <span class="fw-bold text-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                                    {{ number_format($hasilSurvei->nilai_preferensi, 4) }}
                                </span>
                                <div class="progress mt-1" style="height: 8px; width: 200px;">
                                    <div class="progress-bar bg-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $hasilSurvei->nilai_preferensi * 100 }}%;"></div>
                                </div>
                            @else
                                <span class="badge bg-warning">Belum dihitung</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status Rekomendasi</th>
                        <td>
                            @if($hasilSurvei->rekomendasi)
                                <span class="badge bg-{{ $hasilSurvei->rekomendasi->status == 'disetujui' ? 'success' : ($hasilSurvei->rekomendasi->status == 'ditolak' ? 'danger' : ($hasilSurvei->rekomendasi->status == 'diajukan' ? 'warning' : 'secondary')) }}">
                                    <i data-feather="{{ $hasilSurvei->rekomendasi->status == 'disetujui' ? 'check-circle' : ($hasilSurvei->rekomendasi->status == 'ditolak' ? 'x-circle' : 'file-text') }}" style="width: 14px;"></i>
                                    {{ ucfirst($hasilSurvei->rekomendasi->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $hasilSurvei->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $hasilSurvei->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Data Fisik -->
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Data Fisik Lokasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Lebar Jalan</h6>
                            <h4>{{ number_format($hasilSurvei->lebar_jalan, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Panjang Jalan</h6>
                            <h4>{{ number_format($hasilSurvei->panjang_jalan, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Tinggi Tiang</h6>
                            <h4>{{ number_format($hasilSurvei->tinggi_tiang, 0) }} m</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil SAW dan Rekomendasi -->
    <div class="col-lg-6">
        <!-- Hasil SAW -->
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hasil Perhitungan SAW</h5>
                @if(auth()->user()->isStafPerencana() && $hasilSurvei->nilai_preferensi === null)
                    <form action="{{ route('hasil-survei.hitung-saw', $hasilSurvei->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i data-feather="bar-chart-2"></i> Hitung SAW
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @if($hasilSurvei->nilai_preferensi !== null && $hasilSurvei->rankingSaw->isNotEmpty())
                    <div class="alert alert-success">
                        <i data-feather="check-circle" class="me-1"></i>
                        <strong>Nilai Preferensi:</strong> 
                        <span class="fw-bold text-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                            {{ number_format($hasilSurvei->nilai_preferensi, 4) }}
                        </span>
                    </div>
                    
                    <h6>Detail Normalisasi:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Bobot</th>
                                    <th>Atribut</th>
                                    <th>Nilai</th>
                                    <th>Normalisasi</th>
                                    <th>Terbobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilSurvei->rankingSaw as $ranking)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $ranking->kriteria->nama_kriteria)) }}</td>
                                        <td>{{ $ranking->kriteria->bobot }}%</td>
                                        <td>
                                            <span class="badge bg-{{ $ranking->kriteria->atribut == 'benefit' ? 'success' : 'danger' }}">
                                                {{ ucfirst($ranking->kriteria->atribut) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ $hasilSurvei->{$ranking->kriteria->nama_kriteria} }}</strong>
                                        </td>
                                        <td class="text-center">{{ number_format($ranking->nilai_normalisasi, 4) }}</td>
                                        <td class="text-center"><strong>{{ number_format($ranking->nilai_terbobot, 4) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-success">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total Preferensi</strong></td>
                                    <td class="text-center">
                                        <strong class="text-{{ $hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                                            {{ number_format($hasilSurvei->nilai_preferensi, 4) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i data-feather="alert-circle" class="me-1"></i>
                        Belum dilakukan perhitungan SAW
                    </div>
                @endif
            </div>
        </div>

        <!-- Rekomendasi -->
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Rekomendasi</h5>
                @if(auth()->user()->isStafPerencana() && $hasilSurvei->nilai_preferensi !== null && !$hasilSurvei->rekomendasi)
                    <form action="{{ route('hasil-survei.generate-rekomendasi', $hasilSurvei->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i data-feather="thumbs-up"></i> Generate
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @if($hasilSurvei->rekomendasi)
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-light rounded-10 p-3 text-center">
                                <h6 class="text-muted">Jumlah Lampu</h6>
                                <h3 class="text-primary">{{ $hasilSurvei->rekomendasi->jumlah_lampu }}</h3>
                                <small>Unit</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-10 p-3 text-center">
                                <h6 class="text-muted">Jarak Lampu</h6>
                                <h3>{{ number_format($hasilSurvei->rekomendasi->jarak_antar_lampu, 0) }}</h3>
                                <small>Meter</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-success-soft rounded-10 p-3 text-center">
                                <h6 class="text-muted">Total Biaya</h6>
                                <h3 class="text-success">Rp {{ number_format($hasilSurvei->rekomendasi->total_biaya, 0, ',', '.') }}</h3>
                                <small>Estimasi</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-{{ $hasilSurvei->rekomendasi->status == 'disetujui' ? 'success' : ($hasilSurvei->rekomendasi->status == 'ditolak' ? 'danger' : ($hasilSurvei->rekomendasi->status == 'diajukan' ? 'warning' : 'info')) }}">
                                <h6 class="mb-1">Status: 
                                    <strong>{{ ucfirst($hasilSurvei->rekomendasi->status) }}</strong>
                                </h6>
                                @if($hasilSurvei->rekomendasi->catatan)
                                    <p class="mb-0"><strong>Catatan:</strong> {{ $hasilSurvei->rekomendasi->catatan }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->isStafPerencana() && $hasilSurvei->rekomendasi->status == 'draft')
                        <div class="mt-3">
                            <form action="{{ route('rekomendasi.ajukan', $hasilSurvei->rekomendasi->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100">
                                    <i data-feather="send"></i> Ajukan Validasi
                                </button>
                            </form>
                        </div>
                    @endif

                    @if(auth()->user()->isKepalaBidang() && $hasilSurvei->rekomendasi->status == 'diajukan')
                        <div class="mt-3 d-flex gap-2">
                            <button type="button" class="btn btn-success flex-fill" data-bs-toggle="modal" data-bs-target="#setujuiModal">
                                <i data-feather="check"></i> Setujui
                            </button>
                            <button type="button" class="btn btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#tolakModal">
                                <i data-feather="x"></i> Tolak
                            </button>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('rekomendasi.cetak', $hasilSurvei->rekomendasi->id) }}" 
                           target="_blank" class="btn btn-info w-100">
                            <i data-feather="printer"></i> Cetak Rekomendasi
                        </a>
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i data-feather="info" class="me-1"></i>
                        Belum ada rekomendasi
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Dokumentasi -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Dokumentasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Gambar Lokasi</h6>
                        @if($hasilSurvei->dokumentasi && $hasilSurvei->dokumentasi->gambar)
                            <img src="{{ asset('storage/' . $hasilSurvei->dokumentasi->gambar) }}" 
                                 alt="Gambar" 
                                 class="img-fluid rounded-10 mb-2"
                                 style="max-height: 300px; width: 100%; object-fit: cover;">
                            <a href="{{ asset('storage/' . $hasilSurvei->dokumentasi->gambar) }}" 
                               target="_blank" class="btn btn-primary btn-sm">
                                <i data-feather="eye"></i> Lihat Gambar
                            </a>
                        @else
                            <p class="text-muted">Tidak ada gambar</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Foto Survei</h6>
                        @if($hasilSurvei->dokumentasi && $hasilSurvei->dokumentasi->foto_survei)
                            <img src="{{ asset('storage/' . $hasilSurvei->dokumentasi->foto_survei) }}" 
                                 alt="Foto Survei" 
                                 class="img-fluid rounded-10 mb-2"
                                 style="max-height: 300px; width: 100%; object-fit: cover;">
                            <a href="{{ asset('storage/' . $hasilSurvei->dokumentasi->foto_survei) }}" 
                               target="_blank" class="btn btn-primary btn-sm">
                                <i data-feather="eye"></i> Lihat Foto Survei
                            </a>
                        @else
                            <p class="text-muted">Tidak ada foto survei</p>
                        @endif
                    </div>
                    @if($hasilSurvei->dokumentasi && $hasilSurvei->dokumentasi->keterangan)
                        <div class="col-12 mt-3">
                            <h6>Keterangan Dokumentasi</h6>
                            <p>{{ $hasilSurvei->dokumentasi->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div class="modal fade" id="setujuiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rekomendasi.validasi', $hasilSurvei->rekomendasi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="disetujui">
                <div class="modal-header">
                    <h5 class="modal-title">Setujui Rekomendasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menyetujui rekomendasi untuk lokasi:</p>
                    <h6><strong>{{ $hasilSurvei->lokasi->nama_jalan }}</strong></h6>
                    <div class="row mt-3">
                        <div class="col-6">
                            <small class="text-muted">Jumlah Lampu</small>
                            <p><strong>{{ $hasilSurvei->rekomendasi->jumlah_lampu }} unit</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Total Biaya</small>
                            <p><strong>Rp {{ number_format($hasilSurvei->rekomendasi->total_biaya, 0, ',', '.') }}</strong></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="catatan_setujui" class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" id="catatan_setujui" class="form-control" rows="2"></textarea>
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
<div class="modal fade" id="tolakModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rekomendasi.validasi', $hasilSurvei->rekomendasi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="ditolak">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Rekomendasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak rekomendasi untuk lokasi:</p>
                    <h6><strong>{{ $hasilSurvei->lokasi->nama_jalan }}</strong></h6>
                    <div class="mb-3">
                        <label for="catatan_tolak" class="form-label">Catatan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan" id="catatan_tolak" class="form-control" rows="3" required></textarea>
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

@endsection

@push('styles')
<style>
.rounded-10 {
    border-radius: 10px;
}
.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
.bg-danger-soft { background: rgba(220, 53, 69, 0.1); }
</style>
@endpush