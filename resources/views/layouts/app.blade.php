<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Sistem Pengajuan Surat</title>

  <!-- Bootstrap CSS v5.3.5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('favicon-maranatha.png') }}">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <!-- Custom CSS -->
  <style>
    :root {
      --navy: #2c3e50;
      --navy-dark: #1a2634;
    }

    .bg-navy {
      background-color: var(--navy) !important;
    }

    /* Navbar styling */
    .navbar {
      height: 80px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .navbar .nav-link,
    .navbar .navbar-brand {
      color: #ffffff !important;
    }

    .navbar .dropdown-menu {
      background-color: var(--navy-dark);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .navbar .dropdown-item {
      color: #ffffff;
      transition: all 0.3s ease;
    }

    .navbar .dropdown-item:hover {
      background-color: rgba(255,255,255,0.1);
    }

    /* Sidebar styling */
    .sidebar {
      width: 250px;
      height: calc(100vh - 80px);
      background-color: var(--navy-dark);
      position: fixed;
      top: 80px;
      left: 0;
      overflow-y: auto;
      transition: all 0.3s ease;
    }

    .sidebar a {
      color: #ffffff;
      padding: 12px 20px;
      text-decoration: none;
      display: block;
      transition: all 0.3s ease;
    }

    .sidebar a:hover {
      background-color: rgba(255,255,255,0.05);
    }

    /* Content area */
    .content {
      margin-left: 250px;
      padding: 20px;
      margin-top: 80px;
      min-height: calc(100vh - 80px);
      background-color: #f8f9fa;
    }

    /* Notification Styles */
    .notification-container {
      position: fixed;
      top: 90px;
      right: 20px;
      z-index: 9999;
      max-width: 400px;
      width: 100%;
    }

    .alert-notification {
      border: none;
      border-radius: 8px;
      padding: 15px 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      border-left: 4px solid transparent;
    } opacity: 0.2;


/* Success Notification */
.notification-success {
    color: #2b8a3e;
    border-left-color: #2b8a3e;
    background-image: linear-gradient(135deg, 
        rgba(216, 245, 223, 0.9) 0%, 
        rgba(227, 250, 232, 0.9) 100%);
}

/* Error Notification */
.notification-error {
    color: #c92a2a;
    border-left-color: #c92a2a;
    background-image: linear-gradient(135deg, 
        rgba(255, 231, 235, 0.9) 0%, 
        rgba(255, 241, 243, 0.9) 100%);
}

/* Warning Notification */
.notification-warning {
    color: #e67700;
    border-left-color: #e67700;
    background-image: linear-gradient(135deg, 
        rgba(255, 243, 205, 0.9) 0%, 
        rgba(255, 247, 222, 0.9) 100%);
}

/* Info Notification */
.notification-info {
    color: #1864ab;
    border-left-color: #1864ab;
    background-image: linear-gradient(135deg, 
        rgba(207, 244, 252, 0.9) 0%, 
        rgba(222, 248, 252, 0.9) 100%);
}

.notification-icon {
    font-size: 1.6rem;
    margin-right: 15px;
    min-width: 35px;
    text-align: center;
}

.notification-success .notification-icon { color: #2b8a3e; }
.notification-error .notification-icon { color: #c92a2a; }
.notification-warning .notification-icon { color: #e67700; }
.notification-info .notification-icon { color: #1864ab; }

.btn-close {
    opacity: 0.7;
    transition: all 0.3s ease;
}

.btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
}
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-navy fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center ms-2" href="#">
        <img src="https://dsti.dev.mostar.co.id/wp-content/uploads/2020/12/logo-maranatha-white.png"
             alt="Logo Maranatha" height="60" class="me-2">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" 
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          @guest
            <!-- Login/Register links -->
          @else
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                 data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                  <a class="dropdown-item logout-btn" href="#">
                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                  </a>
                </li>
              </ul>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar">
    @php
      $role = Auth::user()->role ?? '';
    @endphp

    @if($role === 'admin')
      @include('layouts.sidebarAdmin')
    @elseif($role === 'kaprodi')
      @include('layouts.sidebarKaprodi')
    @elseif($role === 'mahasiswa')
      @include('layouts.sidebarMahasiswa')
    @elseif($role === 'superadmin')
      @include('layouts.sidebarSuperadmin')
    @endif
  </div>

  <!-- Content Area -->
  <div class="content">
    <!-- Notifications Container -->
    <div class="notification-container">
    @foreach (['success', 'error', 'warning', 'info'] as $type)
        @if(session()->has($type))
            <div class="alert notification-{{ $type }} alert-notification fade show mb-3">
                <i class="notification-icon 
                    @if($type == 'success') fas fa-check-circle
                    @elseif($type == 'error') fas fa-times-circle 
                    @elseif($type == 'warning') fas fa-exclamation-triangle
                    @else fas fa-info-circle
                    @endif"></i>
                <div class="flex-grow-1">{{ session($type) }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert notification-error alert-notification fade show mb-3">
                <i class="notification-icon fas fa-exclamation-circle"></i>
                <div class="flex-grow-1">{{ $error }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif
</div>

    @yield('content')
  </div>

  <!-- Bootstrap JS Bundle v5.3.5 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Auto-dismiss notifications after 5 seconds
      const alerts = document.querySelectorAll('.alert-notification');
      alerts.forEach(alert => {
        setTimeout(() => {
          alert.classList.remove('show');
          alert.classList.add('hide');
          setTimeout(() => alert.remove(), 300);
        }, 5000);
      });

      // Logout confirmation with SweetAlert
      document.querySelectorAll('.logout-btn').forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin keluar dari sistem?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2c3e50',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Ya, Logout',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
              document.getElementById('logout-form').submit();
            }
          });
        });
      });
    });
  </script>
</body>
</html>