<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Advertisment;
use App\Models\Configure;
use App\Models\Currency;
use App\Models\Gateway;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

class BuyCurrenciesController extends Controller
{
    use Upload, Notify;

    public function index(Request $request, $currencyCode = null, $currencyId = null)
{
    $search = $request->all();

    $buyLists = Advertisment::with(['fiatCurrency', 'cryptoCurrency', 'user', 'paymentWindow'])
    ->where('type', 'sell')
    ->when($currencyId != null, function ($query) use ($currencyId) {
        return $query->where("crypto_id", $currencyId);
    })
    ->where('status', 1)->where('user_id', '!=', $request->user_id)
    ->when(isset($search['seller']), function ($query) use ($search) {
        $query->whereHas('user', function ($qq) use ($search) {
            $qq->where('username', 'LIKE', '%' . $search['seller'] . '%')
                ->orWhere('email', 'LIKE', '%' . $search['seller'] . '%');
        });
    })
    ->when(isset($search['crypto']), function ($query) use ($search) {
        return $query->where("crypto_id", $search['crypto']);
    })
    ->when(isset($search['fiat']), function ($query) use ($search) {
        return $query->where("fiat_id", $search['fiat']);
    })
    ->when(isset($search['gateway']), function ($query) use ($search) {
        return $query->whereJsonContains("gateway_id", $search['gateway']);
    })
    ->when(isset($search['location']), function ($query) use ($search) {
        $query->whereHas('user', function ($qq) use ($search) {
            $qq->where('address', 'LIKE', '%' . $search['location'] . '%');
        });
    })
    ->orderBy('id', 'desc')
    ->paginate(config('basic.paginate'));

    $cryptos = Currency::where('status', 1)->where('flag', 1)->orderBy('name')->get();
    $fiats = Currency::where('status', 1)->where('flag', 0)->orderBy('name')->get();
    $gateways = Gateway::where('status', 1)->orderBy('name')->get();
    $locations = User::select('address')->where('status', 1)->orderBy('address')->groupBy('address')->get();
    $buyCurrencyLists = Advertisment::where('type', 'sell')->where('status', 1)->where('user_id', '!=', $request->user_id)->with('cryptoCurrency')->groupBy('crypto_id')->get();

    return response()->json([
        'status' => 200,
        'buyLists' => $buyLists,
        'cryptos' => $cryptos,
        'fiats' => $fiats,
        'gateways' => $gateways,
        'locations' => $locations,
        'buyCurrencyLists' => $buyCurrencyLists,
    ]);
}

