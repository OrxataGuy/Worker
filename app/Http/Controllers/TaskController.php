<?php

namespace App\Http\Controllers;


use \Illuminate\Http\RedirectResponse as Redirection;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\View\View;
use \Illuminate\Http\Request;

use \App\Models\AdvancedTask;
use \App\Models\Document;
use \App\Models\Project;
use \App\Models\Client;
use \App\Models\Task;
use \App\Models\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project) : View
    {
        $project = Project::with('client')->find($project);
        return view('pages.projects.tasks', ['project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        $task = Task::create([
            'project_id' => $request->get('project_id'),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'details' => $request->get('details')
        ]);

        $project = Project::with('client')->find($task->project_id);

        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha generado una nueva tarea para el proyecto #$project->id, $task->title"
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());

        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id) : View
    {
        $task = Task::with('project')->find($id);
        $advanced_tasks = AdvancedTask::with('document')->where('task_id', '=', $task->id)->where('visible', 1)->get();
        return view('pages.projects.task', ['project' => $task->project, 'task' => $task, 'advanced_tasks' => $advanced_tasks]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) : JsonResponse
    {
        $task = Task::with('advanced_tasks')->find($id);
        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if($request->get('title')) $task->title = $request->get('title');
        if($request->get('description')) $task->description = $request->get('description');
        if ($request->get('details') == 'drop.')  $task->details = null;
        else if ($request->get('details')) $task->details = $request->get('details');
        $task->save();

        $project = Project::with('client')->find($task->project_id);

        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha realizado una modificación en la tarea del proyecto #".$task->project->id."."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());

        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::find($id);
        if ($task->counting) {
            $task->counting = 0;
            $task->save();
            $task->calculate();
        }
        $task->finished = 1;
        $task->prioritary = 0;
        $task->solution = $request->get('solution');
        $task->save();
        $project = Project::with('client')->find($task->project_id);

        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha finalizado una tarea del proyecto #".$task->project->id."."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());


        return response()->json(array(
            'status' => '200',
        ));
    }

    public function toggleCounter($id) : JsonResponse {
        $task = Task::with('project')->find($id);
        if ($task->counting) $task->counting = 0;
        else $task->counting = 1;
        $task->save();
        $value = $task->calculate();
        $client = Client::find($task->project->client_id);
        if($task->counting) Log::publish([
                'user_id' => auth()->user()->id,
                'client_id' => $client->id,
                'project_id' => $task->project->id,
                'task_id' => $task->id,
                'description' => "Se ha reanudado la actividad en la tarea $task->title"
            ],
            \App\Models\User::where('role','=', 1)->orWhere('id', '=', $client->user_id)->get());
        else Log::publish([
                'user_id' => auth()->user()->id,
                'client_id' => $client->id,
                'project_id' => $task->project->id,
                'task_id' => $task->id,
                'description' => "Se ha pausado la actividad en la tarea $task->title"
            ],
            \App\Models\User::where('role','=', 1)->orWhere('id', '=', $client->user_id)->get());
        return response()->json(array(
            'status' => '200',
            'value' => $value
        ));
    }

    public function reopen(Request $request, $id) : JsonResponse {
        $task = Task::with('project')->find($id);
        $value = $request->get('bug') == 1 ? $task->bug($request->get('description')) : $task->patch($request->get('description'));

        $client = Client::find($task->project->client_id);

        if($request->get('bug') == 1) Log::publish([
                'user_id' => auth()->user()->id,
                'client_id' => $client->id,
                'project_id' => $task->project->id,
                'task_id' => $task->id,
                'description' => "Se ha generado una nueva tarea a partir de un bug encontrado en la tarea $task->title"
            ],
            \App\Models\User::where('role','=', 1)->orWhere('id', '=', $client->user_id)->get());
        else Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha generado una nueva tarea a partir de una ampliación pedida para la tarea $task->title"
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $client->user_id)->get());
        return response()->json(array(
            'status' => '200',
            'value' => $value
        ));
    }

    public function markAsPrioritary(Request $request) : JsonResponse
    {
        $task = Task::with('project')->find($request->get('id'));
        foreach(Task::where('project_id', '=', $task->project_id)->get() as $t) {
            $t->prioritary = 0;
            $t->save();
        }
        $client = Client::find($task->project->client_id);

        $task->prioritary = 1;
        $task->save();

        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha marcado la tarea #$task->id:$task->title como urgente"
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $client->user_id)->get());

        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    public function updateTime(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));
        $task->time = $request->get('time');
        $task->priority = $request->get('priority');
        $task->save();
        $task->reg();
        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    public function updateWorkingOn(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));
        $task->workingOn = $request->get('work');
        $task->save();
        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    public function delInfo($task, $id) : JsonResponse {
        $advanced = AdvancedTask::find($id);
        $advanced->visible = 0;
        $advanced->save();
        $task = Task::find($task);
        $project = Project::with('client')->find($task->project_id);
        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha eliminado información de una tarea en el proyecto #".$task->project->id."."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());

        return response()->json(array(
            'status' => '200',
        ));
    }

    public function getMimeType(String $url) : String {
        $list = explode('.',$url);
        $type = strtolower($list[count($list)-1]);
        $image_jpg = ['jpg','jpeg', 'jpe'];
        $image_svg = ['svg'];
        $image_gif = ['gif'];
        $image_png = ['png'];
        $image_bmp = ['cmp'];
        $image_ico = ['ico'];
        $image_webp = ['webp'];

        if (in_array($type, $image_jpg)) return "image/jpeg";
        if (in_array($type, $image_svg)) return "image/png";
        if (in_array($type, $image_gif)) return "image/gif";
        if (in_array($type, $image_png)) return "image/png";
        if (in_array($type, $image_bmp)) return "image/bmp";
        if (in_array($type, $image_ico)) return "image/x-icon";
        if (in_array($type, $image_webp)) return "image/webp";
        return "other";
    }

    public function addInfo(Request $request, $id) : JsonResponse {
        $task = Task::find($id);
        if (!$request->get('doc_id') && $request->get('url')) {
            $document = Document::create([
                'name' => "Archivo externo",
                'type' => $this->getMimeType($request->get('url')),
                'url' => $request->get('url')
            ]);
            $document_id = $document->id;
        } else
            $document_id = $request->get('doc_id') ? $request->get('doc_id') : null;

        AdvancedTask::create([
            'task_id' => $task->id,
            'document_id' => $document_id,
            'description' => $request->get('description'),

        ]);

        $advanced = AdvancedTask::where('task_id', '=', $task->id)->with('document')->get();

        $project = Project::with('client')->find($task->project_id);
        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client->id,
            'project_id' => $task->project->id,
            'task_id' => $task->id,
            'description' => "Se ha añadido información a una tarea en el proyecto #".$task->project->id."."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());
        return response()->json(array(
            'status' => '200',
            'value' => [$task, $advanced]
        ));
    }
}
