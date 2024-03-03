<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Coinpayments\CoinPaymentHosted;
use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Configure;
use App\Models\CryptoWallet;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
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

    public function index($cryptId = null)
    {
        $data['wallets'] = Wallet::where('user_id', auth()->id())->with('crypto')->latest()->get();
        $data['cryptoWallets'] = CryptoWallet::where('user_id', $this->user->id)
            ->when($cryptId != null, function ($query) use ($cryptId) {
                return $query->where('crypto_currency_id', $cryptId);
            })->orderBy('id', 'desc')->paginate(config('basic.paginate'));
        return view($this->theme . 'user.wallet.index', $data, compact('cryptId'));
    }

    public function walletGenerate(Request $request)
    {
        $crypto = Currency::active()->findOrFail($request->currencyId);

        $coinPayAcc = Configure::firstOrFail();
        $cps = new CoinPaymentHosted();
        $cps->Setup($coinPayAcc->private_key, $coinPayAcc->public_key);
        $callbackUrl = route('callback');

        $result = $cps->GetCallbackAddress($crypto->code, $callbackUrl);

        if ($result['error'] == 'ok') {
            $newCryptoWallet = new CryptoWallet();
            $newCryptoWallet->user_id = $this->user->id;
            $newCryptoWallet->crypto_currency_id = $crypto->id;
            $newCryptoWallet->wallet_address = $result['result']['address'];
            $newCryptoWallet->save();
            session()->flash('success', 'New Wallet Address Generated Successfully');
            return back();
        } else {
            session()->flash('error', $result['error']);
            return back();
        }
    }
}
