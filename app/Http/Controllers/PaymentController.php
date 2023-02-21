<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends Controller
{
    public function create(Request $request) : JsonResponse {
        $project = Project::find($request->get('id'));

        $payment = Payment::create([
            'client_id' => $project->client_id,
            'project_id' => $project->id,
            'amount' => $request->get('amount'),
            'concept' => $request->get('concept') ? $request->get('concept') : 'Sin concepto.',
        ]);
        $project->registerPayment();
        if($request->get('tasks')) {
            $tasks = Task::whereIn('id', explode(',',$request->get('tasks')))->get();
            $payment->concept = "Pago de tareas: ";
        foreach($tasks as $task) {
            $task->paid = 1;
            $task->save();
            $payment->concept .= $task->title.'('.$task->id.')';
            $payment->save();
        }
        }

        return response()->json(array('status' => 200, 'value' => $payment));
    }
}
