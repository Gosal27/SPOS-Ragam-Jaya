<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SPOS Ragam Jaya | Login</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Bootstrap & AdminLTE -->
  <link rel="stylesheet" href="{{ asset('templates/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #007bff, #6f42c1);
  min-height: 100vh;
  display: flex;
  align-items: center;       /* Pusatkan vertikal */
  justify-content: center;   /* Pusatkan horizontal */
  margin: 0;
  padding: 40px 0;           /* Tambah jarak aman atas bawah */
  overflow: hidden;
}

.login-container {
  display: flex;
  width: 950px;
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  animation: fadeInUp 0.8s ease;
}

.login-image {
  flex: 1.2;
  background: url('https://img.freepik.com/free-vector/retail-store-concept-illustration_114360-9039.jpg') center center no-repeat;
  background-size: cover;
  min-height: 480px;
}

.login-form {
  flex: 1;
  padding: 50px 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.login-logo a {
  font-size: 2rem;
  color: #007bff;
  font-weight: 700;
  text-decoration: none;
}

.form-control {
  border-radius: 10px;
}

.btn-primary {
  background: linear-gradient(90deg, #007bff, #6f42c1);
  border: none;
  border-radius: 10px;
  font-weight: 600;
  transition: 0.3s;
}

.btn-primary:hover {
  background: linear-gradient(90deg, #6f42c1, #007bff);
  transform: translateY(-1px);
}

.small-text {
  font-size: 0.9rem;
  color: #6c757d;
}

.footer-text {
  text-align: center;
  margin-top: 30px;
  font-size: 0.85rem;
  color: #6c757d;
}

@media (max-width: 768px) {
  .login-container {
    flex-direction: column;
    width: 90%;
  }
  .login-image {
    display: none;
  }
}

/* Fade-in animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}


    /* Fade-in animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-container {
      animation: fadeInUp 0.8s ease;
    }
  </style>
</head>

<body>

  @if (session('error-unauthorized'))
  <script>
      Swal.fire({
          icon: 'warning',
          title: 'Terjadi Kesalahan',
          text: 'Silahkan Login Terlebih Dahulu',
          showConfirmButton: false,
          timer: 3000,
          toast: true,
          position: 'top-end',
          timerProgressBar: true
      });
  </script>
  @endif

  <div class="login-container">
    <div class="login-image"></div>

    <div class="login-form">
      <div class="login-logo mb-3 text-center">
        <a href="/"><b>SPOS</b> RagamJaya</a>
      </div>
      <p class="text-center small-text mb-4">Masuk ke akun Anda untuk memulai sistem penjualan.</p>

      {{-- Pesan error --}}
      @if ($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      @endif

      <form action="/login" method="post">
        @csrf

        <!-- Username -->
        <div class="input-group mb-3">
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama User" value="{{ old('name') }}" autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
            <span class="fas fa-user"></span>
            </div>
        </div>
        @error('name')
            <span class="invalid-feedback d-block">{{ $message }}</span>
        @enderror
        </div>

        <!-- Password -->
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kata Sandi">
          <div class="input-group-append">
            <div class="input-group-text">
              <a href="#" onclick="togglePassword(event)">
                <i class="fas fa-eye" id="toggleIcon"></i>
              </a>
            </div>
          </div>
          @error('password')
            <span class="invalid-feedback d-block">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block py-2 mb-3">
          <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
        </button>

        <div class="text-center small-text">
            <a href="{{ route('password.request') }}" class="text-primary">Lupa Password?</a>
        </div>

        <div class="footer-text">
          <hr>
          Sistem Peramalan & Optimalisasi Stok (SPOS)<br>
          © <b>Ragam Jaya 2025</b>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>

  <script>
    function togglePassword(event) {
      event.preventDefault();
      const passwordInput = document.getElementById('password');
      const icon = document.getElementById('toggleIcon');
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        passwordInput.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>

</body>
</html>
