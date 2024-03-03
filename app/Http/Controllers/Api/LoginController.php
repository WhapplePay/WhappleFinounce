<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identity' => 'required',
            'password' => 'required',
        ]);

        $identityType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $identityType => $request->identity,
            'password' => $request->password,
        ];

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Customize the response based on your needs
            $responseData = [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
            ];

            return response()->json(['success' => true, 'data' => $responseData]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
    }
}
