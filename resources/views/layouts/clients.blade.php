<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>{{ config('app.name') }} | @yield('title')</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
  <style>
    .showHide { display:none; }

    img {
        max-width: 100%;
        height: auto;
    }

    /* Desktop*/
    @media screen and (min-width: 768px) {
    .showHide  { display: initial;
    margin-left: 0.5em;}
    }
  </style>
  @yield('styles')
</head>
<body class="hold-transition layout-fixed sidebar-collapse">
<div class="wrapper">

  <!-- include('partials.splash') -->

  @include('partials.splash')

  @include('partials.header-cli')


  <!-- include('partials.aside') -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('section-name')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @yield('breadcrumb')
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       @yield('content')
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include('partials.footer')

</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.js') }}"></script>
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('plugins/showdown/showdown.min.js') }}"></script>
<script src="{{ asset('js/adminlte.js') }}"></script>
<script src="{{ asset('enable-push.js') }}" defer></script>
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
@if(auth()->user()->password_changed == 0)
<script>
    $(() => {
        Swal.fire({
        title: 'Es necesario cambiar la contraseña',
        html: `<p>Por favor, cambie la contraseña a una que le sea fácil de recordar.</p>
        <input type="password" id="password" value="" placeholder="Su contraseña" class="swal2-input" />
        <br/><sub>Recuerde que su contraseña debe contener al menos 8 caracteres, mayúsculas, minúsculas, números y caracteres especiales.</sub>`,
        confirmButtonText: 'Confirmar contraseña',
        allowOutsideClick: false,
        willOpen: () => {
            function checkPwd(str) {
                return str.length >= 8
                    && str.length < 50
                    && str.search(/\d/) != -1
                    && str.search(/[a-zA-Z]/) != -1
                    && str.search(/[^a-zA-Z0-9\!\$\%\(\)]/) != -1
            }

            Swal.disableButtons()
            $('#password').on('keyup', e => {
                if (checkPwd($('#password').val())) Swal.enableButtons()
                else Swal.disableButtons()
            })
        },
        preConfirm: () => {
            const password = $('#password').val();
            return {password: password}
        }
        }).then(e => {
            if (e.isConfirmed){
                $.ajax({
                    type: 'POST',
                    url: "{{ route('pwd.change') }}",
                    data: {password: e.value.password},
                    success: data => {
                        Swal.fire('Su contraseña se ha cambiado correctamente.', 'A continuación, su sesión se cerrará y podrá volver a entrar a la aplicación usando su nueva contraseña.', 'success')
                        .then(() => document.getElementById('logout-form').submit());
                    }
                })
            }
        })

    })
</script>
@endif
@yield('scripts')
</body>
</html>
