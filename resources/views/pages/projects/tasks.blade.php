@extends('layouts.main')
@section('projects-section', 'active')
@section('title', 'Tareas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('projects') }}">Proyectos</a></li>
    <li class="breadcrumb-item">{{ $project->client->name }}</li>
    <li class="breadcrumb-item active">{{ $project->name }}</li>
    @endsection
@section('content')
<div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ $project->name }}</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
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
                      Minutos
                  </th>
                  <th style="width: 8%" class="text-center">
                      Precio
                  </th>
                  <th style="width: 20%">
                  </th>
              </tr>
          </thead>
          <tbody>
            @foreach($project->tasks as $task)
              <tr>
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
                  <td id="time-{{ $task->id }}">
                    @if($task->counting == 1)
                    -
                    @else
                   {{ $task->time }}
                   @endif
                </td>
                  <td id="price-{{ $task->id }}">
                    @if($task->counting == 1)
                    -
                    @else
                   {{ $task->price }}
                   @endif
                  </td>
                  <td class="project-actions text-right">
                      <a class="btn btn-info btn-sm" href="#">
                          <i class="fas fa-eye">
                          </i>
                      </a>
                      <a class="btn @if($task->counting == 0) btn-success @else btn-warning @endif btn-sm" onclick="javascript:toggleStatus(this, {{ $task->id }})" href="#">

                        @if($task->counting == 0)
                        <i class="fas fa-play active" id="run-{{ $task->id }}">
                          </i>
                          <i class="fas fa-pause" id="pause-{{ $task->id }}" style="display: none;">
                        </i>
                        @else
                        <i class="fas fa-play active" id="run-{{ $task->id }}" style="display: none;">
                        </i>
                        <i class="fas fa-pause" id="pause-{{ $task->id }}" >
                      </i>
                        @endif
                      </a>
                    <a class="btn btn-danger btn-sm @if($task->time == 0) disabled @endif" id="stop-{{ $task->id }}" href="#">
                        <i class="fas fa-stop">
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
        }else{
            old = 'pause'
            stat = 'run'
            oldc = 'warning'
            newc = 'success'
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
