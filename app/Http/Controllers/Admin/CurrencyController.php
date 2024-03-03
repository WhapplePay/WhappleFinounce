<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Notify;
use App\Http\Traits\Upload;
use App\Models\Advertisment;
use App\Models\Configure;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class CurrencyController extends Controller
{
    use Upload, Notify;

    public function listCrypto()
    {
        $data['control'] = Configure::first();
        $data['currency'] = Currency::where('flag', 1)->orderBy('id', 'desc')->get();
        return view('admin.currency.crypto.list', $data);
    }

    public function createCrypto()
    {
        return view('admin.currency.crypto.create');
    }

    public function storeCrypto(Request $request)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        $rules = [
            'name' => 'required|max:40',
            'code' => 'required',
            'symbol' => 'required',
            'rate' => 'required|min:0',
            'deposit_charge' => 'required|min:0',
            'deposit_type' => 'required',
            'withdraw_charge' => 'required|min:0',
            'withdraw_type' => 'required',
        ];
        $message = [
            'name.required' => 'Name field is required',
            'name.max' => 'This field may not be greater than :max characters',
            'code.required' => 'Code field is required',
            'symbol.required' => 'Symbol field is required',
            'rate.required' => 'Rate field is required',
            'deposit_charge.required' => 'Deposit Charge is required',
            'withdraw_charge.required' => 'Withdraw Charge is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        try {
            $currency = new Currency();


            if (isset($purifiedData['name'])) {
                $currency->name = @$purifiedData['name'];
            }
            if (isset($purifiedData['code'])) {
                $currency->code = strtoupper(@$purifiedData['code']);
            }
            if (isset($purifiedData['symbol'])) {
                $currency->symbol = @$purifiedData['symbol'];
            }
            if (isset($purifiedData['rate'])) {
                $currency->rate = @$purifiedData['rate'];
            }

            if (isset($purifiedData['deposit_charge'])) {
                $currency->deposit_charge = @$purifiedData['deposit_charge'];
            }
            if (isset($purifiedData['deposit_type'])) {
                $currency->deposit_type = @$purifiedData['deposit_type'];
            }
            if (isset($purifiedData['withdraw_charge'])) {
                $currency->withdraw_charge = @$purifiedData['withdraw_charge'];
            }
            if (isset($purifiedData['withdraw_type'])) {
                $currency->withdraw_type = @$purifiedData['withdraw_type'];
            }

            $currency->status = isset($purifiedData['status']) == 'true' ? 1 : 0;

            if ($request->hasFile('image')) {
                try {
                    $currency->image = $this->uploadImage($request->image, config('location.currency.path'), config('location.currency.size'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }

            $currency->flag = 1;
            $currency->save();
            return back()->with('success', 'Currency Successfully Saved');

        } catch (\Exception $e) {
            return back();
        }

    }

    public function editCrypto($id)
    {
        $data['crypto'] = Currency::findOrFail($id);
        return view('admin.currency.crypto.edit', $data);
    }

    public function updateCrypto(Request $request, $id)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        $rules = [
            'name' => 'required|max:40',
            'code' => 'required',
            'symbol' => 'required',
            'rate' => 'required|min:0',
            'deposit_charge' => 'required|min:0',
            'deposit_type' => 'required',
            'withdraw_charge' => 'required|min:0',
            'withdraw_type' => 'required',
        ];
        $message = [
            'name.required' => 'Name field is required',
            'name.max' => 'This field may not be greater than :max characters',
            'code.required' => 'Code field is required',
            'symbol.required' => 'Symbol field is required',
            'rate.required' => 'Rate field is required',
            'deposit_charge.required' => 'Deposit Charge is required',
            'withdraw_charge.required' => 'Withdraw Charge is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        try {

            $currency = Currency::findOrFail($id);

            if (isset($purifiedData['name'])) {
                $currency->name = @$purifiedData['name'];
            }
            if (isset($purifiedData['code'])) {
                $currency->code = strtoupper(@$purifiedData['code']);
            }
            if (isset($purifiedData['symbol'])) {
                $currency->symbol = @$purifiedData['symbol'];
            }
            if (isset($purifiedData['rate'])) {
                $currency->rate = @$purifiedData['rate'];
            }
            if (isset($purifiedData['deposit_charge'])) {
                $currency->deposit_charge = @$purifiedData['deposit_charge'];
            }
            if (isset($purifiedData['deposit_type'])) {
                $currency->deposit_type = @$purifiedData['deposit_type'];
            }
            if (isset($purifiedData['withdraw_charge'])) {
                $currency->withdraw_charge = @$purifiedData['withdraw_charge'];
            }
            if (isset($purifiedData['withdraw_type'])) {
                $currency->withdraw_type = @$purifiedData['withdraw_type'];
            }

            $currency->status = isset($purifiedData['status']) == 'true' ? 1 : 0;

            if ($request->hasFile('image')) {
                $currency->image = $this->uploadImage($request->image, config('location.currency.path'), config('location.currency.size'), $currency->image);
            }

            $currency->save();
            return back()->with('success', 'Updated Successfully');

        } catch (\Exception $e) {
            return back();
        }

    }

    public function deleteCrypto($id)
    {
        $currency = Currency::with(['advertisesCrypto'])->findOrFail($id);
        if (count($currency->advertisesCrypto) > 0) {
            return back()->with('warning', 'Currency has lot of advertises');
        }
        $old_image = $currency->image;
        $location = config('location.currency.path');
        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }

        $currency->delete();
        return back()->with('success', 'Currency has been deleted');
    }

    public function activeMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select ID.');
            return response()->json(['error' => 1]);
        } else {
            Currency::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Currencies Has Been Active');
            return response()->json(['success' => 1]);
        }

    }

    public function deActiveMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select ID.');
            return response()->json(['error' => 1]);
        } else {
            Currency::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Currencies Has Been Deactive');
            return response()->json(['success' => 1]);
        }
    }

    public function listFiat()
    {
        $data['control'] = Configure::first();
        $data['currency'] = Currency::where('flag', 0)->orderBy('id', 'desc')->get();
        return view('admin.currency.fiat.list', $data);
    }

    public function createFiat()
    {
        return view('admin.currency.fiat.create');
    }

    public function storeFiat(Request $request)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        $rules = [
            'name' => 'required|max:40',
            'code' => 'required',
            'symbol' => 'required',
            'rate' => 'required|min:0',
            'deposit_charge' => 'required|min:0',
            'deposit_type' => 'required',
            'withdraw_charge' => 'required|min:0',
            'withdraw_type' => 'required',
        ];
        $message = [
            'name.required' => 'Name field is required',
            'name.max' => 'This field may not be greater than :max characters',
            'code.required' => 'Code field is required',
            'symbol.required' => 'Symbol field is required',
            'rate.required' => 'Rate field is required',
            'deposit_charge.required' => 'Deposit Charge is required',
            'withdraw_charge.required' => 'Withdraw Charge is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        try {
            $currency = new Currency();

            if (isset($purifiedData['name'])) {
                $currency->name = @$purifiedData['name'];
            }
            if (isset($purifiedData['code'])) {
                $currency->code = strtoupper(@$purifiedData['code']);
            }
            if (isset($purifiedData['symbol'])) {
                $currency->symbol = @$purifiedData['symbol'];
            }
            if (isset($purifiedData['rate'])) {
                $currency->rate = @$purifiedData['rate'];
            }
            if (isset($purifiedData['deposit_charge'])) {
                $currency->deposit_charge = @$purifiedData['deposit_charge'];
            }
            if (isset($purifiedData['deposit_type'])) {
                $currency->deposit_type = @$purifiedData['deposit_type'];
            }
            if (isset($purifiedData['withdraw_charge'])) {
                $currency->withdraw_charge = @$purifiedData['withdraw_charge'];
            }
            if (isset($purifiedData['withdraw_type'])) {
                $currency->withdraw_type = @$purifiedData['withdraw_type'];
            }
            $currency->status = isset($purifiedData['status']) == 'true' ? 1 : 0;

            if ($request->hasFile('image')) {
                try {
                    $currency->image = $this->uploadImage($request->image, config('location.currency.path'), config('location.currency.size'));
                } catch (\Exception $exp) {
                    return back()->with('error', 'Image could not be uploaded.');
                }
            }

            $currency->flag = 0;
            $currency->save();
            return back()->with('success', 'Currency Successfully Saved');

        } catch (\Exception $e) {
            return back();
        }
    }

    public function editFiat($id)
    {
        $data['fiat'] = Currency::findOrFail($id);
        return view('admin.currency.fiat.edit', $data);
    }

    public function updateFiat(Request $request, $id)
    {
        $purifiedData = Purify::clean($request->except('image', '_token', '_method'));
        $rules = [
            'name' => 'required|max:40',
            'code' => 'required',
            'symbol' => 'required',
            'rate' => 'required|min:0',
            'deposit_charge' => 'required|min:0',
            'deposit_type' => 'required',
            'withdraw_charge' => 'required|min:0',
            'withdraw_type' => 'required',
        ];
        $message = [
            'name.required' => 'Name field is required',
            'name.max' => 'This field may not be greater than :max characters',
            'code.required' => 'Code field is required',
            'symbol.required' => 'Symbol field is required',
            'rate.required' => 'Rate field is required',
            'deposit_charge.required' => 'Deposit Charge is required',
            'withdraw_charge.required' => 'Withdraw Charge is required',
        ];

        $validate = Validator::make($purifiedData, $rules, $message);

        if ($validate->fails()) {
            return back()->withInput()->withErrors($validate);
        }

        try {

            $currency = Currency::findOrFail($id);

            if (isset($purifiedData['name'])) {
                $currency->name = @$purifiedData['name'];
            }
            if (isset($purifiedData['code'])) {
                $currency->code = strtoupper(@$purifiedData['code']);
            }
            if (isset($purifiedData['symbol'])) {
                $currency->symbol = @$purifiedData['symbol'];
            }
            if (isset($purifiedData['rate'])) {
                $currency->rate = @$purifiedData['rate'];
            }
            if (isset($purifiedData['deposit_charge'])) {
                $currency->deposit_charge = @$purifiedData['deposit_charge'];
            }
            if (isset($purifiedData['deposit_type'])) {
                $currency->deposit_type = @$purifiedData['deposit_type'];
            }
            if (isset($purifiedData['withdraw_charge'])) {
                $currency->withdraw_charge = @$purifiedData['withdraw_charge'];
            }
            if (isset($purifiedData['withdraw_type'])) {
                $currency->withdraw_type = @$purifiedData['withdraw_type'];
            }

            $currency->status = isset($purifiedData['status']) == 'true' ? 1 : 0;

            if ($request->hasFile('image')) {
                $currency->image = $this->uploadImage($request->image, config('location.currency.path'), config('location.currency.size'), $currency->image);
            }

            $currency->save();
            return back()->with('success', 'Updated Successfully');

        } catch (\Exception $e) {
            return back();
        }
    }

    public function deleteFiat($id)
    {
        $currency = Currency::with(['advertisesFiat'])->findOrFail($id);

        if (count($currency->advertisesFiat) > 0) {
            return back()->with('warning', 'Currency has lot of advertises');
        }

        $old_image = $currency->image;
        $location = config('location.currency.path');
        if (!empty($old_image)) {
            @unlink($location . '/' . $old_image);
        }

        $currency->delete();
        return back()->with('success', 'Currency has been deleted');
    }

    public function activeMultipleFiat(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select ID.');
            return response()->json(['error' => 1]);
        } else {
            Currency::whereIn('id', $request->strIds)->update([
                'status' => 1,
            ]);
            session()->flash('success', 'Currencies Has Been Active');
            return response()->json(['success' => 1]);
        }

    }

    public function deActiveMultipleFiat(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select ID.');
            return response()->json(['error' => 1]);
        } else {
            Currency::whereIn('id', $request->strIds)->update([
                'status' => 0,
            ]);
            session()->flash('success', 'Currencies Has Been Deactive');
            return response()->json(['success' => 1]);
        }
    }

    public function fiatControlAction(Request $request)
    {
        $configure = Configure::firstOrNew();
        $reqData = Purify::clean($request->except('_token', '_method'));

        $request->validate([
            'fiat_currency_api' => 'required',
        ]);


        config(['basic.fiat_currency_status' => (int)$reqData['fiat_currency_status']]);
        config(['basic.fiat_currency_api' => $reqData['fiat_currency_api']]);

        $fp = fopen(base_path() . '/config/basic.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
        fclose($fp);

        $configure->fill($reqData)->save();

        session()->flash('success', ' Updated Successfully');

        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return back();
    }

    public function cryptoControlAction(Request $request)
    {
        $configure = Configure::firstOrNew();
        $reqData = Purify::clean($request->except('_token', '_method'));

        $request->validate([
            'crypto_currency_api' => 'required',
        ]);


        config(['basic.crypto_currency_status' => (int)$reqData['crypto_currency_status']]);
        config(['basic.crypto_currency_api' => $reqData['crypto_currency_api']]);

        $fp = fopen(base_path() . '/config/basic.php', 'w');
        fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
        fclose($fp);

        $configure->fill($reqData)->save();

        session()->flash('success', ' Updated Successfully');

        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        return back();
    }

    public function fiatRate(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select ID.');
            return response()->json(['error' => 1]);
        } else {
            $countries = Currency::select('id', 'code', 'rate')
                ->when($request->strIds != null, function ($query) {
                    $query->whereIn('id', request()->strIds);
                })
                ->where('flag', 0)->where('status', 1)->get();

            $endpoint = 'live';
            $access_key = config('basic.fiat_currency_api');
            $currencies = join(',', $countries->pluck('code')->toArray()) . ',' . config('basic.currency');

            $source = config('basic.currency');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.apilayer.com/currency_data/live?source=$source&currencies=$currencies",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: text/plain",
                    "apikey: $access_key"
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($response);

            if ($res && $res->success == true) {
                $getRateCollect = collect($res->quotes)->toArray();
                foreach ($countries as $data) {
                    $newCode = $source . $data->code;

                    if (isset($getRateCollect[$newCode])) {
                        $data->rate = @$getRateCollect[$newCode];
                        $data->update();

                        if (config('basic.ad_rate_update') == 1) {
                            $ads = Advertisment::with(['cryptoCurrency'])->where('fiat_id', $data->id)
                                ->where('status', 1)->where('price_type', 'margin')->get();

                            if ($ads) {
                                foreach ($ads as $ad) {
                                    $cryptoRate = $ad->cryptoCurrency->rate ?? 1;
                                    $fiatRate = @$getRateCollect[$newCode];
                                    $totalPrice = $fiatRate * $cryptoRate;
                                    $tradePrice = ($totalPrice * $ad->price / 100) + $totalPrice;

                                    $ad->crypto_rate = $cryptoRate;
                                    $ad->fiat_rate = $fiatRate;
                                    $ad->rate = $tradePrice;
                                    $ad->save();
                                }
                            }
                        }
                    }
                }

                $configure = Configure::firstOrNew();
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.apilayer.com/currency_data/live?source=USD&currencies=" . config('basic.currency'),
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: text/plain",
                        "apikey: $access_key"
                    ),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET"
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($response);

                $getRateCollectNew = collect($result->quotes)->toArray();
                $newCode2 = 'USD' . config('basic.currency');


                if (isset($getRateCollectNew[$newCode2])) {
                    $base_currency_rate = 1 / $getRateCollectNew[$newCode2] ?? 1;

                    config(['basic.base_currency_rate' => $base_currency_rate]);
                    $fp = fopen(base_path() . '/config/basic.php', 'w');
                    fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
                    fclose($fp);
                    $configure->base_currency_rate = $base_currency_rate;
                    $configure->save();


                    $currencyBaseCheck = Currency::select('id', 'code', 'rate')
                        ->where('code', config('basic.currency'))->where('flag', 0)->where('status', 1)->get();

                    foreach ($currencyBaseCheck as $currency) {
                        $currency->rate = 1;
                        $currency->save();
                    }

                    $output = new \Symfony\Component\Console\Output\BufferedOutput();
                    Artisan::call('optimize:clear', array(), $output);
                    return $output->fetch();

                }
                session()->flash('success', 'Rate Updated');
                if ($request->ajax()) {
                    return response()->json(['success' => 1]);
                } else {
                    return 0;
                }
            }
        }
    }

    public function cryptoRate(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select ID.');
            return response()->json(['error' => 1]);
        } else {
            $countries = Currency::select('id', 'code', 'rate')
                ->when($request->strIds != null, function ($query) {
                    $query->whereIn('id', request()->strIds);
                })
                ->where('flag', 1)->where('status', 1)->get();
            $access_key = config('basic.crypto_currency_api');
            $currencies = join(',', $countries->pluck('code')->toArray());
            $result = file_get_contents('https://min-api.cryptocompare.com/data/pricemulti?fsyms=' . $currencies . '&tsyms=USD&api_key=' . $access_key);
            $cryptos = json_decode($result);

            foreach ($cryptos as $key => $crypto) {

                $output = $countries->where('code', $key)->first();
                if (!$output) {
                    continue;
                } else {
                    $output->rate = $crypto->USD;
                    $output->save();

                    if (config('basic.ad_rate_update') == 1) {
                        $ads = Advertisment::with(['fiatCurrency'])->where('crypto_id', $output->id)
                            ->where('status', 1)->where('price_type', 'margin')->get();

                        if ($ads) {
                            foreach ($ads as $ad) {
                                $fiatRate = $ad->fiatCurrency->rate ?? 1;
                                $cryptoRate = $crypto->USD;
                                $totalPrice = $fiatRate * $cryptoRate;
                                $tradePrice = ($totalPrice * $ad->price / 100) + $totalPrice;

                                $ad->crypto_rate = $cryptoRate;
                                $ad->fiat_rate = $fiatRate;
                                $ad->rate = $tradePrice;
                                $ad->save();
                            }
                        }
                    }
                }
            }
            session()->flash('success', 'Rate Updated');
            if ($request->ajax()) {
                return response()->json(['success' => 1]);
            } else {
                return 0;
            }
        }
    }
}
