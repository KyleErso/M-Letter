@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-md-6">
            <!-- Header dengan Gradien Navy -->
            <div class="bg-navy-gradient text-white rounded-3 p-4 mb-4 shadow">
                <h4 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Buat User Baru</h4>
                <p class="mb-0 small opacity-75">Superadmin Management Panel</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="bg-white rounded-3 shadow-sm">
                <div class="p-4">
                    @csrf

                    <!-- ID Field -->
                    <div class="mb-3">
                        <label class="form-label small text-navy fw-medium">ID User</label>
                        <input id="id" type="text" 
                               class="form-control form-control-sm border-navy @error('id') is-invalid @enderror" 
                               name="id" 
                               value="{{ old('id') }}" 
                               required 
                               autofocus>
                        @error('id')
                            <div class="invalid-feedback small d-flex align-items-center">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label class="form-label small text-navy fw-medium">Nama Lengkap</label>
                        <input id="name" type="text" 
                               class="form-control form-control-sm border-navy @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback small d-flex align-items-center">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label class="form-label small text-navy fw-medium">Email</label>
                        <input id="email" type="email" 
                               class="form-control form-control-sm border-navy @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback small d-flex align-items-center">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label class="form-label small text-navy fw-medium">Role</label>
                        <select id="role" 
                                class="form-select form-select-sm border-navy @error('role') is-invalid @enderror" 
                                name="role" 
                                required>
                            <option value="">Pilih Role</option>
                            <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kaprodi" {{ old('role') == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback small d-flex align-items-center">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Fakultas & Prodi -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-navy fw-medium">Fakultas</label>
                            <select id="kode_fakultas" 
                                    class="form-select form-select-sm border-navy @error('kode_fakultas') is-invalid @enderror" 
                                    name="kode_fakultas">
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $fak)
                                    <option value="{{ $fak->kode_fakultas }}" {{ old('kode_fakultas') == $fak->kode_fakultas ? 'selected' : '' }}>
                                        {{ $fak->nama_fakultas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_fakultas')
                                <div class="invalid-feedback small d-flex align-items-center">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small text-navy fw-medium">Prodi</label>
                            <select id="kode_prodi" 
                                    class="form-select form-select-sm border-navy @error('kode_prodi') is-invalid @enderror" 
                                    name="kode_prodi">
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->kode_prodi }}" {{ old('kode_prodi') == $prodi->kode_prodi ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_prodi')
                                <div class="invalid-feedback small d-flex align-items-center">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="useDefaultPassword">
                            <label class="form-check-label small text-navy" for="useDefaultPassword">
                                Gunakan password default (12345678)
                            </label>
                        </div>
                    </div>

                    <!-- Password Inputs -->
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <input id="password" 
                                       type="password" 
                                       class="form-control form-control-sm border-navy @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required
                                       placeholder="Password">
                                <button type="button" class="btn btn-navy-outline toggle-password" data-target="password">
                                    <i class="bi bi-eye-slash small"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback small d-flex align-items-center">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control form-control-sm border-navy" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Konfirmasi Password">
                                <button type="button" class="btn btn-navy-outline toggle-password" data-target="password-confirm">
                                    <i class="bi bi-eye-slash small"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-navy btn-sm py-2 fw-medium">
                            <i class="bi bi-save me-2"></i>Simpan User
                        </button>
                    </div>
                </div>
            </form>
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
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = (target) => {
        const input = document.getElementById(target);
        const icon = document.querySelector(`[data-target="${target}"] i`);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    };

    const defaultCheckbox = document.getElementById('useDefaultPassword');
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password-confirm');

    defaultCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordField.value = '12345678';
            confirmField.value = '12345678';
            passwordField.readOnly = confirmField.readOnly = true;
            passwordField.classList.add('bg-light', 'text-muted');
            confirmField.classList.add('bg-light', 'text-muted');
        } else {
            passwordField.value = confirmField.value = '';
            passwordField.readOnly = confirmField.readOnly = false;
            passwordField.classList.remove('bg-light', 'text-muted');
            confirmField.classList.remove('bg-light', 'text-muted');
        }
    });

    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => togglePassword(btn.dataset.target));
    });
});
</script>
@endsection