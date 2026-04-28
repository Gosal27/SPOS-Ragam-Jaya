<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RagamJaya SPOS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
  <!-- Alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ Scroll fix style -->
  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden; /* cegah scroll seluruh body */
      background: #f4f6f9;
    }

    .wrapper {
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Pastikan content-wrapper bisa scroll */
    .content-wrapper {
      flex: 1 1 auto;
      min-height: 0; /* penting agar overflow aktif */
      overflow-y: auto;
      overflow-x: hidden;
      scroll-behavior: smooth;
      background: #f4f6f9;
      padding-bottom: 1rem;
    }

    /* Header section */
    .content-header {
      padding: 15px 20px;
    }

    /* Scrollbar styling */
    .content-wrapper::-webkit-scrollbar {
      width: 8px;
    }

    .content-wrapper::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 4px;
    }

    /* Responsif untuk HP */
    @media (max-width: 767.98px) {
      html, body {
        overflow: auto;
      }
      .content-wrapper {
        height: auto;
      }
    }
  </style>
</head>

@yield('scripts')

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  @include('layout.components.navbar')
  <!-- /.navbar -->

  <!-- Sidebar -->
  @include('layout.components.sidebar')

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Header -->
    <section class="content-header">
      <div class="container-fluid">
        @yield('header')
      </div>
    </section>

    <!-- Content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
<!-- Demo purposes -->
<script src="{{ asset('templates/dist/js/demo.js') }}"></script>
</body>
</html>
