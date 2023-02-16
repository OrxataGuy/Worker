<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\ClientController as Clients;
use App\Http\Controllers\ProjectController as Projects;
use App\Http\Controllers\TaskController as Tasks;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [Home::class, 'index'])->name('home');

Route::prefix('clients')->group(function () {
    Route::get('/', [Clients::class, 'index'])->name('clients');
});

Route::prefix('projects')->group(function () {
    Route::get('/', [Projects::class, 'index'])->name('projects');

    Route::prefix('{project}')->group(function () {
        Route::get('/', [Tasks::class, 'index'])->name('tasks');
    });
});
