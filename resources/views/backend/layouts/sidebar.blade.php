{{-- resources/views/backend/layouts/sidebar.blade.php --}}
<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="index.html" class="d-block text-decoration-none">
            <img src="{{ asset('backend/assets/images/logo-lampu-jalan.png') }}" width="80" alt="logo-icon">
        </a>
        <button
            class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y"
            id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>
    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
        <ul class="menu-inner">
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.' . auth()->user()->role) }}" class="menu-link">
                    <i data-feather="grid" class="menu-icon tf-icons"></i>
                    <span class="title">Dasbor</span>
                </a>
            </li>

            <!-- Menu DATA -->
            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">DATA</span>
            </li>

            <!-- Data User - Hanya untuk Admin -->
            {{-- @if(auth()->user()->isAdmin())
            <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                    <i data-feather="users" class="menu-icon tf-icons"></i>
                    <span class="title">Data User</span>
                </a>
            </li>
            @endif --}}

            <!-- Data Kriteria - Hanya untuk Admin dan Staf Perencana -->
            @if(auth()->user()->isAdmin() || auth()->user()->isStafPerencana())
            <li class="menu-item {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
                <a href="{{ route('kriteria.index') }}" class="menu-link">
                    <i data-feather="sliders" class="menu-icon tf-icons"></i>
                    <span class="title">Data Kriteria</span>
                </a>
            </li>
            @endif

            <!-- Data Lokasi - Semua Role Bisa Melihat -->
            @if(auth()->user()->isAdmin() || auth()->user()->isStafPerencana() || auth()->user()->isKepalaBidang())
            <li class="menu-item {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                <a href="{{ route('lokasi.index') }}" class="menu-link">
                    <i data-feather="map-pin" class="menu-icon tf-icons"></i>
                    <span class="title">Data Lokasi</span>
                </a>
            </li>
            @endif

            <!-- Data Dokumentasi - Hanya untuk Petugas Survei dan Admin -->
            @if(auth()->user()->isPetugasSurvei())
            <li class="menu-item {{ request()->routeIs('dokumentasi.*') ? 'active' : '' }}">
                <a href="{{ route('dokumentasi.index') }}" class="menu-link">
                    <i data-feather="image" class="menu-icon tf-icons"></i>
                    <span class="title">Data Dokumentasi</span>
                </a>
            </li>
            @endif

            <!-- Menu Survei -->
            
            <!-- Data Hasil Survei - Petugas Survei, Staf Perencana, Admin -->
            @if(auth()->user()->isPetugasSurvei() || auth()->user()->isStafPerencana())
            {{-- <li class="menu-title small text-uppercase">
                <span class="menu-title-text">SURVEI & PERHITUNGAN</span>
            </li> --}}
            <li class="menu-item {{ request()->routeIs('hasil-survei.*') ? 'active' : '' }}">
                <a href="{{ route('hasil-survei.index') }}" class="menu-link">
                    <i data-feather="clipboard" class="menu-icon tf-icons"></i>
                    <span class="title">Data Hasil Survei</span>
                </a>
            </li>
            @endif

            <!-- Data Ranking SAW - Staf Perencana dan Admin -->
            @if(auth()->user()->isStafPerencana())
            <li class="menu-item {{ request()->routeIs('ranking-saw.*') ? 'active' : '' }}">
                <a href="{{ route('ranking-saw.index') }}" class="menu-link">
                    <i data-feather="bar-chart-2" class="menu-icon tf-icons"></i>
                    <span class="title">Data Ranking SAW</span>
                </a>
            </li>
            @endif

            <!-- Menu Rekomendasi -->
            {{-- <li class="menu-title small text-uppercase">
                <span class="menu-title-text">REKOMENDASI</span>
            </li> --}}

            <!-- Data Rekomendasi - Semua Role Kecuali Petugas Survei -->
            {{-- @if(auth()->user()->isStafPerencana() || auth()->user()->isKepalaBidang())
            <li class="menu-item {{ request()->routeIs('rekomendasi.*') ? 'active' : '' }}">
                <a href="{{ route('rekomendasi.index') }}" class="menu-link">
                    <i data-feather="thumbs-up" class="menu-icon tf-icons"></i>
                    <span class="title">Data Rekomendasi</span>
                </a>
            </li>
            @endif --}}

            <!-- Menu Laporan -->
            {{-- <li class="menu-title small text-uppercase">
                <span class="menu-title-text">LAPORAN</span>
            </li> --}}

            <!-- Laporan - Admin, Staf Perencana, Kepala Bidang -->
            {{-- @if(auth()->user()->isAdmin() || auth()->user()->isStafPerencana() || auth()->user()->isKepalaBidang())
            <li class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}" class="menu-link">
                    <i data-feather="file-text" class="menu-icon tf-icons"></i>
                    <span class="title">Laporan</span>
                </a>
            </li>
            @endif --}}

            <!-- Menu Pengaturan -->
            {{-- <li class="menu-title small text-uppercase">
                <span class="menu-title-text">PENGATURAN</span>
            </li> --}}

            <!-- Pengaturan - Hanya Admin -->
            {{-- @if(auth()->user()->isAdmin())
            <li class="menu-item">
                <a href="#" class="menu-link" data-bs-toggle="modal" data-bs-target="#settingsModal">
                    <i data-feather="settings" class="menu-icon tf-icons"></i>
                    <span class="title">Pengaturan</span>
                </a>
            </li>
            @endif --}}

            <!-- Logout -->
            <li class="menu-item mt-3">
                <a href="{{ route('logout') }}" class="menu-link" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out" class="menu-icon tf-icons"></i>
                    <span class="title">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>