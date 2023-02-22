@extends('layouts.main')
@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item active">Clientes</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">Clientes</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" onclick="location.href='{{ route('clients.add') }}'">
          <i class="fas fa-plus"></i>
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
                      <a class="btn btn-primary btn-sm" href="{{ route('payment.view', ['id' => $client->id]) }}">
                          <i class="fas fa-eye">
                          </i>
                      </a>
                      <a class="btn btn-success btn-sm" href="{{ route('project.add', ['client' => $client->id]) }}">
                        <i class="fas fa-plus">
                        </i>
                        Proyecto
                    </a>
                      <a class="btn btn-info btn-sm" href="#" onclick="updateForm({{ $client->id }})">
                          <i class="fas fa-pencil-alt">
                          </i>
                      </a>
                      <a class="btn btn-danger btn-sm" href="#" onclick="deleteForm({{ $client->id }})">
                          <i class="fas fa-trash">
                          </i>
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

@section('scripts')
<script>

function getClient(id) {
        return $.ajax({
            url: "{{ route('clients.get') }}",
            data: {id: id},
        })
    }

    function updateForm(id) {
        getClient(id).then(data => {
            Swal.fire({
                title: 'Actualizar cliente',
                html: `<input type="text" class="swal2-form" placeholder="Nombre de cliente" id="name" value="${data.value.name}" style="width:100%;" /><br/><br/>
                <input type="email" class="swal2-form" id="email" placeholder="mail@cliente.com" value="${data.value.email}" style="width:100%;" /><br/><br/>
                <input type="tel" class="swal2-form" id="tel" placeholder="+00 111 22 33 44" value="${data.value.phone}" style="width:100%;" />`,
                confirmButtonText: 'Editar cliente',
                preConfirm: () => {
                    const email = $("#email").val(),
                        name = $("#name").val(),
                        phone = $("#tel").val();
                    return {name: name, email: email, phone: phone}
                }
            }).then(res => {
                if(res.isConfirmed) {
                    if (res.value.name && res.value.email && res.value.phone)
                        $.ajax({
                            type: 'PUT',
                            url: "{{ route('clients.update') }}",
                            data: {id: id, name: res.value.name, email: res.value.email, phone: res.value.phone},
                            success: e => Swal.fire("Actualización existosa", "El cliente se ha actualizado correctamente. La página se va a recargar.", "success").then(() => location.reload())
                        })
                    else Swal.fire("Faltan datos", "No se ha podido actualizar el cliente.", "error")
                }
            })
        })
    }

    function deleteForm(id) {
        Swal.fire({
            title: '¿Seguro que quieres eliminar el cliente?',
            text: "Esta acción no se puede deshacer. Cuando se cancele el proyecto se eliminarán todos los proyectos del cliente, incluyendo sus tareas.",
            confirmButtonText: 'Eliminar cliente'
        }).then(res => {
            if(res.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('clients.delete') }}",
                    data: {id: id},
                    success: e => Swal.fire('Cliente eliminado correctamente', 'La página se va a recargar.', 'success').then(() => location.reload())
                })
            }
        })
    }
</script>
@endsection
