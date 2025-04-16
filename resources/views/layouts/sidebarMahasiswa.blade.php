<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 250px; height: 100%;">
    <a href="{{ url('mahasiswa/dashboard') }}"
        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4">Mahasiswa Panel</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="{{ url('mahasiswa/dashboard') }}"
                class="nav-link text-white {{ Request::is('mahasiswa/dashboard') ? 'active bg-primary' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <a href="{{ route('mahasiswa.pengajuan-surat') }}"
            class="nav-link text-white {{ Request::is('mahasiswa/pengajuan-surat') ? 'active bg-primary' : '' }}">
            <i class="bi bi-pencil-square me-2"></i> Pengajuan Surat
        </a>

        <li class="nav-item mb-2">
            <a href="{{ url('mahasiswa/profil') }}"
                class="nav-link text-white {{ Request::is('mahasiswa/profile') ? 'active bg-primary' : '' }}">
                <i class="bi bi-person-circle me-2"></i> Profil
            </a>
        </li>
        <li class="nav-item mt-4">
            <!-- Ubah href menjadi '#' agar tidak terjadi request GET -->
            <a href="#" class="nav-link text-white logout-btn" onclick="event.preventDefault(); confirmLogout();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
    <hr>
    <div class="text-white-50 small">
        &copy; {{ date('Y') }} Sistem Surat | Mahasiswa
    </div>
</div>

<!-- Sertakan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>