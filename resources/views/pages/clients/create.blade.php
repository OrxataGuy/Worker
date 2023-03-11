@if(auth()->user()->role==1)
    @extends('layouts.main')
@else
    @extends('layout.clients')
@endif

@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Crear nuevo cliente</li>
@endsection
@section('content')
<form method="post" action="{{ route('clients.store') }}">
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
        <div class="col-md-12 col-xs-2">
        <div class="card card-secondary">
            <div class="card-header" data-card-widget="collapse" title="Collapse">
            <h3 class="card-title">Tecnologías a utilizar en el proyecto</h3>
            <div class="card-tools">

            </div>
            </div>
            <div class="card-body">
                <h4>Backend</h4>
                <div class="row flex-row flex-nowrap scroll">
                    @foreach(\App\Models\Technology::where('context', '=', 'BACKEND')->get() as $tech)
                    <div class="col-md-1 col-xs-3"><label><input type="radio" name="backend" value="{{ $tech->id }}"> <img src="{{ $tech->icon}}" class="clickable" style="max-width:6em; height:5em; vertical-align:middle;text-align:center;"
                        alt="{{ $tech->name }}" title="{{ $tech->name }}" /></label></div>
                    @endforeach
                </div>
                <h4>Base de datos</h4>
                <div class="row flex-row flex-nowrap scroll">
                    @foreach(\App\Models\Technology::where('context', '=', 'DATABASE')->get() as $tech)
                    <div class="col-md-1"><label><input type="radio" name="database" value="{{ $tech->id }}"> <img src="{{ $tech->icon}}" class="clickable" style="max-width:6em; height:5em; vertical-align:middle;text-align:center;"
                        alt="{{ $tech->name }}" title="{{ $tech->name }}" /></label></div>
                    @endforeach
                </div>
                <h4>Frontend</h4>
                <div class="row flex-row flex-nowrap scroll">
                    @foreach(\App\Models\Technology::where('context', '=', 'FRONTEND')->get() as $tech)
                    <div class="col-md-1"><label><input type="radio" name="frontend" value="{{ $tech->id }}"> <img src="{{ $tech->icon}}" class="clickable" style="max-width:6em; height:5em; vertical-align:middle;text-align:center;"
                        alt="{{ $tech->name }}" title="{{ $tech->name }}" /></label></div>
                    @endforeach
                </div>
                <h4>Plataforma</h4>
                <div class="row flex-row flex-nowrap scroll">
                    @foreach(\App\Models\Technology::where('context', '=', 'PLATFORM')->get() as $tech)
                    <div class="col-md-1"><label><input type="radio" name="platform" value="{{ $tech->id }}"> <img src="{{ $tech->icon}}" class="clickable" style="max-width:6em; height:5em; vertical-align:middle;text-align:center;"
                        alt="{{ $tech->name }}" title="{{ $tech->name }}" /></label></div>
                    @endforeach
                </div>
                <h4>DevOps</h4>
                <div class="row flex-row flex-nowrap scroll">
                    @foreach(\App\Models\Technology::where('context', '=', 'DEVOPS')->get() as $tech)
                    <div class="col-md-1"><label><input type="checkbox" name="devops[]" value="{{ $tech->id }}"> <img src="{{ $tech->icon}}" class="clickable" style="max-width:6em; height:5em; vertical-align:middle;text-align:center;"
                        alt="{{ $tech->name }}" title="{{ $tech->name }}" /></label></div>
                    @endforeach
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

@section('styles')
<style>

  div.scroll {
        margin: 4px, 4px;
        padding: 4px;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }

    .clickable {
        cursor: pointer;
        transition: all .2s ease-in-out;
        margin:1em;
    }

    .clickable:hover { transform: scale(1.05); }

    [type=radio] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    [type=checkbox] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    [type=radio]:checked + img {
        outline: 2px solid #f00;
    }

    [type=checkbox]:checked + img {
        outline: 2px solid #f00;
    }

</style>
@endsection
