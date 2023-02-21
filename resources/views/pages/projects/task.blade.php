@extends('layouts.main')
@section('projects-section', 'active')
@section('title', 'Tarea')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects') }}">Proyectos</a></li>
    <li class="breadcrumb-item">{{ $project->client->name }}</li>
    <li class="breadcrumb-item"><a href="{{ route('tasks', ['project' => $project->id]) }}">{{ $project->name }}</a></li>
    <li class="breadcrumb-item active">{{ $task->title }}</li>
    @endsection
@section('section-name', $task->title)
@section('content')
@if($task->finished==1)
<div class="card">
    <div class="card-header bg-success" data-card-widget="collapse" title="Collapse">
    <h3 class="card-title">Solución propuesta </h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <h5>{{ $task->updated_at }}</h5>
    <p>{{ $task->solution }}</p>
  </div>
  <!-- /.card-body -->
</div>

@else
<div class="card">
    <div class="card-header" data-card-widget="collapse" title="Collapse">
      <h3 class="card-title">Controles de la tarea</h3>
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
                <th>
                    Estado
                </th>
                  <th>
                      Minutos
                  </th>
                  <th>
                      Precio
                  </th>
              </tr>
          </thead>
          <tbody>
              <tr>
                <td class="project-actions text-left">
                    <a class="btn @if($task->counting == 0) btn-success @else btn-warning @endif btn-sm" onclick="javascript:toggleStatus(this, {{ $task->id }})" href="#">

                      @if($task->counting == 0)
                      <i class="fas fa-play active" id="run-{{ $task->id }}">
                        Iniciar </i>
                        <i class="fas fa-pause" id="pause-{{ $task->id }}" style="display: none;">
                            Pausar</i>
                      @else
                      <i class="fas fa-play" id="run-{{ $task->id }}" style="display: none;">
                        Iniciar </i>
                      <i class="fas fa-pause active" id="pause-{{ $task->id }}" >
                        Pausar </i>
                      @endif
                    </a>
                  <a class="btn btn-danger btn-sm @if($task->time == 0) disabled @endif" id="stop-{{ $task->id }}" onclick="endCounter({{ $task->id }})" href="#">
                      <i class="fas fa-stop">
                      Finalizar</i>
                  </a>
                </td>
                  <td id="time-{{ $task->id }}" class="@if($task->counting == 1) counting @endif">
                    @if($task->counting == 1)
                    {{ $task->getCurrentTime() }}
                    @else
                   {{ $task->getTime() }}
                   @endif
                </td>
                  <td id="price-{{ $task->id }}">
                    @if($task->counting == 1)
                    -
                    @else
                   {{ $task->price }}
                   @endif
                  </td>

              </tr>
          </tbody>
      </table>
    </div>
    <!-- /.card-body -->
</div>
@endif



<div class="card">
    <div class="card-header" data-card-widget="collapse" title="Collapse">
    <h3 class="card-title">Descripción @if($task->details)y detalles @endif </h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <h5>Descripción de la tarea</h5>
    <p onclick="updateDescription({{ $task->id }}, this)">{!! nl2br($task->description) !!}</p>
    <h5>Detalles</h5>
    <p onclick="updateDetails({{ $task->id }}, this)">@if($task->details) {!! nl2br($task->details) !!} @else No hay detalles. @endif</p>
  </div>
  <!-- /.card-body -->
</div>

