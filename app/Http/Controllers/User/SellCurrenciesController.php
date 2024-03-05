<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Advertisment;
use App\Models\Currency;
use App\Models\Gateway;
use App\Models\Trade;
use App\Models\TradeChat;
use App\Models\User;
use App\Models\UserPaymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

class SellCurrenciesController extends Controller
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
        $data['sellLists'] = Advertisment::with(['fiatCurrency', 'cryptoCurrency', 'user'])
            ->where('type', 'buy')
            ->when($currencyId != null, function ($query) use ($currencyId) {
                return $query->where("crypto_id", $currencyId);
            })
            ->where('status', 1)->where('user_id', '!=', $this->user->id)
            ->when(isset($search['buyer']), function ($query) use ($search) {
                $query->whereHas('user', function ($qq) use ($search) {
                    $qq->where('username', 'LIKE', '%' . $search['buyer'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $search['buyer'] . '%');
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
        $data['sellCurrencyLists'] = Advertisment::where('type', 'buy')->where('status', 1)->where('user_id', '!=', auth()->user()->id)->with('cryptoCurrency')->groupBy('crypto_id')->get();
        return view($this->theme . 'user.sell.list', $data);
    }

    public function sellTradeRqst($adId)
    {
        $data['advertisment'] = Advertisment::with(['cryptoCurrency', 'fiatCurrency', 'user', 'paymentWindow', 'feedbacks', 'feedbacks.reviewer', 'userfeedbacks'])
            ->withCount(['like', 'dislike'])
            ->where('type', 'buy')->where('status', 1)->findOrFail($adId);

        $trade = Trade::where('advertise_id', $data['advertisment']->id)->where('sender_id', auth()->id())->exists();
        if ($trade == true) {
            $data['feedbackable'] = 'true';
        } else {
            $data['feedbackable'] = 'false';
        }
        return view($this->theme . 'user.sell.trade', $data);
    }

    public function sendTradeRqst(Request $request)
    {
        $purifiedData = Purify::clean($request->except('_token', '_method'));
        $rules = [
            'pay' => 'required|max:40',
            'message' => 'required',
            'method_id' => 'required',
        ];
        $message = [
            'pay.required' => 'Pay field is required',
            'message.required' => 'Message field is required',
            'method_id.required' => 'Payment Method field is required',
        ];
    
        $validate = Validator::make($purifiedData, $rules, $message);
    
        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }
        $existingTrade = Trade::where('advertise_id', $request->advertiseId)
        ->where('status', '!=', 4) 
        ->where('status', '!=', 3)
        ->where('status', '!=', 6)
        ->where('status', '!=', 7)
        ->where('status', '!=', 8) 
        ->first();

    if ($existingTrade) {
        session()->flash('error', 'Another user has already placed a trade on this advertisement. Please choose another one.');
        return redirect()->back();
    }
        $method = Gateway::where('status', 1)->findOrFail($request->method_id);
    
        $information = [];
        $information[] = 'Payment Method : '.$method->name;
        $information = implode('  ', $information);
    
        $advertise = Advertisment::with(['paymentWindow'])->findOrFail($request->advertiseId);
    
        $rate = $advertise->rate;
    
        // Check if $rate is zero to avoid DivisionByZeroError
        if ($rate == 0) {
            session()->flash('error', 'Rate cannot be zero. Please check the advertisement rate.');
            return redirect()->back();
        }
    
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
            return back()->with('error', 'amount can not be greater than trade limit');
        }
    
        $trade = new Trade();
    
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
        $trade->payment_method_id = $method->id;
        $trade->payment_window = optional($advertise->paymentWindow)->name ?? 0;
        $trade->hash_slug = Str::uuid();
        $trade->payment_details = $advertise->payment_details;
        $trade->terms_of_trade = $advertise->terms_of_trade;
    
        $trade->save();
    
        $user = Auth::user();
        $tradeChat = new TradeChat();
        $tradeChat->description = $information."\n".$request->message;
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
    
        session()->forget('payment-method');
        session()->forget('user_payment_infoId');
    
        session()->flash('success', 'Trade request sent');
        return redirect()->route('user.buyCurrencies.tradeDetails', $trade->hash_slug);
    }
    
    public function gatewayInfo($adsId)
    {
        $advertise = Advertisment::findOrFail($adsId);

        $data['infos'] = UserPaymentInfo::with(['gateway'])->where('user_id', auth()->id())->whereIn('gateway_id', $advertise->gateways->pluck('id')->toArray())->orderBy('gateway_id', 'ASC')->get();

        $data['advertise'] = $advertise;
        return view($this->theme . 'user.paymentCredential.index', $data);

    }

    public function gatewayInfoSave(Request $request)
    {
        $purifiedData = Purify::clean($request->all());
        if (empty($purifiedData['gatewayId'])) {
            return back()->with('error', 'Something went wrong')->withInput();
        }
        $gateway = Gateway::findOrFail($purifiedData['gatewayId']);

        $rules = [];
        $inputField = [];
        if ($gateway->input_form != null) {
            foreach ($gateway->input_form as $key => $cus) {

                $rules[$key] = [$cus->validation];
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }


        $userPaymentInfo = new UserPaymentInfo();
        $userPaymentInfo->user_id = auth()->id();
        $userPaymentInfo->gateway_id = $purifiedData['gatewayId'];

        $collection = collect($purifiedData);
        $reqField = [];
        if ($gateway->input_form != null) {
            foreach ($collection as $k => $v) {
                foreach ($gateway->input_form as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {

                        $arr = array();
                        $arr['name'] = $inVal->name;
                        $arr['label'] = $inVal->label;
                        $arr['type'] = $inVal->type;
                        $arr['validation'] = $inVal->validation;
                        $arr['fieldValue'] = $v;
                        $reqField[$inKey] = $arr;
                    }
                }
            }
            $userPaymentInfo->information = $reqField;
        } else {
            $userPaymentInfo->information = null;
        }
        $userPaymentInfo->save();
        session()->flash('success', 'Added Successfully');
        return back();
    }

    public function gatewayInfoUpdate(Request $request, $id)
    {
        $purifiedData = Purify::clean($request->all());
        $paymentInfo = UserPaymentInfo::with(['gateway'])->where('user_id', auth()->id())->findOrFail($id);
        if ($request->submitBtn == 'update') {
            $rules = [];
            $inputField = [];
            if ($paymentInfo->gateway->input_form != null) {
                foreach ($paymentInfo->gateway->input_form as $key => $cus) {

                    $rules[$key] = [$cus->validation];
                    if ($cus->type == 'text') {
                        array_push($rules[$key], 'max:191');
                    }
                    if ($cus->type == 'textarea') {
                        array_push($rules[$key], 'max:300');
                    }
                    $inputField[] = $key;
                }
            }

            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return back()->withErrors($validate)->withInput();
            }

            $collection = collect($purifiedData);
            $reqField = [];
            if ($paymentInfo->gateway->input_form != null) {
                foreach ($collection as $k => $v) {
                    foreach ($paymentInfo->gateway->input_form as $inKey => $inVal) {
                        if ($k != $inKey) {
                            continue;
                        } else {

                            $arr = array();
                            $arr['name'] = $inVal->name;
                            $arr['label'] = $inVal->label;
                            $arr['type'] = $inVal->type;
                            $arr['validation'] = $inVal->validation;
                            $arr['fieldValue'] = $v;
                            $reqField[$inKey] = $arr;
                        }
                    }
                }
                $paymentInfo->information = $reqField;
            } else {
                $paymentInfo->information = null;
            }
            $paymentInfo->save();
            session()->flash('success', 'Updated Successfully');
            return back();

        } elseif ($request->submitBtn == 'delete') {
            $paymentInfo->delete();
            session()->flash('success', 'Deleted Successfully');
            return back();
        }
    }

    public function gatewaySelect(Request $request)
    {
        session()->put('payment-method', $request->gateway_id);
        session()->put('user_payment_infoId', $request->id);
        return response()->json([
            'url' => route('user.sellCurrencies.tradeRqst', $request->adds_id),
        ]);
    }

    public function fetchPaymentInfo(Request $request)
    {
        $paymentInfo = UserPaymentInfo::where('user_id', auth()->id())->where('gateway_id', $request->gatewayId)->get()->map(function ($item) {

            $data['id'] = $item->id;

            $information = [];
            foreach (collect($item->information)->take(2) as $inKey => $inData) {
                array_push($information, Str::limit($inData->fieldValue, 10));
            }
            $data['information'] = implode(' | ', $information);
            return $data;
        });
        return response()->json([
            'info' => $paymentInfo,
        ]);
    }

}
