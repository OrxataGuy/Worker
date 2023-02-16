<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;

class ClientController extends Controller
{
    public function index() : View
    {
        return view('pages.clients.index');
    }
}
