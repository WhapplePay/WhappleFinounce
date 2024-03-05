<?php

namespace App\Http\Controllers\Api;

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

    public function sellTradeRqst($adId)
    {
        $advertisment = Advertisment::with(['cryptoCurrency', 'fiatCurrency', 'user', 'paymentWindow', 'feedbacks', 'feedbacks.reviewer', 'userfeedbacks'])
            ->withCount(['like', 'dislike'])
            ->where('type', 'buy')->where('status', 1)->findOrFail($adId);

        $tradeExists = Trade::where('advertise_id', $advertisment->id)->where('sender_id', Auth::id())->exists();
        $feedbackable = $tradeExists ? 'true' : 'false';

        $data = [
            'advertisment' => $advertisment,
            'feedbackable' => $feedbackable,
        ];

        return response()->json($data);
    }

    public function sendTradeRqst(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'pay' => 'required|max:40',
            'message' => 'required',
            'method_id' => 'required',
        ], [
            'pay.required' => 'Pay field is required',
            'message.required' => 'Message field is required',
            'method_id.required' => 'Payment Method field is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $purifiedData = Purify::clean($request->all());

        $advertise = Advertisment::with(['paymentWindow'])->findOrFail($purifiedData['advertiseId']);
        $rate = $advertise->rate;
        $receiveAmount = 1 * $purifiedData['pay'] / $rate;

        $charge = $receiveAmount * config('basic.tradeCharge') / 100;

        $check = BasicService::checkIsTradeable($advertise, $receiveAmount, $charge);

        if ($check['status'] !== 'success') {
            return response()->json(['error' => $check['message']], 422);
        }

        if ($advertise->minimum_limit > $purifiedData['pay']) {
            return response()->json(['error' => 'Amount cannot be less than the trade limit'], 422);
        }

        if ($advertise->maximum_limit < $purifiedData['pay']) {
            return response()->json(['error' => 'Amount cannot be greater than the trade limit'], 422);
        }

        // Fetch trade extra time from configuration
        $configure = Configure::select('trade_extra_time')->firstOrFail();

        // Create a new trade
        $trade = new Trade();
        $trade->advertise_id = $advertise->id;
        $trade->sender_id = Auth::id();
        $trade->owner_id = $advertise->user_id;
        $trade->trade_number = Str::random(12);
        $trade->type = $advertise->type;
        $trade->currency_id = $advertise->fiat_id;
        $trade->receiver_currency_id = $advertise->crypto_id;
        $trade->payment_method = $advertise->gateway_id;
        $trade->rate = $advertise->rate;
        $trade->pay_amount = $purifiedData['pay'];
        $trade->receive_amount = $receiveAmount;
        $trade->status = 0;
        $trade->payment_window = optional($advertise->paymentWindow)->name ?? 0;
        $trade->hash_slug = Str::uuid();
        $trade->payment_details = $advertise->payment_details;
        $trade->terms_of_trade = $advertise->terms_of_trade;
        $trade->save();

        // Add trade time remaining
        $trade->time_remaining = now()->addMinutes($trade->payment_window + $configure->trade_extra_time);
        $trade->save();

        // Create a new trade chat
        $user = Auth::user();
        $tradeChat = new TradeChat();
        $tradeChat->description = $purifiedData['message'];
        $tradeChat->trades_id = $trade->id;
        $user->chats()->save($tradeChat);

        // Notify the trade owner about the new trade request
        $msg = [
            'username' => $user->username,
            'amount' => getAmount($trade->pay_amount),
            'tradeNumber' => $trade->trade_number,
            'currency' => optional($trade->currency)->code ?? 'Null',
        ];

        $action = [
            'link' => route('user.buyCurrencies.tradeDetails', $trade->hash_slug),
            'icon' => 'fa fa-money-bill-alt text-white',
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

        $data = [
            'success' => 'Trade request sent',
        ];

        return response()->json($data);
    }

    public function gatewayInfo($adsId)
    {
        $advertise = Advertisment::findOrFail($adsId);

        $infos = UserPaymentInfo::with(['gateway'])
            ->where('user_id', auth()->id())
            ->whereIn('gateway_id', $advertise->gateways->pluck('id')->toArray())
            ->orderBy('gateway_id', 'ASC')
            ->get();

        $data = [
            'infos' => $infos,
            'advertise' => $advertise,
        ];

        return response()->json($data);
    }

    public function gatewayInfoSave(Request $request)
    {
        $purifiedData = Purify::clean($request->all());
        if (empty($purifiedData['gatewayId'])) {
            return response()->json(['error' => 'Something went wrong'], 400);
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
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
                        $arr = [
                            'name' => $inVal->name,
                            'label' => $inVal->label,
                            'type' => $inVal->type,
                            'validation' => $inVal->validation,
                            'fieldValue' => $v,
                        ];
                        $reqField[$inKey] = $arr;
                    }
                }
            }
            $userPaymentInfo->information = $reqField;
        } else {
            $userPaymentInfo->information = null;
        }

        $userPaymentInfo->save();

        return response()->json(['success' => 'Added Successfully']);
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

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $collection = collect($purifiedData);
            $reqField = [];

            if ($paymentInfo->gateway->input_form != null) {
                foreach ($collection as $k => $v) {
                    foreach ($paymentInfo->gateway->input_form as $inKey => $inVal) {
                        if ($k != $inKey) {
                            continue;
                        } else {
                            $arr = [
                                'name' => $inVal->name,
                                'label' => $inVal->label,
                                'type' => $inVal->type,
                                'validation' => $inVal->validation,
                                'fieldValue' => $v,
                            ];
                            $reqField[$inKey] = $arr;
                        }
                    }
                }
                $paymentInfo->information = $reqField;
            } else {
                $paymentInfo->information = null;
            }

            $paymentInfo->save();

            return response()->json(['success' => 'Updated Successfully']);
        } elseif ($request->submitBtn == 'delete') {
            $paymentInfo->delete();
            return response()->json(['success' => 'Deleted Successfully']);
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
        $paymentInfo = UserPaymentInfo::where('user_id', $request->user_id)
            ->where('gateway_id', $request->gatewayId)
            ->get()
            ->map(function ($item) {
                $data = [
                    'id' => $item->id,
                ];

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
