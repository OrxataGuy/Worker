@extends('layouts.page')

@section('content')
<div class="error-page">
    <h2 class="headline text-warning"> 404</h2>

    <div class="error-content">
      <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Parece que la página no existe.</h3>

      <p>
        No he podido encontrar la sección que buscas.
        Puedes volver a la <a href="{{ route('home') }}">página principal</a> y buscarlo mejor desde ahí.
      </p>

    </div>
    <!-- /.error-content -->
  </div>
@endsection
