<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Models\Project;
use App\Models\AdvancedTask;
use App\Models\Document;
use App\Models\Task;
use Symfony\Component\HttpFoundation\JsonResponse;


class TaskController extends Controller
{
    public function index($project) : View
    {
        $project = Project::with('client')->find($project);
        return $project ? view('pages.projects.tasks', ['project' => $project]) : view('errors.404');
    }

    public function toggleCounter(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));
        if ($task->counting) $task->counting = 0;
        else $task->counting = 1;
        $task->save();
        $value = $task->calculate();
        return response()->json(array(
            'status' => '200',
            'value' => $value
        ));
    }

    public function reopen(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));
        $value = $request->get('bug') == 1 ? $task->bug() : $task->patch();
        return response()->json(array(
            'status' => '200',
            'value' => $value
        ));
    }

    public function get(Request $request) : JsonResponse {
        $task = Task::with('advanced_tasks')->find($request->get('id'));
        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    public function create(Request $request) : JsonResponse {
        $task = Task::create([
            'project_id' => $request->get('project_id'),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'details' => $request->get('details')
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    public function update(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));

        $task->title = $request->get('title');
        $task->description = $request->get('description');
        $task->details = $request->get('details');
        $task->save();

        return response()->json(array(
            'status' => '200',
            'value' => $task
        ));
    }

    public function addInfo(Request $request) : JsonResponse {
        $document_id = '';
        $task = Task::find($request->get('id'));
        if($request->get('url')) {
            $document = Document::create([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'url' => $request->get('url')
            ]);
            $document_id = $document->id;
        }
        AdvancedTask::create([
            'task_id' => $task->id,
            'document_id' => $document_id,
            'description' => $request->get('description'),

        ]);

        $advanced = AdvancedTask::where('task_id', '=', $task->id)->with('documents')->get();

        return response()->json(array(
            'status' => '200',
            'value' => [$task, $advanced]
        ));
    }

    public function finish(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));
        $task->finished = 1;
        $task->save();
        return response()->json(array(
            'status' => '200',
        ));
    }
}
