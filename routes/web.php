<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\ClientController as Clients;
use App\Http\Controllers\ProjectController as Projects;
use App\Http\Controllers\TaskController as Tasks;
use App\Http\Controllers\PaymentController as Payments;
use App\Http\Controllers\PlanificationController as Plans;
use App\Http\Controllers\DocumentController as Docs;

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


Route::group(['middleware' => 'auth'], function () {
    Route::post('upload', [Docs::class, 'store'])->name('upload');

    Route::resource('clients', Clients::class)->only('index','store','create','show','update','destroy');
    Route::resource('projects', Projects::class)->only('index','store','show','update','destroy');
    Route::get('projects/create/{client}', [Projects::class, 'create'])->name('projects.create');
    Route::get('projects/{project}/tasks', [Tasks::class, 'index'])->name('tasks.index');
    Route::resource('tasks', Tasks::class)->only('show','store','update');
    Route::group(['prefix' => 'tasks/{task}'], function () {
        Route::get('view', [Tasks::class, 'view'])->name('tasks.view');
        Route::post('open', [Tasks::class, 'reopen'])->name('tasks.reopen');
        Route::put('toggle', [Tasks::class, 'toggleCounter'])->name('tasks.toggle');
        Route::put('finish', [Tasks::class, 'destroy'])->name('tasks.destroy');
        Route::group(['prefix' => 'info'], function () {
            Route::post('add', [Tasks::class, 'addInfo'])->name('tasks.info.add');
            Route::put('{info}/delete', [Tasks::class, 'delInfo'])->name('tasks.info.del');
        });
    });
    Route::resource('payments', Payments::class)->only('store','update','destroy');
    Route::group(['prefix' => 'payments'], function () {
        Route::get('{client}/view', [Payments::class, 'view'])->name('payments.view');
    });



   /* Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [Projects::class, 'index'])->name('projects');
        Route::get('get', [Projects::class, 'get'])->name('project.get');
        Route::get('add/{client}', [Projects::class, 'add'])->name('project.add');
        Route::post('create', [Projects::class, 'create'])->name('project.create');
        Route::put('update', [Projects::class, 'update'])->name('project.update');
        Route::delete('delete', [Projects::class, 'delete'])->name('project.delete');
        Route::post('payment', [Payments::class, 'create'])->name('project.pay');

        Route::group(['prefix' => 'payments'], function() {
            Route::get('{id}', [Payments::class, 'view'])->name('payment.view');
            Route::put('pay', [Payments::class, 'update'])->name('payment.update');
            Route::delete('delete-payment', [Payments::class, 'delete'])->name('payment.delete');
        });

        Route::group(['prefix' => '{project}'], function () {
            Route::get('/', [Tasks::class, 'index'])->name('tasks');

            Route::get('/view/{task}', [Tasks::class, 'view'])->name('task.view');
            Route::post('toggle', [Tasks::class, 'toggleCounter'])->name('tasks.toggle');
            Route::post('reopen', [Tasks::class, 'reopen'])->name('tasks.reopen');

            Route::get('get', [Tasks::class, 'get'])->name('tasks.get');
            Route::post('create', [Tasks::class, 'create'])->name('tasks.create');
            Route::put('update', [Tasks::class, 'update'])->name('tasks.update');
            Route::put('finish', [Tasks::class, 'finish'])->name('tasks.finish');

            Route::post('add', [Tasks::class, 'addInfo'])->name('tasks.add.details');
            Route::put('del', [Tasks::class, 'delInfo'])->name('tasks.del.details');

        });
    });*/
});
