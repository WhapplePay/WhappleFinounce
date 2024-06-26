<?php

namespace App\Services;

use App\Http\Traits\Notify;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Image;

class BasicService
{
    use Notify;

    public function validateImage(object $getImage, string $path)
    {
        if ($getImage->getClientOriginalExtension() == 'jpg' or $getImage->getClientOriginalName() == 'jpeg' or $getImage->getClientOriginalName() == 'png') {
            $image = uniqid() . '.' . $getImage->getClientOriginalExtension();
        } else {
            $image = uniqid() . '.jpg';
        }
        Image::make($getImage->getRealPath())->resize(300, 250)->save($path . $image);
        return $image;
    }

    public function validateDate(string $date)
    {
        if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/", $date)) {
            return true;
        } else {
            return false;
        }
    }

    public function validateKeyword(string $search, string $keyword)
    {
        return preg_match('~' . preg_quote($search, '~') . '~i', $keyword);
    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {

        $varb = $wallet . "?amount=" . $amount;
        return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
    }

    public function preparePaymentUpgradation($order)
    {
        $basic = (object)config('basic');
        $gateway = $order->gateway;


        if ($order->status == 0) {
            $order['status'] = 1;
            $order->update();

            $user = $order->user;
            $user->balance += $order->amount;
            $user->save();

            $this->makeTransaction($user, getAmount($order->amount), getAmount($order->charge), '+', $order->transaction, 'Payment Via ' . $gateway->name);

            $msg = [
                'username' => $user->username,
                'amount' => getAmount($order->amount),
                'currency' => $basic->currency,
                'gateway' => $gateway->name
            ];
            $action = [
                "link" => route('admin.user.fundLog', $user->id),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->adminPushNotification('PAYMENT_COMPLETE', $msg, $action);
            $this->sendMailSms($user, 'PAYMENT_COMPLETE', [
                'gateway_name' => $gateway->name,
                'amount' => getAmount($order->amount),
                'charge' => getAmount($order->charge),
                'currency' => $basic->currency,
                'transaction' => $order->transaction,
                'remaining_balance' => getAmount($user->balance)
            ]);
        }
        session()->forget('amount');

    }


    public function setBonus($user, $amount, $commissionType = '')
    {

        $basic = (object)config('basic');
        $userId = $user->id;
        $i = 1;
        $level = \App\Models\Referral::where('commission_type', $commissionType)->count();
        while ($userId != "" || $userId != "0" || $i < $level) {
            $me = \App\Models\User::with('referral')->find($userId);
            $refer = $me->referral;
            if (!$refer) {
                break;
            }
            $commission = \App\Models\Referral::where('commission_type', $commissionType)->where('level', $i)->first();
            if (!$commission) {
                break;
            }
            $com = ($amount * $commission->percent) / 100;
            $new_bal = getAmount($refer->balance + $com);
            $refer->balance = $new_bal;
            $refer->save();

            $trx = strRandom();

            $remarks = ' level ' . $i . ' Referral bonus From ' . $user->username;

            $this->makeTransaction($refer, $com, 0, '+', $trx, $remarks);

            $bonus = new \App\Models\ReferralBonus();
            $bonus->from_user_id = $refer->id;
            $bonus->to_user_id = $user->id;
            $bonus->level = $i;
            $bonus->amount = getAmount($com);
            $bonus->main_balance = $new_bal;
            $bonus->transaction = $trx;
            $bonus->type = $commissionType;
            $bonus->remarks = $remarks;
            $bonus->save();


            $this->sendMailSms($refer, $type = 'REFERRAL_BONUS', [
                'transaction_id' => $trx,
                'amount' => getAmount($com),
                'currency' => $basic->currency_symbol,
                'bonus_from' => $user->username,
                'final_balance' => $refer->interest_balance,
                'level' => $i
            ]);


            $msg = [
                'bonus_from' => $user->username,
                'amount' => getAmount($com),
                'currency' => $basic->currency_symbol,
                'level' => $i
            ];
            $action = [
                "link" => route('user.referral.bonus'),
                "icon" => "fa fa-money-bill-alt"
            ];
            $this->userPushNotification($refer, 'REFERRAL_BONUS', $msg, $action);

            $userId = $refer->id;
            $i++;
        }
        return 0;

    }


    /**
     * @param $user
     * @param $amount
     * @param $charge
     * @param $trx_type
     * @param $balance_type
     * @param $trx_id
     * @param $remarks
     */
    public static function makeTransaction($user, $amount, $charge, $trx_type = null, $trx_id, $remarks = null, $code = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = getAmount($amount);
        $transaction->charge = $charge;
        $transaction->trx_type = $trx_type;
        $transaction->final_balance = $user->balance;
        $transaction->trx_id = $trx_id;
        $transaction->remarks = $remarks;
        $transaction->code = $code ?? null;
        $transaction->save();
    }

    public function checkIsTradeable($ad = null, $receiveAmount = 0, $charge = 0){

        $loginWallet = \App\Models\Wallet::where('user_id', auth()->id())->where('crypto_currency_id', $ad->crypto_id)->exists();

        if ($ad->status == 0) {
            return [
                'status' => 'error',
                'message' => 'This advertisement is currently disabled'
            ];
        }

        if ($ad->user_id == auth()->id()) {
            return [
                'status' => 'error',
                'message' => "You can't trade on your own advertisement"
            ];
        }

        if (optional($ad->cryptoCurrency)->status == 0) {
            return [
                'status' => 'error',
                'message' => 'Trading with this crypto currency is currently disabled'
            ];
        }

        if ($ad->gateways) {
            foreach ($ad->gateways as $item) {
                if ($item->status == 0) {
                    return [
                        'status' => 'error',
                        'message' => 'Trading with this payment method is currently disabled'
                    ];
                }
            }
        }

        if (optional($ad->fiatCurrency)->status == 0) {
            return [
                'status' => 'error',
                'message' => 'Trading with this fiat currency is currently disabled'
            ];
        }

        if ($ad->type == 'sell') {
            $wallet = \App\Models\Wallet::where('user_id', $ad->user_id)->where('crypto_currency_id', $ad->crypto_id)->first();
            if ($wallet) {
                if ($wallet->balance < $receiveAmount + $charge) {
                    return [
                        'status' => 'error',
                        'message' => 'Seller doesn\'t have enough balance'
                    ];
                }
            }
        }

        if ($loginWallet == false) {
            return [
                'status' => 'error',
                'message' => 'You can not proceed this action'
            ];
        }

        if ($ad->type == 'buy') {
            $wallet = \App\Models\Wallet::where('user_id', auth()->id())->where('crypto_currency_id', $ad->crypto_id)->first();
            if ($wallet) {
                if ($wallet->balance < $receiveAmount) {
                    return [
                        'status' => 'error',
                        'message' => 'You have not enough balance'
                    ];
                }
            }
        }

        return [
            'status' => 'success',
        ];

    }

}
