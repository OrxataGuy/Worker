<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientController extends Controller
{
    public function index() : View
    {
        return Client::count() > 0 ? view('pages.clients.index') : view('pages.clients.create');
    }

    public function create(Request $request) : View {
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

        Task::create([
            'title' => "Creación de proyecto en la aplicación",
            'description' => "Se da de alta el proyecto en la aplicación",
            'time' => 10,
            'price' => \Auth::user()->price_per_minute*10,
            'project_id' => $project->id
        ]);

        return view('pages.clients.index');
    }
}
