<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maranatha Letter</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon-maranatha.png') }}">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome (untuk icon) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    /* Layout dua kolom: kiri gambar, kanan form */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      height: 100vh;
      display: flex;
      overflow: hidden;
    }

    /* Kolom kiri dengan gambar kampus dan animasi diagonal */
    .left-side {
      flex: 2;
      background: url('https://lh3.googleusercontent.com/gps-cs-s/AB5caB99CeO2mNQd7wa2aTID59R8u7aQqnZjRAi-S96mMNl4tH-h-NiyPnxPdmuHavz2JF5QxvHfwolC6k61bdJ8Fi09Us1kTSzTal2Fcwps3fXbFczkcLCXV6iEFXdWYofnJm-nulXV=s1360-w1360-h1020') no-repeat;
      background-size: cover;
      background-position: left bottom;
      animation: diagonalMove 60s infinite alternate;
      position: relative;
    }

    /* Overlay gelap */
    .left-side::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
    }

    /* Animasi background bergerak diagonal */
    @keyframes diagonalMove {
      0% {
        background-position: left bottom;
      }

      50% {
        background-position: center center;
      }

      100% {
        background-position: right top;
      }
    }

    /* Kolom kanan untuk form login */
    .right-side {
      flex: 1;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem 3rem;
      min-width: 300px;
    }

    /* Logo universitas */
    .logo-maranatha {
      max-width: 100px;
      margin-bottom: 1rem;
    }

    /* Judul Sign in */
    .sign-in-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    /* Input dengan garis bawah */
    .form-control {
      border: none;
      border-bottom: 1px solid #ccc;
      border-radius: 0;
      outline: none;
      box-shadow: none;
      transition: border-color 0.3s ease;
      font-size: 14px;
      padding: 8px 2px;
    }

    .form-control:focus {
      border-color: #0078D4;
    }

    /* Tombol login dengan efek hover elegan */
    .btn-primary {
      background-color: #0078D4;
      border: none;
      width: 100%;
      font-weight: 500;
      transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
      background-color: #005EA6;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 120, 212, 0.3);
    }

    /* Link bantuan */
    .assist-link {
      font-size: 14px;
      text-decoration: none;
      color: #0078D4;
    }

    .assist-link:hover {
      text-decoration: underline;
    }

    /* Disclaimer privasi */
    .disclaimer {
      font-size: 12px;
      color: #666;
      margin-top: 1rem;
    }

    /* Footer by Evan */
    .footer {
      font-size: 13px;
      color: #666;
      margin-top: 2rem;
      text-align: center;
      z-index: 2;
    }

    .footer .social-icons a {
      color: #666;
      transition: color 0.3s ease;
    }

    .footer .social-icons a:hover {
      color: #0078D4;
      /* Warna hover elegan */
    }
  </style>
</head>

<body>
  <!-- Kolom kiri: Gambar kampus Maranatha -->
  <div class="left-side"></div>

  <!-- Kolom kanan: Form login -->
  <div class="right-side">
    <!-- Logo (opsional) -->
    <img src="https://upload.wikimedia.org/wikipedia/id/thumb/7/73/Maranatha_Logo.svg/1200px-Maranatha_Logo.svg.png"
      alt="Logo Universitas Kristen Maranatha" class="logo-maranatha" />
    <!-- Judul Sign In -->
    <div class="sign-in-title">Sign in</div>

    <form method="POST" action="{{ route('login') }}">
  @csrf

  <!-- Field Email -->
  <div class="mb-3">
    <input id="email" type="email"
           name="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email') }}"
           placeholder="Email"
           required autocomplete="email" autofocus>
    @error('email')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
    @enderror
  </div>

  <!-- Field Password -->
  <div class="mb-3">
    <input id="password" type="password"
           name="password"
           class="form-control @error('password') is-invalid @enderror"
           placeholder="Password"
           required autocomplete="current-password">
    @error('password')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
    @enderror
  </div>

  <!-- Link "Can't access your account?" -->
  <div class="mb-3">
    <a href="https://wa.me/6281234567890" target="_blank" class="assist-link">
      Can't access your account?
    </a>
  </div>

  <!-- Tombol Login -->
  <button type="submit" class="btn btn-primary mb-3">Login</button>
</form>

    <!-- Disclaimer -->
    <div class="disclaimer">
      Personal information would be shared with the service if you proceed. By sign in, you agree to release your
      personal information to the service every time you access it.
    </div>

    <!-- Footer -->
    <div class="footer">
      <!-- Social Icons -->
      <div class="social-icons mb-2">
        <a href="https://www.maranatha.edu" target="blank" class="me-3" title="Website Maranatha">
          <i class="fas fa-globe fa-lg"></i>
        </a>
        <a href="https://www.instagram.com/universitaskristenmaranatha/" target="blank" class="me-3" title="Instagram Maranatha">
          <i class="fab fa-instagram fa-lg"></i>
        </a>
        <a href="https://www.youtube.com/@UniversitasKristenMaranatha" target="blank" title="YouTube Maranatha">
          <i class="fab fa-youtube fa-lg"></i>
        </a>
      </div>

      <!-- Copyright -->
      <div class="copyright">
        &copy; <span id="year"></span> Evan Kristian Pratama. All rights reserved.
      </div>
    </div>


  </div>
  <!-- Akhir kolom kanan -->

  <!-- Bootstrap JS & Font Awesome JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>