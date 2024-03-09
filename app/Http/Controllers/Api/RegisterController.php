<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Currency; 
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class RegisterController extends Controller
{
   /**
 * Create a new user instance after a valid registration.
 *
 * @param  array  $data
 * @return \App\Models\User
 */
protected function create(array $data)
{
   
    $basic = (object) config('basic');

    $sponsor = $data['sponsor']; 
    $sponsorId = ($sponsor != null) ? User::where('username', $sponsor)->first() : null;

    $user = User::create([
        'firstname' => $data['firstname'],
        'lastname' => $data['lastname'],
        'username' => $data['username'],
        'email' => $data['email'],
        'referral_id' => ($sponsorId != null) ? $sponsorId->id : null,
        'country_code' => $data['country_code'],
        'phone_code' => $data['phone_code'],
        'phone' => $data['phone'],
        'password' => Hash::make($data['password']),
        'email_verification' => 0,
        'sms_verification' => 0,
    ]);

    // Create wallets for the user
    $walletData = [];
    $cryptos = Currency::latest()->where('flag', 1)->pluck('id'); 

    foreach ($cryptos as $id) {
        $walletData[] = [
            'crypto_currency_id' => $id,
            'user_id' => $user->id,
            'balance' => 0,
        ];
    }

    \App\Models\Wallet::insert($walletData);

    return $user;
}

    /**
     * Handle the registration of a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        
        Log::info(['data' =>$request->all()]);
       
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone'      => 'required|unique:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $user = $this->create($request->all());
    
        return response()->json(['status' => 200, 'message' => 'Registration successfuls']);
    }
}
