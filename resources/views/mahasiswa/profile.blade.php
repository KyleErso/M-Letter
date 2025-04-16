@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-md-8">
            <!-- Header dengan Gradien Navy -->
            <div class="bg-navy-gradient text-white rounded-3 p-4 mb-4 shadow">
                <h4 class="mb-0 fw-semibold"><i class="bi bi-person-badge me-2"></i>Profil Mahasiswa</h4>
                <p class="mb-0 small opacity-75">Manajemen Data Pribadi</p>
            </div>

            <!-- Card Profil -->
            <div class="bg-white rounded-3 shadow-sm">
                <div class="p-4">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert bg-navy-gradient text-white alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif

                    <form action="{{ route('mahasiswa.profile') }}" method="POST" id="profile-form">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Mahasiswa -->
                        <div class="row g-3 mb-4">
                            <!-- Data Pribadi -->
                            <div class="col-md-6">
                                <label class="form-label small text-navy fw-medium">Nama Lengkap</label>
                                <div class="form-control border-navy bg-light text-muted">{{ $user->name }}</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small text-navy fw-medium">NRP</label>
                                <div class="form-control border-navy bg-light text-muted">{{ $user->id }}</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small text-navy fw-medium">Program Studi</label>
                                <div class="form-control border-navy bg-light text-muted">
                                    {{ $mahasiswa->kode_prodi ?? 'Belum diatur' }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small text-navy fw-medium">Fakultas</label>
                                <div class="form-control border-navy bg-light text-muted">
                                    {{ $mahasiswa->prodi->fakultas->nama_fakultas ?? 'Belum diatur' }}
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Mahasiswa -->
                        <div class="border-top border-navy pt-4">
                            @if(empty($mahasiswa->alamat))
                                <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Alamat belum diisi. Silakan isi alamat Anda.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small text-navy fw-medium">Alamat Lengkap</label>
                                    <textarea name="alamat" id="alamat" 
                                        class="form-control border-navy" 
                                        rows="3" 
                                        placeholder="Masukkan alamat lengkap...">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-navy">
                                    <i class="bi bi-save me-2"></i>Simpan Alamat
                                </button>
                            @else
                                <div class="mb-3">
                                    <label class="form-label small text-navy fw-medium">Alamat Lengkap</label>
                                    <textarea name="alamat" id="alamat" 
                                        class="form-control border-navy bg-light" 
                                        rows="3" 
                                        readonly>{{ $mahasiswa->alamat }}</textarea>
                                    @error('alamat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-navy-outline" id="btn-edit">
                                        <i class="bi bi-pencil me-2"></i>Edit Alamat
                                    </button>
                                    
                                    <button type="submit" class="btn btn-navy d-none" id="btn-save">
                                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --navy: #2c3e50;
        --navy-gradient: linear-gradient(45deg, #2c3e50, #34495e);
    }

    .bg-navy-gradient { background: var(--navy-gradient); }
    .text-navy { color: var(--navy); }
    .border-navy { border-color: var(--navy) !important; }
    
    .btn-navy {
        background-color: var(--navy);
        color: white;
        border: none;
    }
    
    .btn-navy:hover {
        background-color: #34495e;
        color: white;
    }
    
    .btn-navy-outline {
        border: 1px solid var(--navy);
        color: var(--navy);
    }
    
    .btn-navy-outline:hover {
        background-color: var(--navy);
        color: white;
    }
    
    .form-control-sm, .form-select-sm {
        border-radius: 0.5rem;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    
    .rounded-3 { border-radius: 1rem !important; }
</style>

<script>
    // Tetap sama dengan script sebelumnya
    document.getElementById('btn-edit')?.addEventListener('click', function () {
        const alamat = document.getElementById('alamat');
        alamat.classList.remove('bg-light');
        alamat.removeAttribute('readonly');
        alamat.focus();

        document.getElementById('btn-edit').classList.add('d-none');
        document.getElementById('btn-save').classList.remove('d-none');
    });
</script>
@endsection