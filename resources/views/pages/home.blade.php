@if(auth()->user()->role==1)
    @extends('layouts.main')
@else
    @extends('layouts.clients')
@endif

@section('title', 'Bienvenido')

@section('content')

@endsection
