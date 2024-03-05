<?php

namespace App\Http\Controllers\User;

use App\Helper\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Advertisment;
use App\Models\Currency;
use App\Models\FirebaseNotify;
use App\Models\Fund;
use App\Models\IdentifyForm;
use App\Models\KYC;
use App\Models\Language;
use App\Models\PayoutLog;
use App\Models\PayoutMethod;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Stevebauman\Purify\Facades\Purify;
use Facades\App\Services\BasicService;

use hisorange\BrowserDetect\Parser as Browser;

class HomeController extends Controller
{
    use Upload, Notify;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userid = $this->user->id;
        $data['trades'] = collect(Trade::where(function ($query) use ($userid) {
            $query->where("sender_id", $userid)
                ->orWhere("owner_id", $userid);
        })
            ->selectRaw('COUNT(id) AS totalTrade')
            ->selectRaw('COUNT(CASE WHEN status = 0 OR status = 1 OR status = 5 OR status = 6 OR status = 7 THEN id END) AS runningTrade')
            ->selectRaw('COUNT(CASE WHEN status = 3 OR status = 4 OR status = 8 THEN id END) AS completeTrade')
            ->orderBy('id', 'desc')
            ->get()->toArray())->collapse();

        $data['advertise'] = collect(Advertisment::where("user_id", $userid)
            ->selectRaw('COUNT(id) AS totalAdvertise')
            ->orderBy('id', 'desc')
            ->get()->toArray())->collapse();

