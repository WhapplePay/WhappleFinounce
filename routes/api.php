<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    AdvertismentController,
    BuyCurrenciesController,
    ChatNotificationController,
    PaymentLogController,
    RegisterController,
    SellCurrenciesController,
    SupportController,
    TradeController,
    VerificationController,
    WalletController,
    WebhookController,
    HomeController,
    LoginController

};
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('webhook/coinpayment', 'Api\WebhookController@webhookResponse')->name('callback');

Route::group(['prefix' => 'coin'], function () {

    Route::post('/register',[RegisterController::class, 'register']);
    Route::get('/wallets', [WalletController::class, 'getUserCryptoWallet']); //just get the crypto data
    Route::get('/user-wallets', [WalletController::class, 'wallets']);//get the user wallet id with crypto details
    Route::post('/wallet/generate', [WalletController::class, 'walletGenerate']);
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/running-trades', [TradeController::class, 'runningTrades']);//navigate to the details page with the token!!
    Route::get('/completed-trades', [TradeController::class, 'completedTrades']);
    Route::get('/fund-history', [HomeController::class, 'fundHistory']);
    Route::get('/user-transactions', [HomeController::class, 'transaction']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/buy-currencies/buy-trade-request', [BuyCurrenciesController::class, 'buyTradeRqst']); //able to work when a user click the buy button
    Route::post('/buy-currencies/send-trade-request', [BuyCurrenciesController::class, 'sendTradeRqst']); // when user has filled the form and submited
    Route::get('/buy-currencies/trade-details/{hash_slug}', [BuyCurrenciesController::class, 'tradeDetails']);


    Route::prefix('advertisements')->group(function () {
        Route::post('/advertisements', [AdvertismentController::class, 'create']); //create an advert
        Route::get('/advertisements', [AdvertismentController::class, 'getAllAdvertisements']);
        Route::get('/advertisements/user', [AdvertismentController::class, 'getUserAdvertisements']);    
        Route::put('/{id}', [AdvertismentController::class, 'edit']);
        Route::patch('/{id}', [AdvertismentController::class, 'update']);
        Route::patch('/{id}/enable', [AdvertismentController::class, 'enable']);
        Route::patch('/{id}/disable', [AdvertismentController::class, 'disable']);
        Route::post('/feedback', [AdvertismentController::class, 'feedback']);
        Route::get('/{adId}/feedback/list', [AdvertismentController::class, 'feedbackList']);
    });
   
  
});