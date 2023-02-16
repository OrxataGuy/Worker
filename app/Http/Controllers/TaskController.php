<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function index($project) : View
    {
        $project = Project::find($project);
        return $project ? view('pages.projects.tasks', ['project' => $project]) : view('errors.404');
    }
}
