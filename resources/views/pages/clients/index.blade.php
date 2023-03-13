@if(auth()->user()->role==1)
    @php $layout = 'layouts.main'; @endphp
@else
    @php $layout = 'layouts.clients'; @endphp
@endif

@extends($layout)

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
        <button type="button" class="btn btn-tool" onclick="location.href='{{ route('clients.create') }}'">
          <i class="fas fa-plus"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-striped table-responsive-xs projects" style="width: 100%;">
          <thead>
              <tr>
                  <th>
                      Nombre
                  </th>
                  <th>
                      Email
                  </th>
                  <th>
                      Teléfono
                  </th>
                  <th>
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($clients as $client)
              <tr>
                  <td>
                      <a>
                        {{ $client->name }}
                      </a>
                  </td>
                  <td>
                    <a class="btn btn-warning btn-sm" href="mailto:{{ $client->email }}">
                        <i class="fas fa-envelope">
                        </i>
                    </a>
                    <span class="showHide">{{ $client->email }}</span>
                  </td>
                  <td>
                    <a class="btn btn-warning btn-sm" href="tel:{{ $client->phone }}">
                        <i class="fas fa-phone">
                        </i>
                    </a>
                    <span class="showHide">{{ $client->phone }}</span>

                </td>
                  <td class="project-actions float-right">
                      <a class="btn btn-primary btn-sm" href="{{ route('payments.view', ['client' => $client->id]) }}">
                          <i class="fas fa-eye">
                          </i>
                          <span class="showHide">Pagos</span>
                      </a>
                      <a class="btn btn-success btn-sm" href="{{ route('projects.create', ['client' => $client->id]) }}">
                        <i class="fas fa-plus">
                        </i>
                        <span class="showHide">Proyecto</span>
                    </a>
                      <a class="btn btn-info btn-sm" href="#" onclick="updateForm({{ $client->id }})">
                          <i class="fas fa-pencil-alt">
                          </i>
                          <span class="showHide">Editar</span>
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
            url: "{{ route('clients.show', ['client' => ':id']) }}".replace(':id', id)
        })
    }

    function updateForm(id) {
        getClient(id).then(data => {
            Swal.fire({
                title: 'Actualizar cliente',
                html: `<input type="text" class="swal2-input" placeholder="Nombre de cliente" id="name" value="${data.value.name}" /><br/><br/>
                <input type="email" class="swal2-input" id="email" placeholder="mail@cliente.com" value="${data.value.email}" /><br/><br/>
                <input type="tel" class="swal2-input" id="tel" placeholder="+00 111 22 33 44" value="${data.value.phone}" />`,
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
                            url: "{{ route('clients.update', ['client' => ':id']) }}".replace(':id', id),
                            data: {name: res.value.name, email: res.value.email, phone: res.value.phone},
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
                    url: "{{ route('clients.destroy', ['client' => ':id']) }}".replace(':id', id),
                    data: {id: id},
                    success: e => Swal.fire('Cliente eliminado correctamente', 'La página se va a recargar.', 'success').then(() => location.reload())
                })
            }
        })
    }
</script>
@endsection
