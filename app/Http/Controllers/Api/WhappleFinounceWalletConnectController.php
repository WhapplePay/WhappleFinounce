<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    User,
    Trade,
    WhappleFinounce
};
use App\Models\CryptoWallet;
use App\Models\Currency;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
class WhappleFinounceWalletConnectController extends Controller
{
    public function connect(Request $request){
    Log::info($request->all());
        $phone      = $request->phone;
        $checkUser  = User::where(['phone' => $phone])->first(); //get the user phone to connect the user
        if(!$checkUser){
            return response()->json(['status' => 404, 'message' => 'user not found in our system']);
        } 

         $userData  = User::where(['phone' => $phone])->get();
        $checkStatus        = WhappleFinounce::where(['user_id' => $userData[0]->id])->first(); //want to check if the user has already marked status

        if($checkStatus){
            return response()->json(['status' => 401, 'message' => 'Wallet already connect you can now deposit']); //if true return 
        }

        $connect    = WhappleFinounce::create([ //create a new connect 
            'user_id'   => $userData[0]->id,
            'status'    => 'true'
        ]);

        if($connect){  //check if the wallet was connected successfully
            return response()->json(['status' => 200, 'message' => 'Successfully connected your Whapple Pay account to your trading account']);
        }
        else{  ///retrun false if not
            return response()->json(['status' => 500, 'message' => "unable to connect wallets please try again"]);
        }

    }

  public function checkConnection(Request $request){
    $user_id = $request->user_id;
    $checkStatus = WhappleFinounce::where('user_id', $user_id)->first();

    if($checkStatus){
        return response()->json(['status' => 200, 'message' => 'User\'s wallet is connected']);
    } else {
        return response()->json(['status' => 400, 'message' => 'User\'s wallet not connected yet']);
    }
}

public function depositCrypto(Request $request) {
    Log::info($request->all());
    $request->validate([
        'phone' => 'required|numeric',
        'crypto_code' => 'required|string',
        'amount' => 'required|numeric',
    ]);
    $user = User::where(['phone' => $request->phone])->get();

    $checkStatus    = WhappleFinounce::where(['user_id' => $user[0]->id, 'status' => 'true'])->first();

    if(!$checkStatus){
        return response()->json(['status' => 404, 'message' => 'Ur wallet has not being linked yet, link and try again']);
    }
    if (!$user) {
        return response()->json(['status' => 404, 'message' => 'User not found']);
    }

    $currency = Currency::where('code', $request->crypto_code)->get();
    $users = User::where('phone', $request->phone)->get();

    
    $cryptoWallet = Wallet::where([
        'user_id' =>  $user[0]->id,
        'crypto_currency_id' => $currency[0]->id,
    ])->first();

    if (!$cryptoWallet) {
        return response()->json(['status' => 404, 'message' => 'Crypto wallet not found for the specified user and crypto code']);
    }

   
    $cryptoWallet->balance += $request->amount;
    $cryptoWallet->save();


    $transactionId = Str::random(12);


      $remarks = 'Whapple Pay deposit to ' . $currency[0]->code . ' wallet';

      Transaction::create([
          'user_id' => $user[0]->id,
          'amount' => $request->amount,
          'charge' => 0, 
          'final_balance' => '0.00',
          'trx_type' => '+',
          'remarks' => $remarks,
          'trx_id' => $transactionId,
          'code' => $currency[0]->code,
          'crypto_currency_code' => $request->crypto_code,
      ]);
    return response()->json(['status' => 200, 'message' => 'Deposit successful']);
}

public function withdrawCrypto(Request $request){
        $request->validate([
            'phone' => 'required|numeric',
            'crypto_code' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $phone      = $request->phone;
        $crypto_code = $request->crypto_code;
        $amount     = $request->amount;
        
        $checkUser  = User::where('phone', $phone)->get();
        if(!$checkUser){
            return response()->json(['status' => 404, 'message' => 'User not found']);
        }

        $checkStatus    =  WhappleFinounce::where(['user_id' => $checkUser[0]->id, 'status' => 'true'])->first();

        if(!$checkStatus){
            return response()->json(['status' => 404, 'message' => 'Ur wallet has not being linked yet, link and try again']);
        }
            $currency = Currency::where('code', $request->crypto_code)->get();

            // return response()->json($user);
            $cryptoWallet = Wallet::where([
                'user_id' =>  $checkUser[0]->id,
                'crypto_currency_id' => $currency[0]->id,
            ])->first();

            if (!$cryptoWallet) {
                return response()->json(['status' => 404, 'message' => 'Crypto wallet not found for the specified user and crypto code']);
            }
            if($request->amount >= $cryptoWallet->balance){
                return response()->json(['status' => 400, 'message' => 'You don\'t have enough money, trade to get cash']);
            }

            $cryptoWallet->balance -= $request->amount;
            $cryptoWallet->save();
        
        
            $transactionId = Str::random(12);
            $remarks = $request->amount. $currency[0]->code .' Withdrawn to Whapple Pay ' . $currency[0]->code . ' wallet';

            Transaction::create([
                'user_id' => $checkUser[0]->id,
                'amount' => $request->amount,
                'charge' => 0, 
                'final_balance' => '0.00',
                'trx_type' => '-',
                'remarks' => $remarks,
                'trx_id' => $transactionId,
                'code' => $currency[0]->code,
                'crypto_currency_code' => $request->crypto_code,
            ]);
          return response()->json(['status' => 200, 'message' => 'Withdrawal successful']);
}

}
