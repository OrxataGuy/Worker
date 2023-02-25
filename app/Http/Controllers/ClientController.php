<?php

namespace App\Http\Controllers;

use \Illuminate\Http\RedirectResponse as Redirection;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Contracts\View\View;
use \Illuminate\Http\Request;

use \App\Models\Project;
use \App\Models\Client;
use \App\Models\Task;
use \App\Models\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index() : View|Redirection
    {
        return Client::count() > 0 ? view('pages.clients.index', ['clients' => Client::with('projects')->get()]) : redirect()->route('clients.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create() : View
    {
        return view('pages.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) : Redirection
    {
        $client = $request->get('client_id') ?
                    Client::find($request->get('client_id')) :
                    Client::create([
                        'name' => $request->get('clientName'),
                        'email' => $request->get('clientEmail'),
                        'phone' => $request->get('clientPhone'),
                        'user_id' => auth()->user()->id
                    ]);

        $project = Project::create([
            'name' => $request->get('projectName'),
            'description' => $request->get('projectDescription'),
            'price_per_minute' => auth()->user()->price_per_minute,
            'client_id' => $client->id
        ]);

        $project->attachPlatforms($request);

        $task = Task::create([
            'title' => "Planificación del proyecto",
            'description' => "Planificación de todos los aspectos del proyecto.",
            'project_id' => $project->id
        ]);

        Log::create([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client->id,
            'project_id' => $project->id,
            'task_id' => $task->id,
            'description' => "Se crea el cliente $client->name con el proyecto #".$project->id." como proyecto inicial."
        ]);

        return $request->get('client_id') ? redirect()->route('projects.index') : redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function show($id) : JsonResponse
    {
        $client = Client::find($id);
        return response()->json(array(
            'status' => '200',
            'value' => $client
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        $client = Client::find($id);
        $oldName = $client->name;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->save();

        Log::create([
            'user_id' => auth()->user()->id,
            'client_id' => $client->id,
            'description' => "Se modifica el nombre del cliente $oldName, ahora se llama $client->name."
        ]);

        return response()->json(array(
            'status' => '200',
            'value' => $client
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function destroy($id) : JsonResponse
    {
        $client = Client::find($id);
        $amount = 0;

        foreach($client->projects() as $project) {
            $project->calculate();
            $project->registerPayment();
            $amount += $project->price-$project->paid;
        }

        Log::create([
            'user_id' => auth()->user()->id,
            'client_id' => $client->id,
            'description' => "Se elimina el cliente $client->name, junto a sus proyectos y sus pagos con una deuda de $amount €.
            Información de contacto:
            - Nombre: $client->name.
            - Email: $client->email.
            - Teléfono: $client->phone."
        ]);

        $client->delete();

        return response()->json(array(
            'status' => '200',
        ));
    }
}