    public function buyTradeRqst(Request $request)
    {
        try {
            $data['advertisment'] = Advertisment::with(['cryptoCurrency', 'fiatCurrency', 'user', 'paymentWindow', 'feedbacks', 'feedbacks.reviewer', 'userfeedbacks'])
                ->withCount(['like', 'dislike'])
                ->where('type', 'sell')
                ->where('status', 1)
                ->findOrFail($request->adId);
    
            $trade = Trade::where('advertise_id', $data['advertisment']->id)
                ->where('sender_id', $request->user_id)
                ->exists();
    
            $data['feedbackable'] = $trade ? 'true' : 'false';
    
            return response()->json(['advertisment' => $data, 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status' => 404], 404);
        }
    }
    
    public function sendTradeRqst(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'pay' => 'required|max:40',
            'message' => 'required',
        ], [
            'pay.required' => 'Pay field is required',
            'message.required' => 'Message field is required',
        ]);
    
        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Clean and extract required data from the request
        $purifiedData = Purify::clean($request->all());
        $advertiseId = $purifiedData['advertiseId'];
        $payAmount = $purifiedData['pay'];
        $message = $purifiedData['message'];
    
        // Fetch the advertisement
        $advertisement = Advertisment::with(['paymentWindow'])->findOrFail($advertiseId);
    
        // Calculate receive amount and charge
        $rate = $advertisement->rate;
        $receiveAmount = 1 * $payAmount / $rate;
        $charge = $receiveAmount * config('basic.tradeCharge') / 100;
    
        // Check if the trade is possible
        $check = BasicService::checkIsTradeable($advertisement, $receiveAmount, $charge);
    
        // If trade is not possible, return an error response
        if ($check['status'] != 'success') {
            return response()->json(['error' => $check['message']], 422);
        }
    
        // Check if the amount is within the trade limits
        if ($advertisement->minimum_limit > $payAmount) {
            return response()->json(['error' => 'Amount cannot be less than the trade limit'], 422);
        }
    
        if ($advertisement->maximum_limit < $payAmount) {
            return response()->json(['error' => 'Amount cannot be greater than the trade limit'], 422);
        }
    
        // Fetch trade extra time from configuration
        $configure = Configure::select('trade_extra_time')->firstOrFail();
    
        // Create a new trade
        $trade = new Trade();
        $trade->fill([
            'advertise_id' => $advertisement->id,
            'sender_id' => $this->user->id,
            'owner_id' => $advertisement->user_id,
            'trade_number' => strRandom(12),
            'type' => $advertisement->type,
            'currency_id' => $advertisement->fiat_id,
            'receiver_currency_id' => $advertisement->crypto_id,
            'payment_method' => $advertisement->gateway_id,
            'rate' => $advertisement->rate,
            'pay_amount' => $payAmount,
            'receive_amount' => $receiveAmount,
            'status' => 0,
            'payment_window' => optional($advertisement->paymentWindow)->name ?? 0,
            'hash_slug' => Str::uuid(),
            'payment_details' => $advertisement->payment_details,
            'terms_of_trade' => $advertisement->terms_of_trade,
        ])->save();
    
        // Add trade time remaining
        $trade->time_remaining = Carbon::now()->addMinutes($trade->payment_window + $configure->trade_extra_time);
        $trade->save();
    
        // Create a new trade chat
        $user = $request->user_id;
        $tradeChat = new TradeChat();
        $tradeChat->fill([
            'description' => $message,
            'trades_id' => $trade->id,
        ])->save();
    
        $msg = [
            'username' => $user->username,
            'amount' => getAmount($trade->pay_amount),
            'tradeNumber' => $trade->trade_number,
            'currency' => optional($trade->currency)->code ?? 'Null',
        ];
    
        $action = [
            'link' => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
            'icon' => 'fa fa-money-bill-alt text-white'
        ];
    
        $firebaseAction = route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
        $this->userPushNotification($trade->owner, 'SEND_TRADE', $msg, $action);
        $this->userFirebasePushNotification($trade->owner, 'SEND_TRADE', $msg, $firebaseAction);
    
        $this->sendMailSms($trade->owner, 'SEND_TRADE', [
            'username' => $user->username,
            'amount' => getAmount($trade->pay_amount),
            'tradeNumber' => $trade->trade_number,
            'currency' => optional($trade->currency)->code ?? 'Null',
        ]);
    
        return response()->json(['success' => 'Trade request sent', 'status' => 200]);
    }
    

 

    public function tradeDetails(Request $request, $hash_slug)
    {
        $userId = $request->user_id;
        $auth = User::find($userId);
    
        if (!$auth) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $trade = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'disputeBy'])
            ->where('hash_slug', $hash_slug)
            ->where(function ($query) use ($auth) {
                $query->where('sender_id', $auth->id)
                    ->orWhere('owner_id', $auth->id);
            })
            ->firstOrFail();
    
        if ($auth && $trade->owner_id == $auth->id) {
            $isAuthor = true;
        } else {
            $isAuthor = false;
        }
    
        $persons = TradeChat::where([
            'trades_id' => $trade->id,
        ])
            ->with('chatable')
            ->get()
            ->pluck('chatable')
            ->unique('chatable');
    
        $configure = Configure::select(['trade_extra_time'])->firstOrFail();
    
        // Calculate remaining time in minutes and seconds
        $currentTime = now();
        $remainingTime = $trade->time_remaining;
        $timeDifference = strtotime($remainingTime) - strtotime($currentTime);
        $minutes = floor($timeDifference / 60);
        $seconds = $timeDifference % 60;
    
        $remainingTimeFormatted = sprintf('%02d:%02d', $minutes, $seconds);
    
        $data = [
            'trade' => $trade,
            'isAuthor' => $isAuthor,
            'persons' => $persons,
            'configure' => $configure,
            'remainingTime' => $remainingTimeFormatted,
        ];
    
        return response()->json(['data' => $data]);
    }
    
}
