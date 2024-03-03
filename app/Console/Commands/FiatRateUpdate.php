<?php

namespace App\Console\Commands;

use App\Models\Advertisment;
use App\Models\Configure;
use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FiatRateUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiat-rate:update';

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
        $basic = (object)config('basic');

        if (config('basic.fiat_currency_status') == 1) {
            $countries = Currency::select('id', 'code', 'rate')
                ->where('flag', 0)->where('status', 1)->get();
            $endpoint = 'live';
            $access_key = config('basic.fiat_currency_api');
            $currencies = join(',', $countries->pluck('code')->toArray()) . ',' . config('basic.currency');


            $source = config('basic.currency');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.apilayer.com/currency_data/$endpoint?source=$source&currencies=$currencies",
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
         


            if (isset($res->success) && $res->success == true) {
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
                    Artisan::call('optimize:clear');
                }
            }
        }

        $this->info('status');
    }
}
