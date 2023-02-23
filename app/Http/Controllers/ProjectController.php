<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Models\Project;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Log;

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

    public function add($client) : View
    {
        $client = Client::find($client);
        return view('pages.clients.create', ['client' => $client]);
    }

    public function create(Request $request) : JsonResponse {
        $project = Project::create([
            'client_id' => $request->get('client_id'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price_per_minute' => \Auth::user()->price_per_minute
        ]);
        $client = Client::find($project->client_id);

        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'description' => "Se crea el proyecto #$project->id: $project->name para el cliente $client->name."
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    public function update(Request $request) : JsonResponse {
        $project = Project::find($request->get('id'));
        $oldName = $project->name;
        $project->name = $request->get('name');
        $project->save();

        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'description' => "Se modifica el nombre del proyecto #$project->id: $oldName, ahora el proyecto se llama $project->name."
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    public function delete(Request $request) : JsonResponse {
        $project = Project::find($request->get('id'));
        $project->calculate();
        $project->registerPayment();

        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'description' => "Se elimina el proyecto #$project->id, junto a sus respectivos pagos y tareas con un pago pendiente de ".($project->price-$project->paid)."€"
        ]);

        $project->delete();
        return response()->json(array(
            'status' => '200',
        ));
    }
}
