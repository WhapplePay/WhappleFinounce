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
            session()->flash('error', 'You did not select any ID.');
            return response()->json(['error' => 1]);
        } else {
            // Fetch currencies from the database
            $countries = Currency::select('id', 'code', 'rate')
                ->when($request->strIds != null, function ($query) {
                    $query->whereIn('id', request()->strIds);
                })
                ->where('flag', 0)->where('status', 1)->get();
    
            // Currency Layer API URL
            $apiUrl = "http://api.currencylayer.com/live?access_key=6e387febe52946ced168844270623502";
            
            // Set up cURL for the API request
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
    
            $apiData = json_decode($response);
    
            if ($apiData && $apiData->success == true) {
                // Extract relevant data from the API response
                $getRateCollect = collect($apiData->quotes)->toArray();
                $sourceCurrency = $apiData->source;
    
                foreach ($countries as $data) {
                    $newCode = $sourceCurrency . $data->code;
    
                    if (isset($getRateCollect[$newCode])) {
                        $data->rate = @$getRateCollect[$newCode];
                        $data->update();
    
                        // Update advertisements if configured
                        if (config('basic.ad_rate_update') == 1) {
                            $this->updateAdvertisementRates($data, $getRateCollect);
                        }
                    }
                }
    
                // Update base currency rate in the configuration
                $this->updateBaseCurrencyRate();
    
                session()->flash('success', 'Rate Updated');
                return $request->ajax() ? response()->json(['success' => 1]) : 0;
            }
        }
    }
    
    /**
     * Update advertisement rates based on currency rates
     */
    private function updateAdvertisementRates($currency, $rateCollect)
    {
        $ads = Advertisment::with(['cryptoCurrency'])
            ->where('fiat_id', $currency->id)
            ->where('status', 1)
            ->where('price_type', 'margin')
            ->get();
    
        if ($ads) {
            foreach ($ads as $ad) {
                $cryptoRate = $ad->cryptoCurrency->rate ?? 1;
                $fiatRate = @$rateCollect[$currency->code];
                $totalPrice = $fiatRate * $cryptoRate;
                $tradePrice = ($totalPrice * $ad->price / 100) + $totalPrice;
    
                $ad->crypto_rate = $cryptoRate;
                $ad->fiat_rate = $fiatRate;
                $ad->rate = $tradePrice;
                $ad->save();
            }
        }
    }
    
    /**
     * Update base currency rate in the configuration
     */
    private function updateBaseCurrencyRate()
    {
        $apiUrl = "http://api.currencylayer.com/live?access_key=6e387febe52946ced168844270623502";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    
        $apiData = json_decode($response);
    
        if ($apiData && $apiData->success == true) {
            $getRateCollectNew = collect($apiData->quotes)->toArray();
            $newCode2 = 'USD' . config('basic.currency');
    
            if (isset($getRateCollectNew[$newCode2])) {
                $base_currency_rate = 1 / $getRateCollectNew[$newCode2] ?? 1;
    
                config(['basic.base_currency_rate' => $base_currency_rate]);
                $fp = fopen(base_path() . '/config/basic.php', 'w');
                fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
                fclose($fp);
    
                $configure = Configure::firstOrNew();
                $configure->base_currency_rate = $base_currency_rate;
                $configure->save();
    
                // Reset currency rates to 1 for the base currency
                $currencyBaseCheck = Currency::select('id', 'code', 'rate')
                    ->where('code', config('basic.currency'))->where('flag', 0)->where('status', 1)->get();
    
                foreach ($currencyBaseCheck as $currency) {
                    $currency->rate = 1;
                    $currency->save();
                }
    
                // Clear cache
                $output = new \Symfony\Component\Console\Output\BufferedOutput();
                Artisan::call('optimize:clear', array(), $output);
                return $output->fetch();
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
