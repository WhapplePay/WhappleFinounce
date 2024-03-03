<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Models\Configure;
use App\Models\CryptoWallet;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Fund;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    use Notify;

    public function webhookResponse(Request $request)
    {
        if ($request->status >= 100 || $request->status == 2) {
            $userCryptoWallet = CryptoWallet::where('wallet_address', $request->address)->first();
            $user = $userCryptoWallet->user;
            $general = Configure::firstOrFail();

            if ($general->merchant_id == $request->merchant) {

                $exist = Fund::where('cp_trx', $request->txn_id)->count();
                if ($exist == 0) {

                    $crypto = Currency::find($userCryptoWallet->crypto_currency_id);
                    $sentAmount = $request->amount;

                    if ($crypto->deposit_type == 0) {
                        $charge = $sentAmount * $crypto->deposit_charge / 100;
                    } else {
                        $charge = $crypto->deposit_charge;
                    }
                    $finalAmount = $sentAmount - $charge;

                    if ($finalAmount > 0) {
                        $data = new Fund();
                        $data->user_id = $user->id;
                        $data->crypto_currency_id = $crypto->id;
                        $data->wallet_address = $request->address;
                        $data->amount = $sentAmount;
                        $data->charge = $charge;
                        $data->final_amount = $finalAmount;
                        $data->trx = strRandom();
                        $data->status = 1;
                        $data->cp_trx = $request->txn_id;
                        $data->save();

                        $userWallet = Wallet::where('user_id', $userCryptoWallet->user_id)->where('crypto_currency_id', $userCryptoWallet->crypto_currency_id)->first();
                        $userWallet->balance += $finalAmount;
                        $userWallet->save();

                        $transaction = new Transaction();
                        $transaction->user_id = $data->user_id;
                        $transaction->amount = $data->amount;
                        $transaction->charge = getAmount($data->charge, 8);
                        $transaction->final_balance = getAmount($userWallet->balance, 8);
                        $transaction->trx_type = '+';
                        $transaction->remarks = 'Deposit Via ' . optional($data->crypto)->code;
                        $transaction->trx_id = $data->trx;
                        $transaction->code = optional($data->crypto)->code ?? null;
                        $transaction->save();

                        $msg = [
                            'username' => $data->user->username,
                            'amount' => getAmount($data->amount, 8),
                            'currency' => $data->crypto->code,
                            'payable' => getAmount($data->final_amount, 8),
                            'trx' => $data->trx,
                            'post_balance' => getAmount($userWallet->balance, 8)
                        ];
                        $action = [
                            "link" => route('admin.user.fundLog', $data->user_id),
                            "icon" => "fa fa-money-bill-alt text-white"
                        ];
                        $firebaseAction = route('admin.user.fundLog', $data->user_id);
                        $this->adminPushNotification('DEPOSIT_COMPLETE', $msg, $action);
                        $this->adminFirebasePushNotification('DEPOSIT_COMPLETE', $msg, $firebaseAction);
                    }
                }
            }
        }
    }
}
