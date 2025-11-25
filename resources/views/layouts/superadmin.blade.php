<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPORTYKUY Admin')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #D85C5C;
            --brand-dark: #c24a4a;
            --sidebar-bg: #2d3748;
            --sidebar-text: #e2e8f0;
            --sidebar-hover: #4a5568;
            --sidebar-active: #4299e1;
        }

        body {
            font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            width: 260px;
            z-index: 1020;
            overflow-y: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar .brand-logo {
            background-color: var(--brand);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .brand-logo i {
            margin-right: 0.75rem;
            font-size: 1.4rem;
        }

        .admin-sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 0;
            transition: background-color 0.2s, color 0.2s;
        }

        .admin-sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background-color: var(--brand);
            color: white;
        }

        .admin-sidebar .nav-link i {
            width: 28px;
            font-size: 1.1rem;
            margin-right: 0.85rem;
            text-align: center;
        }

        .admin-sidebar .nav-divider {
            margin: 0.5rem 1rem;
            border-color: #4a5568;
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            padding: 1.5rem;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .admin-header h1 {
            font-weight: 700;
            color: #1a202c;
            font-size: 1.75rem;
        }

        .admin-breadcrumb {
            background-color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
            font-size: 0.9rem;
        }

        /* Stats Cards */
        .stat-card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.bg-primary { background-color: #e3f2fd; color: #1976d2; }
        .stat-icon.bg-success { background-color: #e8f5e9; color: #2e7d32; }
        .stat-icon.bg-warning { background-color: #fff8e1; color: #f57c00; }
        .stat-icon.bg-danger { background-color: #ffebee; color: #c62828; }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
        }

        .stat-label {
            color: #718096;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar { width: 240px; }
            .admin-main { margin-left: 240px; }
        }

        @media (max-width: 767.98px) {
            .admin-sidebar {
                width: 0;
                position: fixed;
                top: 0;
                left: 0;
                transition: width 0.3s ease;
            }
            .admin-main { margin-left: 0; }
            .admin-sidebar.show { width: 260px; }
        }

        /* Table */
        .table th {
            font-weight: 600;
            color: #4a5568;
            background-color: #f8fafc;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="admin-sidebar" id="adminSidebar">
    <div class="brand-logo">
        <i class="fas fa-shield-alt"></i>
        SPORTYKUY ADMIN
    </div>
    <div class="p-2">
        <div class="px-3 pb-3">
            <small class="text-muted d-block">Logged in as</small>
            <strong class="d-block">{{ Auth::user()->name }}</strong>
            <span class="badge bg-success mt-1">Super Admin</span>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('superadmin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}" 
                   href="{{ route('superadmin.users.index') }}">
                    <i class="fas fa-users"></i> Kelola Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.partners.*') ? 'active' : '' }}" 
                   href="{{ route('superadmin.partners.index') }}">
                    <i class="fas fa-building"></i> Kelola Partner
                </a>
            </li>

            <li><hr class="nav-divider"></li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog"></i> Pengaturan Profil
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link text-danger w-100 text-start">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<main class="admin-main">
    <div class="admin-header">
        <h1>@yield('title', 'Dashboard')</h1>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-user me-1"></i> Profil
        </a>
    </div>

    <nav aria-label="breadcrumb" class="admin-breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Admin</a></li>
            @yield('breadcrumb')
        </ol>
    </nav>

    <div class="mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>