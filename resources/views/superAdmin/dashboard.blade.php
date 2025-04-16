@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-navy fw-bold mb-0">Super Admin Dashboard</h1>
            <p class="text-muted mb-0">Selamat datang, <strong class="text-navy">{{ Auth::user()->name }}</strong> (ID: {{ Auth::user()->id }})</p>
        </div>
        <a href="{{ route('register') }}" class="btn btn-navy">
            <i class="bi bi-person-plus me-2"></i>Tambah User
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-lg rounded-3 mb-4">
        <div class="card-header bg-navy-gradient text-white py-3">
            <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Data User</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.dashboard') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="text" name="search" 
                               class="form-control form-control-sm" 
                               placeholder="Cari nama..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select form-select-sm">
                            <option value="">Semua Role</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kaprodi" {{ request('role') == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="fakultas" class="form-select form-select-sm">
                            <option value="">Semua Fakultas</option>
                            @foreach ($fakultas as $f)
                                <option value="{{ $f->kode_fakultas }}" {{ request('fakultas') == $f->kode_fakultas ? 'selected' : '' }}>
                                    {{ $f->nama_fakultas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="prodi" class="form-select form-select-sm">
                            <option value="">Semua Prodi</option>
                            @foreach ($prodi as $p)
                                <option value="{{ $p->kode_prodi }}" {{ request('prodi') == $p->kode_prodi ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-navy btn-sm w-100">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-navy btn-sm w-100">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- User Table -->
    <div class="card border-0 shadow-lg rounded-3">
        <div class="card-header bg-navy-gradient text-white py-3">
            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Daftar User</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light-navy">
                        <tr>
                            <th class="text-navy border-bottom-0">ID</th>
                            <th class="text-navy border-bottom-0">Nama</th>
                            <th class="text-navy border-bottom-0">Email</th>
                            <th class="text-navy border-bottom-0">Role</th>
                            <th class="text-navy border-bottom-0">Fakultas</th>
                            <th class="text-navy border-bottom-0">Prodi</th>
                            <th class="text-navy border-bottom-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr class="hover-blue">
                            <td class="fw-medium text-primary">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge rounded-pill py-2 px-3 
                                    @switch($user->role)
                                        @case('superadmin') bg-navy @break
                                        @case('admin') bg-secondary @break
                                        @case('kaprodi') bg-info @break
                                        @case('mahasiswa') bg-success @break
                                        @default bg-light text-dark
                                    @endswitch">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->fakultas->nama_fakultas ?? '-' }}</td>
                            <td>{{ $user->prodi->nama_prodi ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('superadmin.users.edit', $user->id) }}" 
                                       class="btn btn-sm btn-navy-outline"
                                       data-bs-toggle="tooltip" 
                                       title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" 
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-navy-outline"
                                                data-bs-toggle="tooltip" 
                                                title="Hapus User"
                                                onclick="return confirm('Yakin hapus user ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-people-slash me-2"></i>Tidak ada data user
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --navy: #2c3e50;
        --navy-gradient: linear-gradient(45deg, #2c3e50, #34495e);
        --light-navy: #f8f9fa;
        --hover-blue: #e9f2ff;
    }

    .bg-navy-gradient { background: var(--navy-gradient); }
    .bg-light-navy { background-color: var(--light-navy); }
    .text-navy { color: var(--navy); }
    .btn-navy { 
        background-color: var(--navy);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
    }
    .btn-navy:hover {
        background-color: #34495e;
        color: white;
    }
    .btn-navy-outline {
        border: 1px solid var(--navy);
        color: var(--navy);
        padding: 0.35rem 0.75rem;
    }
    .btn-navy-outline:hover {
        background-color: var(--navy);
        color: white;
    }
    .hover-blue:hover { background-color: var(--hover-blue); }
    .table-hover tbody tr:hover { background-color: var(--hover-blue); }
    .badge.bg-navy { background-color: var(--navy); }
    .form-select-sm { 
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
@endsection