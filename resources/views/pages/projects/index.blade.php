@extends('layouts.main')
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
      <h3 class="card-title" data-card-widget="collapse" title="Collapse">Proyectos de {{ $client->name }}</h3>

      <div class="card-tools">
        @if(auth()->user()->role == 1)
        <button type="button" class="btn btn-tool" onclick="location.href='{{ route('projects.create', ['client' => $client->id]) }}'">
          <i class="fas fa-plus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
          <i class="fas fa-times"></i>
        </button>
        @endif
      </div>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped table-responsive projects">
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
                    Tecnologías
                  </th>
                  <th>
                      Estado
                  </th>
                  <th style="width: 8%" class="text-center">
                      A pagar
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
                  <td>
                   @if($project->technologies()->where('context', '=', 'PLATFORM')->count())
                        <span title="Plataforma: {{ $project->technologies()->where('context', '=', 'PLATFORM')->first()->name }}" style="cursor:pointer;"><b style="color:darkblue;">P</b></span>
                    @else
                        <span title="Backend: {{ $project->technologies()->where('context', '=', 'BACKEND')->first()->name }}" style="cursor:pointer;"><b style="color:red;">B</b></span>
                        <span title="BD: {{ $project->technologies()->where('context', '=', 'DATABASE')->first()->name }}"style="cursor:pointer;"><b style="color:green;">D</b></span>
                        <span title="Frontend: {{ $project->technologies()->where('context', '=', 'FRONTEND')->first()->name }}" style="cursor:pointer;"><b style="color:blue;">F</b></span>
                   @endif

                   @if($project->technologies()->where('context', '=', 'DEVOPS')->count())
                    <span title="DevOps: {{ $project->technologies()->where('context', '=', 'DEVOPS')->pluck('name')->implode(', ') }}" style="cursor:pointer;"><b style="color:orange;">DO</b></span>
                   @endif
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
                        {{ $project->price - $project->paid }}
                  </td>
                  <td class="project-actions text-right">
                      <a class="btn btn-primary btn-sm" href="{{ route('tasks.index', ['project' => $project->id]) }}">
                          <i class="fas fa-folder">
                          </i>
                          <span class="showHide">Abrir</span>
                      </a>
                     @if(auth()->user()->role==1)
                            <a class="btn btn-info btn-sm" href="#" onclick="editForm({{ $project->id }})">
                            <i class="fas fa-pencil-alt">
                            </i>
                            <span class="showHide">Editar</span>
                        </a>
                        <a class="btn btn-success btn-sm" href="#" onclick="payForm({{ $project->id }})">
                            <i class="fas fa-dollar-sign">
                            </i>
                            <span class="showHide">Cobrar</span>
                        </a>
                        <a class="btn btn-danger btn-sm" href="#" onclick="abortForm({{ $project->id }})">
                            <i class="fas fa-trash">
                            </i>
                        </a>
                        @endif
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

