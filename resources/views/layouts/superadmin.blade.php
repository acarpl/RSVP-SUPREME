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
            --brand-light: #fde8e8;
            --brand-hover: #c24a4a;
            --brand-dark: #a63c3c;

            --sidebar-bg: #2D3748;
            --sidebar-text: #E2E8F0;
            --sidebar-hover: #4A5568;
            --sidebar-active: var(--brand);   

            --success: #48BB78;         
            --success-light: #e8f7ee;
            --warning: #F6AD55;        
            --warning-light: #fff8e6;
            --danger: #E53E3E;
            --danger-light: #ffe9e9;

            --card-border: #EDF2F7;
        }

        body {
            font-family: "Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F9FAFB;
            color: #2D3748;
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .admin-sidebar .brand-logo {
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }

        .admin-sidebar .brand-logo i {
            margin-right: 0.75rem;
            font-size: 1.5rem;
        }

        .admin-sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.25s ease;
        }

        .admin-sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background-color: var(--brand);
            color: white;
            border-left: 4px solid white;
            position: relative;
        }

        .admin-sidebar .nav-link i {
            width: 28px;
            font-size: 1.15rem;
            margin-right: 0.85rem;
            text-align: center;
        }

        .admin-sidebar .nav-divider {
            margin: 0.75rem 1rem;
            border-color: #4A5568;
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            padding: 1.75rem;
            transition: margin-left 0.3s ease;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .admin-header h1 {
            font-weight: 700;
            color: #1A202C;
            font-size: 1.875rem;
        }

        .admin-breadcrumb {
            background-color: white;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            font-size: 0.95rem;
            border: 1px solid var(--card-border);
        }

        .admin-breadcrumb .breadcrumb-item a {
            color: var(--brand);
            text-decoration: none;
        }

        .admin-breadcrumb .breadcrumb-item.active {
            color: #718096;
        }

        /* Stats Cards */
        .stat-card {
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--card-border);
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(216, 92, 92, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }

        .stat-icon.bg-brand { 
            background-color: var(--brand-light); 
            color: var(--brand); 
        }
        .stat-icon.bg-success { 
            background-color: var(--success-light); 
            color: var(--success); 
        }
        .stat-icon.bg-warning { 
            background-color: var(--warning-light); 
            color: var(--warning); 
        }
        .stat-icon.bg-danger { 
            background-color: var(--danger-light); 
            color: var(--danger); 
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1A202C;
        }

        .stat-label {
            color: #718096;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary,
        .btn-outline-primary:hover:not(:disabled):not(.disabled) {
            background-color: var(--brand);
            border-color: var(--brand);
        }
        .btn-primary:hover:not(:disabled):not(.disabled) {
            background-color: var(--brand-hover);
            border-color: var(--brand-hover);
        }
        .btn-outline-primary {
            color: var(--brand);
            border-color: var(--brand);
        }

        /* Alerts */
        .alert-success {
            background-color: var(--success-light);
            border-color: var(--success);
            color: #2F855A;
        }
        .alert-danger {
            background-color: var(--danger-light);
            border-color: var(--danger);
            color: #C53030;
        }

        /* Table */
        .table th {
            font-weight: 600;
            color: #4A5568;
            background-color: #F8FAFC;
            border-bottom: 2px solid #E2E8F0;
        }

        .table-hover tbody tr:hover {
            background-color: #F9FAFB;
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
                z-index: 1030;
            }
            .admin-main { margin-left: 0; }
            .admin-sidebar.show { width: 260px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="admin-sidebar" id="adminSidebar">
    <div class="brand-logo">
        <i class="fas fa-futbol"></i>
        SPORTYKUY ADMIN
    </div>
    <div class="p-2">
        <div class="px-3 pb-3 border-bottom border-gray-700">
            <strong class="d-block">{{ Auth::user()->name }}</strong>
            <span class="badge bg-brand text-white mt-1 px-2 py-1" style="background-color: var(--brand)!important;">
                <i class="fas fa-crown fa-xs me-1"></i> Super Admin
            </span>
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('superadmin.dashboard') }}">
                    <i class="fas fa-chart-line"></i> Dashboard
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
                    <i class="fas fa-handshake"></i> Kelola Partner
                </a>
            </li>
            <li><hr class="nav-divider"></li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-gear"></i> Pengaturan Profil
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link text-danger w-100 text-start">
                        <i class="fas fa-right-from-bracket me-2"></i> Keluar
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
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-user me-1"></i> Profil Saya
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
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle fs-4 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle fs-4 me-2"></i>
                <div>{{ session('error') }}</div>
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