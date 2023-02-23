<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\Log;

use Symfony\Component\HttpFoundation\JsonResponse;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('projects')->get();
        return Client::count() > 0 ? view('pages.clients.index', ['clients' => $clients]) : redirect()->route('clients.add');
    }

    public function add() : View
    {
        return view('pages.clients.create');
    }

    public function create(Request $request) {
        if ($request->get('client_id'))
            $client = Client::find($request->get('client_id'));
        else
            $client = Client::create([
                'name' => $request->get('clientName'),
                'email' => $request->get('clientEmail'),
                'phone' => $request->get('clientPhone'),
                'user_id' => \Auth::user()->id
            ]);

        $project = Project::create([
            'name' => $request->get('projectName'),
            'description' => $request->get('projectDescription'),
            'price_per_minute' => \Auth::user()->price_per_minute,
            'client_id' => $client->id
        ]);

        $task = Task::create([
            'title' => "Planificación del proyecto",
            'description' => "Planificación de todos los aspectos del proyecto.",
            'project_id' => $project->id
        ]);

        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $project->client->id,
            'project_id' => $project->id,
            'task_id' => $task->id,
            'description' => "Se crea el cliente $client->name con el proyecto #".$project->id." como proyecto inicial."
        ]);

        return $request->get('client_id') ? redirect()->route('projects') : redirect()->route('clients');
    }

    public function get(Request $request) : JsonResponse {
        $client = Client::find($request->get('id'));
        return response()->json(array(
            'status' => '200',
            'value' => $client
        ));
    }

    public function update(Request $request) : JsonResponse {
        $client = Client::find($request->get('id'));
        $oldName = $client->name;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->save();

        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $client->id,
            'description' => "Se modifica el nombre del cliente $oldName, ahora se llama $client->name."
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $client
        ));
    }

    public function delete(Request $request) : JsonResponse {
        $client = Client::find($request->get('id'));
        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $client->id,
            'description' => "Se elimina el cliente $client->name, junto a sus proyectos y sus pagos."
        ]);
        $client->delete();

        return response()->json(array(
            'status' => '200',
        ));
    }
}
