<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetAppConfigMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorized = Auth::user();
        if(!App::runningInConsole() && !is_null($authorized)) {
            if($authorized->role==1) {
                Config::set('url', 'https://clients.orxatasoftware.com');
                Config::set('site', 'CLIENTS');
            }
        }else {
            $url = $request->get('url');
            dd(Request::url());
        }
        return $next($request);
    }
}
