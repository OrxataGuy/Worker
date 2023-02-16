@extends('frames.main')
@section('projects-section', 'active')
@section('title', 'Proyectos')
@section('section-name', 'Proyectos')
@section('breadcrumb')
    <li class="breadcrumb-item active">Proyectos</li>
@endsection
@section('content')

@foreach($clients as $client)
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Proyectos de {{ $client->name }}</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped projects">
          <thead>
              <tr>
                  <th style="width: 1%">
                      #
                  </th>
                  <th style="width: 20%">
                      Nombre
                  </th>
                  <th style="width: 30%">
                      Descripción
                  </th>
                  <th>
                      Estado
                  </th>
                  <th style="width: 8%" class="text-center">
                      Precio total
                  </th>
                  <th style="width: 20%">
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($client->projects as $project)
              <tr>
                  <td>
                      {{ $project->id }}
                  </td>
                  <td>
                      <a>
                        {{ $project->name }}
                      </a>
                      <br/>
                      <small>
                          Creado el {{ $project->created_at }}
                      </small>
                  </td>
                  <td>
                      {{ $project->description }}
                  </td>
                  <td class="project_progress">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $project->getPercentStatus() }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->getPercentStatus() }}%">
                        </div>
                    </div>
                    <small>
                        {{ $project->getPercentStatus() }}% Completado
                    </small>
                </td>
                  <td>
                        {{ $project->price }}
                  </td>
                  <td class="project-actions text-right">
                      <a class="btn btn-primary btn-sm" href="#">
                          <i class="fas fa-folder">
                          </i>
                          Abrir
                      </a>
                      <a class="btn btn-info btn-sm" href="#">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Editar
                      </a>
                      <a class="btn btn-danger btn-sm" href="#">
                          <i class="fas fa-trash">
                          </i>
                          Cancelar
                      </a>
                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
@endforeach
@endsection
