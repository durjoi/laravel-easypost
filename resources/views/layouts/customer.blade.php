<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>{{ config('app.name') }} | Customer</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/dist/css/adminlte.min.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  @yield('page-css')
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="#" class="navbar-brand">
        <!-- <img src="" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
      </a>
      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      @include('partial.front.customermenu')

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="javascript:void(0)" role="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt fa-fw"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('customer.auth.logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </li>
      </ul>
    </div>
  </nav>
  <div class="content-wrapper">
    @yield('content')
  </div>
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="{{ url('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('assets/dist/js/adminlte.min.js') }}"></script>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
</script>
@yield('page-js')
</body>
</html>