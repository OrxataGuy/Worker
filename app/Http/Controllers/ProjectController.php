<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Models\Project;
use App\Models\Client;
use App\Models\Payment;

class ProjectController extends Controller
{
    public function index() : View
    {
        $clients = Client::with('projects')->get();
        return view('pages.projects.index', ['clients' => $clients]);
    }

    public function get(Request $request) : JsonResponse {
        $project = Project::with('tasks')->find($request->get('id'));
        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    public function create(Request $request) : JsonResponse {
        $project = Project::create([
            'client_id' => $request->get('client_id'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price_per_minute' => \Auth::user()->price_per_minute
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    public function update(Request $request) : JsonResponse {
        $project = Project::find($request->get('id'));

        $project->name = $request->get('name');
        $project->description = $request->get('description');
        $project->save();

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    public function delete(Request $request) : JsonResponse {
        $project = Project::find($request->get('id'));
        $project->delete();
        return response()->json(array(
            'status' => '200',
        ));
    }

    public function pay(Request $request) : JsonResponse {
        $project = Project::find($request->get('id'));

        Payment::create([
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'amount' => $request->get('paid')
        ]);

        $project->registerPayment();

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }
}
