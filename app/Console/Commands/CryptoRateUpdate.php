<?php

namespace App\Console\Commands;

use App\Models\Advertisment;
use App\Models\Currency;
use Facades\App\Services\BasicCurl;
use Illuminate\Console\Command;

class CryptoRateUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto-rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (config('basic.crypto_currency_status') == 1) {
            $countries = Currency::select('id', 'code', 'rate')
                ->where('flag', 1)->where('status', 1)->get();
            $access_key = config('basic.crypto_currency_api');
            $currencies = join(',', $countries->pluck('code')->toArray());
            $url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=$currencies";
//			$url = "https://sandbox-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=$currencies";
            $headers = [
                'Accepts: application/json',
                'X-CMC_PRO_API_KEY:' . $access_key
            ];
            $allCryptoConvert = BasicCurl::curlGetRequestWithHeaders($url, $headers);
            $allCryptoConvert = json_decode($allCryptoConvert, true);


            if (@$allCryptoConvert['data'] == '') {
                return 'error';
            }
            $coins = $allCryptoConvert['data'];

            foreach ($coins as $coin) {
                $symbol = $coin['symbol'];
                $curRate = $coin['quote']['USD']['price'];
                Currency::where(['code' => $symbol, 'flag' => 1, 'status' => 1])->update(['rate' => $curRate]);

                $currency = $countries->where('code', $symbol)->first();
                

                if ($currency && config('basic.ad_rate_update') == 1) {
                    $ads = Advertisment::with(['fiatCurrency'])->where('crypto_id', $currency->id)
                        ->where('status', 1)->where('price_type', 'margin')->get();

                    foreach ($ads as $ad) {
                        $fiatRate = $ad->fiatCurrency->rate ?? 1;

                        $cryptoRate = $curRate;
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
        $this->info('status');
    }
}
