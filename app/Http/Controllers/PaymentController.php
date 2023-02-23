<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Client;
use App\Models\Log;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends Controller
{
    public function create(Request $request) : JsonResponse {
        $project = Project::with('client')->find($request->get('id'));

        $payment = Payment::create([
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'amount' => $request->get('amount'),
            'concept' => $request->get('concept') ? $request->get('concept') : 'Sin concepto.',
        ]);
        $project->registerPayment();
            $acuenta = $request->get('tasks') ? "" : " a cuenta";
        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'payment_id' => $payment->id,
            'description' => "Se realiza un pago".$acuenta." por el cliente ".$project->client->name." en el proyecto #$project->id: ".$project->name." con un importe de ".$payment->amount."€."
        ]);

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

    public function view($id) {
        $client = Client::find($id);
        $payments = Payment::where('client_id', '=', $client->id)->with('project')->get();
        return $client->payments->count() ? view('pages.clients.payments', ['client' => $client, 'payments' => $payments]) : redirect()->route('clients');
    }

    public function update(Request $request) : JsonResponse {
        $payment = Payment::find($request->get('id'));
        $payment->confirmed = 1;
        $payment->save();
        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $payment->client_id,
            'project_id' => $payment->project_id,
            'payment_id' => $payment->id,
            'description' => "Se confirma un pago del proyecto #$payment->project_id con un importe de $payment->amount €."
        ]);
        return response()->json(array('status' => 200, 'value' => $payment));
    }

    public function delete(Request $request) : JsonResponse {
        $payment = Payment::find($request->get('id'));
        $tasks = Task::whereIn('id', explode(',',$payment->tasks))->get();
        foreach($tasks as $task) {
            $task->paid =0;
            $task->save();
        }
        $project = Project::find($payment->project_id);
        Log::create([
            'user_id' => \Auth::user()->id,
            'client_id' => $payment->client_id,
            'project_id' => $payment->project_id,
            'payment_id' => $payment->id,
            'description' => "Se elimina un pago del proyecto #$payment->project_id con un importe de $payment->amount €."
        ]);
        $payment->delete();
        if($project) $project->registerPayment();
        return response()->json(array('status' => 200));
    }
}
