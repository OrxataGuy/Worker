<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\ClientController as Clients;
use App\Http\Controllers\ProjectController as Projects;
use App\Http\Controllers\TaskController as Tasks;
use App\Http\Controllers\PaymentController as Payments;
use App\Http\Controllers\PlanificationController as Plans;
use App\Http\Controllers\DocumentController as Docs;
use App\Http\Controllers\UserController as Users;

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
Route::get('register', [Home::class, 'index'])->name('prevent.reg.get');
Route::post('register', [Home::class, 'index'])->name('prevent.reg.post');
Route::get('kitten', function() {
    \App\Models\User::find(1)->notify(new \App\Notifications\TaskFinished());
});

Route::group(['middleware' => ['auth', 'only-dev']], function () {
    Route::post('upload', [Docs::class, 'store'])->name('upload');

    Route::resource('clients', Clients::class)->only('index','store','create','show','update','destroy');
    Route::get('projects/create/{client}', [Projects::class, 'create'])->name('projects.create');
    Route::resource('payments', Payments::class)->only('store','update','destroy');
    Route::group(['prefix' => 'payments'], function () {
        Route::get('{client}/view', [Payments::class, 'view'])->name('payments.view');
    });

    Route::resource('projects', Projects::class)->only('store','update','destroy');
    Route::resource('tasks', Tasks::class)->only('store','update');
    Route::group(['prefix' => 'tasks/{task}'], function () {
        Route::put('config', [Tasks::class, 'updateTime'])->name('tasks.time');
        Route::put('work', [Tasks::class, 'updateWorkingOn'])->name('tasks.work');
        Route::post('open', [Tasks::class, 'reopen'])->name('tasks.reopen');
        Route::put('toggle', [Tasks::class, 'toggleCounter'])->name('tasks.toggle');
        Route::put('finish', [Tasks::class, 'destroy'])->name('tasks.destroy');
        Route::group(['prefix' => 'info'], function () {
            Route::post('add', [Tasks::class, 'addInfo'])->name('tasks.info.add');
            Route::put('{info}/delete', [Tasks::class, 'delInfo'])->name('tasks.info.del');
        });
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('change-pwd', [Users::class, 'passwordChange'])->name('pwd.change');
    Route::resource('projects', Projects::class)->only('index','show');
    Route::get('projects/{project}/tasks', [Tasks::class, 'index'])->name('tasks.index');
    Route::resource('tasks', Tasks::class)->only('show');
    Route::group(['prefix' => 'tasks/{task}'], function () {
        Route::get('view', [Tasks::class, 'view'])->name('tasks.view');
    });
});
