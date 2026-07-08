{{-- resources/views/backend/hasil-survei/create.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Input Data Hasil Survei</h3>
    <a href="{{ route('hasil-survei.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left"></i> Kembali
    </a>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('hasil-survei.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Data Lokasi & Survei -->
                <div class="col-12 mb-3">
                    <h5 class="border-bottom pb-2">Informasi Survei</h5>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="lokasi_id" class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <select name="lokasi_id" 
                            id="lokasi_id" 
                            class="form-select @error('lokasi_id') is-invalid @enderror" 
                            required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasi as $item)
                            <option value="{{ $item->id }}" {{ old('lokasi_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_jalan }} - {{ $item->distrik }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="dokumentasi_id" class="form-label">Dokumentasi <span class="text-danger">*</span></label>
                    <select name="dokumentasi_id" 
                            id="dokumentasi_id" 
                            class="form-select @error('dokumentasi_id') is-invalid @enderror" 
                            required>
                        <option value="">Pilih Dokumentasi</option>
                        @foreach($dokumentasi as $item)
                            <option value="{{ $item->id }}" {{ old('dokumentasi_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->lokasi->nama_jalan }} - {{ Str::limit($item->keterangan, 30) }}
                            </option>
                        @endforeach
                    </select>
                    @error('dokumentasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="tanggal_survei" class="form-label">Tanggal Survei <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="tanggal_survei" 
                           id="tanggal_survei" 
                           class="form-control @error('tanggal_survei') is-invalid @enderror" 
                           value="{{ old('tanggal_survei', date('Y-m-d')) }}"
                           required>
                    @error('tanggal_survei')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <div class="form-control bg-light">
                        <small class="text-muted">Surveyor</small>
                        <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                    </div>
                </div>

                <!-- Data Fisik Lokasi -->
                <div class="col-12 mb-3 mt-3">
                    <h5 class="border-bottom pb-2">Data Fisik Lokasi</h5>
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="panjang_jalan" class="form-label">Panjang Jalan (meter) <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="panjang_jalan" 
                           id="panjang_jalan" 
                           class="form-control @error('panjang_jalan') is-invalid @enderror" 
                           value="{{ old('panjang_jalan') }}"
                           placeholder="Contoh: 1000" 
                           step="0.01"
                           min="0"
                           required>
                    @error('panjang_jalan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="lebar_jalan" class="form-label">Lebar Jalan (meter) <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="lebar_jalan" 
                           id="lebar_jalan" 
                           class="form-control @error('lebar_jalan') is-invalid @enderror" 
                           value="{{ old('lebar_jalan') }}"
                           placeholder="Contoh: 6" 
                           step="0.01"
                           min="0"
                           required>
                    @error('lebar_jalan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="tinggi_tiang" class="form-label">Tinggi Tiang (meter) <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="tinggi_tiang" 
                           id="tinggi_tiang" 
                           class="form-control @error('tinggi_tiang') is-invalid @enderror" 
                           value="{{ old('tinggi_tiang') }}"
                           placeholder="Contoh: 8" 
                           step="0.01"
                           min="0"
                           required>
                    @error('tinggi_tiang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kriteria Penilaian (C1-C5) -->
                <div class="col-12 mb-3 mt-3">
                    <h5 class="border-bottom pb-2">Penilaian Kriteria SAW (Skala 1-5)</h5>
                    <p class="text-muted small">
                        <i data-feather="info" class="me-1" style="width: 14px;"></i>
                        Masukkan nilai 1-5 untuk setiap kriteria
                    </p>
                </div>

                <!-- C1 - Kepadatan Penduduk -->
                <div class="col-lg-6 mb-3">
                    <label for="kepadatan_penduduk" class="form-label">
                        C1 - Kepadatan Penduduk <span class="text-danger">*</span>
                        <span class="badge bg-success">Benefit</span>
                    </label>
                    <input type="number" 
                           name="kepadatan_penduduk" 
                           id="kepadatan_penduduk" 
                           class="form-control @error('kepadatan_penduduk') is-invalid @enderror" 
                           value="{{ old('kepadatan_penduduk', 3) }}"
                           placeholder="Masukkan nilai 1-5" 
                           min="1" 
                           max="5"
                           step="1"
                           required>
                    <small class="text-muted">1=Sangat Rendah, 2=Rendah, 3=Sedang, 4=Tinggi, 5=Sangat Tinggi</small>
                    @error('kepadatan_penduduk')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- C2 - Volume Lalu Lintas -->
                <div class="col-lg-6 mb-3">
                    <label for="volume_lalu_lintas" class="form-label">
                        C2 - Volume Lalu Lintas <span class="text-danger">*</span>
                        <span class="badge bg-success">Benefit</span>
                    </label>
                    <input type="number" 
                           name="volume_lalu_lintas" 
                           id="volume_lalu_lintas" 
                           class="form-control @error('volume_lalu_lintas') is-invalid @enderror" 
                           value="{{ old('volume_lalu_lintas', 3) }}"
                           placeholder="Masukkan nilai 1-5" 
                           min="1" 
                           max="5"
                           step="1"
                           required>
                    <small class="text-muted">1=Sangat Rendah, 2=Rendah, 3=Sedang, 4=Tinggi, 5=Sangat Tinggi</small>
                    @error('volume_lalu_lintas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- C3 - Aktivitas Malam -->
                <div class="col-lg-6 mb-3">
                    <label for="aktivitas_malam" class="form-label">
                        C3 - Aktivitas Malam <span class="text-danger">*</span>
                        <span class="badge bg-success">Benefit</span>
                    </label>
                    <input type="number" 
                           name="aktivitas_malam" 
                           id="aktivitas_malam" 
                           class="form-control @error('aktivitas_malam') is-invalid @enderror" 
                           value="{{ old('aktivitas_malam', 3) }}"
                           placeholder="Masukkan nilai 1-5" 
                           min="1" 
                           max="5"
                           step="1"
                           required>
                    <small class="text-muted">1=Sangat Rendah, 2=Rendah, 3=Sedang, 4=Tinggi, 5=Sangat Tinggi</small>
                    @error('aktivitas_malam')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- C4 - Penerangan Saat Ini -->
                <div class="col-lg-6 mb-3">
                    <label for="penerangan_saat_ini" class="form-label">
                        C4 - Penerangan Saat Ini <span class="text-danger">*</span>
                        <span class="badge bg-danger">Cost</span>
                    </label>
                    <input type="number" 
                           name="penerangan_saat_ini" 
                           id="penerangan_saat_ini" 
                           class="form-control @error('penerangan_saat_ini') is-invalid @enderror" 
                           value="{{ old('penerangan_saat_ini', 3) }}"
                           placeholder="Masukkan nilai 1-5" 
                           min="1" 
                           max="5"
                           step="1"
                           required>
                    <small class="text-muted">1=Sangat Rendah, 2=Rendah, 3=Sedang, 4=Tinggi, 5=Sangat Tinggi</small>
                    @error('penerangan_saat_ini')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- C5 - Kerawanan Kecelakaan -->
                <div class="col-lg-6 mb-3">
                    <label for="kerawanan_kecelakaan" class="form-label">
                        C5 - Kerawanan Kecelakaan <span class="text-danger">*</span>
                        <span class="badge bg-success">Benefit</span>
                    </label>
                    <input type="number" 
                           name="kerawanan_kecelakaan" 
                           id="kerawanan_kecelakaan" 
                           class="form-control @error('kerawanan_kecelakaan') is-invalid @enderror" 
                           value="{{ old('kerawanan_kecelakaan', 3) }}"
                           placeholder="Masukkan nilai 1-5" 
                           min="1" 
                           max="5"
                           step="1"
                           required>
                    <small class="text-muted">1=Sangat Rendah, 2=Rendah, 3=Sedang, 4=Tinggi, 5=Sangat Tinggi</small>
                    @error('kerawanan_kecelakaan')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Informasi -->
                <div class="col-lg-12 mb-3 mt-3">
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-1"></i>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Semua nilai kriteria menggunakan skala <strong>1-5</strong></li>
                            <li>
                                <span class="text-success"><strong>Benefit</strong></span> (C1, C2, C3, C5): Semakin tinggi nilai semakin baik
                                <br>
                                <span class="text-danger"><strong>Cost</strong></span> (C4): Semakin rendah nilai semakin baik
                            </li>
                            <li>Panjang jalan digunakan untuk menghitung kebutuhan lampu</li>
                            <li>Jarak antar lampu = 4 × Tinggi Tiang</li>
                            <li>Kebutuhan LPJU = Panjang Jalan / Jarak Antar Lampu</li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Simpan Survei
                    </button>
                    <button type="reset" class="btn btn-warning">
                        <i data-feather="refresh-ccw"></i> Reset
                    </button>
                    <a href="{{ route('hasil-survei.index') }}" class="btn btn-secondary">
                        <i data-feather="x"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Validasi input agar tidak kurang dari 1 dan lebih dari 5
    document.querySelectorAll('input[type="number"][min="1"][max="5"]').forEach(function(input) {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 5) this.value = 5;
        });
        
        input.addEventListener('blur', function() {
            let value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 5) this.value = 5;
        });
    });
</script>
@endpush

@push('styles')
<style>
.rounded-10 {
    border-radius: 10px;
}
</style>
@endpush