@if(auth()->user()->role==1)
    @php $layout = 'layouts.main'; @endphp
@else
    @php $layout = 'layouts.clients'; @endphp
@endif

@extends($layout)

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
    <h5>{{ date('d/m/Y H:i', strtotime($task->updated_at)) }}</h5>                                                                                                                                    $task->updated_at }}</h5>
    <p>{{ $task->solution }}</p>
  </div>
  <!-- /.card-body -->
</div>

@else
@if(auth()->user()->role == 1)
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
                          <span class="showHide">Finalizar</span></i>
                    </a>
                    <a class="btn btn-secondary btn-sm" href="#" onclick="configTime({{ $task->id }})">
                        <i class="fas fa-pencil-alt">
                          <span class="showHide">Corregir</span></i>
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
@endif



<div class="card">
    <div class="card-header" data-card-widget="collapse" title="Collapse">
    <h3 class="card-title">Descripción @if($task->details)y detalles @endif </h3>
    <div class="card-tools">
       @if(auth()->user()->role==1)
      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
        <i class="fas fa-minus"></i>
      </button>
      @endif
    </div>
  </div>
  <div class="card-body">
    <h5>Descripción de la tarea</h5>
    <p  @if(auth()->user()->role==1) onclick="updateDescription({{ $task->id }}, this)" @endif>{!! nl2br($task->description) !!}</p>
    <h5>Detalles</h5>
    <p class="task-details" @if(auth()->user()->role==1) onclick="updateDetails({{ $task->id }}, this)" @endif>@if($task->details) {!! nl2br($task->details) !!} @else No hay detalles. @endif</p>
  </div>
  <!-- /.card-body -->
</div>

<div class="card">
    <div class="card-header" >
    <h3 class="card-title"  data-card-widget="collapse" title="Collapse">Comentarios adicionales </h3>
    <div class="card-tools">
        <button type="button" class="btn btn-tool" onclick="addAdvancedTask({{ $task->id }})">
            <i class="fas fa-plus"></i>
          </button>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-striped table-responsive-sm projects">
        <thead>
            <tr>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
          @foreach ($advanced_tasks as $atask)
            <tr>
                <td id="content-{{ $task->id }}" style="width:100%;">
                  @if(!$atask->document)
                  <div class="card">
                    <div class="card-body">
                        {!! nl2br($atask->description) !!}
                    </div>
                </div>
                  @else
                      @if(explode('/',$atask->document->type)[0] == 'image')
                          <div class="card">
                            <div class="card-header photo-card" data-card-widget="collapse">
                                <i class="fas fa-image"></i> {!! nl2br($atask->description) !!}
                            </div>
                            <div class="card-body">
                                <img src="{{ $atask->document->url }}" data-card-widget="maximize" alt="{{ $atask->description }}" />
                            </div>
                        </div>
                          @else
                          <div class="card">
                            <div class="card-header">
                                {!! nl2br($atask->description) !!}
                            </div>
                            <div class="card-body">
                                <a class="btn btn-primary" href="{{ $atask->document->url }}" target="_blank" title="{{ $atask->description }}">Ver adjunto</a>
                            </div>
                        </div>
                    @endif
                 @endif
              </td>
              <td>
               @if(auth()->user()->role==1)
                <a class="btn btn-danger btn-sm" onclick="delete_advanced({{ $atask->id }})" href="#">
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


<input type="hidden" id="ppm" value="{{ $project->price_per_minute }}" />

@endsection

@section('scripts')
<script>
    const mkdown = new showdown.Converter();

     $(() => {
        $('.counting').toArray().forEach(e => startCounter(e.id.split('-')[1]))
        $('.task-details').toArray().forEach(e => $(e).html(mkdown.makeHtml(e.innerText)))
        $('.photo-card').click()
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

    function configTime(id) {
        Swal.fire({
            title: 'Corregir tiempos',
            html: '<input type="number" placeholder="Minutos" class="swal2-input" id="time" />',
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

    }

    function delete_advanced(id) {
        $.ajax({
                type: 'PUT',
                url: "{{ route('tasks.info.del', ['task' => ':task', 'info' => ':id']) }}".replace(':task', '{{ $task->id }}').replace(':id', id),
                success: data => Swal.fire("Comentario eliminado", "El comentario se ha eliminado correctamente. La página se va a recargar.", "success").then(() => location.reload())
        })
    }

    function updateDescription(id, el) {
        Swal.fire({
            title: 'Actualizar descripción',
            html: `<textarea id="text" class="swal2-input" style="height:5em;">${el.innerText}</textarea>`,
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
            html: `<textarea id="text" class="swal2-input" style="height:5em;">${mkdown.makeMarkdown(el.innerHTML)}</textarea>`,
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
            title: 'Añadir comentario',
            html: `<textarea id="text" class="swal2-input" style="height:5em;" placeholder="Comentario (Mínimo 5 catacteres)." required></textarea><br/><br/><h5>Adjuntos</h5><input type="file" id="input-file" class="swal2-file" onchange="call_upload()" /><br/><br/><input type="text" class="swal2-input" placeholder="URL" id="url" /><input type="hidden" id="docid" value="" />`,
            confirmButtonText: 'Confirmar',
            willOpen: () => {
                Swal.disableButtons()
                $('#text').on('keyup', () => {
                    if ($('#text').val().length >= 5) Swal.enableButtons()
                    else Swal.disableButtons()
                })
            },
            preConfirm: () => {
                const text = $("#text").val(),
                    url = $("#url").val(),
                    docid = $("#docid").val();

                return {description: text, url: url, doc_id: docid}
            }
        }).then(res => {
            if (res.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('tasks.info.add', ['task' => ':id']) }}".replace(':id', id),
                    data: {
                        doc_id: res.value.doc_id,
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
            html: `<textarea id="text" placeholder="Solución propuesta" style="height:5em;" class="swal2-input"></textarea>`,
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
            Swal.fire({
                title: 'Selecciona tecnología',
                allowOutsideClick: false,
                html:`<h5>¿Con qué tecnología vas a trabajar?</h5>
                    <div style="display:flex; flex-wrap: wrap; justify-content: space-around; align-content: center;">
                        @foreach($project->technologies as $tech)
                            <button type="button" style="margin:0.1em;" onclick="setWorkTo(${id}, {{ $tech->id }}, () => Swal.close())" class="btn work-btn @if($tech->context=="FRONTEND") btn-info @elseif($tech->context=="DATABASE") btn-danger @elseif($tech->context=="BACKEND") btn-success @else  btn-primary @endif">{{ $tech->name }} ({{ $tech->context }})</button>
                        @endforeach
                    </div>
                `,
                willOpen: () => {
                    $('.swal2-confirm').css('display', 'none');
                }
            }).then(e => {
                startCounter(id);
            })

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

    function setWorkTo(id, target, cb) {
        $.ajax({
            url: "{{ route('tasks.work', ['task' => ':id']) }}".replace(':id',id),
            type: 'PUT',
            data: {id: id, work: target},
            success: () => cb()
        })
    }

    function call_upload() {

        var form_data = new FormData();
        form_data.append("file", $("#input-file").prop("files")[0]);
        uploadInBack(form_data).then(data => {
            console.log(data)
            $('#url').val(data.value.url)
            $('#url').attr("readonly", true)
            $('#docid').val(data.value.id)
        })
    }

    function uploadInBack(form_data) {
        return $.ajax({
            url: "{{ route('upload') }}",
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post'
        });
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