@section('scripts')
<script>
    function getProject(id) {
        return $.ajax({
            url: "{{ route('projects.show', ['project' => ':id']) }}".replace(':id', id),
        })
    }

    function editForm(id) {
        getProject(id).then(data => {
            Swal.fire({
                title: 'Editar nombre del proyecto',
                html: `<input type="text" name="text" id="text" placeholder="Nuevo nombre del proyecto" value="${data.value.name}" class="swal2-input" />`,
                confirmButtonText: 'Confirmar nombre',
                preConfirm: () => {
                    const text = $("#text").val();
                    return {text: text}
                }
            }).then(res => {
                if(res.isConfirmed)
                    if(res.value.text)
                        $.ajax({
                            type: 'PUT',
                            url: "{{ route('projects.update', ['project' => ':id']) }}".replace(':id', id),
                            data: {
                                name: res.value.text
                            },
                            success: e => Swal.fire("Actualización existosa", "El proyecto se ha actualizado correctamente. La página se va a recargar.", "success").then(() => location.reload())
                        })
                    else Swal.fire("Faltan datos", "No se ha podido editar el proyecto.", "error")
            })
        })
    }

    function abortForm(id) {
        Swal.fire({
            title: '¿Seguro que quieres cancelar el proyecto?',
            text: "Esta acción no se puede deshacer. Cuando se cancele el proyecto se eliminarán todas las tareas del proyecto.",
            confirmButtonText: 'Eliminar proyecto'
        }).then(res => {
            if(res.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('projects.destroy', ['project' => ':id']) }}".replace(':id', id),
                    success: e => Swal.fire('Proyecto eliminado correctamente', 'La página se va a recargar.', 'success').then(() => location.reload())
                })
            }
        })
    }

    function payForm(id) {
        getProject(id).then(data => {
            Swal.fire({
                title: 'Dar de alta pago',
                text: 'Selecciona la naturaleza del pago.',
                showDenyButton: true,
                confirmButtonText: 'Pagar tareas realizadas',
                denyButtonText: 'Pago a cuenta'
            }).then(r => {
                if(r.isConfirmed) {
                    let tasks = [];
                    data.value.tasks.forEach(t => {
                        if (t.finished && !t.paid) tasks.push(t);
                    });
                    let tasksHtml = '';
                    tasks.forEach((t, a) => tasksHtml += `<label><input type="checkbox" class="pay-task" price="${t.price}" value="${t.id}" /> ${t.title}</label> <br />`)
                    if(tasks.length > 0) {
                        Swal.fire({
                            title: 'Dar de alta pago de tareas',
                            html: `<p>Selecciona tareas que se pagan</p>${tasksHtml}`,
                            confirmButtonText: 'Confirmar pago',
                            willOpen: () => {
                                let amount = 0;
                                $('.pay-task').on('change', () => {
                                    amount = 0;
                                    $('.pay-task').toArray().forEach(t => {
                                        if(t.checked) amount += parseFloat(t.attributes['price'].value);
                                        $('.swal2-confirm')[0].innerText = `Confirmar pago (${amount}€)`
                                    })
                                })
                            },
                            preConfirm: () => {
                                const tasks = [];
                                let amount = 0;
                                $('.pay-task').toArray().forEach(t => {
                                        if(t.checked) {
                                            amount += parseFloat(t.attributes['price'].value)
                                            tasks.push(t.value)
                                        }
                                });
                                return {pay: amount, tasks: tasks, text: ''}
                            }
                        }).then(res => {
                            if(res.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('payments.store') }}",
                                    type: 'POST',
                                    data: {
                                        id: id,
                                        amount: res.value.pay,
                                        concept: '',
                                        tasks: res.value.tasks.join(',')
                                    },
                                    success: d => Swal.fire('Pago realizado', 'La página se recargará.', 'success').then(() => location.reload())
                                })
                            }
                        })
                    } else {
                        Swal.fire('Nada que pagar', 'Por ahora no quedan tareas pendientes por pagar.', 'info')
                    }
                }else if(r.isDenied) {
                    Swal.fire({
                        title: 'Dar de alta pago a cuenta',
                        html: `<input type="text" placeholder="Concepto" class="swal2-input" id="text" /><input type="number" step="0.1" placeholder="Cantidad pagada" class="swal2-input" id="pay" />`,
                        confirmButtonText: 'Confirmar pago',
                        preConfirm: () => {
                            const p = $("#pay").val(),
                                text = $("#text").val();
                            return {pay: p, text: text, tasks: []}
                        }
                    }).then(res => {
                        if(res.isConfirmed) {
                            $.ajax({
                                url: "{{ route('payments.store') }}",
                                type: 'POST',
                                data: {
                                    id: id,
                                    amount: res.value.pay,
                                    concept: res.value.text,
                                    tasks: res.value.tasks.join(',')
                                },
                                success: d => Swal.fire('Pago realizado', 'La página se recargará.', 'success').then(() => location.reload())
                            })
                        }
                    })
                }
            })
        })
    }
</script>
@endsection
