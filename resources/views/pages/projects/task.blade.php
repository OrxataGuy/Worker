@extends('layouts.main')
@section('projects-section', 'active')
@section('title', 'Tarea')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Proyectos</a></li>
    <li class="breadcrumb-item">{{ $project->client->name }}</li>
    <li class="breadcrumb-item"><a href="{{ route('tasks.index', ['project' => $project->id]) }}">{{ $project->name }}</a></li>
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
      <table class="table table-striped table-responsive-sm projects">
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
                        <span class="showHide">Iniciar</span> </i>

                        <i class="fas fa-pause" id="pause-{{ $task->id }}" style="display: none;">
                            <span class="showHide">Pausar</span> </i>

                      @else
                      <i class="fas fa-play" id="run-{{ $task->id }}" style="display: none;">
                        <span class="showHide">Iniciar</span> </i>
                      <i class="fas fa-pause active" id="pause-{{ $task->id }}" >
                        <span class="showHide">Pausar</span> </i>
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
                    {{ $task->price }}
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
    <p class="task-details" onclick="updateDetails({{ $task->id }}, this)">@if($task->details) {!! nl2br($task->details) !!} @else No hay detalles. @endif</p>
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
    <table class="table table-striped table-responsive projects">
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
                <td id="content-{{ $task->id }}" >
                  @if(!$atask->document)
                  <div class="card">
                    <div class="card-body">
                        {!! nl2br($atask->description) !!}
                    </div>
                </div>

                  @else
                      @switch($atask->document->type)
                          @case('img')
                          <div class="card">
                            <div class="card-header" data-card-widget="collapse">
                                {!! nl2br($atask->description) !!}
                            </div>
                            <div class="card-body">
                                <img src="{{ $atask->document->url }}" alt="{{ $atask->description }}" />
                            </div>
                        </div>
                              @break
                          @default
                          <div class="card">
                            <div class="card-header" data-card-widget="collapse">
                                {!! nl2br($atask->description) !!}
                            </div>
                            <div class="card-body">
                                <a class="btn btn-primary" href="{{ $atask->document->url }}" target="_blank" title="{{ $atask->description }}">Ver adjunto</a>
                            </div>
                        </div>
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


<input type="hidden" id="ppm" value="{{ $project->price_per_minute }}" />

@endsection

@section('scripts')
<script>
    const mkdown = new showdown.Converter();

     $(() => {
        $('.counting').toArray().forEach(e => startCounter(e.id.split('-')[1]))
        $('.task-details').toArray().forEach(e => $(e).html(mkdown.makeHtml(e.innerText)))
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

    function delete_advanced(id) {
        $.ajax({
                type: 'PUT',
                url: "{{ route('tasks.info.del', ['task' => ':task', 'info' => ':id']) }}".replace(':task', '{{ $task->id }}').replace(':id', id),
                success: data => Swal.fire("Detalle eliminado", "El detalle se ha eliminado correctamente. La página se va a recargar.", "success").then(() => location.reload())
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
                    url: "{{ route('tasks.update', ['task' => ':id']) }}".replace(':id', id),
                    data: {
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
            html: `<textarea id="text" class="swal2-form" style="width:100%" rows="10">${mkdown.makeMarkdown(el.innerHTML)}</textarea>`,
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
                    url: "{{ route('tasks.update', ['task' => ':id']) }}".replace(':id', id),
                    data: {
                        details: res.value.text
                    },
                    success: data => {
                        el.innerText = res.value.text;
                        $('.task-details').toArray().forEach(e => $(e).html(mkdown.makeHtml(e.innerText)))
                    }
                })
            }else if(res.isDenied) {
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('tasks.update', ['task' => ':id']) }}".replace(':id', id),
                    data: {
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
                    url: "{{ route('tasks.info.add', ['task' => ':id']) }}".replace(':id', id),
                    data: {
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
