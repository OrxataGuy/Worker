@extends('layouts.main')

@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Pagos de {{ $client->name }}</a></li>
@endsection
@section('content')
