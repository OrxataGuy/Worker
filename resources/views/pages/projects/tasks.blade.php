@extends('layouts.main')
@section('projects-section', 'active')
@section('title', 'Tareas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Proyectos</a></li>
    <li class="breadcrumb-item">{{ $project->client->name }}</li>
    <li class="breadcrumb-item active">{{ $project->name }}</li>
    @endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ $project->name }}</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" onclick="addTaskForm({{ $project->id }})">
          <i class="fas fa-plus"></i>
        </button>
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
                      Minutos
                  </th>
                  <th style="width: 8%" class="text-center">
                      Precio (€)
                  </th>
                  <th style="width: 20%">
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($project->tasks()->orderBy('finished', 'asc')->orderBy('priority','desc')->get() as $task)
              <tr id="row-{{ $task->id }}" class="@if($task->finished==1) bg-success @elseif($task->counting ==1) bg-warning @endif">
                  <td>
                      {{ $task->id }}
                  </td>
                  <td>
                      <a>
                        {{ $task->title }}
                      </a>
                  </td>
                  <td>
                      {{ $task->description }}
                      @if($task->details)
                      <small>
                        Hay más detalles.
                    </small>
                      @endif
                  </td>
                  <td id="time-{{ $task->id }}" class=" @if($task->counting == 1) counting @endif">
                    @if($task->counting == 1)
                   {{ $task->getCurrentTime() }}
                   @else
                   {{ $task->getTime() }}
                   @endif
                </td>
                  <td id="price-{{ $task->id }}">
                   {{ $task->price }}
                  </td>
                  <td class="project-actions text-right">
                      <a class="btn btn-info btn-sm" href="{{ route('tasks.view', ['task' => $task->id]) }}">
                          <i class="fas fa-eye">
                          </i>
                      </a>
                      @if($task->finished==0)
                      <a class="btn @if($task->counting == 0) btn-success @else btn-warning @endif btn-sm" onclick="javascript:toggleStatus(this, {{ $task->id }})" href="#">

                        @if($task->counting == 0)
                        <i class="fas fa-play active" id="run-{{ $task->id }}">
                          </i>
                          <i class="fas fa-pause" id="pause-{{ $task->id }}" style="display: none;">
                        </i>
                        @else
                        <i class="fas fa-play" id="run-{{ $task->id }}" style="display: none;">
                        </i>
                        <i class="fas fa-pause active" id="pause-{{ $task->id }}" >
                      </i>
                        @endif
                      </a>
                    <a class="btn btn-danger btn-sm @if($task->time == 0) disabled @endif" id="stop-{{ $task->id }}" onclick="endCounter({{ $task->id }})" href="#">
                        <i class="fas fa-stop">
                        </i>
                    </a>
                    <a class="btn btn-secondary btn-sm" href="#" onclick="configTime({{ $task->id }})">
                        <i class="fas fa-gear">
                          <span class="showHide">Corregir</span></i>
                    </a>
                    @else
                    <a class="btn btn-warning btn-sm" onclick="reopen({{ $task->id }}, 0)" href="#">
                        <i class="fas fa-plus">
                        </i>
                    </a>
                    <a class="btn btn-danger btn-sm" onclick="reopen({{ $task->id }}, 1)" href="#">
                        <i class="fas fa-bug">
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

  <input type="hidden" id="ppm" value="{{ $project->price_per_minute }}" />
@endsection

