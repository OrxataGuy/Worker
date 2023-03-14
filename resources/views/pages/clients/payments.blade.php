@if(auth()->user()->role==1)
    @php $layout = ; @endphp
@else
    @php $layout = 'layouts.clients'; @endphp
@endif

@extends($layout)

@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')

@endsection
@section('content')

@endsection
