<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisment;
use App\Models\Currency;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Request;

class AdvertismentController extends Controller
{

    public function getAllAdvertisements(Request $request)
    {
        $authUserId = $request->user_id;
        $advertisements = Advertisment::where('user_id', '!=', $authUserId)->get();
    
        $buyAdvertisements = $advertisements->where('type', 'buy');
        $sellAdvertisements = $advertisements->where('type', 'sell');
    
        return response()->json([
            'buy_advertisements' => $buyAdvertisements,
            'sell_advertisements' => $sellAdvertisements,
        ]);
    }


public function getUserAdvertisements(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        // Add other validation rules as needed
    ]);

    $userId = $request->input('user_id');

    $userAdvertisements = Advertisment::where('user_id', $userId)->get();

    return response()->json(['user_advertisements' => $userAdvertisements]);
}



    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            // Add other validation rules as needed
        ]);

        $userId = $request->input('user_id');
        $gatewayId = $request->input('gateway_id');

        $trade = Trade::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('owner_id', $userId);
        })
            ->whereIn('status', [0, 1, 5])->count();

        $user = User::findOrFail($userId);

        if (!$user) {
            return response()->json(['error' => 'Invalid user_id'], 403);
        }

        if ($user->trade_limit == null) {
            if ($trade >= config('basic.incompleteLimit')) {
                return response()->json(['error' => 'Please complete running trades'], 400);
            }
        } else {
            if ($trade >= $user->trade_limit) {
                return response()->json(['error' => 'Please complete running trades'], 400);
            }
        }

        $crypto = Currency::findOrFail($request->crypto_id);
        $fiat = Currency::findOrFail($request->fiat_id);
        $fiatRate = $fiat->rate;
        $cryptoRate = $crypto->rate;
        $priceType = $request->price_type;

        $totalPrice = $fiatRate * $cryptoRate;
        $tradePrice = $totalPrice;
        if ($priceType == 'margin') {
            $tradePrice = ($totalPrice * $request->price / 100) + $totalPrice;
        } else {
            $tradePrice = $request->price;
        }

        $advertisement = new Advertisment();
        $advertisement->user_id = $userId; // Use the user_id from the request
        $advertisement->type = $request->type;
        $advertisement->crypto_id = $request->crypto_id;
        $advertisement->fiat_id = $request->fiat_id;
        $advertisement->gateway_id = [$gatewayId];
        $advertisement->crypto_rate = $cryptoRate;
        $advertisement->fiat_rate = $fiatRate;
        $advertisement->price_type = $request->price_type;
        $advertisement->price = $request->price;
        $advertisement->rate = $tradePrice;
        $advertisement->payment_window_id = $request->payment_window_id;
        $advertisement->minimum_limit = $request->minimum_limit;
        $advertisement->maximum_limit = $request->maximum_limit;
        $advertisement->payment_details = $request->payment_details;
        $advertisement->terms_of_trade = $request->terms_of_trade;

        $advertisement->save();

        return response()->json(['message' => 'Advertisement added successfully'], 201);
    }

    public function edit($id)
{
    $data['advertise'] = Advertisment::with(['fiatCurrency', 'gateways:id,name'])->findOrFail($id);
    $data['cryptos'] = Currency::where('flag', 1)->whereStatus(1)->orderBy('name', 'asc')->get();
    $data['fiats'] = Currency::where('flag', 0)->whereStatus(1)->orderBy('name', 'asc')->get();
    $data['gateways'] = Gateway::whereStatus(1)->orderBy('name', 'asc')->get();
    $data['paymentWindows'] = PaymentWindow::groupBy('name')->get();
    return response()->json($data);
}


public function update(AdvertismentRequest $request, $id)
{
    $advertisement = Advertisment::findOrFail($id);
    $advertisement->type = $request->type;
    $advertisement->crypto_id = $request->crypto_id;
    $advertisement->fiat_id = $request->fiat_id;
    $advertisement->gateway_id = $request->gateway_id;
    $advertisement->price_type = $request->price_type;
    $advertisement->price = $request->price;
    $advertisement->payment_window_id = $request->payment_window_id;
    $advertisement->minimum_limit = $request->minimum_limit;
    $advertisement->maximum_limit = $request->maximum_limit;
    $advertisement->payment_details = $request->payment_details;
    $advertisement->terms_of_trade = $request->terms_of_trade;

    $advertisement->save();
    return response()->json(['message' => 'Updated Successfully']);
}


public function enable($id)
{
    $advertisement = Advertisment::findOrFail($id);
    $advertisement->status = 1;
    $advertisement->save();
    return response()->json(['message' => 'Enabled Successfully']);
}


public function disable($id)
{
    $advertisement = Advertisment::findOrFail($id);
    $advertisement->status = 0;
    $advertisement->save();
    return response()->json(['message' => 'Disabled Successfully']);
}


public function feedback(FeedbackRequest $request)
{
    $ad = Advertisment::with(['userfeedbacks'])->findOrFail($request->adId);
    if ($ad->userfeedbacks) {
        return response()->json([
            'status' => 'error',
            'msg' => 'You can not give feedback more than once',
        ], 400);
    }

    $feedback = new Feedback();
    $feedback->advertisement_id = $ad->id;
    $feedback->creator_id = $ad->user_id;
    $feedback->reviewer_id = auth()->id();
    $feedback->feedback = $request->feedback;
    $feedback->position = $request->position;
    $feedback->save();

    $data['feedback'] = $feedback->feedback;
    $data['reviewer'] = $feedback->reviewer;
    $data['position'] = $feedback->position;
    $data['date_formatted'] = $feedback->created_at->format('M d, Y h:i A');

    return response()->json(['status' => 'success', 'data' => $data]);
}


public function feedbackList($adId)
{
    $data['ads'] = Feedback::with(['reviewer'])->where('advertisement_id', $adId)->paginate(config('basic.paginate'));
    return response()->json($data);
}

}
