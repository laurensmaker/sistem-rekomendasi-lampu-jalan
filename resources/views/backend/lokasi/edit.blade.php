@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Edit Data Lokasi</h3>
    <a href="{{ route('lokasi.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left"></i> Kembali
    </a>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('lokasi.update', $lokasi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="nama_jalan" class="form-label">Nama Jalan <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="nama_jalan" 
                           id="nama_jalan" 
                           class="form-control @error('nama_jalan') is-invalid @enderror" 
                           value="{{ old('nama_jalan', $lokasi->nama_jalan) }}"
                           placeholder="Masukkan nama jalan" 
                           required>
                    @error('nama_jalan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="distrik" class="form-label">Distrik <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="distrik" 
                           id="distrik" 
                           class="form-control @error('distrik') is-invalid @enderror" 
                           value="{{ old('distrik', $lokasi->distrik) }}"
                           placeholder="Masukkan nama distrik" 
                           required>
                    @error('distrik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                        <input type="number" 
                               name="latitude" 
                               id="latitude" 
                               class="form-control @error('latitude') is-invalid @enderror" 
                               value="{{ old('latitude', $lokasi->latitude) }}"
                               placeholder="Contoh: -6.2087634" 
                               step="0.0000001"
                               required>
                    </div>
                    <small class="text-muted">Format: -90.0000000 sampai 90.0000000</small>
                    @error('latitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="map-pin"></i></span>
                        <input type="number" 
                               name="longitude" 
                               id="longitude" 
                               class="form-control @error('longitude') is-invalid @enderror" 
                               value="{{ old('longitude', $lokasi->longitude) }}"
                               placeholder="Contoh: 106.845599" 
                               step="0.0000001"
                               required>
                    </div>
                    <small class="text-muted">Format: -180.0000000 sampai 180.0000000</small>
                    @error('longitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-control bg-light">
                        <span class="badge bg-info">
                            ID: #{{ $lokasi->id }}
                        </span>
                        <small class="text-muted ms-2">
                            Dibuat: {{ $lokasi->created_at->format('d/m/Y H:i') }}
                        </small>
                        @if($lokasi->created_at != $lokasi->updated_at)
                            <small class="text-muted ms-2">
                                | Diupdate: {{ $lokasi->updated_at->format('d/m/Y H:i') }}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label class="form-label">Lokasi di Peta</label>
                    <div class="form-control bg-light">
                        <a href="https://www.google.com/maps?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i data-feather="map"></i> Lihat di Google Maps
                        </a>
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-1"></i>
                        <strong>Informasi:</strong> 
                        <ul class="mb-0 mt-1">
                            <li>Latitude bernilai negatif untuk lokasi di selatan garis khatulistiwa (Indonesia: -6.2087634)</li>
                            <li>Longitude bernilai positif untuk lokasi di timur garis meridian (Indonesia: 106.845599)</li>
                            <li>Gunakan titik (.) sebagai pemisah desimal</li>
                            <li>Anda bisa menggunakan Google Maps untuk mendapatkan koordinat</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update Lokasi
                    </button>
                    <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">
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
    // Auto-format latitude to 7 decimal places
    document.getElementById('latitude').addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(7);
        }
    });

    // Auto-format longitude to 7 decimal places
    document.getElementById('longitude').addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(7);
        }
    });

    // Validate latitude range
    document.getElementById('latitude').addEventListener('change', function() {
        const value = parseFloat(this.value);
        if (value < -90 || value > 90) {
            alert('Latitude harus antara -90 dan 90');
            this.value = '';
            this.focus();
        }
    });

    // Validate longitude range
    document.getElementById('longitude').addEventListener('change', function() {
        const value = parseFloat(this.value);
        if (value < -180 || value > 180) {
            alert('Longitude harus antara -180 dan 180');
            this.value = '';
            this.focus();
        }
    });
</script>
@endpush