        $data['recentTrades'] = Trade::with(['sender', 'currency', 'owner', 'receiverCurrency', 'advertise'])
            ->where(function ($query) use ($userid) {
                $query->where("sender_id", $userid)
                    ->orWhere("owner_id", $userid);
            })
            ->orderBy('id', 'desc')
            ->limit(10)->get();
        $firebaseNotify = FirebaseNotify::first();
        return view($this->theme . 'user.dashboard', $data, compact('firebaseNotify'));
    }


    public function transaction($code = null)
    {
        $transactions = $this->user->transaction()->orderBy('id', 'DESC')
            ->when($code != null, function ($query) use ($code) {
                return $query->where('code', $code);
            })
            ->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.index', compact('transactions'));
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->all();
        $dateSearch = $request->datetrx;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);
        $transaction = Transaction::where('user_id', $this->user->id)->with('user')
            ->when(@$search['transaction_id'], function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
            })
            ->when(@$search['remark'], function ($query) use ($search) {
                return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));
        $transactions = $transaction->appends($search);


        return view($this->theme . 'user.transaction.index', compact('transactions'));

    }

    public function fundHistory()
    {
        $funds = Fund::where('user_id', $this->user->id)->where('status', '!=', 0)->orderBy('id', 'DESC')->with('gateway')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.transaction.fundHistory', compact('funds'));
    }

    public function fundHistorySearch(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $funds = Fund::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('transaction', 'LIKE', $search['name']);
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->with('gateway')
            ->paginate(config('basic.paginate'));
        $funds->appends($search);

        return view($this->theme . 'user.transaction.fundHistory', compact('funds'));

    }

    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), []);
        $data['user'] = $this->user;
        $data['languages'] = Language::all();
        $data['identityFormList'] = IdentifyForm::where('status', 1)->get();
        if ($request->has('identity_type')) {
            $validator->errors()->add('identity', '1');
            $data['identity_type'] = $request->identity_type;
            $data['identityForm'] = IdentifyForm::where('slug', trim($request->identity_type))->where('status', 1)->firstOrFail();
            return view($this->theme . 'user.profile.myprofile', $data)->withErrors($validator);
        }

        return view($this->theme . 'user.profile.myprofile', $data);
    }


    public function updateProfile($request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');

        $image = $request->image;
        $user = $this->user;

        if ($request->hasFile('image')) {
            $path = config('location.user.path');
            try {
                $user->image = $this->uploadImage($image, $path);
            } catch (\Exception $exp) {
                return back()->with('error', 'Could not upload your ' . $image)->withInput();
            }
        }
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }


    public function updateInformation(Request $request)
    {

        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $req = Purify::clean($request->all());
        $user = $this->user;
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'address' => 'required',
            'language_id' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First Name field is required',
            'lastname.required' => 'Last Name field is required',
        ];

        if ($request->hasFile('image')) {
            $this->updateProfile($request);
        }
        if ($request->hasFile('coverImage')) {
            $coverImage = $request->coverImage;
            $path = config('location.user.path');
            $user->cover_image = $this->uploadImage($coverImage, $path);
        }

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user->language_id = $req['language_id'];
        $user->firstname = $req['firstname'];
        $user->lastname = $req['lastname'];
        $user->username = $req['username'];
        $user->address = $req['address'];
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }

    public function password()
    {
        $data['user'] = $this->user;
        return view($this->theme . 'user.profile.password', $data);
    }

    public function updatePassword(Request $request)
    {

        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('password', '1');
            return back()->withErrors($validator)->withInput();
        }
        $user = $this->user;
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function twoStepSecurity()
    {
        $basic = (object)config('basic');
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $secret);
        $previousCode = $this->user->two_fa_code;

        $previousQR = $ga->getQRCodeGoogleUrl($this->user->username . '@' . $basic->site_title, $previousCode);
        return view($this->theme . 'user.twoFA.index', compact('secret', 'qrCodeUrl', 'previousCode', 'previousQR'));
    }

    public function twoStepEnable(Request $request)
    {
        $user = $this->user;
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        $userCode = $request->code;
        if ($oneCode == $userCode) {
            $user['two_fa'] = 1;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = $request->key;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_ENABLED', [
                'action' => 'Enabled',
                'code' => $user->two_fa_code,
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);
            return back()->with('success', 'Google Authenticator Has Been Enabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }


    }


    public function twoStepDisable(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $user = $this->user;
        $ga = new GoogleAuthenticator();

        $secret = $user->two_fa_code;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {
            $user['two_fa'] = 0;
            $user['two_fa_verify'] = 1;
            $user['two_fa_code'] = null;
            $user->save();
            $browser = new Browser();
            $this->mail($user, 'TWO_STEP_DISABLED', [
                'action' => 'Disabled',
                'ip' => request()->ip(),
                'browser' => $browser->browserName() . ', ' . $browser->platformName(),
                'time' => date('d M, Y h:i:s A'),
            ]);

            return back()->with('success', 'Google Authenticator Has Been Disabled.');
        } else {
            return back()->with('error', 'Wrong Verification Code.');
        }
    }


    /*
     * User payout Operation
     */
    public function payoutMoney()
    {
        $data['title'] = "Payout Money";
        $data['gateways'] = PayoutMethod::whereStatus(1)->get();
        return view($this->theme . 'user.payout.money', $data);
    }

    public function payoutMoneyRequest(Request $request)
    {
        $this->validate($request, [
            'currencyId' => 'required|integer',
            'walletAddress' => 'required',
            'withdrawAmount' => ['required', 'numeric']
        ]);


        $crypto = Currency::findOrFail($request->currencyId);
        $user = auth()->user();
        $userWallet = Wallet::where('user_id', $user->id)->where('crypto_currency_id', $crypto->id)->firstOrFail();
      
        $runningTradesWithImportantConditions = Trade::where('owner_id', $user->id)
        ->whereIn('status', [1, 5]) 
        ->count();

        if ($runningTradesWithImportantConditions >= 1) {
        session()->flash('error', 'You cannot withdraw with more than 1 running trades where the buyer has paid, is in dispute, or canceled.');
        return redirect()->back();
        }


        if ($crypto->withdraw_type == 0) {
            $charge = $request->withdrawAmount * $crypto->withdraw_charge / 100;
        } else {
            $charge = $crypto->withdraw_charge;
        }

        $finalWithdrawAmount = $request->withdrawAmount + $charge;

        if ($finalWithdrawAmount > $userWallet->balance) {
            session()->flash('error', 'You do not have sufficient balance for withdraw');
            return redirect()->back();
        }

        $userWallet->balance -= $finalWithdrawAmount;
        $userWallet->save();

        $activeMethod = PayoutMethod::where('status', 1)->firstOrFail();

        $trx = strRandom();
        $withdraw = new PayoutLog();
        $withdraw->user_id = $user->id;
        $withdraw->method_id = $activeMethod->id;
        $withdraw->network = $request->network;
        $withdraw->wallet_address = $request->walletAddress;
        $withdraw->code = $crypto->code;
        $withdraw->amount = getAmount($request->withdrawAmount);
        $withdraw->charge = $charge;
        $withdraw->net_amount = $finalWithdrawAmount;
        $withdraw->trx_id = $trx;
        $withdraw->status = 1;
        $withdraw->save();

        $remarks = 'Withdraw Crypto';
        BasicService::makeTransaction($user, $withdraw->amount, $withdraw->charge, '-', $withdraw->trx_id, $remarks, $withdraw->code);


        $this->sendMailSms($user, $type = 'PAYOUT_REQUEST', [
            'amount' => getAmount($withdraw->amount, 8),
            'charge' => getAmount($withdraw->charge, 8),
            'currency' => $crypto->code,
            'trx' => $withdraw->trx_id,
        ]);

        $msg = [
            'username' => $user->username,
            'amount' => getAmount($withdraw->amount, 8),
            'currency' => $crypto->code,
        ];
        $action = [
            "link" => route('admin.user.withdrawal', $user->id),
            "icon" => "fa fa-money-bill-alt "
        ];
        // $firebaseAction = route('admin.user.withdrawal', $user->id);
        // $this->adminPushNotification('PAYOUT_REQUEST', $msg, $action);
        // $this->adminFirebasePushNotification('PAYOUT_REQUEST', $msg, $firebaseAction);
        session()->flash('success', 'Payout request Successfully Submitted. Wait For Confirmation.');

        return redirect()->route('user.payout.history');
    }

    

    public function depositHistory(Request $request)
    {

        $search = $request->all();
        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $user = $this->user;
        $data['depositLog'] = Fund::with(['user'])->where('user_id', $user->id)
            ->when(isset($search['trx']), function ($query) use ($search) {
                return $query->where("trx", 'LIKE', '%' . $search['trx'] . '%');
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->paginate(config('basic.paginate'));;
        $data['title'] = "Deposit Log";
        return view($this->theme . 'user.deposit.log', $data);
    }

    public function payoutHistory()
    {
        $user = $this->user;
        $data['payoutLog'] = PayoutLog::whereUser_id($user->id)->where('status', '!=', 0)->latest()->with('user', 'method')->paginate(config('basic.paginate'));
        $data['title'] = "Payout Log";
        return view($this->theme . 'user.payout.log', $data);
    }


    public function payoutHistorySearch(Request $request)
    {
        $search = $request->all();

        $dateSearch = $request->date_time;
        $date = preg_match("/^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $dateSearch);

        $payoutLog = PayoutLog::orderBy('id', 'DESC')->where('user_id', $this->user->id)->where('status', '!=', 0)
            ->when(isset($search['name']), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search['name']);
            })
            ->when($date == 1, function ($query) use ($dateSearch) {
                return $query->whereDate("created_at", $dateSearch);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->with('user', 'method')->paginate(config('basic.paginate'));
        $payoutLog->appends($search);

        $title = "Payout Log";
        return view($this->theme . 'user.payout.log', compact('title', 'payoutLog'));
    }


    public function verificationSubmit(Request $request)
    {
        $identityFormList = IdentifyForm::where('status', 1)->get();
        $rules['identity_type'] = ["required", Rule::in($identityFormList->pluck('slug')->toArray())];
        $identity_type = $request->identity_type;
        $identityForm = IdentifyForm::where('slug', trim($identity_type))->where('status', 1)->firstOrFail();

        $params = $identityForm->services_form;

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], 'mimes:jpeg,jpg,png');
                    array_push($rules[$key], 'max:2048');
                    array_push($verifyImages, $key);
                }
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
            $validator->errors()->add('identity', '1');

            return back()->withErrors($validator)->withInput();
        }


        $path = config('location.kyc.path') . date('Y') . '/' . date('m') . '/' . date('d');
        $collection = collect($request);

        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $this->uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    session()->flash('error', 'Could not upload your ' . $inKey);
                                    return back()->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
        }

        try {

            DB::beginTransaction();

            $user = $this->user;
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = $identityForm->slug;
            $kyc->details = $reqField;
            $kyc->save();

            $user->identity_verify = 1;
            $user->save();

            if (!$kyc) {
                DB::rollBack();
                $validator->errors()->add('identity', '1');
                return back()->withErrors($validator)->withInput()->with('error', "Failed to submit request");
            }
            DB::commit();
            return redirect()->route('user.profile')->withErrors($validator)->with('success', 'KYC request has been submitted.');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')->withErrors($validator)->with('error', $e->getMessage());
        }
    }

    public function addressVerification(Request $request)
    {

        $rules = [];
        $rules['addressProof'] = ['image', 'mimes:jpeg,jpg,png', 'max:2048'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->errors()->add('addressVerification', '1');
            return back()->withErrors($validator)->withInput();
        }

        $path = config('location.kyc.path') . date('Y') . '/' . date('m') . '/' . date('d');

        $reqField = [];
        try {
            if ($request->hasFile('addressProof')) {
                $reqField['addressProof'] = [
                    'field_name' => $this->uploadImage($request['addressProof'], $path),
                    'type' => 'file',
                ];
            } else {
                $validator->errors()->add('addressVerification', '1');

                session()->flash('error', 'Please select a ' . 'address Proof');
                return back()->withInput();
            }
        } catch (\Exception $exp) {
            session()->flash('error', 'Could not upload your ' . 'address Proof');
            return redirect()->route('user.profile')->withInput();
        }

        try {

            DB::beginTransaction();
            $user = $this->user;
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->kyc_type = 'address-verification';
            $kyc->details = $reqField;
            $kyc->save();
            $user->address_verify = 1;
            $user->save();

            if (!$kyc) {
                DB::rollBack();
                $validator->errors()->add('addressVerification', '1');
                return redirect()->route('user.profile')->withErrors($validator)->withInput()->with('error', "Failed to submit request");
            }
            DB::commit();
            return redirect()->route('user.profile')->withErrors($validator)->with('success', 'Your request has been submitted.');

        } catch (\Exception $e) {
            $validator->errors()->add('addressVerification', '1');
            return redirect()->route('user.profile')->with('error', $e->getMessage())->withErrors($validator);
        }
    }

    public function identityVerify(Request $request)
    {
        $validator = Validator::make($request->all(), []);
        $data['user'] = $this->user;
        $data['identityFormList'] = IdentifyForm::where('status', 1)->get();
        if ($request->has('identity_type')) {
            $validator->errors()->add('identity', '1');
            $data['identity_type'] = $request->identity_type;
            $data['identityForm'] = IdentifyForm::where('slug', trim($request->identity_type))->where('status', 1)->firstOrFail();
            return view($this->theme . 'user.profile.identity', $data)->withErrors($validator);
        }

        return view($this->theme . 'user.profile.identity', $data);
    }

    public function saveToken(Request $request)
    {
        auth()->user()->update(['fcm_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

}
