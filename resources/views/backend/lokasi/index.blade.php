@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Data Lokasi</h3>
    <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm">
        <i data-feather="plus"></i> Tambah Lokasi
    </a>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Lokasi</h6>
                <h3 class="mb-0">{{ $lokasi->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Distrik</h6>
                <h3 class="mb-0">{{ \App\Models\Lokasi::distinct('distrik')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Koordinat Terakhir</h6>
                <h6 class="mb-0 text-muted">
                    @php
                        $last = \App\Models\Lokasi::latest()->first();
                    @endphp
                    @if($last)
                        {{ number_format($last->latitude, 4) }}, {{ number_format($last->longitude, 4) }}
                    @else
                        -
                    @endif
                </h6>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Terakhir Ditambahkan</h6>
                <h6 class="mb-0 text-muted">
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
                <form action="{{ route('lokasi.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" 
                           name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Cari nama jalan atau distrik..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('lokasi.index') }}" class="btn btn-secondary btn-sm">
                            <i data-feather="x"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('lokasi.index') }}" class="btn btn-outline-primary btn-sm">
                    <i data-feather="refresh-cw"></i> Refresh
                </a>
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
                        <th>Nama Jalan</th>
                        <th>Distrik</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Koordinat</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasi as $key => $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="lokasi-checkbox" value="{{ $item->id }}">
                            </td>
                            <td>{{ $lokasi->firstItem() + $key }}</td>
                            <td>
                                <i data-feather="map-pin" class="me-2 text-primary" style="width: 16px; height: 16px;"></i>
                                {{ $item->nama_jalan }}
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $item->distrik }}
                                </span>
                            </td>
                            <td>
                                <code>{{ number_format($item->latitude, 7) }}</code>
                            </td>
                            <td>
                                <code>{{ number_format($item->longitude, 7) }}</code>
                            </td>
                            <td>
                                <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Lihat di Google Maps">
                                    <i data-feather="map"></i> Lihat
                                </a>
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('lokasi.edit', $item->id) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit">
                                        <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                    </a>
                                    <form action="{{ route('lokasi.destroy', $item->id) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus lokasi {{ $item->nama_jalan }}?')">
                                            <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i data-feather="map-pin" class="mb-2" style="width: 48px; height: 48px; color: #ccc;"></i>
                                <p class="mb-0">Tidak ada data lokasi</p>
                                @if(request('search'))
                                    <small class="text-muted">Coba dengan kata kunci lain</small>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="9">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button id="deleteSelected" class="btn btn-danger btn-sm" disabled>
                                        <i data-feather="trash-2"></i> Hapus Terpilih
                                    </button>
                                    <span class="ms-2 text-muted small">
                                        <span id="selectedCount">0</span> dipilih
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        Menampilkan {{ $lokasi->firstItem() ?? 0 }} - {{ $lokasi->lastItem() ?? 0 }} 
                                        dari {{ $lokasi->total() }} data
                                    </small>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $lokasi->links() }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.lokasi-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox
    document.querySelectorAll('.lokasi-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Update selected count
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.lokasi-checkbox:checked');
        const deleteBtn = document.getElementById('deleteSelected');
        const countDisplay = document.getElementById('selectedCount');
        
        deleteBtn.disabled = checked.length === 0;
        countDisplay.textContent = checked.length;
    }

    // Bulk delete
    document.getElementById('deleteSelected').addEventListener('click', function() {
        const checked = document.querySelectorAll('.lokasi-checkbox:checked');
        if (checked.length === 0) return;
        
        const ids = Array.from(checked).map(cb => cb.value);
        const count = ids.length;
        
        if (confirm(`Yakin ingin menghapus ${count} lokasi terpilih?`)) {
            // Tampilkan loading
            this.disabled = true;
            this.innerHTML = '<i data-feather="loader" class="spinner"></i> Menghapus...';
            
            fetch('{{ route("lokasi.bulk-delete") }}', {
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

    // Keyboard shortcut: Ctrl+A untuk select all
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'a') {
            const checkboxes = document.querySelectorAll('.lokasi-checkbox');
            if (document.activeElement?.tagName !== 'INPUT') {
                e.preventDefault();
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                checkboxes.forEach(checkbox => {
                    if (!checkbox.disabled) {
                        checkbox.checked = !allChecked;
                    }
                });
                updateSelectedCount();
            }
        }
    });
</script>
@endpush