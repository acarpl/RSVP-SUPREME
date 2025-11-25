<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPORTYKUY - Super Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #D85C5C;
        }
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #343a40;
            color: #e9ecef;
            height: 100vh;
            position: fixed;
            width: 260px;
            z-index: 1000;
        }
        .sidebar .brand {
            background-color: var(--brand);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.85rem 1.5rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #495057;
            color: white;
        }
        .sidebar .nav-link i {
            width: 28px;
            text-align: center;
            margin-right: 8px;
        }
        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
        }
        @media (max-width: 768px) {
            .sidebar { width: 220px; }
            .main-content { margin-left: 220px; }
        }
        @media (max-width: 576px) {
            .sidebar { width: 0; position: absolute; }
            .main-content { margin-left: 0; }
            .sidebar.show { width: 260px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="brand">
        <i class="fas fa-shield-alt me-2"></i> SUPER ADMIN
    </div>
    <div class="p-2">
        <small class="text-muted d-block px-3 pb-2">{{ Auth::user()->name }}</small>
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
                    <i class="fas fa-users"></i> Kelola User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.partners.*') ? 'active' : '' }}" 
                   href="{{ route('superadmin.partners.index') }}">
                    <i class="fas fa-building"></i> Kelola Partner
                </a>
            </li>
            <li><hr class="dropdown-divider mx-3"></li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog"></i> Profil Saya
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>@yield('title', 'Dashboard Super Admin')</h2>
        <span class="badge bg-danger">🔐 Super Admin</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>