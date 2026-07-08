{{-- resources/views/backend/kriteria/edit.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Edit Data Kriteria</h3>
    <a href="{{ route('kriteria.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left"></i> Kembali
    </a>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('kriteria.update', $kriterium->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="nama_kriteria" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="nama_kriteria" 
                           id="nama_kriteria" 
                           class="form-control @error('nama_kriteria') is-invalid @enderror" 
                           value="{{ old('nama_kriteria', $kriterium->nama_kriteria) }}"
                           placeholder="Masukkan nama kriteria" 
                           required>
                    <small class="text-muted">Contoh: lebar_jalan, panjang_jalan, tinggi_tiang</small>
                    @error('nama_kriteria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="bobot" class="form-label">Bobot <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" 
                               name="bobot" 
                               id="bobot" 
                               class="form-control @error('bobot') is-invalid @enderror" 
                               value="{{ old('bobot', $kriterium->bobot) }}"
                               placeholder="Contoh: 25" 
                               step="0.01"
                               min="0"
                               max="100"
                               required>
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="text-muted">Masukkan bobot dalam angka (contoh: 25 = 25%)</small>
                    @error('bobot')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="atribut" class="form-label">Atribut <span class="text-danger">*</span></label>
                    <select name="atribut" 
                            id="atribut" 
                            class="form-select @error('atribut') is-invalid @enderror" 
                            required>
                        <option value="">Pilih Atribut</option>
                        <option value="benefit" {{ old('atribut', $kriterium->atribut) == 'benefit' ? 'selected' : '' }}>
                            <i data-feather="trending-up"></i> Benefit (Semakin besar semakin baik)
                        </option>
                        <option value="cost" {{ old('atribut', $kriterium->atribut) == 'cost' ? 'selected' : '' }}>
                            <i data-feather="trending-down"></i> Cost (Semakin kecil semakin baik)
                        </option>
                    </select>
                    <small class="text-muted">
                        <span class="text-success">Benefit:</span> Nilai semakin besar lebih baik (contoh: lebar jalan)
                        <br>
                        <span class="text-danger">Cost:</span> Nilai semakin kecil lebih baik (contoh: biaya)
                    </small>
                    @error('atribut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 mb-3">
                    <div class="card bg-light border-0 rounded-10">
                        <div class="card-body">
                            <h6 class="mb-2">Preview Atribut</h6>
                            <div id="atributPreview" class="text-muted">
                                @if($kriterium->atribut == 'benefit')
                                    <div class="text-success">
                                        <i data-feather="trending-up" class="me-1"></i>
                                        <strong>Benefit</strong> - Semakin besar nilai, semakin baik
                                        <br>
                                        <small>Contoh: Lebar jalan, panjang jalan, tinggi tiang</small>
                                    </div>
                                @elseif($kriterium->atribut == 'cost')
                                    <div class="text-danger">
                                        <i data-feather="trending-down" class="me-1"></i>
                                        <strong>Cost</strong> - Semakin kecil nilai, semakin baik
                                        <br>
                                        <small>Contoh: Biaya, jarak, waktu</small>
                                    </div>
                                @else
                                    <i data-feather="info" class="me-1"></i>
                                    Pilih atribut untuk melihat preview
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <div class="card bg-light border-0 rounded-10">
                        <div class="card-body">
                            <h6 class="mb-2">Informasi Kriteria</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">ID</small>
                                    <p><strong>#{{ $kriterium->id }}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Dibuat</small>
                                    <p><strong>{{ $kriterium->created_at->format('d/m/Y H:i') }}</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Terakhir Update</small>
                                    <p><strong>{{ $kriterium->updated_at->format('d/m/Y H:i') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <div class="alert alert-warning">
                        <i data-feather="alert-triangle" class="me-1"></i>
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Mengubah nama kriteria dapat mempengaruhi data hasil survei yang sudah ada</li>
                            <li>Pastikan total bobot semua kriteria tetap 100%</li>
                            <li>Perubahan atribut akan mempengaruhi perhitungan SAW</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 mb-3">
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-1"></i>
                        <strong>Informasi:</strong> 
                        <ul class="mb-0 mt-1">
                            <li>Bobot diisi dalam angka (contoh: 25 = 25%)</li>
                            <li>Total bobot semua kriteria harus 100%</li>
                            <li>Nama kriteria harus unik dan tidak boleh sama</li>
                            <li>
                                <span class="text-success">Benefit:</span> Kriteria yang semakin besar nilainya semakin baik
                                <br>
                                <span class="text-danger">Cost:</span> Kriteria yang semakin kecil nilainya semakin baik
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save"></i> Update Kriteria
                    </button>
                    <a href="{{ route('kriteria.index') }}" class="btn btn-secondary">
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
    // Auto-format bobot to 2 decimal places
    document.getElementById('bobot').addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });

    // Preview atribut
    document.getElementById('atribut').addEventListener('change', function() {
        const preview = document.getElementById('atributPreview');
        const value = this.value;
        
        if (value === 'benefit') {
            preview.innerHTML = `
                <div class="text-success">
                    <i data-feather="trending-up" class="me-1"></i>
                    <strong>Benefit</strong> - Semakin besar nilai, semakin baik
                    <br>
                    <small>Contoh: Lebar jalan, panjang jalan, tinggi tiang</small>
                </div>
            `;
            feather.replace();
        } else if (value === 'cost') {
            preview.innerHTML = `
                <div class="text-danger">
                    <i data-feather="trending-down" class="me-1"></i>
                    <strong>Cost</strong> - Semakin kecil nilai, semakin baik
                    <br>
                    <small>Contoh: Biaya, jarak, waktu</small>
                </div>
            `;
            feather.replace();
        } else {
            preview.innerHTML = `
                <i data-feather="info" class="me-1"></i>
                Pilih atribut untuk melihat preview
            `;
            feather.replace();
        }
    });

    // Validate total bobot before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const bobotInput = document.getElementById('bobot');
        const bobotValue = parseFloat(bobotInput.value);
        
        if (bobotValue < 0 || bobotValue > 100) {
            e.preventDefault();
            alert('Bobot harus antara 0 dan 100');
            bobotInput.focus();
        }
    });
</script>
@endpush