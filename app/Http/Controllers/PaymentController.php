<?php

namespace App\Http\Controllers;

use \Illuminate\Http\RedirectResponse as Redirection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Payment;
use App\Models\Client;
use App\Models\Task;
use App\Models\Log;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        $project = Project::with('client')->find($request->get('id'));

        $payment = Payment::create([
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'amount' => $request->get('amount'),
            'concept' => $request->get('concept') ? $request->get('concept') : 'Sin concepto.',
        ]);
        $project->registerPayment();
            $acuenta = $request->get('tasks') ? "" : " a cuenta";
        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'payment_id' => $payment->id,
            'description' => "Se ha realizado un pago".$acuenta." por el cliente ".$project->client->name." en el proyecto #$project->id: ".$project->name." con un importe de ".$payment->amount."€."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());

        if($request->get('tasks')) {
            $tasks = Task::whereIn('id', explode(',',$request->get('tasks')))->get();
            $payment->concept = "Pago de tareas:
            <b>Proyecto:</b> $project->name.
            <b>Tareas:</b><ul>";
            foreach($tasks as $task) {
                $task->paid = 1;
                $task->save();
                $payment->concept .= '<li>'.$task->title.'('.$task->id.')</li>';
                $payment->save();
            }
            $payment->concept .= "</ul>";
            $payment->tasks = $request->get('tasks');
            $payment->save();
        }

        return response()->json(array('status' => 200, 'value' => $payment));
    }

    public function view($id) : View|Redirection
    {
        $client = Client::find($id);
        $payments = Payment::where('client_id', '=', $client->id)->with('project')->get();
        return $client->payments->count() ? view('pages.clients.payments', ['client' => $client, 'payments' => $payments]) : redirect()->route('clients.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $payment = Payment::find($id);
        $payment->confirmed = 1;
        $payment->save();
        $client = Client::find($payment->client_id);
        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $payment->client_id,
            'project_id' => $payment->project_id,
            'payment_id' => $payment->id,
            'description' => "Se ha confirmado un pago del proyecto #$payment->project_id con un importe de $payment->amount €."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $client->user_id)->get());
        return response()->json(array('status' => 200, 'value' => $payment));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        $tasks = Task::whereIn('id', explode(',',$payment->tasks))->get();
        foreach($tasks as $task) {
            $task->paid =0;
            $task->save();
        }
        $project = Project::with('client')->find($payment->project_id);
        Log::publish([
            'user_id' => auth()->user()->id,
            'client_id' => $payment->client_id,
            'project_id' => $payment->project_id,
            'payment_id' => $payment->id,
            'description' => "Se ha eliminado un pago del proyecto #$payment->project_id con un importe de $payment->amount €."
        ],
        \App\Models\User::where('role','=', 1)->orWhere('id', '=', $project->client->user_id)->get());
        $payment->delete();
        if($project) $project->registerPayment();
        return response()->json(array('status' => 200));
    }
}
