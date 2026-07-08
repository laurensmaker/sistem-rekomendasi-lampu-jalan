{{-- resources/views/backend/kriteria/index.blade.php --}}
@extends('backend.layouts.main')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="fs-18">Data Kriteria</h3>
    <div>
        <a href="{{ route('kriteria.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus"></i> Tambah Kriteria
        </a>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Kriteria</h6>
                <h3 class="mb-0">{{ $kriteria->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Bobot</h6>
                <h3 class="mb-0 {{ $totalBobot == 100 ? 'text-success' : 'text-warning' }}">
                    {{ number_format($totalBobot, 2) }}%
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Sisa Bobot</h6>
                <h3 class="mb-0 {{ (100 - $totalBobot) == 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format(100 - $totalBobot, 2) }}%
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white border-0 rounded-10 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Status</h6>
                <h3 class="mb-0">
                    <span class="badge bg-{{ $totalBobot == 100 ? 'success' : 'warning' }} fs-6">
                        {{ $totalBobot == 100 ? '✅ Lengkap' : '⏳ Belum Lengkap' }}
                    </span>
                </h3>
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

        <!-- Pencarian -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('kriteria.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" 
                           name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Cari kriteria..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('kriteria.index') }}" class="btn btn-secondary btn-sm">
                            <i data-feather="x"></i> Reset
                        </a>
                    @endif
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
                        <th>Nama Kriteria</th>
                        <th>Bobot</th>
                        <th>Atribut</th>
                        <th>Persentase</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kriteria as $key => $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="kriteria-checkbox" value="{{ $item->id }}">
                            </td>
                            <td>{{ $kriteria->firstItem() + $key }}</td>
                            <td>
                                <i data-feather="sliders" class="me-2 text-primary" style="width: 16px;"></i>
                                {{ ucfirst(str_replace('_', ' ', $item->nama_kriteria)) }}
                                <br>
                                <small class="text-muted">{{ $item->nama_kriteria }}</small>
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($item->bobot, 2) }}%</span>
                            </td>
                            <td>
                                @if($item->atribut == 'benefit')
                                    <span class="badge bg-success">
                                        <i data-feather="trending-up" style="width: 12px;"></i>
                                        Benefit
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i data-feather="trending-down" style="width: 12px;"></i>
                                        Cost
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $item->bobot >= 30 ? 'success' : ($item->bobot >= 20 ? 'warning' : 'info') }}" 
                                         role="progressbar" 
                                         style="width: {{ $item->bobot }}%;" 
                                         aria-valuenow="{{ $item->bobot }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format($item->bobot, 0) }}%
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('kriteria.edit', $item->id) }}" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i data-feather="edit-2" style="width: 16px;"></i>
                                    </a>
                                    <form action="{{ route('kriteria.destroy', $item->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus kriteria {{ $item->nama_kriteria }}?')">
                                            <i data-feather="trash-2" style="width: 16px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i data-feather="sliders" style="width: 48px; height: 48px; color: #ccc;"></i>
                                <p class="mb-0">Tidak ada data kriteria</p>
                                <small class="text-muted">Silakan tambahkan kriteria baru</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <td colspan="2"><strong>Total</strong></td>
                        <td><strong>{{ $kriteria->total() }} Kriteria</strong></td>
                        <td><strong>{{ number_format($totalBobot, 2) }}%</strong></td>
                        <td colspan="4">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $totalBobot }}%;" 
                                     aria-valuenow="{{ $totalBobot }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($totalBobot, 2) }}%
                                </div>
                                @if(100 - $totalBobot > 0)
                                    <div class="progress-bar bg-danger" 
                                         role="progressbar" 
                                         style="width: {{ 100 - $totalBobot }}%;" 
                                         aria-valuenow="{{ 100 - $totalBobot }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format(100 - $totalBobot, 2) }}%
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tfoot>
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
                {{ $kriteria->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.kriteria-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox
    document.querySelectorAll('.kriteria-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Update selected count
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.kriteria-checkbox:checked');
        const deleteBtn = document.getElementById('deleteSelected');
        const countDisplay = document.getElementById('selectedCount');
        
        deleteBtn.disabled = checked.length === 0;
        countDisplay.textContent = checked.length;
    }

    // Bulk delete
    document.getElementById('deleteSelected').addEventListener('click', function() {
        const checked = document.querySelectorAll('.kriteria-checkbox:checked');
        if (checked.length === 0) return;
        
        const ids = Array.from(checked).map(cb => cb.value);
        const count = ids.length;
        
        if (confirm(`Yakin ingin menghapus ${count} kriteria terpilih?`)) {
            this.disabled = true;
            this.innerHTML = '<i data-feather="loader" class="spinner"></i> Menghapus...';
            
            fetch('{{ route("kriteria.bulk-delete") }}', {
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
.rounded-10 {
    border-radius: 10px;
}
</style>
@endpush