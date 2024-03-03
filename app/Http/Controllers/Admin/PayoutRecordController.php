<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Currency;
use App\Models\PayoutLog;
use App\Models\PayoutMethod;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;

class PayoutRecordController extends Controller
{
    use Notify, Upload;

    public function methodList()
    {
        $page_title = 'Payout Methods';
        $data['methods'] = PayoutMethod::get();
        return view('admin.payout_methods.index', $data, compact('page_title'));
    }

    public function methodEdit(Request $request, $id)
    {
        $page_title = 'Payout Methods Edit';
        $payoutMethod = PayoutMethod::findOrFail($id);
        if ($request->method() == 'GET') {
            return view('admin.payout_methods.edit', compact('page_title', 'payoutMethod'));
        }
        if ($request->method() == 'POST') {
            $purifiedData = Purify::clean($request->all());
            $validator = Validator::make($purifiedData, [
                'name' => 'required|min:3|max:50|unique:payout_methods,name,' . $payoutMethod->id,
            ]);


            $purifiedData = (object)$purifiedData;

            $parameters = [];
            if ($payoutMethod->parameters) {
                foreach ($request->except('_token', '_method', 'image') as $k => $v) {
                    foreach ($payoutMethod->parameters as $key => $cus) {
                        if ($k != $key) {
                            continue;
                        } else {
                            $rules[$key] = 'required|max:191';
                            $parameters[$key] = $v;
                        }
                    }
                }
            }

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $payoutMethod->name = $purifiedData->name;
            $payoutMethod->parameters = @$parameters;
            $payoutMethod->environment = @$purifiedData->environment;


            if ($request->file('image') && $request->file('image')->isValid()) {
                $extension = $request->image->extension();
                $logoName = strtolower($purifiedData->name . '.' . $extension);
                $old = $payoutMethod->image;
                $payoutMethod->image = $this->uploadImage($request->image, config('location.payoutMethod.path'), config('location.payoutMethod.size'), $old, null, $logoName);
            }

            $payoutMethod->save();
            return back()->with('success', 'Updated Successfully');

        }
    }

    public function methodStatus($id)
    {
        try {
            DB::beginTransaction();
            $payoutMethod = PayoutMethod::findOrFail($id);
            $oldActive = PayoutMethod::where('status', 1)->firstOrFail();
            $oldActive->status = 0;
            $oldActive->save();


            $payoutMethod->status = 1;
            $payoutMethod->save();
            DB::commit();
            return back()->with('success', 'Updated Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong');
        }

    }

    public function index($id = null)
    {
        $page_title = "Payout Logs";
        $records = PayoutLog::where('status', '!=', 0)
            ->when($id != null, function ($query) use ($id) {
                return $query->where("user_id", $id);
            })
            ->orderBy('id', 'DESC')->with('user', 'method')->paginate(config('basic.paginate'));
        return view('admin.payout.logs', compact('records', 'page_title'));
    }


    public function request()
    {
        $page_title = "Payout Request";
        $records = PayoutLog::where('status', 1)->orderBy('id', 'DESC')->with('user', 'method')->paginate(config('basic.paginate'));
        return view('admin.payout.logs', compact('records', 'page_title'));
    }

