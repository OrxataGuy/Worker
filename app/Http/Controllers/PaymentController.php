<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;

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
        ]);
        $project->registerPayment();
        return response()->json(array('status' => 200, 'value' => $payment));
    }
}
