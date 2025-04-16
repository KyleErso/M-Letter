@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-navy fw-bold mb-0">Kelola Institusi</h1>
            <p class="text-muted">Manajemen Fakultas dan Program Studi</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInstitutionModal">
            <i class="bi bi-plus-circle me-2"></i>Tambah Data
        </button>
    </div>

    <!-- Alert Notification -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Data Table -->
    <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
        <div class="card-header bg-navy-gradient text-white py-3">
            <h5 class="mb-0"><i class="bi bi-building me-2"></i>Daftar Institusi</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light-blue">
                        <tr>
                            <th class="text-nowrap text-navy">Kode Fakultas</th>
                            <th class="text-navy">Nama Fakultas</th>
                            <th class="text-navy">Kode Prodi</th>
                            <th class="text-navy">Nama Prodi</th>
                            <th class="text-navy">Jenjang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prodis as $prodi)
                        <tr class="hover-blue">
                            <td class="fw-medium text-primary">{{ $prodi->kode_fakultas }}</td>
                            <td>{{ $prodi->fakultas->nama_fakultas ?? 'N/A' }}</td>
                            <td class="fw-medium text-primary">{{ $prodi->kode_prodi }}</td>
                            <td>{{ $prodi->nama_prodi }}</td>
                            <td><span class="badge bg-navy rounded-pill">{{ $prodi->jenjang }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-database-exclamation me-2"></i>Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="addInstitutionModal" tabindex="-1" aria-labelledby="addInstitutionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <!-- Modal Header -->
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title"><i class="bi bi-building-add me-2"></i>Tambah Institusi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Tab Navigation -->
                <nav class="bg-light-blue px-4 pt-3">
                    <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-fakultas-prodi-tab" data-bs-toggle="tab" 
                            data-bs-target="#nav-fakultas-prodi" type="button" role="tab">
                            <i class="bi bi-building me-2"></i>Fakultas & Prodi
                        </button>
                        <button class="nav-link" id="nav-prodi-tab" data-bs-toggle="tab" 
                            data-bs-target="#nav-prodi" type="button" role="tab">
                            <i class="bi bi-journal-code me-2"></i>Prodi Saja
                        </button>
                    </div>
                </nav>

                <!-- Tab Content -->
                <div class="modal-body tab-content pt-4">
                    <!-- Tab 1 Content -->
                    <div class="tab-pane fade show active" id="nav-fakultas-prodi" role="tabpanel">
                        <form action="{{ route('superadmin.institution.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <!-- Fakultas Section -->
                                <div class="col-md-6">
                                    <h6 class="text-navy mb-3"><i class="bi bi-building me-2"></i>Data Fakultas</h6>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Kode Fakultas</label>
                                        <input type="text" name="kode_fakultas" class="form-control form-control-sm rounded-2" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Nama Fakultas</label>
                                        <input type="text" name="nama_fakultas" class="form-control form-control-sm rounded-2" required>
                                    </div>
                                </div>

                                <!-- Prodi Section -->
                                <div class="col-md-6">
                                    <h6 class="text-navy mb-3"><i class="bi bi-journal-code me-2"></i>Data Prodi</h6>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Kode Prodi</label>
                                        <input type="text" name="kode_prodi" class="form-control form-control-sm rounded-2" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Nama Prodi</label>
                                        <input type="text" name="nama_prodi" class="form-control form-control-sm rounded-2" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Jenjang</label>
                                        <input type="text" name="jenjang" class="form-control form-control-sm rounded-2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-4">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-navy btn-sm">
                                    <i class="bi bi-save me-2"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2 Content -->
                    <div class="tab-pane fade" id="nav-prodi" role="tabpanel">
                        <form action="{{ route('superadmin.institution.storeProdi') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <h6 class="text-navy mb-3"><i class="bi bi-journal-code me-2"></i>Data Prodi</h6>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Fakultas</label>
                                        <select name="kode_fakultas" class="form-select form-select-sm rounded-2" required>
                                            <option value="">Pilih Fakultas</option>
                                            @foreach ($fakultas as $f)
                                            <option value="{{ $f->kode_fakultas }}">
                                                {{ $f->nama_fakultas }} ({{ $f->kode_fakultas }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Kode Prodi</label>
                                            <input type="text" name="kode_prodi" class="form-control form-control-sm rounded-2" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">Nama Prodi</label>
                                            <input type="text" name="nama_prodi" class="form-control form-control-sm rounded-2" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small text-muted">Jenjang</label>
                                            <input type="text" name="jenjang" class="form-control form-control-sm rounded-2" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-4">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-navy btn-sm">
                                    <i class="bi bi-save me-2"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --navy: #2c3e50;
        --light-blue: #f8f9fa;
        --hover-blue: #e9f2ff;
    }

    .bg-navy { background-color: var(--navy) !important; }
    .text-navy { color: var(--navy) !important; }
    .bg-navy-gradient { background: linear-gradient(45deg, var(--navy), #34495e); }
    .bg-light-blue { background-color: var(--light-blue) !important; }
    .btn-navy { 
        background-color: var(--navy);
        color: white;
    }
    .btn-navy:hover {
        background-color: #34495e;
        color: white;
    }
    .hover-blue:hover { background-color: var(--hover-blue) !important; }
    .rounded-2 { border-radius: 0.5rem !important; }
    .badge.bg-navy { background-color: var(--navy) !important; }
    .table-hover tbody tr:hover { background-color: var(--hover-blue); }
    .form-control-sm { 
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation dialog for deletion
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection