<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function passwordChange(Request $request) : JsonResponse
    {
        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($request->get('password'));
        $user->save();
        return response()->json(array('status' => 200));
    }
}
