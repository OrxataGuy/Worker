@extends('layouts.main')
@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Crear nuevo cliente</li>
@endsection
@section('content')
<form method="post" action="{{ route('clients.create') }}">
    @csrf
    <div class="row">
        <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
            <h3 class="card-title">@if(!isset($client)) Nuevo @endif Cliente</h3>

            <div class="card-tools">

            </div>
            </div>
            <div class="card-body">
            <div class="form-group">
                <label for="inputName">Nombre</label>
                @if(isset($client))
                    <input type="hidden" id="client_id" name="client_id" value="{{ $client->id }}" />
                @endif
                <input type="text" id="inputName" name="clientName" @if(isset($client)) value="{{ $client->name }}" readonly @endif class="form-control">
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputEmail" name="clientEmail" @if(isset($client)) value="{{ $client->email }}" readonly @endif class="form-control">
            </div>
            <div class="form-group">
                <label for="inputPhone">Teléfono</label>
                <input type="tel" id="inputPhone" name="clientPhone" @if(isset($client)) value="{{ $client->phone }}" readonly @endif class="form-control">
            </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <div class="col-md-6">
        <div class="card card-secondary">
            <div class="card-header">
            <h3 class="card-title">@if(!isset($client)) Primer @else Nuevo @endif proyecto</h3>
            <div class="card-tools">

            </div>
            </div>
            <div class="card-body">
            <div class="form-group">
                <label for="inputProjectName">Nombre</label>
                <input type="text" id="inputProjectName" name="projectName" class="form-control">
            </div>
            <div class="form-group">
                <label for="inputProjectDescription">Descripción</label>
                <textarea id="inputProjectDescription" name="projectDescription" class="form-control"></textarea>
            </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-12">
             @if(isset($client))
             <input type="submit" value="Crear Proyecto" class="btn btn-success float-right">
             @else
             <input type="submit" value="Crear Cliente" class="btn btn-success float-right">
                @endif

        </div>
    </div>
</form>
@endsection
