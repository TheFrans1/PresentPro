<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - SmartPresence</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================= */
        /* == 1. PERBAIKAN SCROLLBAR GANDA == */
        /* ============================================= */
        body {
            display: flex;
            height: 100vh; /* Kunci tinggi body ke 100% layar */
            flex-direction: column;
            overflow: hidden; /* Matikan scrollbar di body */
        }
        .main-wrapper {
            display: flex;
            flex: 1;
            overflow: hidden; /* Pastikan wrapper ini juga tidak scroll */
        }
        .content-wrapper {
            flex:1;
            padding: 1rem;
            background-color: #f8f9fa;
            overflow-y: auto; /* JADIKAN INI SATU-SATUNYA SCROLLBAR */
            min-height: auto; /* Hapus min-height vh */
        }
        /* ============================================= */
        /* == AKHIR PERBAIKAN == */
        /* ============================================= */

        /* Style Sidebar (Biarkan tetap sama) */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #2c3e50;
            color: white;
            padding-top: 1rem;
        }
        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #34495e;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            font-size: 1.1rem;
            text-align: center;
        }
        .sidebar small.text-muted {
            color: #95a5a6 !important;
        }
        
        /* Style Navbar Atas (Biarkan tetap sama) */
        .navbar-brand-top {
            background-color: #2c3e50;
            color: white !important;
            padding-left: 1rem;
            padding-right: 1rem;
            width: 250px;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 500;
        }
        .top-navbar {
            width: 100%;
            height: 56px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg p-0 top-navbar">
        <a class="navbar-brand navbar-brand-top" href="{{ route('admin.dashboard') }}">
            SmartPresence
        </a>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto pe-3">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->nama }} (Admin)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-wrapper">
    
        <nav class="sidebar">
            <div class="nav flex-column">
                
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
                
                
                <a class="nav-link {{ request()->routeIs('admin.akun.*') ? 'active' : '' }}" href="{{ route('admin.akun.index') }}">
                    <i class="bi bi-people-fill"></i>
                    Kelola Akun Karyawan
                </a>

                <a class="nav-link {{ request()->routeIs('admin.izin.index') ? 'active' : '' }}" href="{{ route('admin.izin.index') }}">
                    <i class="bi bi-check-circle-fill"></i>
                    Kelola Surat
                </a>

                <a class="nav-link {{ request()->routeIs('admin.izin.riwayat') ? 'active' : '' }}" href="{{ route('admin.izin.riwayat') }}">
                    <i class="bi bi-clock-history"></i>
                    Riwayat Pengajuan
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    Rekap Laporan
                </a>
                
                <a class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}" href="{{ route('admin.jadwal.index') }}">
                    <i class="bi bi-calendar-event-fill"></i>
                    Kelola Jadwal
                </a>
            </div>
        </nav>

        <main class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">@yield('page-title')</h1>
                <div>
                    @yield('page-actions')
                </div>
            </div>

            @yield('content')
        </main>
    </div>

</body>
</html>