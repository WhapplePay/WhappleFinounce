<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Configure;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Models\Wallet;
use Carbon\Carbon;
use App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    use Upload, Notify;


    public function runningTrades(Request $request)
    {
        $userid = $request->user_id;
    
        $runningTrades = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->whereIn("status", [0, 1, 5, 6, 7])
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));
    
        return response()->json(['running_trades' => $runningTrades]);
    }
    
    public function completedTrades(Request $request)
    {
        $userid = $request->user_id;
    
        $completedTrades = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->whereIn("status", [3, 4, 8])
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));
    
        return response()->json(['completed_trades' => $completedTrades]);
    }

    public function cancelTrade(Request $request)
    {
        try {
            $userid = auth()->id();
            $trade = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->findOrFail($request->tradeId);

            if ($trade->status != 0) {
                return response()->json(['error' => 'You cannot cancel this trade'], 400);
            }

            $trade->status = 3;
            $trade->cancel_by = $userid;
            $trade->save();

            $user = auth()->user();
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' canceled this trade.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            $demoUser = $trade->owner_id == $userid ? $trade->sender : $trade->owner;

            $msg = [
                'username' => $user->username,
                'amount' => BasicService::getAmount($trade->receive_amount),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ];

            $action = [
                "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $firebaseAction = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
            $this->userPushNotification($demoUser, 'CANCEL_TRADE', $msg, $action);
            $this->userFirebasePushNotification($demoUser, 'CANCEL_TRADE', $msg, $firebaseAction);

            $this->sendMailSms($demoUser, 'CANCEL_TRADE', [
                'username' => $user->username,
                'amount' => BasicService::getAmount($trade->receive_amount),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ]);

            return response()->json(['success' => 'Trade has been canceled']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong'], 500);
        }
    }

    public function paidTrade(Request $request)
    {
        try {
            $userid = auth()->id();
            $trade = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->findOrFail($request->tradeId);

            $trade->status = 1;
            $trade->paid_at = Carbon::now();
            $trade->time_remaining = Carbon::now()->addMinutes($trade->payment_window);
            $trade->save();

            $user = auth()->user();
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' has marked this trade as paid. Check if you have received the payment.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            $mailNotifyUser = $trade->type == 'sell' ? $trade->owner : $trade->sender;

            $msg = [
                'username' => $user->username,
                'amount' => BasicService::getAmount($trade->pay_amount),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->currency)->code ?? 'Null',
            ];

            $action = [
                "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $firebaseAction = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
            $this->userPushNotification($mailNotifyUser, 'BUYER_PAID', $msg, $action);
            $this->userFirebasePushNotification($mailNotifyUser, 'BUYER_PAID', $msg, $firebaseAction);

            $this->sendMailSms($mailNotifyUser, 'BUYER_PAID', [
                'username' => $user->username,
                'amount' => BasicService::getAmount($trade->pay_amount),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->currency)->code ?? 'Null',
            ]);

            return response()->json(['success' => 'Trade has been Paid']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong'], 500);
        }
    }

    public function releaseTrade(Request $request)
    {
        try {
            $userid = auth()->id();
            $trade = Trade::with(['advertise'])->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->whereStatus(1)->findOrFail($request->tradeId);

            $wallet = $trade->type == 'sell' ?
                Wallet::with('crypto')->where('user_id', $trade->owner_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first() :
                Wallet::with('crypto')->where('user_id', $trade->sender_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();

            $receiverWallet = $trade->type == 'sell' ?
                Wallet::with('crypto')->where('user_id', $trade->sender_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first() :
                Wallet::with('crypto')->where('user_id', $trade->owner_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();

            if (!$wallet) {
                return response()->json(['error' => 'You cannot proceed with this action'], 400);
            }

            $tradeCharge = config('basic.tradeCharge');
            $adminCharge = $tradeCharge * $trade->receive_amount / 100;

            if ($wallet->balance < $trade->receive_amount + $adminCharge) {
                return response()->json(['error' => 'Insufficient Balance'], 400);
            }

            $processingMin = Carbon::now()->diffInMinutes(Carbon::parse($trade->created_at));
            $processingMin = doubleval($processingMin);

            DB::beginTransaction();
            $trade->status = 4;
            $trade->complete_at = Carbon::now();
            $trade->admin_charge = $adminCharge;
            $trade->processing_minutes = $processingMin;
            $trade->save();

            $trade->advertise->completed_trade += 1;
            $trade->advertise->total_min += $processingMin;
            $trade->advertise->save();

            $trade->owner->total_min += $processingMin;
            $trade->owner->completed_trade += 1;
            $trade->owner->save();

            $trade->sender->total_min += $processingMin;
            $trade->sender->completed_trade += 1;
            $trade->sender->save();

            $wallet->balance -= $trade->receive_amount + $adminCharge;
            $wallet->save();

            $receiverWallet->balance += $trade->receive_amount;
            $receiverWallet->save();

            $newMaxLimit = $trade->advertise->maximum_limit - $trade->pay_amount;
            $trade->advertise->maximum_limit = $newMaxLimit;
            $trade->advertise->save();

            if ($newMaxLimit <= $trade->advertise->minimum_limit) {
                $trade->advertise->minimum_limit = $newMaxLimit;
                $trade->advertise->save();
            }

            $user = auth()->user();
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' has marked this as completed.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            $code = optional($trade->receiverCurrency)->code;

            $owner = $trade->type == 'sell' ? $trade->owner : $trade->sender;
            $sender = $trade->type == 'sell' ? $trade->sender : $trade->owner;

            $ownerTrx = str_random(12);
            BasicService::makeTransaction($owner, BasicService::getAmount($trade->receive_amount), BasicService::getAmount($adminCharge), '-', $ownerTrx, 'Subtracted from ' . optional($wallet->crypto)->code . ' wallet for a sell trade', $code);

            $requesterTrx = str_random(12);
            BasicService::makeTransaction($sender, BasicService::getAmount($trade->receive_amount), BasicService::getAmount($adminCharge), '+', $requesterTrx, 'Added With Your ' . optional($wallet->crypto)->code . ' wallet for a buy trade', $code);
            DB::commit();

            $msg = [
                'username' => optional(auth()->user())->username,
                'amount' => BasicService::getAmount($trade->receive_amount, 8),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ];

            $action = [
                "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $firebaseAction = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
            $this->userPushNotification($sender, 'TRADE_COMPLETED', $msg, $action);
            $this->userFirebasePushNotification($sender, 'TRADE_COMPLETED', $msg, $firebaseAction);

            $this->sendMailSms($sender, 'TRADE_COMPLETED', [
                'username' => optional(auth()->user())->username,
                'amount' => BasicService::getAmount($trade->receive_amount, 8),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
            ]);

            return response()->json(['success' => 'Trade has been completed']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong'], 500);
        }
    }

    public function disputeTrade(Request $request)
    {
        try {
            $userid = auth()->id();
            $trade = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->findOrFail($request->tradeId);

            $configure = Configure::select(['trade_extra_time'])->firstOrFail();

            if (Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window + $configure->trade_extra_time) > Carbon::now()) {
                return response()->json(['error' => 'Please wait until the window time is finished'], 400);
            }

            $trade->status = 5;
            $trade->dispute_by = auth()->id();
            $trade->dispute_at = Carbon::now();
            $trade->save();

            $user = auth()->user();
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' disputed this trade.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            $demoUser = $trade->owner_id == auth()->id() ? $trade->sender : $trade->owner;

            $msg = [
                'username' => optional(auth()->user())->username,
                'amount' => BasicService::getAmount($trade->receive_amount, 8),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ];

            $action = [
                "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $firebaseAction = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
            $this->userPushNotification($demoUser, 'TRADE_DISPUTE', $msg, $action);
            $this->userFirebasePushNotification($demoUser, 'TRADE_DISPUTE', $msg, $firebaseAction);

            $actionAdmin = [
                "link" => route('admin.trade.Details', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $firebaseActionAdmin = route('admin.trade.Details', $trade->hash_slug);
            $this->adminPushNotification('TRADE_DISPUTE', $msg, $actionAdmin);
            $this->adminFirebasePushNotification('TRADE_DISPUTE', $msg, $firebaseActionAdmin);

            $this->sendMailSms($demoUser, 'TRADE_DISPUTE', [
                'username' => optional(auth()->user())->username,
                'amount' => BasicService::getAmount($trade->receive_amount, 8),
                'tradeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
            ]);

            return response()->json(['success' => 'Trade has been disputed']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something Went Wrong'], 500);
        }
    }

}
