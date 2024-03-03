<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertismentRequest;
use App\Http\Requests\FeedbackRequest;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Advertisment;
use App\Models\Currency;
use App\Models\Feedback;
use App\Models\Gateway;
use App\Models\PaymentWindow;
use App\Models\Trade;
use Illuminate\Http\Request;

class AdvertismentsController extends Controller
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

    public function index(Request $request)
    {
        $search = $request->all();
        $data['advertises'] = Advertisment::with(['fiatCurrency', 'cryptoCurrency', 'paymentWindow'])->where('user_id', $this->user->id)
            ->when(isset($search['currencyCode']), function ($query) use ($search) {
                $query->whereHas('fiatCurrency', function ($qq) use ($search) {
                    $qq->where('code', 'LIKE', '%' . $search['currencyCode'] . '%');
                });
            })
            ->when(isset($search['type']), function ($query) use ($search) {
                $query->where('type', $search['type']);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                $query->where('status', $search['status']);
            })
            ->orderBy('id', 'desc')
            ->paginate(config('basic.paginate'));
        return view($this->theme . 'user.advertise.index', $data);
    }

    public function create()
    {
        $auth = $this->user;
        $trade = Trade::where(function ($query) use ($auth) {
            $query->where('sender_id', $auth->id)
                ->orWhere('owner_id', $auth->id);
        })
            ->whereIn('status', [0, 1, 5])->count();

        if ($auth->trade_limit == null) {
            if ($trade >= config('basic.incompleteLimit')) {
                return back()->with('warning', 'Please complete running trades');
            }
        } else {
            if ($trade >= $auth->trade_limit) {
                return back()->with('warning', 'Please complete running trades');
            }
        }

        $data['cryptos'] = Currency::where('flag', 1)->whereStatus(1)->orderBy('name', 'asc')->get();
        $data['fiats'] = Currency::where('flag', 0)->whereStatus(1)->orderBy('name', 'asc')->get();
        $data['gateways'] = Gateway::whereStatus(1)->orderBy('name', 'asc')->get();
        $data['paymentWindows'] = PaymentWindow::groupBy('name')->get();
        return view($this->theme . 'user.advertise.create', $data);
    }

    public function store(AdvertismentRequest $request)
    {
        $auth = $this->user;
        $trade = Trade::where(function ($query) use ($auth) {
            $query->where('sender_id', $auth->id)
                ->orWhere('owner_id', $auth->id);
        })
            ->whereIn('status', [0, 1, 5])->count();

        if ($auth->trade_limit == null) {
            if ($trade >= config('basic.incompleteLimit')) {
                return back()->with('warning', 'Please complete running trades');
            }
        } else {
            if ($trade >= $auth->trade_limit) {
                return back()->with('warning', 'Please complete running trades');
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
        $advertisement->user_id = $this->user->id;
        $advertisement->type = $request->type;
        $advertisement->crypto_id = $request->crypto_id;
        $advertisement->fiat_id = $request->fiat_id;
        $advertisement->gateway_id = $request->gateway_id;
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
        session()->flash('Add Successfully');
        return redirect()->route('user.advertisements.list');
    }

    public function edit($id)
    {
        $data['advertise'] = Advertisment::with(['fiatCurrency'])->findOrFail($id);
        $data['cryptos'] = Currency::where('flag', 1)->whereStatus(1)->orderBy('name', 'asc')->get();
        $data['fiats'] = Currency::where('flag', 0)->whereStatus(1)->orderBy('name', 'asc')->get();
        $data['gateways'] = Gateway::whereStatus(1)->orderBy('name', 'asc')->get();
        $data['paymentWindows'] = PaymentWindow::groupBy('name')->get();
        return view($this->theme . 'user.advertise.edit', $data);
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
        return back()->with('success', 'Updated Successfully');
    }

    public function enable($id)
    {
        $advertisement = Advertisment::findOrFail($id);
        $advertisement->status = 1;
        $advertisement->save();
        return back()->with('success', 'Enable Successfully');
    }

    public function disable($id)
    {
        $advertisement = Advertisment::findOrFail($id);
        $advertisement->status = 0;
        $advertisement->save();
        return back()->with('success', 'Disable Successfully');
    }

    public function feedback(FeedbackRequest $request)
    {
        $ad = Advertisment::with(['userfeedbacks'])->findOrFail($request->adId);
        if ($ad->userfeedbacks) {
            return response([
                'status' => 'error',
                'msg' => 'You can not give feedback more than time',
            ]);
        }

        $feedback = new Feedback();
        $feedback->advertisement_id = $ad->id;
        $feedback->creator_id = $ad->user_id;
        $feedback->reviewer_id = $this->user->id;
        $feedback->feedback = $request->feedback;
        $feedback->position = $request->position;
        $feedback->save();


        $data['feedback'] = $feedback->feedback;
        $data['reviewer'] = $feedback->reviewer;
        $data['position'] = $feedback->position;
        $data['date_formatted'] = $feedback->created_at->format('M d, Y h:i A');

        return response([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function feedbackList($adId)
    {
        $data['ads'] = Feedback::with(['reviewer'])->where('advertisement_id', $adId)->paginate(config('basic.paginate'));
        return view($this->theme . 'user.feedback.index', $data);
    }
}