@section('scripts')
<script>
    $(() => {
        $('.counting').toArray().forEach(e => startCounter(e.id.split('-')[1]))
    });
    var counting = {};
    function startCounter(id) {
        let str = $(`#time-${id}`).html().trim(),
            ppm = parseFloat($('#ppm').val())/60,
            mins = parseInt(str.split(':')[0]),
            secs = parseInt(str.split(':')[1]),
            time = mins*60+secs;
        $(`#price-${id}`).html(Math.round(ppm*time*100)/100);
        counting[id] = setInterval(() => {
            if(++secs==60) {
                mins+=1;
                secs = 0
            }
            price = parseFloat($(`#price-${id}`).html())
            let m = (mins+'').length == 1 ? `0${mins}` : mins,
                s = (secs+'').length == 1 ? `0${secs}` : secs,
                pr = Math.round((price+ppm)*1000)/1000;
            $(`#time-${id}`).html(`${m}:${s}`);
            $(`#price-${id}`).html(pr)
        }, 1000)
    }
    function reopen(id, bug) {
        Swal.fire({
            title: 'Reabrir tarea',
            html: bug ? `<textarea id="text" placeholder="Explicación del bug" class="swal2-form" style="width: 100%" rows="6"></textarea>` : `<textarea id="text" placeholder="Explicación de la ampliación" class="swal2-form" style="width: 100%" rows="6"></textarea>`,
            confirmButtonText: 'Confirmar',
            preConfirm: () => {
                const text = $("#text").val();
                return {text: text};
            }
        }).then(res => {
            if(res.isConfirmed){
                $.ajax({
                    url: "{{ route('tasks.reopen', ['task' => ':id']) }}".replace(':id',id),
                    type: 'POST',
                    data: {bug: bug, description: res.value.text},
                    success: () => location.reload()
                })
            }
        })
    }
    function addTaskForm(id) {
        Swal.fire({
            title: 'Crear tarea',
            html: `<input type="text" placeholder="Título" id="title" style="width:100%;" class="swal2-form" />
            <br/><br/>
            <textarea id="desc" class="swal2-form" style="width:100%"  rows="5" placeholder="Descripción"></textarea>
            <br/><br/><textarea id="details" class="swal2-form" style="width:100%"   rows="5" placeholder="Detalles"></textarea>`,
            confirmButtonText: 'Crear',
            preConfirm: () => {
                const title = $("#title").val(),
                description = $("#desc").val(),
                details = $("#details").val();
                return {title: title, description: description, details: details}
            }
        }).then(res => {
            if (res.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('tasks.store') }}",
                    data: {
                        project_id: id,
                        title : res.value.title,
                        description: res.value.description,
                        details: res.value.details
                    },
                    success: data => Swal.fire('Tarea creada', 'La página se va a actualizar', 'success').then(() => location.reload())
                })
            }
        });
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
            url: "{{ route('tasks.destroy', ['task' => ':id']) }}".replace(':id',id),
            type: "PUT",
            data: {solution: res.value.text},
            success: data => {
                location.reload();
            }
            })
        }
    })
}
    function toggleCounter (id) {
        $.ajax({
            url: "{{ route('tasks.toggle', ['task' => ':id']) }}".replace(':id',id),
            type: "PUT",
            success: data => {
                console.log(data)
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
            $(`#row-${id}`).addClass('bg-warning')
            startCounter(id);
        }else{
            old = 'pause'
            stat = 'run'
            oldc = 'warning'
            newc = 'success'
            $(`#row-${id}`).removeClass('bg-warning')
            clearInterval(counting[id]);
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

    function getTask(id) {
        return $.ajax({
            url: "{{ route('tasks.show', ['task' => ':id']) }}"
        })
    }

    function configTime(id) {
        getTask(id).then(data => {
            Swal.fire({
                title: 'Corregir tiempos',
                html: `<input type="number" placeholder="Minutos" class="swal2-form" value="${data.value.time}" id="time" /><br/><input type="number" value="${data.value.priority}" placeholder="Prioridad" class="swal2-form" id="priority" max="9" min="0" />`,
                confirmButtonText: 'Corregir',
                preConfirm: () => {
                    const time = $("#time").val()
                    return {time: time}
                    }
                }).then(e => {
                    if (e.isConfirmed) {

                        $.ajax({
                            url: "{{ route('tasks.time', ['task' => ':id']) }}".replace(':id',id),
                            data: {id:id, time: e.value.time},
                            type: 'PUT',
                            success: data => Swal.fire('Tiempo corregido', 'La página se va a recargar', 'success').then(() => location.reload())
                        })
                    }
                })
        })
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
