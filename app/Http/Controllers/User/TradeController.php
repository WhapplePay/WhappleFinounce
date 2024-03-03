<?php

namespace App\Http\Controllers\User;

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

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function index($stage = null, Request $request)
    {
        $adId = null;
        if ($request->has('adId')) {
            $adId = $request->adId;
        }
        $search = $request->all();
        $userid = $this->user->id;
        $data['trades'] = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->when($stage == 'running', function ($query) use ($stage) {
                $query->whereIn("status", [0, 1, 5, 6, 7]);
            })
            ->when($stage == 'complete', function ($query) use ($stage) {
                $query->whereIn("status", [3, 4, 8]);
            })
            ->when($adId != null, function ($query) use ($adId) {
                $query->where("advertise_id", $adId);
            })
            ->when(isset($search['tradeNumber']), function ($query) use ($search) {
                $query->where("trade_number", 'LIKE', '%' . $search['tradeNumber'] . '%');
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                $query->where("status", $search['status']);
            })
            ->when(isset($search['username']), function ($query) use ($search) {
                $query->whereHas('sender', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['username'] . '%');
                })
                    ->orWhereHas('owner', function ($qq) use ($search) {
                        $qq->where('username', 'LIKE', '%' . $search['username'] . '%');
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));
        return view($this->theme . 'user.trade.list', $data);
    }

    public function cancelTrade(Request $request)
    {
        try {
            $userid = $this->user->id;
            $trade = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->findOrFail($request->tradeId);

            if ($trade->status != 0) {
                return back()->with('error', 'You cannot cancel this trade');
            }
            $trade->status = 3;
            $trade->cancel_by = $this->user->id;
            $trade->save();

            $user = $this->user;
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' canceled this trade.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            if ($this->user->id == $trade->owner_id) {
                $demoUser = $trade->sender;
            } else {
                $demoUser = $trade->owner;
            }
            $msg = [
                'username' => $user->username,
                'amount' => getAmount($trade->receive_amount),
                'tardeNumber' => $trade->trade_number,
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
                'amount' => getAmount($trade->receive_amount),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ]);

            return back()->with('success', 'Trade has been canceled');
        } catch (\Exception $e) {
            return back()->with('error', 'Something Went Wrong');
        }
    }

    public function paidTrade(Request $request)
    {
        try {
            $userid = $this->user->id;
            $trade = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->findOrFail($request->tradeId);

            $trade->status = 1;
            $trade->paid_at = Carbon::now();
            $trade->time_remaining = Carbon::now()->addMinutes($trade->payment_window);
            $trade->save();

            $user = $this->user;
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' has marked this trade as paid. Check if you have received the payment.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            if ($trade->type == 'sell') {
                $mailNotifyUser = $trade->owner;
            } else {
                $mailNotifyUser = $trade->sender;
            }

            $msg = [
                'username' => $user->username,
                'amount' => getAmount($trade->pay_amount),
                'tardeNumber' => $trade->trade_number,
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
                'amount' => getAmount($trade->pay_amount),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->currency)->code ?? 'Null',
            ]);

            return back()->with('success', 'Trade has been Paid');
        } catch (\Exception $e) {
            return back()->with('error', 'Something Went Wrong');
        }
    }

    public function releaseTrade(Request $request)
    {
        try {
            $userid = $this->user->id;
            $trade = Trade::with(['advertise'])->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->whereStatus(1)->findOrFail($request->tradeId);

            if ($trade->type == 'sell') {
                $wallet = Wallet::with('crypto')->where('user_id', $trade->owner_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();
                $receiverWallet = Wallet::with('crypto')->where('user_id', $trade->sender_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();
            } else {
                $receiverWallet = Wallet::with('crypto')->where('user_id', $trade->owner_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();
                $wallet = Wallet::with('crypto')->where('user_id', $trade->sender_id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();
            }

            if (!$wallet) {
                return back()->with('error', 'You can not proceed this action');
            }

            $tadeCharge = config('basic.tradeCharge');
            $adminCharge = $tadeCharge * $trade->receive_amount / 100;

            if ($wallet->balance < $trade->receive_amount + $adminCharge) {
                return back()->with('error', 'Insufficient Balance');
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
            $trade->owner->save();

            $trade->sender->total_min += $processingMin;
            $trade->sender->save();

            $trade->owner->completed_trade += 1;
            $trade->owner->save();

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

            $user = $this->user;
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' has marked this as completed.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            $code = optional($trade->receiverCurrency)->code;

            if ($trade->type == 'sell') {
                $owner = $trade->owner;
                $sender = $trade->sender;
            } else {
                $sender = $trade->owner;
                $owner = $trade->sender;
            }
            $ownerTrx = strRandom(12);
            BasicService::makeTransaction($owner, getAmount($trade->receive_amount), getAmount($adminCharge), '-', $ownerTrx, 'Subtracted from ' . optional($wallet->crypto)->code . ' wallet for a sell trade', $code);

            $requesterTrx = strRandom(12);
            BasicService::makeTransaction($sender, getAmount($trade->receive_amount), getAmount($adminCharge), '+', $requesterTrx, 'Added With Your ' . optional($wallet->crypto)->code . ' wallet for a buy trade', $code);
            DB::commit();
            $msg = [
                'username' => optional($this->user)->username ?? null,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
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
                'username' => optional($this->user)->username,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
            ]);

            return back()->with('success', 'Trade has been completed');
        } catch (\Exception $e) {
            return back()->with('error', 'Something Went Wrong');
        }
    }

    public function disputeTrade(Request $request)
    {
        try {
            $userid = $this->user->id;
            $trade = Trade::where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })->findOrFail($request->tradeId);

            $configure = Configure::select(['trade_extra_time'])->firstOrFail();

            if (\Carbon\Carbon::parse($trade->time_remaining)->addMinutes($trade->payment_window + $configure->trade_extra_time) > \Carbon\Carbon::now()) {
                return back()->with('error', 'Please wait until window time not finished');
            }

            $trade->status = 5;
            $trade->dispute_by = $this->user->id;
            $trade->dispute_at = Carbon::now();
            $trade->save();

            $user = $this->user;
            $tradeChat = new TradeChat();
            $tradeChat->description = $user->username . ' disputed this trade.';
            $tradeChat->trades_id = $trade->id;
            $user->chats()->save($tradeChat);

            if ($this->user->id == $trade->owner_id) {
                $demoUser = $trade->sender;
            } else {
                $demoUser = $trade->owner;
            }
            $msg = [
                'username' => optional($this->user)->username ?? null,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
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
                'username' => optional($this->user)->username ?? null,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
            ]);

            return back()->with('success', 'Trade has been disputed');
        } catch (\Exception $e) {
            return back()->with('error', 'Something Went Wrong');
        }
    }
}
