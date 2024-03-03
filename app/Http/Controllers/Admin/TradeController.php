<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Admin;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\BasicService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class TradeController extends Controller
{
    use Upload, Notify;

    public function tradeList($status = null, Request $request)
    {
        $adId = null;
        if ($request->has('adId')) {
            $adId = $request->adId;
        }
        $search = $request->all();
        $data['trades'] = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->when($status == 'running', function ($query) use ($status) {
                $query->whereIn("status", [0, 1, 6, 7]);
            })
            ->when($status == 'reported', function ($query) use ($status) {
                $query->where("status", 5);
            })
            ->when($status == 'complete', function ($query) use ($status) {
                $query->whereIn("status", [3, 4, 8]);
            })
            ->when($adId != null, function ($query) use ($adId) {
                $query->where("advertise_id", $adId);
            })
            ->when(isset($search['tradeNumber']), function ($query) use ($search) {
                $query->where("trade_number", 'LIKE', '%' . $search['tradeNumber'] . '%');
            })
            ->when(isset($search['owner']), function ($query) use ($search) {
                $query->whereHas('owner', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['owner'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $search['owner'] . '%');
                });
            })
            ->when(isset($search['sender']), function ($query) use ($search) {
                $query->whereHas('sender', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['sender'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $search['sender'] . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));

        return view('admin.trades.list', $data);
    }

    public function tradeDetails($hashSlug)
    {
        $auth = Auth::user();
        $trade = Trade::with(['sender', 'currency', 'cancelBy', 'owner', 'receiverCurrency', 'disputeBy'])
            ->where('hash_slug', $hashSlug)
            ->firstOrFail();

        $initiate = array();
        $persons = TradeChat::where([
            'trades_id' => $trade->id,
        ])
            ->where('chatable_type', '!=', Admin::class)
            ->with('chatable')
            ->get()->unique('chatable');
        foreach ($persons as $person) {
            $initiate[] = $person->chatable;
        }
        $data['persons'] = $initiate;
        $data['trade'] = $trade;

        return view('admin.trades.details', $data);
    }

    public function returnTrade($hashSlug)
    {
        $trade = Trade::where('status', 5)->where('hash_slug', $hashSlug)->firstOrFail();
        if ($trade->type == 'sell') {
            $seller = $trade->owner;
            $buyer = $trade->sender;
            $trade->status = 6;
        } else {
            $seller = $trade->sender;
            $buyer = $trade->owner;
            $trade->status = 7;
        }
        $sellerWallet = Wallet::where('user_id', $seller->id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();
        $buyerWallet = Wallet::where('user_id', $buyer->id)->where('crypto_currency_id', $trade->receiver_currency_id)->first();

        if (!$sellerWallet || !$buyerWallet) {
            return back()->with('error', 'You can not proceed this action');
        }

        $sellerWallet->balance += ($trade->receive_amount + $trade->admin_charge);
        $sellerWallet->save();

        $buyerWallet->balance -= $trade->receive_amount;
        $buyerWallet->save();

        $user = Auth::guard('admin')->user();
        $tradeChat = new TradeChat();
        $tradeChat->description = 'system has return to the seller.';
        $tradeChat->trades_id = $trade->id;
        $user->chats()->save($tradeChat);

        $sellerTrx = strRandom(12);
        BasicService::makeTransaction($seller, getAmount($trade->receive_amount), '0', '+', $sellerTrx, 'Crypto amount returned by system to seller', optional($trade->receiverCurrency)->code);

        $buyerTrx = strRandom(12);
        BasicService::makeTransaction($buyer, getAmount($trade->receive_amount), '0', '-', $buyerTrx, 'Crypto amount returned by system to seller', optional($trade->receiverCurrency)->code);

        $trade->save();

        $msg = [
            'username' => $seller->username ?? null,
            'amount' => getAmount($trade->receive_amount, 8),
            'tardeNumber' => $trade->trade_number,
            'currency' => optional($trade->receiverCurrency)->code ?? null,
        ];
        $actionSeller = [
            "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseActionSeller = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
        $this->userPushNotification($seller, 'TRADE_SETTLED', $msg, $actionSeller);
        $this->userFirebasePushNotification($seller, 'TRADE_SETTLED', $msg, $firebaseActionSeller);

        $this->sendMailSms($seller, 'TRADE_SETTLED', [
            'username' => $seller->username,
            'amount' => getAmount($trade->receive_amount, 8),
            'tardeNumber' => $trade->trade_number,
            'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
        ]);

        $msg = [
            'username' => $seller->username ?? null,
            'amount' => getAmount($trade->receive_amount, 8),
            'tardeNumber' => $trade->trade_number,
            'currency' => optional($trade->receiverCurrency)->code ?? null,
        ];
        $actionBuyer = [
            "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseActionBuyer = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
        $this->userPushNotification($buyer, 'TRADE_SETTLED', $msg, $actionBuyer);
        $this->userFirebasePushNotification($buyer, 'TRADE_SETTLED', $msg, $firebaseActionBuyer);

        $this->sendMailSms($buyer, 'TRADE_SETTLED', [
            'username' => $seller->username,
            'amount' => getAmount($trade->receive_amount, 8),
            'tardeNumber' => $trade->trade_number,
            'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
        ]);

        return back()->with('success', 'Favour goes to seller');
    }

    public function releaseTrade($hashSlug)
    {
        try {
            $trade = Trade::whereStatus(5)->where('hash_slug', $hashSlug)->firstOrFail();

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

            $trade->status = 8;
            $trade->complete_at = Carbon::now();
            $trade->save();

            $processingMin = Carbon::now()->diffInMinutes(Carbon::parse($trade->created_at));

            $trade->advertise->completed_trade += 1;
            $trade->advertise->total_min += $processingMin;

            $trade->advertise->save();

            if ($trade->type == 'sell') {
                $trade->owner->total_min += $processingMin;
                $trade->owner->save();
            } else {
                $trade->sender->total_min += $processingMin;
                $trade->sender->save();
            }

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

            $user = Auth::guard('admin')->user();
            $tradeChat = new TradeChat();
            $tradeChat->description = 'system has marked this as completed.';
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
            BasicService::makeTransaction($owner, getAmount($trade->receive_amount), getAmount($adminCharge), '-', $ownerTrx, 'Subtracted from ' . optional($wallet->crypto)->code . 'wallet for a sell trade', $code);

            $requesterTrx = strRandom(12);
            BasicService::makeTransaction($sender, getAmount($trade->receive_amount), getAmount($adminCharge), '+', $requesterTrx, 'Added With Your ' . optional($wallet->crypto)->code . 'wallet for a buy trade', $code);

            $msg = [
                'username' => $owner->username ?? null,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ];
            $action = [
                "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($sender, 'SYSTEM_TRADE_COMPLETED', $msg, $action);
            $this->userFirebasePushNotification($sender, 'SYSTEM_TRADE_COMPLETED', $msg);

            $this->sendMailSms($sender, 'TRADE_COMPLETED', [
                'username' => $owner->username,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
            ]);

            $msg = [
                'username' => $sender->username ?? null,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? null,
            ];
            $action = [
                "link" => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $firebaseAction = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
            $this->userPushNotification($owner, 'SYSTEM_TRADE_COMPLETED', $msg, $action);
            $this->userFirebasePushNotification($owner, 'SYSTEM_TRADE_COMPLETED', $msg, $firebaseAction);

            $this->sendMailSms($owner, 'SYSTEM_TRADE_COMPLETED', [
                'username' => $sender->username,
                'amount' => getAmount($trade->receive_amount, 8),
                'tardeNumber' => $trade->trade_number,
                'currency' => optional($trade->receiverCurrency)->code ?? 'Null',
            ]);

            return back()->with('success', 'Trade has been completed');
        } catch (\Exception $e) {
            return back()->with('error', 'Something Went Wrong');
        }
    }
}
