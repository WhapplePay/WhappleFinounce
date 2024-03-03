<?php

namespace App\Console\Commands;

use App\Http\Traits\Notify;
use App\Models\Currency;
use App\Models\Payout;
use App\Models\PayoutLog;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Console\Command;

class BinanceGetStatus extends Command
{
    use Notify;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout-status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $methodObj = 'App\\Services\\Payout\\binance\\Card';
        $data = $methodObj::getStatus();
        if ($data) {
            $apiResponses = collect($data);
            $binaceIds = $apiResponses->pluck('id');
            $payouts = PayoutLog::whereIn('response_id', $binaceIds)->where('status', 1)->get();
            foreach ($payouts as $payout) {
                foreach ($apiResponses as $apiResponse) {
                    if ($payout->response_id == $apiResponse->id) {
                        $status = $apiResponse->status;
                        if ($status == 6) {

                            $payout->status = 2;
                            $payout->save();
                            $binance = new BinanceGetStatus();
                            $binance->userNotify($payout, 1);

                        } elseif ($status == 1 || $status == 3 || $status == 5) {
                            $user = $payouts->user;
                            $currency = Currency::where('code', $payouts->code)->first();
                            $wallet = Wallet::where('user_id', $data->user_id)->where('crypto_currency_id', $currency->id)->first();
                            $wallet->balance += $data->net_amount;
                            $wallet->save();

                            $transaction = new Transaction();
                            $transaction->user_id = $payouts->user_id;
                            $transaction->amount = getAmount($payouts->net_amount, 8);
                            $transaction->charge = $payouts->charge;
                            $transaction->trx_type = '+';
                            $transaction->remarks = getAmount($payouts->amount, 8) . ' ' . $payouts->code . ' withdraw amount has been refunded';
                            $transaction->trx_id = $payouts->trx_id;
                            $transaction->save();

                            $payout->status = 3;
                            $payout->save();
                            $binance = new BinanceGetStatus();
                            $binance->userNotify($payout, 0);
                        }
                        break;
                    }
                }
            }
        }
        return 0;
    }

    public function userNotify($payout, $type = 1)
    {
        if ($type == 1) {
            $template = 'PAYOUT_APPROVE';
        } else {
            $template = 'PAYOUT_FAIL';
        }

        $receivedUser = $payout->user;
        $this->sendMailSms($receivedUser, $template, [
            'amount' => getAmount($payout->amount, 8),
            'charge' => getAmount($payout->charge, 8),
            'currency' => $payout->code,
            'transaction' => $payout->trx_id,
            'feedback' => $payout->feedback
        ]);


        $msg = [
            'amount' => getAmount($payout->amount, 8),
            'currency' => $payout->code,
        ];

        $action = [
            "link" => '#',
            "icon" => "fa fa-money-bill-alt "
        ];
        $this->userPushNotification($receivedUser, $template, $msg, $action);
        $this->userFirebasePushNotification($receivedUser, $template, $msg);

        return 0;
    }
}
