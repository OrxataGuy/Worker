@extends('frames.main')
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
            <h3 class="card-title">Cliente</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
            </div>
            <div class="card-body">
            <div class="form-group">
                <label for="inputName">Nombre</label>
                <input type="text" id="inputName" name="clientName" class="form-control">
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputEmail" name="clientEmail" class="form-control">
            </div>
            <div class="form-group">
                <label for="inputPhone">Teléfono</label>
                <input type="tel" id="inputPhone" name="clientPhone" class="form-control">
            </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <div class="col-md-6">
        <div class="card card-secondary">
            <div class="card-header">
            <h3 class="card-title">Proyecto</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
                </button>
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
        <input type="submit" value="Crear Cliente" class="btn btn-success float-right">
        </div>
    </div>
</form>
@endsection
