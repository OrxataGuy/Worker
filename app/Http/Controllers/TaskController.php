<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
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

    public function finish(Request $request) : JsonResponse {
        $task = Task::find($request->get('id'));
        $task->finished = 1;
        $task->save();
        return response()->json(array(
            'status' => '200',
        ));

    }
}
