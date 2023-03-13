<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Client;
use \Illuminate\Http\RedirectResponse as Redirection;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() : View|Redirection
    {
        return auth()->user()->role==1 ? view('pages.home') : redirect('projects');
    }
}
