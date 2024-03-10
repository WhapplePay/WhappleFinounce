<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{
    public function login(Request $request)
    {
        Log::info(['data' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'message' => "invalid credentials "], 422);
        }

        $identityType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $identityType => $request->identity,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $responseData = [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
            ];

            return response()->json(['status' => 200, 'message' => $responseData]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }
}
