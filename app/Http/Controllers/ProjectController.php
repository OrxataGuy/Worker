<?php

namespace App\Http\Controllers;

use \Illuminate\Http\RedirectResponse as Redirection;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\View\View;
use \Illuminate\Http\Request;
use \App\Models\Project;
use \App\Models\Payment;
use \App\Models\Client;
use \App\Models\Task;
use \App\Models\Log;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index() : View
    {
        $clients = auth()->user()->role==1 ?
                Client::with('projects')->get() :
                Client::with('projects')->where('user_id', '=', auth()->user()->id)->get();
        return view('pages.projects.index', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id) : View
    {
        $client = Client::find($id);
        return view('pages.clients.create', ['client' => $client]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        $project = Project::create([
            'client_id' => $request->get('client_id'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price_per_minute' => auth()->user()->price_per_minute
        ]);

        Log::create([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'description' => "Se crea el proyecto #$project->id: $project->name para el cliente $client->name."
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) : JsonResponse
    {
        $project = Project::with('tasks')->find($id);
        return response()->json(array(
            'status' => '200',
            'value' => $project
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
        $project = Project::find($id);
        $oldName = $project->name;
        $project->name = $request->get('name');
        $project->save();

        Log::create([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'description' => "Se modifica el nombre del proyecto #$project->id: $oldName, ahora el proyecto se llama $project->name."
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $project
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->calculate();
        $project->registerPayment();

        Log::create([
            'user_id' => auth()->user()->id,
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
