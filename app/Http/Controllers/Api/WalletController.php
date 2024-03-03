<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CryptoWallet;
use App\Models\Configure;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Coinpayments\CoinPaymentHosted;

class WalletController extends Controller
{
    public function wallets(Request $request)
    {
        $userId = $request->input('user_id');
    
        $userWallets = Wallet::where('user_id', $userId)->with('crypto')->latest()->get();
    
      
        $formattedWallets = $userWallets->map(function ($wallet) {
            $cryptoDetails = $wallet->crypto;
            return [
                'wallet_id' => $wallet->id,
                'crypto_id' => $cryptoDetails->id,
                'crypto_name' => $cryptoDetails->name,
                'crypto_symbol' => $cryptoDetails->symbol,
                'balance' => $wallet->balance,
            ];
        });
    
        return response()->json(['data' => $formattedWallets]);
    }

    public function getUserCryptoWallet(Request $request){
        $userId = $request->input('user_id');

        $cryptoWallet = CryptoWallet::where('user_id', $userId)->get()->all();

        return response()->json(['data' => $cryptoWallet]);
    }

    public function walletGenerate(Request $request)
    {
        $userId = $request->input('user_id'); 
    
        $crypto = Currency::active()->findOrFail($request->input('currency_id'));
    
        $coinPayAcc = Configure::firstOrFail();
        $cps = new CoinPaymentHosted();
        $cps->Setup($coinPayAcc->private_key, $coinPayAcc->public_key);
    
        $result = $cps->GetCallbackAddress($crypto->code);
    
        if ($result['error'] == 'ok') {
            $newCryptoWallet = new CryptoWallet();
            $newCryptoWallet->user_id = $userId;
            $newCryptoWallet->crypto_currency_id = $crypto->id;
            $newCryptoWallet->wallet_address = $result['result']['address'];
            $newCryptoWallet->save();
    
            return response()->json(['message' => 'New Wallet Address Generated Successfully', 'address' => $newCryptoWallet->wallet_address ]);
        } else {
            return response()->json(['error' => $result['error']], 400);
        }
    }
    
}