    public function search(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $records = PayoutLog::when(isset($search['name']), function ($query) use ($search) {
            return $query->where('trx_id', 'LIKE', $search['name'])
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'LIKE', "%{$search['name']}%")
                        ->orWhere('username', 'LIKE', "%{$search['name']}%");
                });

        })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->where('status', '!=', 0)
            ->with('user', 'method')
            ->paginate(config('basic.paginate'));
        $records->appends($search);

        $page_title = "Search Payout Logs";
        return view('admin.payout.logs', compact('records', 'page_title'));
    }

    public function action(Request $request, $id)
    {

        $this->validate($request, [
            'id' => 'required',
            'status' => ['required', Rule::in(['1', '2', '3'])],
        ]);

        $data = PayoutLog::where('id', $request->id)->whereIn('status', [1])->with('user')->firstOrFail();

        $basic = (object)config('basic');

        if ($request->status == '1') {
            if (optional($data->method)->is_automatic == 1) {
                $methodObj = 'App\\Services\\Payout\\' . optional($data->method)->code . '\\Card';
                $data = $methodObj::payouts($data);
                if (!$data) {
                    return back()->with('error', 'Method not available or unknown errors occur');
                }

                if ($data['status'] == 'error') {
                    return back()->with('error', $data['data']);
                }
                if (optional($data->method)->code == 'binance') {
                    $data->response_id = $data['response_id'];
                    $data->save();
                    session()->flash('success', 'Approve Successfully');
                    return back();
                } else {

                    $data->status = 2;
                    $data->feedback = $request->feedback;
                    $data->save();

                    $this->sendMailSms($data->user, 'PAYOUT_APPROVE', [
                        'amount' => getAmount($data->amount, 8),
                        'charge' => getAmount($data->charge, 8),
                        'currency' => $data->code,
                        'transaction' => $data->trx_id,
                        'feedback' => $data->feedback
                    ]);


                    $msg = [
                        'amount' => getAmount($data->amount, 8),
                        'currency' => $data->code,
                    ];
                    $action = [
                        "link" => '#',
                        "icon" => "fa fa-money-bill-alt "
                    ];
                    $this->userPushNotification($data->user, 'PAYOUT_APPROVE', $msg, $action);
                    $this->userFirebasePushNotification($data->user, 'PAYOUT_APPROVE', $msg);
                    session()->flash('success', 'Approve Successfully');
                    return back();
                }
            }
        }

        if ($request->status == '2') {
            $data->status = 2;
            $data->feedback = $request->feedback;
            $data->save();

            $user = $data->user;

            $this->sendMailSms($user, 'PAYOUT_APPROVE', [
                'amount' => getAmount($data->amount, 8),
                'charge' => getAmount($data->charge, 8),
                'currency' => $data->code,
                'transaction' => $data->trx_id,
                'feedback' => $data->feedback
            ]);


            $msg = [
                'amount' => getAmount($data->amount, 8),
                'currency' => $data->code,
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt "
            ];
            $this->userPushNotification($user, 'PAYOUT_APPROVE', $msg, $action);
            $this->userFirebasePushNotification($user, 'PAYOUT_APPROVE', $msg);

            session()->flash('success', 'Approve Successfully');
            return back();

        } elseif ($request->status == '3') {
            $data->status = 3;
            $data->feedback = $request->feedback;
            $data->save();

            $user = $data->user;
            $currency = Currency::where('code', $data->code)->firstOrFail();
            $wallet = Wallet::where('user_id', $data->user_id)->where('crypto_currency_id', $currency->id)->firstOrFail();
            $wallet->balance += $data->net_amount;
            $wallet->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = getAmount($data->net_amount, 8);
            $transaction->final_balance = $wallet->balance;
            $transaction->charge = $data->charge;
            $transaction->trx_type = '+';
            $transaction->remarks = getAmount($data->amount, 8) . ' ' . $data->code . ' withdraw amount has been refunded';
            $transaction->trx_id = $data->trx_id;
            $transaction->save();


            $this->sendMailSms($user, $type = 'PAYOUT_REJECTED', [
                'amount' => getAmount($data->amount, 8),
                'charge' => getAmount($data->charge, 8),
                'currency' => $data->code,
                'transaction' => $data->trx_id,
                'feedback' => $data->feedback
            ]);


            $msg = [
                'amount' => getAmount($data->amount, 8),
                'currency' => $data->code,
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt "
            ];
            $this->userPushNotification($user, 'PAYOUT_REJECTED', $msg, $action);
            $this->userFirebasePushNotification($user, 'PAYOUT_REJECTED', $msg);
            session()->flash('success', 'Reject Successfully');
            return back();
        }
    }
}
