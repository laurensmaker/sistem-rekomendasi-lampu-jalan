@extends('backend.layouts.main')
@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary border-0 rounded-4 shadow-lg">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i data-feather="home" class="text-white" width="32" height="32"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-4">
                            <h1 class="display-6 fw-bold text-white mb-2">Dashboard</h1>
                            <p class="text-white text-opacity-75 mb-0">Selamat datang di sistem manajemen dokumen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Section -->
    <div class="row g-4">
        <div class="col-xxl-4 col-lg-4 col-md-6">
            <a href="" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm hover-lift transition-all h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="icon-circle bg-soft-primary rounded-circle p-3">
                                    <i data-feather="file-text" class="text-primary" width="28" height="28"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">INVOICE</h5>
                                <p class="text-muted small mb-0">Kelola data invoice</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i data-feather="arrow-right" width="14" height="14" class="me-1"></i>
                                Lihat Detail
                            </span>
                            <span class="text-primary small">→</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-4 col-lg-4 col-md-6">
            <a href="" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm hover-lift transition-all h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="icon-circle bg-soft-success rounded-circle p-3">
                                    <i data-feather="package" class="text-success" width="28" height="28"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">PACKING LIST</h5>
                                <p class="text-muted small mb-0">Kelola data packing list</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i data-feather="arrow-right" width="14" height="14" class="me-1"></i>
                                Lihat Detail
                            </span>
                            <span class="text-success small">→</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xxl-4 col-lg-4 col-md-6">
            <a href="" class="text-decoration-none">
                <div class="card border-0 rounded-4 shadow-sm hover-lift transition-all h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="icon-circle bg-soft-warning rounded-circle p-3">
                                    <i data-feather="truck" class="text-warning" width="28" height="28"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">SURAT JALAN</h5>
                                <p class="text-muted small mb-0">Kelola data surat jalan</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i data-feather="arrow-right" width="14" height="14" class="me-1"></i>
                                Lihat Detail
                            </span>
                            <span class="text-warning small">→</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Additional Info Section -->
    {{-- <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm bg-light">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-2">Statistik Cepat</h5>
                            <p class="text-muted mb-0">Ringkasan data dokumen Anda</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                                <div class="text-center me-4">
                                    <h4 class="fw-bold text-primary mb-1">0</h4>
                                    <small class="text-muted">Invoice</small>
                                </div>
                                <div class="text-center me-4">
                                    <h4 class="fw-bold text-success mb-1">0</h4>
                                    <small class="text-muted">Packing List</small>
                                </div>
                                <div class="text-center">
                                    <h4 class="fw-bold text-warning mb-1">0</h4>
                                    <small class="text-muted">Surat Jalan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>

<style>
/* Custom Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-soft-primary {
    background-color: rgba(102, 126, 234, 0.1);
}

.bg-soft-success {
    background-color: rgba(40, 167, 69, 0.1);
}

.bg-soft-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.icon-circle {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-lift {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}

.transition-all {
    transition: all 0.2s ease-in-out;
}

.card {
    transition: all 0.2s ease-in-out;
}

.card:hover {
    border-color: transparent;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .display-6 {
        font-size: 1.5rem;
    }
    
    .icon-circle {
        width: 48px;
        height: 48px;
    }
    
    .icon-circle i {
        width: 20px;
        height: 20px;
    }
}
</style>

<script>
// Initialize Feather Icons
document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
@endsection