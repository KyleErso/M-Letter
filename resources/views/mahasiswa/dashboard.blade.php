@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Error Message -->
        @php
            $pending = $pengajuan->where('status', 'menunggu');
            $approved = $pengajuan->where('status', 'disetujui');
            $rejected = $pengajuan->where('status', 'ditolak');
        @endphp

        <div class="bg-navy-gradient text-white rounded-3 shadow-lg overflow-hidden mb-4 position-relative">
            <div class="container-fluid p-0 min-vh-100"
                style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
                <!-- Animated Waves Background -->
                <div class="position-absolute w-100 h-100 overflow-hidden">
                    <div class="wave wave-1"></div>
                    <div class="wave wave-2"></div>
                    <div class="wave wave-3"></div>
                </div>

                <!-- Main Content -->
                <div class="position-relative" style="z-index: 1; padding: 2rem">
                    <!-- Dashboard Header -->
                    <div class="py-5 px-4 px-lg-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="flex-grow-1">
                                <h1 class="display-4 fw-bold text-white mb-3">
                                    <i class="me-3"></i>Dashboard Mahasiswa
                                </h1>

                                <!-- Welcome Section -->
                                <div class="mb-5">
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <i class="bi bi-person-fill fs-1 text-warning"></i>
                                        <div>
                                            <p class="lead text-white-80 mb-1">Selamat datang !</p>
                                            <h2 class="text-white mb-0">{{ Auth::user()->name }}</h2>
                                        </div>
                                    </div>

                                    <!-- Student Info Grid -->
                                    <div class="row g-4">
                                        <!-- NRP Card -->
                                        <div class="col-md-6">
                                            <div class="bg-white-10 p-4 rounded-3 shadow-inset hover-scale">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="bi bi-qr-code fs-1 text-primary"></i>
                                                    <div>
                                                        <div class="text-white-60 small">Nomor Induk</div>
                                                        <div class="h3 text-white mb-0">{{ Auth::user()->id }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Program Studi Card -->
                                        <div class="col-md-6">
                                            <div class="bg-white-10 p-4 rounded-3 shadow-inset hover-scale">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="bi bi-building-gear fs-1 text-success"></i>
                                                    <div>
                                                        <div class="text-white-60 small">Program Studi</div>
                                                        <div class="h3 text-white mb-0">
                                                            {{ $mahasiswa->prodi->nama_prodi ?? '-' }}
                                                            <small class="d-block text-white-60 fs-6">
                                                                {{ optional($mahasiswa->prodi->fakultas)->nama_fakultas ?? '-' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Icon -->
                            <div class="floating-student">
                                <i class="bi bi-person-workspace display-3 text-white-50"></i>
                            </div>
                        </div>

                        <!-- Stats Row -->
                        <div class="row g-4 mb-5">
                            <!-- Pending Card -->
                            <div class="col-md-6">
                                <div class="stats-card bg-white-10 hover-float">
                                    <div class="d-flex align-items-center p-4">
                                        <div class="icon-wrapper bg-warning-20 me-4">
                                            <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="text-white-60 small">Menunggu Verifikasi</div>
                                            <div class="display-5 fw-bold text-white">{{ $pending->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Card -->
                            <div class="col-md-6">
                                <div class="stats-card bg-white-10 hover-float">
                                    <div class="d-flex align-items-center p-4">
                                        <div class="icon-wrapper bg-success-20 me-4">
                                            <i class="bi bi-check-circle fs-1 text-success"></i>
                                        </div>
                                        <div>
                                            <div class="text-white-60 small">Pengajuan Disetujui</div>
                                            <div class="display-5 fw-bold text-white">{{ $approved->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="text-center mb-5">
                            <button class="btn btn-xl btn-glow-primary rounded-pill px-5 py-3 shadow-lg"
                                data-bs-toggle="modal" data-bs-target="#modalPengajuan">
                                <i class="bi bi-magic me-2"></i>Buat Pengajuan Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Request Modal -->
        <div class="modal fade" id="modalPengajuan" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('mahasiswa.pengajuan.redirect') }}" method="GET">
                        @csrf
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold" id="modalPengajuanLabel">
                                <i class="bi bi-file-earmark-plus me-2"></i>Jenis Pengajuan
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Pilih jenis surat yang ingin diajukan</label>
                                <select class="form-select rounded-3" name="jenis_surat" required>
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    <option value="1">Surat Keterangan Aktif</option>
                                    <option value="2">Surat Pengantar Tugas Kuliah</option>
                                    <option value="4">Laporan Hasil Studi</option>
                                    <option value="5">Surat Keterangan Lulus</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-3">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



    </div>
    <style>

        :root {
            --navy: #2c3e50;
            --blue: #3498db;
            --success: #2ecc71;
            --warning: #f1c40f;
        }

        /* Animated Waves */
        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 200%;
            height: 15em;
            background-repeat: repeat no-repeat;
            animation: wave 15s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
        }

        .wave-1 {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 88.7'%3E%3Cpath d='M800 56.9c-155.5 0-204.9-50-405.5-49.9-200 0-250 49.9-394.5 49.9v31.8h800v-.2-31.6z' fill='%23FFFFFF' opacity='0.1'/%3E%3C/svg%3E");
            animation-duration: 20s;
        }

        .wave-2 {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 88.7'%3E%3Cpath d='M800 56.9c-155.5 0-204.9-50-405.5-49.9-200 0-250 49.9-394.5 49.9v31.8h800v-.2-31.6z' fill='%23FFFFFF' opacity='0.15'/%3E%3C/svg%3E");
            animation-duration: 15s;
            bottom: -2em;
        }

        .wave-3 {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 88.7'%3E%3Cpath d='M800 56.9c-155.5 0-204.9-50-405.5-49.9-200 0-250 49.9-394.5 49.9v31.8h800v-.2-31.6z' fill='%23FFFFFF' opacity='0.2'/%3E%3C/svg%3E");
            animation-duration: 10s;
            bottom: -4em;
        }

        @keyframes wave {
            0% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        /* Custom Components */
        .stats-card {
            border-radius: 1.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hover-float:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .bg-white-10 {
            background: rgba(255, 255, 255, 0.1);
        }

        .icon-wrapper {
            padding: 1rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-glow-primary {
            background: var(--blue);
            color: white;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-glow-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-glow-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: all 0.5s;
        }

        .btn-glow-primary:hover::after {
            left: 100%;
        }

        .floating-student {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        :root {
            --navy: #2c3e50;
            --navy-gradient: linear-gradient(45deg, #2c3e50, #34495e);
        }

        .bg-navy-gradient {
            background: var(--navy-gradient);
        }

        .text-navy {
            color: var(--navy);
        }

        .border-navy {
            border-color: var(--navy) !important;
        }

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

        .form-control-sm,
        .form-select-sm {
            border-radius: 0.5rem;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .rounded-3 {
            border-radius: 1rem !important;
        }

        /* Tambahan Style untuk Header Profil */
        .border-bottom-white {
            border-bottom: 2px solid rgba(255, 255, 255, 0.2) !important;
        }

        .lead {
            font-size: 1.25rem;
        }

        .fs-5 {
            font-size: 1.1rem;
        }

        /* Memperhalus gradien */
        .bg-navy-gradient {
            background: linear-gradient(45deg,
                    #2c3e50 0%,
                    #34495e 50%,
                    #2c3e50 100%);
            background-size: 200% 200%;
            transition: background-position 0.5s ease;
        }

        .bg-navy-gradient:hover {
            background-position: 100% 100%;
        }

        /* Efek Tambahan */
        .hover-scale {
            transition: all 0.3s ease;
            transform-origin: left;
        }

        .hover-scale:hover {
            transform: scale(1.02);
            cursor: default;
        }

        .shadow-inset {
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .bg-white-10 {
            background: rgba(255, 255, 255, 0.1);
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.85);
        }

        .border-bottom-gradient {
            border-bottom: 2px solid;
            border-image: linear-gradient(45deg, #fff, rgba(255, 255, 255, 0.5)) 1;
        }

        .text-gradient {
            background: linear-gradient(45deg, #fff, #e0f2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection