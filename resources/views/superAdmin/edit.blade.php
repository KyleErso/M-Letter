@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center py-4">
        <div class="col-md-6">
            <!-- Header dengan Gradien Navy -->
            <div class="bg-navy-gradient text-white rounded-3 p-4 mb-4 shadow">
                <h4 class="mb-0 fw-semibold">
                    <i class="bi bi-person-gear me-2"></i>Edit User
                </h4>
                <p class="mb-0 small opacity-75">Superadmin Management Panel</p>
            </div>

            <form id="editForm" action="{{ route('superadmin.users.update', $user->id) }}" method="POST" class="bg-white rounded-3 shadow-sm">
                <div class="p-4">
                    @csrf
                    @method('PUT')

                    <!-- Input Hidden untuk role -->
                    <input type="hidden" name="role" value="{{ old('role', $user->role) }}">

                    <!-- Nama Field -->
                    <div class="mb-3">
                        <label class="form-label small text-navy fw-medium">Nama Lengkap</label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-sm border-navy @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
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
                        <input type="email" 
                               name="email" 
                               class="form-control form-control-sm border-navy @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback small d-flex align-items-center">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label class="form-label small text-navy fw-medium">Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" 
                                   id="password"
                                   name="password" 
                                   class="form-control form-control-sm border-navy @error('password') is-invalid @enderror"
                                   placeholder="Masukkan password baru">
                            <button type="button" 
                                    class="btn btn-navy-outline toggle-password" 
                                    data-target="password"
                                    title="Toggle Password Visibility">
                                <i class="bi bi-eye-slash small"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted mt-1 d-block">Kosongkan jika tidak ingin mengubah password</small>
                        @error('password')
                            <div class="invalid-feedback small d-flex align-items-center mt-1">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="mb-4">
                        <label class="form-label small text-navy fw-medium">Konfirmasi Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" 
                                   id="password_confirmation"
                                   name="password_confirmation" 
                                   class="form-control form-control-sm border-navy @error('password_confirmation') is-invalid @enderror"
                                   placeholder="Konfirmasi password baru">
                            <button type="button" 
                                    class="btn btn-navy-outline toggle-password" 
                                    data-target="password_confirmation"
                                    title="Toggle Password Visibility">
                                <i class="bi bi-eye-slash small"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback small d-flex align-items-center mt-1">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-navy-outline btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-navy btn-sm">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
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
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-navy:hover {
        background-color: #34495e;
        color: white;
    }

    .btn-navy-outline {
        border: 1px solid var(--navy);
        color: var(--navy);
        padding: 0.35rem 0.75rem;
        transition: all 0.3s ease;
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
    
    .input-group-sm > .btn {
        padding: 0.35rem 0.75rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.dataset.target;
            const input = document.getElementById(target);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    });

    // Form Submission Confirmation
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: "Apakah Anda yakin ingin menyimpan perubahan?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2c3e50',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-save me-2"></i>Ya, Simpan',
            cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endsection