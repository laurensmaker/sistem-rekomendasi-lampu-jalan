{{-- resources/views/backend/dokumentasi/edit.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Edit Dokumentasi</h3>
    <a href="{{ route('dokumentasi.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left"></i> Kembali
    </a>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('dokumentasi.update', $dokumentasi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="lokasi_id" class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <select name="lokasi_id" 
                            id="lokasi_id" 
                            class="form-select @error('lokasi_id') is-invalid @enderror" 
                            required>
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasi as $item)
                            <option value="{{ $item->id }}" {{ old('lokasi_id', $dokumentasi->lokasi_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_jalan }} - {{ $item->distrik }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-control bg-light">
                        <span class="badge bg-info">
                            ID: #{{ $dokumentasi->id }}
                        </span>
                        <small class="text-muted ms-2">
                            Dibuat: {{ $dokumentasi->created_at->format('d/m/Y H:i') }}
                        </small>
                        @if($dokumentasi->created_at != $dokumentasi->updated_at)
                            <small class="text-muted ms-2">
                                | Diupdate: {{ $dokumentasi->updated_at->format('d/m/Y H:i') }}
                            </small>
                        @endif
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                    <textarea name="keterangan" 
                              id="keterangan" 
                              class="form-control @error('keterangan') is-invalid @enderror" 
                              rows="3"
                              placeholder="Masukkan keterangan dokumentasi"
                              required>{{ old('keterangan', $dokumentasi->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="gambar" class="form-label">Gambar</label>
                    <input type="file" 
                           name="gambar" 
                           id="gambar" 
                           class="form-control @error('gambar') is-invalid @enderror" 
                           accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, JPEG, GIF. Max 5MB (Kosongkan jika tidak ingin mengubah)</small>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    @if($dokumentasi->gambar)
                        <div class="mt-2">
                            <p class="mb-1"><strong>Gambar Saat Ini:</strong></p>
                            <img src="{{ asset('storage/' . $dokumentasi->gambar) }}" 
                                 alt="Gambar" 
                                 class="img-fluid rounded-10" 
                                 style="max-height: 200px;">
                        </div>
                    @endif
                    
                    <div class="mt-2">
                        <img id="gambarPreview" src="#" alt="Preview" class="img-fluid rounded-10" style="max-height: 200px; display: none;">
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="foto_survei" class="form-label">Foto Survei</label>
                    <input type="file" 
                           name="foto_survei" 
                           id="foto_survei" 
                           class="form-control @error('foto_survei') is-invalid @enderror" 
                           accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, JPEG, GIF. Max 5MB (Kosongkan jika tidak ingin mengubah)</small>
                    @error('foto_survei')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    @if($dokumentasi->foto_survei)
                        <div class="mt-2">
                            <p class="mb-1"><strong>Foto Survei Saat Ini:</strong></p>
                            <img src="{{ asset('storage/' . $dokumentasi->foto_survei) }}" 
                                 alt="Foto Survei" 
                                 class="img-fluid rounded-10" 
                                 style="max-height: 200px;">
                        </div>
                    @endif
                    
                    <div class="mt-2">
                        <img id="fotoSurveiPreview" src="#" alt="Preview" class="img-fluid rounded-10" style="max-height: 200px; display: none;">
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-1"></i>
                        <strong>Informasi:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Upload gambar baru untuk mengganti gambar lama</li>
                            <li>Kosongkan field gambar jika tidak ingin mengubah</li>
                            <li>Foto survei opsional untuk dokumentasi tambahan</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update Dokumentasi
                    </button>
                    <a href="{{ route('dokumentasi.index') }}" class="btn btn-secondary">
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
    // Preview gambar baru
    document.getElementById('gambar').addEventListener('change', function(e) {
        const preview = document.getElementById('gambarPreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Preview foto survei baru
    document.getElementById('foto_survei').addEventListener('change', function(e) {
        const preview = document.getElementById('fotoSurveiPreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
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