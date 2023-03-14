@if(auth()->user()->role==1)
    @php $layout = 'layouts.main'; @endphp
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
