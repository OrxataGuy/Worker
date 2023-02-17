@extends('layouts.main')
@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item active">Clientes</a></li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Clientes</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
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
                      Email
                  </th>
                  <th>
                      Teléfono
                  </th>
                  <th style="width: 8%" class="text-center">
                      Proyectos
                  </th>
                  <th style="width: 20%">
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($clients as $client)
              <tr>
                  <td>
                      {{ $client->id }}
                  </td>
                  <td>
                      <a>
                        {{ $client->name }}
                      </a>
                  </td>
                  <td>
                      {{ $client->email }}
                  </td>
                  <td>
                    {{ $client->phone }}
                </td>
                  <td>
                        {{ $client->projects->count() }}
                  </td>
                  <td class="project-actions text-right">
                      <a class="btn btn-primary btn-sm" href="#">
                          <i class="fas fa-eye">
                          </i>
                          Ver
                      </a>
                      <a class="btn btn-info btn-sm" href="#">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Editar
                      </a>
                      <a class="btn btn-danger btn-sm" href="#">
                          <i class="fas fa-trash">
                          </i>
                          Eliminar
                      </a>
                  </td>
              </tr>
              @endforeach
          </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
