@if(auth()->user()->role==1)
    @php $layout = 'layouts.main'; @endphp
@else
    @php $layout = 'layouts.clients'; @endphp
@endif

@extends($layout)

@section('title', 'Bienvenido')

@section('content')

@endsection
