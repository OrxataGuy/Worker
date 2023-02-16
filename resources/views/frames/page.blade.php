<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env('APP_NAME') }} | @yield('title')</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/css/adminlte.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/plugins/summernote/summernote-bs4.min.css">
  @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- include('partials.splash') -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin:0;">
    <!-- Content Header (Page header) -->
       <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
       @yield('content')
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('partials.footer')

</div>

<script src="{{ env('APP_URL') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ env('APP_URL') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/chart.js/Chart.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/sparklines/sparkline.js"></script>
<script src="{{ env('APP_URL') }}/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="{{ env('APP_URL') }}/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/moment/moment.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/daterangepicker/daterangepicker.js"></script>
<script src="{{ env('APP_URL') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/summernote/summernote-bs4.min.js"></script>
<script src="{{ env('APP_URL') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ env('APP_URL') }}/js/adminlte.js"></script>
<script src="{{ env('APP_URL') }}/js/pages/dashboard.js"></script>
@yield('scripts')
</body>
</html>