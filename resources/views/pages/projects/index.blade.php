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
        <button type="button" class="btn btn-tool" onclick="location.href='{{ route('project.add', ['client' => $client->id]) }}'">
          <i class="fas fa-plus"></i>
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
                      <a class="btn btn-primary btn-sm" href="{{ route('tasks', ['project' => $project->id]) }}">
                          <i class="fas fa-folder">
                          </i>
                          Abrir
                      </a>
                      <a class="btn btn-info btn-sm" href="#" onclick="editForm({{ $project->id }})">
                          <i class="fas fa-pencil-alt">
                          </i>
                          Editar
                      </a>
                      <a class="btn btn-success btn-sm" href="#" onclick="payForm({{ $project->id }})">
                        <i class="fas fa-dollar-sign">
                        </i>
                            Pago
                      </a>
                      <a class="btn btn-danger btn-sm" href="#" onclick="abortForm({{ $project->id }})">
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

@section('scripts')
<script>
    function getProject(id) {
        return $.ajax({
            url: "{{ route('project.get') }}",
            data: {id: id},
        })
    }

    function editForm(id) {
        getProject(id).then(data => {
            Swal.fire({
                title: 'Editar nombre del proyecto',
                html: `<input type="text" name="text" id="text" placeholder="Nuevo nombre del proyecto" value="${data.value.name}" class="swal2-form" />`,
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
                            url: "{{ route('project.update') }}",
                            data: {
                                id: id,
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
                    url: "{{ route('project.delete') }}",
                    data: {id: id},
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
                                    url: "{{ route('project.pay') }}",
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
                                url: "{{ route('project.pay') }}",
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
