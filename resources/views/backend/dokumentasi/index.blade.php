{{-- resources/views/backend/dokumentasi/index.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Data Dokumentasi</h3>
    <div>
        <a href="{{ route('dokumentasi.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus"></i> Tambah Dokumentasi
        </a>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Dokumentasi</h6>
                <h3 class="mb-0">{{ $dokumentasi->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Lokasi</h6>
                <h3 class="mb-0">{{ \App\Models\Lokasi::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Terakhir Ditambahkan</h6>
                <h6 class="mb-0 text-muted">
                    @php
                        $last = \App\Models\Dokumentasi::latest()->first();
                    @endphp
                    @if($last)
                        {{ $last->created_at->format('d/m/Y H:i') }}
                    @else
                        -
                    @endif
                </h6>
            </div>
        </div>
    </div>
</div>

<div class="card bg-white border-0 rounded-10 mb-4">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" class="me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i data-feather="alert-circle" class="me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter dan Pencarian -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('dokumentasi.index') }}" method="GET" class="d-flex gap-2">
                    <select name="lokasi_id" class="form-select form-select-sm">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasi as $item)
                            <option value="{{ $item->id }}" {{ request('lokasi_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_jalan }} - {{ $item->distrik }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="filter"></i> Filter
                    </button>
                    @if(request('lokasi_id') || request('search'))
                        <a href="{{ route('dokumentasi.index') }}" class="btn btn-secondary btn-sm">
                            <i data-feather="x"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="col-md-6 text-end">
                <form action="{{ route('dokumentasi.index') }}" method="GET" class="d-flex gap-2 justify-content-end">
                    <input type="text" 
                           name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Cari dokumentasi..." 
                           value="{{ request('search') }}" 
                           style="width: 200px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="search"></i> Cari
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Lokasi</th>
                        <th>Distrik</th>
                        <th>Keterangan</th>
                        <th>Foto Survei</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumentasi as $key => $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="dokumentasi-checkbox" value="{{ $item->id }}">
                            </td>
                            <td>{{ $dokumentasi->firstItem() + $key }}</td>
                            <td>
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" 
                                         alt="Gambar" 
                                         width="50" 
                                         height="50" 
                                         class="rounded-10 object-fit-cover"
                                         style="object-fit: cover;">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                <i data-feather="map-pin" class="me-1 text-primary" style="width: 16px;"></i>
                                {{ $item->lokasi->nama_jalan }}
                            </td>
                            <td>{{ $item->lokasi->distrik }}</td>
                            <td>{{ Str::limit($item->keterangan, 50) }}</td>
                            <td>
                                @if($item->foto_survei)
                                    <span class="badge bg-success">
                                        <i data-feather="check-circle" style="width: 12px;"></i> Ada
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Tidak ada</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('dokumentasi.show', $item->id) }}" 
                                       class="btn btn-info btn-sm" title="Detail">
                                        <i data-feather="eye" style="width: 16px;"></i>
                                    </a>
                                    <a href="{{ route('dokumentasi.edit', $item->id) }}" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i data-feather="edit-2" style="width: 16px;"></i>
                                    </a>
                                    <form action="{{ route('dokumentasi.destroy', $item->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus dokumentasi ini?')">
                                            <i data-feather="trash-2" style="width: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i data-feather="image" style="width: 48px; height: 48px; color: #ccc;"></i>
                                <p class="mb-0">Tidak ada data dokumentasi</p>
                                <small class="text-muted">Silakan tambahkan dokumentasi baru</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <button id="deleteSelected" class="btn btn-danger btn-sm" disabled>
                    <i data-feather="trash-2"></i> Hapus Terpilih
                </button>
                <span class="ms-2 text-muted small">
                    <span id="selectedCount">0</span> dipilih
                </span>
            </div>
            <div>
                {{ $dokumentasi->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.dokumentasi-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox
    document.querySelectorAll('.dokumentasi-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Update selected count
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.dokumentasi-checkbox:checked');
        const deleteBtn = document.getElementById('deleteSelected');
        const countDisplay = document.getElementById('selectedCount');
        
        deleteBtn.disabled = checked.length === 0;
        countDisplay.textContent = checked.length;
    }

    // Bulk delete
    document.getElementById('deleteSelected').addEventListener('click', function() {
        const checked = document.querySelectorAll('.dokumentasi-checkbox:checked');
        if (checked.length === 0) return;
        
        const ids = Array.from(checked).map(cb => cb.value);
        const count = ids.length;
        
        if (confirm(`Yakin ingin menghapus ${count} dokumentasi terpilih?`)) {
            this.disabled = true;
            this.innerHTML = '<i data-feather="loader" class="spinner"></i> Menghapus...';
            
            fetch('{{ route("dokumentasi.bulk-delete") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                    this.disabled = false;
                    this.innerHTML = '<i data-feather="trash-2"></i> Hapus Terpilih';
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error);
                this.disabled = false;
                this.innerHTML = '<i data-feather="trash-2"></i> Hapus Terpilih';
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
.object-fit-cover {
    object-fit: cover;
}
.rounded-10 {
    border-radius: 10px;
}
</style>
@endpush