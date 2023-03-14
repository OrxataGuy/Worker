@if(auth()->user()->role==1)
    @php $layout = ; @endphp
@else
    @php $layout = 'layouts.clients'; @endphp
@endif

@extends($layout)

@section('clients-section', 'active')
@section('title', 'Clientes')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Pagos de {{ $client->name }}</a></li>
@endsection
@section('content')
<div class="card">

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
