@if(auth()->user()->role==1)
    @extends('layouts.main')
@else
    @extends('layout.clients')
@endif

@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Pagos de {{ $client->name }}</a></li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title" data-card-widget="collapse" title="Collapse">Pagos de {{ $client->name }}</h3>

      <div class="card-tools"></div>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped table-responsive projects">
          <thead>
              <tr>
                  <th style="width: 1%">
                      #
                  </th>
                  <th style="width: 20%">
                      Proyecto
                  </th>
                  <th style="width: 30%">
                      Fecha
                  </th>
                  <th>
                      Concepto
                  </th>
                  <th style="width: 8%" class="text-center">
                      Cantidad
                  </th>
                  <th style="width: 20%">
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($payments as $payment)
              <tr>
                  <td>
                      {{ $payment->id }}
                  </td>
                  <td>
                     {{ $payment->project->name }}
                  </td>
                  <td>
                    {{ $payment->concept }}
                  </td>
                  <td>
                    {{ $payment->updated_at }}
                  </td>
                  <td>
                    {{ $payment->amount }} €
                  </td>
                  <td class="project-actions float-right">
                      @if(!$payment->confirmed)
                      <a class="btn btn-primary btn-sm" onclick="confirmForm({{ $payment->id }})">
                          <i class="fas fa-check">
                          </i>
                      </a>
                      @endif
                      <a class="btn btn-danger btn-sm" href="#" onclick="deleteForm({{ $payment->id }})">
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
    function confirmForm(id) {
        Swal.fire({
            title: '¿Seguro que quieres confirmar el pago?',
            text: "Una vez el pago sea confirmado, la fecha del pago se actualizará a la actual.",
            confirmButtonText: 'Confirmar pago'
        }).then(res => {
            if(res.isConfirmed) {
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('payments.update', ['payment' => ':id']) }}".replace(':id', id),
                    success: e => Swal.fire('Pago confirmado correctamente', 'La página se va a recargar.', 'success').then(() => location.reload())
                })
            }
        })
    }

    function deleteForm(id) {
        Swal.fire({
            title: '¿Seguro que quieres eliminar el pago?',
            text: "Esta acción no se puede deshacer. No quedará ningún registro de este pago.",
            confirmButtonText: 'Eliminar pago'
        }).then(res => {
            if(res.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('payments.delete', ['payment' => ':id']) }}".replace(':id', id),
                    data: {id: id},
                    success: e => Swal.fire('Pago eliminado correctamente', 'La página se va a recargar.', 'success').then(() => location.reload())
                })
            }
        })
    }
</script>
@endsection