<div class="card">
    <div class="card-header" >
    <h3 class="card-title"  data-card-widget="collapse" title="Collapse">Detalles adicionales </h3>
    <div class="card-tools">
        <button type="button" class="btn btn-tool" onclick="addAdvancedTask({{ $task->id }})">
            <i class="fas fa-plus"></i>
          </button>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-striped projects">
        <thead>
            <tr>
              <th>
                  Tipo
              </th>
                <th>Contenido</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
          @foreach ($advanced_tasks as $atask)
            <tr>
              <td class="project-actions text-left">
                 @if(!$atask->document)
                  Comentario
                  @else
                  {{ strtoupper($atask->document->type) }}
                  @endif
              </td>
                <td id="time-{{ $task->id }}">
                  @if(!$atask->document)
                      {!! nl2br($atask->description) !!}
                  @else
                      @switch($atask->document->type)
                          @case('img')
                              <img src="{{ $atask->document->url }}" alt="{{ $atask->description }}" />
                              @break
                          @default
                              <a class="btn btn-primary" href="{{ $atask->document->url }}" target="_blank" title="{{ $atask->description }}">Ver adjunto</a>
                          @break
                      @endswitch

                 @endif
              </td>
              <td><a class="btn btn-danger btn-sm" onclick="delete_advanced({{ $atask->id }})" href="#">
                <i class="fas fa-trash">
                </i>
            </a></td>
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
     $(() => {
        $('.counting').toArray().forEach(e => startCounter(e.id.split('-')[1]))
    });

    var counting = 0;
    function startCounter(id) {
        let str = $(`#time-${id}`).html().trim();
        counting = 1;
        let mins = parseInt(str.split(':')[0]),
            secs = parseInt(str.split(':')[1]);
        setInterval(() => {
            if (counting == 0) return;
            if(++secs==60) {
                mins+=1;
                secs = 0;
            }
            let m = (mins+'').length == 1 ? `0${mins}` : mins,
                s = (secs+'').length == 1 ? `0${secs}` : secs;
            $(`#time-${id}`).html(`${m}:${s}`);
        }, 1000)
    }

    function delete_advanced(id) {
        $.ajax({
                    type: 'PUT',
                    url: "{{ route('tasks.del.details', ['project' => $project->id]) }}",
                    data: {
                        id: id,
                    },
                    success: data => {
                        location.reload()
                    }
                })
    }

    function updateDescription(id, el) {
        Swal.fire({
            title: 'Actualizar descripción',
            html: `<textarea id="text" class="swal2-form" style="width:100%" rows="10">${el.innerText}</textarea>`,
            confirmButtonText: 'Confirmar',
            preConfirm: () => {
                const text = $("#text").val();
                return {text: text}
            }
        }).then(res => {
            if (res.isConfirmed) {
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('tasks.update', ['project' => $project->id]) }}",
                    data: {
                        id: id,
                        description: res.value.text
                    },
                    success: data => {
                        el.innerText = res.value.text;
                    }
                })
            }
        })
    }

    function updateDetails(id, el) {
        Swal.fire({
            title: 'Actualizar detalles',
            html: `<textarea id="text" class="swal2-form" style="width:100%" rows="10">${el.innerText}</textarea>`,
            confirmButtonText: 'Confirmar',
            showDenyButton: true,
            denyButtonText: 'Borrar',
            preConfirm: () => {
                const text = $("#text").val();
                return {text: text}
            }
        }).then(res => {
            if (res.isConfirmed) {
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('tasks.update', ['project' => $project->id]) }}",
                    data: {
                        id: id,
                        details: res.value.text
                    },
                    success: data => {
                        el.innerText = res.value.text;
                    }
                })
            }else if(res.isDenied) {
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('tasks.update', ['project' => $project->id]) }}",
                    data: {
                        id: id,
                        details: 'drop.'
                    },
                    success: data => {
                        el.innerText = 'No hay detalles.';
                    }
                })
            }
        })
    }

    function addAdvancedTask(id) {
        Swal.fire({
            title: 'Añadir detalles',
            html: `<textarea id="text" class="swal2-form" style="width:100%" placeholder="Comentarios" rows="5"></textarea><br/><br/><h5>Adjuntos</h5><input type="text" class="swal2-form" placeholder="URL" style="width:100%" id="url" /><br/><br/><input type="text" id="title" class="swal2-form" placeholder="Nombre de archivo" style="width:80%" /><select id="type" class="swal2-"><option value="" disabled selected>Tipo</option><option value="img">IMG</option><option value="pdf">PDF</option><option value="other">OTRO</option></select>`,
            confirmButtonText: 'Confirmar',
            preConfirm: () => {

                const text = $("#text").val(),
                    name = $("#title").val(),
                    url = $("#url").val(),
                    type = $("#type").val();
                return {description: text, name: name, type: type, url: url}
            }
        }).then(res => {
            if (res.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('tasks.add.details', ['project' => $project->id]) }}",
                    data: {
                        id: id,
                        name: res.value.name,
                        type: res.value.type,
                        url: res.value.url,
                        description: res.value.description
                    },
                    success: data => {
                        location.reload();
                    }
                })
            }
        })
    }

    function endCounter(id) {
        Swal.fire({
            title: 'Finalizar tarea',
            html: `<textarea id="text" placeholder="Solución propuesta" style="width:100%" cols="6" class="swal2-form"></textarea>`,
            confirmButtonText: "Finalizar",
            preConfirm: () => {
                const text = $("#text").val();
                return {text: text}
            }
        }).then(res => {
            if(res.isConfirmed){
                $.ajax({
            url: "{{ route('tasks.finish', ['project' => $project->id]) }}",
            type: "PUT",
            data: {id: id, solution: res.value.text},
            success: data => {
                location.reload();
            }
            })
        }
    })
}

    function toggleCounter (id) {
        $.ajax({
            url: "{{ route('tasks.toggle', ['project' => $project->id]) }}",
            type: "POST",
            data: {id: id},
            success: data => {
                if(data.value[0] != '-' && data.value[0] == 0) $(`#stop-${id}`).addClass('disabled');
                $(`#time-${id}`)[0].innerText = data.value[0];
                $(`#price-${id}`)[0].innerText = data.value[1];
            }
        })
    }

    function toggleStatus(e, id) {
        let old = stat = oldc = newc = ''
        if ($(`#run-${id}`).hasClass('active')) {
            old = 'run'
            stat = 'pause'
            oldc = 'success'
            newc = 'warning'
            startCounter(id);
        }else{
            old = 'pause'
            stat = 'run'
            oldc = 'warning'
            newc = 'success'
            counting = 0;
        }

        $(`#${old}-${id}`).removeClass('active');
        $(`#${old}-${id}`).css('display', 'none');
        $(e).removeClass(`btn-${oldc}`)
        $(e).addClass(`btn-${newc}`)
        $(`#${stat}-${id}`).css('display', 'initial');
        $(`#${stat}-${id}`).addClass('active');
        $(`#stop-${id}`).removeClass('disabled');
        toggleCounter(id);

    }
</script>
@endsection
@section('styles')
<style>
.animation__timer {
  -webkit-animation: wobble 1500ms infinite;
  animation: wobble 1500ms infinite;
}
</style>
@endsection
