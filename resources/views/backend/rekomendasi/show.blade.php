{{-- resources/views/backend/rekomendasi/show.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Detail Rekomendasi</h3>
    <div>
        <a href="{{ route('rekomendasi.cetak', $rekomendasi->id) }}" 
           target="_blank" class="btn btn-secondary btn-sm">
            <i data-feather="printer"></i> Cetak
        </a>
        <a href="{{ route('rekomendasi.index') }}" class="btn btn-secondary btn-sm">
            <i data-feather="arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Informasi Rekomendasi -->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informasi Rekomendasi</h5>
                <span class="badge bg-{{ $rekomendasi->status == 'disetujui' ? 'success' : ($rekomendasi->status == 'ditolak' ? 'danger' : ($rekomendasi->status == 'diajukan' ? 'warning' : 'secondary')) }} fs-6">
                    <i data-feather="{{ $rekomendasi->status == 'disetujui' ? 'check-circle' : ($rekomendasi->status == 'ditolak' ? 'x-circle' : ($rekomendasi->status == 'diajukan' ? 'send' : 'file-text')) }}" style="width: 16px;"></i>
                    {{ ucfirst($rekomendasi->status) }}
                </span>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="150">ID</th>
                        <td>#{{ $rekomendasi->id }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                            {{ $rekomendasi->hasilSurvei->lokasi->nama_jalan }}
                        </td>
                    </tr>
                    <tr>
                        <th>Distrik</th>
                        <td>{{ $rekomendasi->hasilSurvei->lokasi->distrik }}</td>
                    </tr>
                    <tr>
                        <th>Surveyor</th>
                        <td>{{ $rekomendasi->hasilSurvei->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Survei</th>
                        <td>{{ $rekomendasi->hasilSurvei->tanggal_survei->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Preferensi</th>
                        <td>
                            <span class="fw-bold text-{{ $rekomendasi->hasilSurvei->nilai_preferensi >= 0.8 ? 'success' : ($rekomendasi->hasilSurvei->nilai_preferensi >= 0.6 ? 'warning' : 'danger') }}">
                                {{ number_format($rekomendasi->hasilSurvei->nilai_preferensi, 4) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $rekomendasi->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $rekomendasi->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @if($rekomendasi->tanggal_disetujui)
                        <tr>
                            <th>Tanggal Disetujui</th>
                            <td>{{ $rekomendasi->tanggal_disetujui->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @endif
                    @if($rekomendasi->catatan)
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $rekomendasi->catatan }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Detail Perhitungan -->
    <div class="col-lg-6">
        <div class="card bg-white border-0 rounded-10 shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Perhitungan LPJU</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Panjang Jalan</h6>
                            <h4>{{ number_format($rekomendasi->hasilSurvei->panjang_jalan, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Lebar Jalan</h6>
                            <h4>{{ number_format($rekomendasi->hasilSurvei->lebar_jalan, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Tinggi Tiang</h6>
                            <h4>{{ number_format($rekomendasi->hasilSurvei->tinggi_tiang, 0) }} m</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-10 p-3 text-center">
                            <h6 class="text-muted">Jarak Antar Lampu</h6>
                            <h4>{{ number_format($rekomendasi->jarak_antar_lampu, 0) }} m</h4>
                            <small class="text-muted">4 × Tinggi Tiang</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-success-soft rounded-10 p-3 text-center">
                            <h6 class="text-muted">Jumlah Lampu yang Dibutuhkan</h6>
                            <h2 class="fw-bold text-success">{{ $rekomendasi->jumlah_lampu }} Unit</h2>
                            <small>Rumus: Panjang Jalan / Jarak Antar Lampu</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-warning-soft rounded-10 p-3 text-center">
                            <h6 class="text-muted">Total Estimasi Biaya</h6>
                            <h2 class="fw-bold text-warning">Rp {{ number_format($rekomendasi->total_biaya, 0, ',', '.') }}</h2>
                            <small>@ Rp 5.000.000 / Unit</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi -->
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @if(auth()->user()->isStafPerencana() && $rekomendasi->status == 'draft')
                        <form action="{{ route('rekomendasi.ajukan', $rekomendasi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Yakin ingin mengajukan rekomendasi ini untuk validasi?')">
                                <i data-feather="send"></i> Ajukan Validasi
                            </button>
                        </form>
                    @endif

                    @if(auth()->user()->isKepalaBidang() && $rekomendasi->status == 'diajukan')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#setujuiModal">
                            <i data-feather="check"></i> Setujui
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">
                            <i data-feather="x"></i> Tolak
                        </button>
                    @endif

                    <a href="{{ route('rekomendasi.cetak', $rekomendasi->id) }}" 
                       target="_blank" class="btn btn-secondary">
                        <i data-feather="printer"></i> Cetak
                    </a>
                </div>

                <!-- Modal Setujui -->
                <div class="modal fade" id="setujuiModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('rekomendasi.validasi', $rekomendasi->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="disetujui">
                                <div class="modal-header">
                                    <h5 class="modal-title">Setujui Rekomendasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Anda akan menyetujui rekomendasi untuk lokasi:</p>
                                    <h6><strong>{{ $rekomendasi->hasilSurvei->lokasi->nama_jalan }}</strong></h6>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <small class="text-muted">Jumlah Lampu</small>
                                            <p><strong>{{ $rekomendasi->jumlah_lampu }} unit</strong></p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Total Biaya</small>
                                            <p><strong>Rp {{ number_format($rekomendasi->total_biaya, 0, ',', '.') }}</strong></p>
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
                            <form action="{{ route('rekomendasi.validasi', $rekomendasi->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tolak Rekomendasi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Anda akan menolak rekomendasi untuk lokasi:</p>
                                    <h6><strong>{{ $rekomendasi->hasilSurvei->lokasi->nama_jalan }}</strong></h6>
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
            </div>
        </div>
    </div>
</div>

<!-- Dokumentasi -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Dokumentasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($rekomendasi->hasilSurvei->dokumentasi)
                        <div class="col-md-6">
                            <h6>Gambar Lokasi</h6>
                            @if($rekomendasi->hasilSurvei->dokumentasi->gambar)
                                <img src="{{ asset('storage/' . $rekomendasi->hasilSurvei->dokumentasi->gambar) }}" 
                                     alt="Gambar" 
                                     class="img-fluid rounded-10 mb-2"
                                     style="max-height: 300px; width: 100%; object-fit: cover;">
                                <a href="{{ asset('storage/' . $rekomendasi->hasilSurvei->dokumentasi->gambar) }}" 
                                   target="_blank" class="btn btn-primary btn-sm">
                                    <i data-feather="eye"></i> Lihat Gambar
                                </a>
                            @else
                                <p class="text-muted">Tidak ada gambar</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Foto Survei</h6>
                            @if($rekomendasi->hasilSurvei->dokumentasi->foto_survei)
                                <img src="{{ asset('storage/' . $rekomendasi->hasilSurvei->dokumentasi->foto_survei) }}" 
                                     alt="Foto Survei" 
                                     class="img-fluid rounded-10 mb-2"
                                     style="max-height: 300px; width: 100%; object-fit: cover;">
                                <a href="{{ asset('storage/' . $rekomendasi->hasilSurvei->dokumentasi->foto_survei) }}" 
                                   target="_blank" class="btn btn-primary btn-sm">
                                    <i data-feather="eye"></i> Lihat Foto Survei
                                </a>
                            @else
                                <p class="text-muted">Tidak ada foto survei</p>
                            @endif
                        </div>
                    @else
                        <div class="col-12 text-center">
                            <p class="text-muted">Dokumentasi tidak tersedia</p>
                        </div>
                    @endif
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
.bg-success-soft { background: rgba(25, 135, 84, 0.1); }
.bg-warning-soft { background: rgba(255, 193, 7, 0.1); }
</style>
@endpush