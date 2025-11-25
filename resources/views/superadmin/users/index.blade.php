@extends('layouts.superadmin')

@section('title', 'Kelola Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i> Daftar Pengguna</h5>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-brand">
            <i class="fas fa-plus me-1"></i> Tambah Pengguna
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($users->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-users-slash text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">Belum ada pengguna</h5>
                <p class="text-muted">Pengguna akan muncul setelah mendaftar atau ditambahkan secara manual.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col"><i class="fas fa-user-tag me-1"></i> Role</th>
                            <th scope="col"><i class="fas fa-calendar me-1"></i> Terdaftar</th>
                            <th scope="col" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center me-2"
                                         style="width: 36px; height: 36px; font-weight: 600;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'super_admin')
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($user->role === 'partner')
                                    <span class="badge bg-success">Partner</span>
                                @else
                                    <span class="badge bg-secondary">Customer</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('superadmin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== Auth::id() && $user->role !== 'super_admin')
                                    <form action="{{ route('superadmin.users.destroy', $user) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection