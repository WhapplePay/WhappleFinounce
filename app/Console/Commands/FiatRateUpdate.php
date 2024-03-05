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
            $baseCurrency = config('basic.currency');
            $accessKey = '6e387febe52946ced168844270623502';
    
            $response = Http::get("http://api.currencylayer.com/live", [
                'access_key' => $accessKey,
            ]);
    
            $result = $response->json();
    
            if (isset($result['success']) && $result['success'] == true) {
                $getRateCollect = collect($result['quotes'])->toArray();
                $countries = Currency::select('id', 'code', 'rate')
                    ->where('flag', 0)->where('status', 1)->get();
    
                foreach ($countries as $data) {
                    $newCode = $baseCurrency . $data->code;
    
                    if (isset($getRateCollect[$newCode])) {
                        $data->rate = @$getRateCollect[$newCode];
                        $data->update();
    
                        if (config('basic.ad_rate_update') == 1) {
                            $ads = Advertise::with(['cryptoCurrency'])->where('fiat_id', $data->id)
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
    
                $response = Http::get("http://api.currencylayer.com/live", [
                    'access_key' => $accessKey,
                    'source' => 'USD',
                    'currencies' => $baseCurrency,
                ]);
    
                $result = $response->json();
                $getRateCollectNew = collect($result['quotes'])->toArray();
                $newCode2 = 'USD' . $baseCurrency;
    
                if (isset($getRateCollectNew[$newCode2])) {
                    $base_currency_rate = 1 / $getRateCollectNew[$newCode2] ?? 1;
                    config(['basic.base_currency_rate' => $base_currency_rate]);
                    $fp = fopen(base_path() . '/config/basic.php', 'w');
                    fwrite($fp, '<?php return ' . var_export(config('basic'), true) . ';');
                    fclose($fp);
                    $configure->base_currency_rate = $base_currency_rate;
                    $configure->save();
    
                    $currencyBaseCheck = Currency::select('id', 'code', 'rate')
                        ->where('code', $baseCurrency)
                        ->where('flag', 0)
                        ->where('status', 1)
                        ->get();
    
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
