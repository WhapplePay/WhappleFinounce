<?php

namespace App\Http\Controllers\User;

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

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function index(Request $request, $currencyCode = null, $currencyId = null)
    {

        $search = $request->all();
        $data['currencyCode'] = $currencyCode;
        $data['buyLists'] = Advertisment::with(['fiatCurrency', 'cryptoCurrency', 'user'])
            ->where('type', 'sell')
            ->when($currencyId != null, function ($query) use ($currencyId) {
                return $query->where("crypto_id", $currencyId);
            })
            ->where('status', 1)->where('user_id', '!=', $this->user->id)
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
            })->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));

        $data['cryptos'] = Currency::where('status', 1)->where('flag', 1)->orderBy('name')->get();
        $data['fiats'] = Currency::where('status', 1)->where('flag', 0)->orderBy('name')->get();
        $data['gateways'] = Gateway::where('status', 1)->orderBy('name')->get();
        $data['locations'] = User::select('address')->where('status', 1)->orderBy('address')->groupBy('address')->get();
        $data['buyCurrencyLists'] = Advertisment::where('type', 'sell')->where('status', 1)->where('user_id', '!=', auth()->user()->id)->with('cryptoCurrency')->groupBy('crypto_id')->get();
        return view($this->theme . 'user.buy.list', $data);
    }

    public function buyTradeRqst($adId)
    {
        $data['advertisment'] = Advertisment::with(['cryptoCurrency', 'fiatCurrency', 'user', 'paymentWindow', 'feedbacks', 'feedbacks.reviewer', 'userfeedbacks'])
            ->withCount(['like', 'dislike'])
            ->where('type', 'sell')->where('status', 1)->findOrFail($adId);

        $trade = Trade::where('advertise_id', $data['advertisment']->id)->where('sender_id', auth()->id())->exists();
        if ($trade == true) {
            $data['feedbackable'] = 'true';
        } else {
            $data['feedbackable'] = 'false';
        }
        return view($this->theme . 'user.buy.trade', $data);
    }

    public function sendTradeRqst(Request $request)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'pay' => 'required|max:40',
            'message' => 'required',
        ];
        $message = [
            'pay.required' => 'Pay field is required',
            'message.required' => 'Message field is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }
        $advertise = Advertisment::with(['paymentWindow'])->findOrFail($request->advertiseId);

        $rate = $advertise->rate;
        $receiveAmount = 1 * $request->pay / $rate;
        $charge = $receiveAmount * config('basic.tradeCharge') / 100;

        $check = BasicService::checkIsTradeable($advertise, $receiveAmount, $charge);
        if ($check['status'] != 'success') {
            session()->flash('error', $check['message']);
            return redirect()->back();
        }

        if ($advertise->minimum_limit > $request->pay) {
            return back()->with('error', 'amount can not be less than trade limit');
        }
        if ($advertise->maximum_limit < $request->pay) {
            return back()->with('error', 'amount can not be grater than trade limit');
        }

        $configure = Configure::select('trade_extra_time')->firstOrFail();
        $trade = new Trade();

        $time = explode(" ", optional($advertise->paymentWindow)->name);

        $trade->advertise_id = $advertise->id;
        $trade->sender_id = $this->user->id;
        $trade->owner_id = $advertise->user_id;
        $trade->trade_number = strRandom(12);
        $trade->type = $advertise->type;
        $trade->currency_id = $advertise->fiat_id;
        $trade->receiver_currency_id = $advertise->crypto_id;
        $trade->payment_method = $advertise->gateway_id;
        $trade->rate = $advertise->rate;
        $trade->pay_amount = $request->pay;
        $trade->receive_amount = $receiveAmount;
        $trade->status = 0;
        $trade->payment_window = $time[0] ?? 0;
        $trade->hash_slug = Str::uuid();
        $trade->payment_details = $advertise->payment_details;
        $trade->terms_of_trade = $advertise->terms_of_trade;

        $trade->save();
        $trade->time_remaining = Carbon::now()->addMinutes($trade->payment_window + $configure->trade_extra_time);
        $trade->save();

        $user = Auth::user();
        $tradeChat = new TradeChat();
        $tradeChat->description = $request->message;
        $tradeChat->trades_id = $trade->id;
        $user->chats()->save($tradeChat);

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
        $this->userPushNotification($trade->owner, 'SEND_TRADE', $msg, $action);
        $this->userFirebasePushNotification($trade->owner, 'SEND_TRADE', $msg, $firebaseAction);

        $this->sendMailSms($trade->owner, 'SEND_TRADE', [
            'username' => $user->username,
            'amount' => getAmount($trade->pay_amount),
            'tardeNumber' => $trade->trade_number,
            'currency' => optional($trade->currency)->code ?? 'Null',
        ]);

        session()->flash('success', 'Trade request send');
        return redirect()->route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
    }

    public function tradeDetails($hash_slug)
    {
        $auth = Auth::user();
        $data['trade'] = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'disputeBy'])
            ->where('hash_slug', $hash_slug)
            ->whereHas('sender')
            ->whereHas('owner')
            ->where(function ($query) use ($auth) {
                $query->where('sender_id', $auth->id)
                    ->orWhere('owner_id', $auth->id);
            })
            ->firstOrFail();

        if (Auth::check() && $data['trade']->owner_id == Auth::id()) {
            $data['isAuthor'] = true;
        } else {
            $data['isAuthor'] = false;
        }

        $data['persons'] = TradeChat::where([
            'trades_id' => $data['trade']->id,
        ])
            ->with('chatable')
            ->get()->pluck('chatable')->unique('chatable');

        $data['configure'] = Configure::select(['trade_extra_time'])->firstOrFail();
        return view($this->theme . 'user.trade.details', $data);
    }

}
