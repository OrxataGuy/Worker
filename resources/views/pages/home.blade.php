@if(auth()->user()->role==1)
    @extends('layouts.main')
@else
    @extends('layout.clients')
@endif

@section('title', 'Bienvenido')

@section('content')

@endsection
