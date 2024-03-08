<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    $output = new \Symfony\Component\Console\Output\BufferedOutput();
    Artisan::call('optimize:clear', array(), $output);
    return $output->fetch();
})->name('/clear');

Route::get('/user', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/loginModal', 'Auth\LoginController@loginModal')->name('loginModal');

Route::get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::get('cron', function () {
    return Illuminate\Support\Facades\Artisan::call('schedule:run');
})->name('cron');


Auth::routes(['verify' => true]);

Route::group(['middleware' => ['guest']], function () {
    Route::get('register/{sponsor?}', 'Auth\RegisterController@sponsor')->name('register.sponsor');
});

Route::group(['middleware' => ['auth'   ], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/check', 'User\VerificationController@check')->name('check');
    Route::get('/resend_code', 'User\VerificationController@resendCode')->name('resendCode');
    Route::post('/mail-verify', 'User\VerificationController@mailVerify')->name('mailVerify');
    Route::post('/sms-verify', 'User\VerificationController@smsVerify')->name('smsVerify');
    Route::post('twoFA-Verify', 'User\VerificationController@twoFAverify')->name('twoFA-Verify');
    Route::middleware('userCheck')->group(function () {

        Route::get('/dashboard', 'User\HomeController@index')->name('home');
        Route::post('/save-token', 'User\HomeController@saveToken')->name('save.token');

        Route::get('/public/profile/{id}', 'User\ProfileController@page')->name('profile.page');

        Route::get('/settings', 'User\SettingController@index')->name('list.setting');
        Route::get('/settings/notify', 'User\SettingController@settingNotify')->name('list.setting.notify');
        Route::put('/settings/notify', 'User\SettingController@settingNotifyUpdate')->name('update.setting.notify');

        Route::get('/settings/notify/active/{id}', 'User\SettingController@settingNotifyActive')->name('list.setting.notify.active');
        Route::get('/settings/notify/inactive/{id}', 'User\SettingController@settingNotifyInactive')->name('list.setting.notify.inactive');

        // //Advertisements
        // Route::get('/advertisments', 'User\AdvertismentsController@index')->name('advertisements.list');
        // Route::get('/advertisments/create', 'User\AdvertismentsController@create')->name('advertisements.create');
        // Route::post('/advertisments/store', 'User\AdvertismentsController@store')->name('advertisements.store');
        // Route::get('/advertisments/edit/{id}', 'User\AdvertismentsController@edit')->name('advertisements.edit');
        // Route::post('/advertisments/update/{id}', 'User\AdvertismentsController@update')->name('advertisements.update');
        // Route::post('/advertisments/enable/{id}', 'User\AdvertismentsController@enable')->name('advertisements.enable');
        // Route::post('/advertisments/disable/{id}', 'User\AdvertismentsController@disable')->name('advertisements.disable');

        // Route::post('/advertisments/getFiat', 'User\AdvertismentsController@getFiat')->name('getFiat');

        //Feedback
        Route::post('/feedback', 'User\AdvertismentsController@feedback')->name('advertisements.feedback');
        Route::get('/feedback/{id}', 'User\AdvertismentsController@feedbackList')->name('feedback.list');

        // //Buy Currencies
        // Route::get('/buy/{currencyCode?}/{currencyId?}', 'User\BuyCurrenciesController@index')->name('buyCurrencies.list');
        // Route::get('/buy/trade/request/{advertiseId}', 'User\BuyCurrenciesController@buyTradeRqst')->name('buyCurrencies.tradeRqst');
        // Route::post('/buy/trade-request/send', 'User\BuyCurrenciesController@sendTradeRqst')->name('buyCurrencies.trade.send');
        // Route::get('/trade/details/{hash_slug}', 'User\BuyCurrenciesController@tradeDetails')->name('buyCurrencies.tradeDetails');

        // //Sell Currencies
        // Route::get('/sell/{currencyCode?}/{currencyId?}', 'User\SellCurrenciesController@index')->name('sellCurrencies.list');
        // Route::get('/sell/trade/request/{advertiseId}', 'User\SellCurrenciesController@sellTradeRqst')->name('sellCurrencies.tradeRqst');
        // Route::post('/sell/trade-request/send', 'User\SellCurrenciesController@sendTradeRqst')->name('sellCurrencies.trade.send');
        // Route::post('/sell/trade-request/fetch-info', 'User\SellCurrenciesController@fetchPaymentInfo')->name('sellCurrencies.FetchPaymentInfo');

        // //Credentials Set
        // Route::get('/sell/gateway/add/{adsId}', 'User\SellCurrenciesController@gatewayInfo')->name('sellCurrencies.gatewayInfo');
        // Route::post('/sell/gateway/save', 'User\SellCurrenciesController@gatewayInfoSave')->name('sellCurrencies.gatewayInfoSave');
        // Route::post('/sell/gateway/update/{id}', 'User\SellCurrenciesController@gatewayInfoUpdate')->name('sellCurrencies.gatewayInfoUpdate');
        // Route::post('/sell/gateway/select', 'User\SellCurrenciesController@gatewaySelect')->name('sellCurrencies.gatewaySelect');

        // //chat
        // Route::get('push-chat-show/{hashSlug}', 'ChatNotificationController@show')->name('push.chat.show');
        // Route::post('push-chat-newMessage', 'ChatNotificationController@newMessage')->name('push.chat.newMessage');


        //Trade List
        Route::get('/trade/list/{stage?}', 'User\TradeController@index')->name('trade.list');
        Route::post('/trade/cancel', 'User\TradeController@cancelTrade')->name('trade.cancel');
        Route::post('/trade/paid', 'User\TradeController@paidTrade')->name('trade.paid');
        Route::post('/trade/release', 'User\TradeController@releaseTrade')->name('trade.release');
        Route::post('/trade/dispute', 'User\TradeController@disputeTrade')->name('trade.dispute');

        //Wallet
        Route::get('/wallet/{crypto_id?}', 'User\WalletController@index')->name('wallet.list');
        // Route::post('/wallet/generate', 'User\WalletController@walletGenerate')->name('wallet.generate');


        Route::get('/identity/verification', 'User\HomeController@identityVerify')->name('identityVerify');
        Route::post('/verificationSubmit', 'User\HomeController@verificationSubmit')->name('verificationSubmit');

        // //transaction
        // Route::get('/transaction/{code?}', 'User\HomeController@transaction')->name('transaction');
        // Route::get('/transaction-search', 'User\HomeController@transactionSearch')->name('transaction.search');
        // Route::get('fund-history', 'User\HomeController@fundHistory')->name('fund-history');
        // Route::get('fund-history-search', 'User\HomeController@fundHistorySearch')->name('fund-history.search');


        // TWO-FACTOR SECURITY
        Route::get('/twostep-security', 'User\HomeController@twoStepSecurity')->name('twostep.security');
        Route::post('twoStep-enable', 'User\HomeController@twoStepEnable')->name('twoStepEnable');
        Route::post('twoStep-disable', 'User\HomeController@twoStepDisable')->name('twoStepDisable');


        Route::get('push-notification-show', 'SiteNotificationController@show')->name('push.notification.show');
        Route::get('push.notification.readAll', 'SiteNotificationController@readAll')->name('push.notification.readAll');
        Route::get('push-notification-readAt/{id}', 'SiteNotificationController@readAt')->name('push.notification.readAt');
        
        //Deposit History
        Route::get('deposit-history', 'User\HomeController@depositHistory')->name('deposit.history');

        Route::get('payout-history', 'User\HomeController@payoutHistory')->name('payout.history');
        Route::get('payout-history-search', 'User\HomeController@payoutHistorySearch')->name('payout.history.search');


        Route::get('/profile', 'User\HomeController@profile')->name('profile');
        Route::post('/updateProfile', 'User\HomeController@updateProfile')->name('updateProfile');
        Route::post('/updateInformation', 'User\HomeController@updateInformation')->name('updateInformation');
        Route::get('/setting/password', 'User\HomeController@password')->name('password');
        Route::post('/setting/updatePassword', 'User\HomeController@updatePassword')->name('updatePassword');

        Route::post('/verificationSubmit', 'User\HomeController@verificationSubmit')->name('verificationSubmit');
        Route::post('/addressVerification', 'User\HomeController@addressVerification')->name('addressVerification');


        Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
            Route::get('/', 'User\SupportController@index')->name('list');
            Route::get('/create', 'User\SupportController@create')->name('create');
            Route::post('/create', 'User\SupportController@store')->name('store');
            Route::get('/view/{ticket}', 'User\SupportController@ticketView')->name('view');
            Route::put('/reply/{ticket}', 'User\SupportController@reply')->name('reply');
            Route::get('/download/{ticket}', 'User\SupportController@download')->name('download');
        });

        Route::middleware('kyc')->group(function () {


            Route::get('/payout', 'User\HomeController@payoutMoney')->name('payout.money');
            Route::post('/payout', 'User\HomeController@payoutMoneyRequest')->name('payout.moneyRequest');
    
               //transaction
        Route::get('/transaction/{code?}', 'User\HomeController@transaction')->name('transaction');
        Route::get('/transaction-search', 'User\HomeController@transactionSearch')->name('transaction.search');
        Route::get('fund-history', 'User\HomeController@fundHistory')->name('fund-history');
        Route::get('fund-history-search', 'User\HomeController@fundHistorySearch')->name('fund-history.search');
        Route::post('/wallet/generate', 'User\WalletController@walletGenerate')->name('wallet.generate');
   //Buy Currencies
   Route::get('/buy/{currencyCode?}/{currencyId?}', 'User\BuyCurrenciesController@index')->name('buyCurrencies.list');
   Route::get('/buy/trade/request/{advertiseId}', 'User\BuyCurrenciesController@buyTradeRqst')->name('buyCurrencies.tradeRqst');
   Route::post('/buy/trade-request/send', 'User\BuyCurrenciesController@sendTradeRqst')->name('buyCurrencies.trade.send');
   Route::get('/trade/details/{hash_slug}', 'User\BuyCurrenciesController@tradeDetails')->name('buyCurrencies.tradeDetails');

   //Sell Currencies
   Route::get('/sell/{currencyCode?}/{currencyId?}', 'User\SellCurrenciesController@index')->name('sellCurrencies.list');
   Route::get('/sell/trade/request/{advertiseId}', 'User\SellCurrenciesController@sellTradeRqst')->name('sellCurrencies.tradeRqst');
   Route::post('/sell/trade-request/send', 'User\SellCurrenciesController@sendTradeRqst')->name('sellCurrencies.trade.send');
   Route::post('/sell/trade-request/fetch-info', 'User\SellCurrenciesController@fetchPaymentInfo')->name('sellCurrencies.FetchPaymentInfo');

   //Credentials Set
   Route::get('/sell/gateway/add/{adsId}', 'User\SellCurrenciesController@gatewayInfo')->name('sellCurrencies.gatewayInfo');
   Route::post('/sell/gateway/save', 'User\SellCurrenciesController@gatewayInfoSave')->name('sellCurrencies.gatewayInfoSave');
   Route::post('/sell/gateway/update/{id}', 'User\SellCurrenciesController@gatewayInfoUpdate')->name('sellCurrencies.gatewayInfoUpdate');
   Route::post('/sell/gateway/select', 'User\SellCurrenciesController@gatewaySelect')->name('sellCurrencies.gatewaySelect');

   //chat
   Route::get('push-chat-show/{hashSlug}', 'ChatNotificationController@show')->name('push.chat.show');
   Route::post('push-chat-newMessage', 'ChatNotificationController@newMessage')->name('push.chat.newMessage');

   //Advertisements
   Route::get('/advertisments', 'User\AdvertismentsController@index')->name('advertisements.list');
   Route::get('/advertisments/create', 'User\AdvertismentsController@create')->name('advertisements.create');
   Route::post('/advertisments/store', 'User\AdvertismentsController@store')->name('advertisements.store');
   Route::get('/advertisments/edit/{id}', 'User\AdvertismentsController@edit')->name('advertisements.edit');
   Route::post('/advertisments/update/{id}', 'User\AdvertismentsController@update')->name('advertisements.update');
   Route::post('/advertisments/enable/{id}', 'User\AdvertismentsController@enable')->name('advertisements.enable');
   Route::post('/advertisments/disable/{id}', 'User\AdvertismentsController@disable')->name('advertisements.disable');

   Route::post('/advertisments/getFiat', 'User\AdvertismentsController@getFiat')->name('getFiat');

        });
    });
});


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', 'Admin\LoginController@showLoginForm')->name('login');
    Route::post('/', 'Admin\LoginController@login')->name('login');
    Route::post('/logout', 'Admin\LoginController@logout')->name('logout');

    Route::get('/password/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset', 'Admin\Auth\ResetPasswordController@reset')->name('password.update');


    Route::get('/403', 'Admin\DashboardController@forbidden')->name('403');

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('dashboard');
        Route::post('/save-token', 'Admin\DashboardController@saveToken')->name('save.token');


        //Rate Update
        Route::post('crypto-rate', 'Admin\CurrencyController@cryptoRate')->name('cryptoRate');
        Route::post('fiat-rate', 'Admin\CurrencyController@fiatRate')->name('fiatRate');


        //Crypto Currencies
        Route::get('crypto/list', 'Admin\CurrencyController@listCrypto')->name('listCrypto');
        Route::get('crypto/create', 'Admin\CurrencyController@createCrypto')->name('createCrypto');
        Route::post('crypto/store', 'Admin\CurrencyController@storeCrypto')->name('storeCrypto');
        Route::get('crypto/edit/{id}', 'Admin\CurrencyController@editCrypto')->name('editCrypto');
        Route::post('crypto/update/{id}', 'Admin\CurrencyController@updateCrypto')->name('updateCrypto');
        Route::delete('crypto/delete/{id}', 'Admin\CurrencyController@deleteCrypto')->name('deleteCrypto');

        Route::post('crypto-active', 'Admin\CurrencyController@activeMultiple')->name('crypto-active');
        Route::post('crypto-deactive', 'Admin\CurrencyController@deActiveMultiple')->name('crypto-deactive');
        Route::post('crypto/control/Action', 'Admin\CurrencyController@cryptoControlAction')->name('cryptoControl.action');

        //Fiat Currencies
        Route::get('fiat/list', 'Admin\CurrencyController@listFiat')->name('listFiat');
        Route::get('fiat/create', 'Admin\CurrencyController@createFiat')->name('createFiat');
        Route::post('fiat/store', 'Admin\CurrencyController@storeFiat')->name('storeFiat');
        Route::get('fiat/edit/{id}', 'Admin\CurrencyController@editFiat')->name('editFiat');
        Route::post('fiat/update/{id}', 'Admin\CurrencyController@updateFiat')->name('updateFiat');
        Route::delete('fiat/delete/{id}', 'Admin\CurrencyController@deleteFiat')->name('deleteFiat');

        Route::post('fiat-active', 'Admin\CurrencyController@activeMultipleFiat')->name('fiat-active');
        Route::post('fiat-deactive', 'Admin\CurrencyController@deActiveMultipleFiat')->name('fiat-deactive');
        Route::post('fiat/control/Action', 'Admin\CurrencyController@fiatControlAction')->name('fiatControl.action');

        //Advertisement
        Route::get('advertise/{status?}', 'Admin\AdvertiseController@advertiseList')->name('advertise.list');
        Route::post('advertise/enable/{id}', 'Admin\AdvertiseController@enable')->name('advertise.enable');
        Route::post('advertise/disable/{id}', 'Admin\AdvertiseController@disable')->name('advertise.disable');

        //Feedbacks
        Route::get('advertise/feedback/list/{adId?}', 'Admin\AdvertiseController@feedbackList')->name('feedback.list');
        Route::delete('advertise/feedback/{id}', 'Admin\AdvertiseController@feedbackDelete')->name('feedback.Delete');

        //Trade
        Route::get('trade/{status?}', 'Admin\TradeController@tradeList')->name('trade.list');
        Route::get('trade/details/{hashSlug}', 'Admin\TradeController@tradeDetails')->name('trade.Details');
        Route::post('trade/return/{hashSlug}', 'Admin\TradeController@returnTrade')->name('trade.return');
        Route::post('trade/release/{hashSlug}', 'Admin\TradeController@releaseTrade')->name('trade.release');

        Route::get('push-chat-show/{uuId}', 'ChatNotificationController@showByAdmin')->name('push.chat.show');
        Route::post('push-chat-newMessage', 'ChatNotificationController@newMessageByAdmin')->name('push.chat.newMessage');


        //Payment Windows
        Route::get('payment/window', 'Admin\PaymentWindowController@list')->name('payment.windows');
        Route::post('payment/store', 'Admin\PaymentWindowController@store')->name('payment.windows.store');
        Route::post('payment/update/{id}', 'Admin\PaymentWindowController@update')->name('payment.windows.update');
        Route::delete('payment/delete/{id}', 'Admin\PaymentWindowController@delete')->name('payment.windows.delete');


        Route::get('/profile', 'Admin\DashboardController@profile')->name('profile');
        Route::put('/profile', 'Admin\DashboardController@profileUpdate')->name('profileUpdate');
        Route::get('/password', 'Admin\DashboardController@password')->name('password');
        Route::put('/password', 'Admin\DashboardController@passwordUpdate')->name('passwordUpdate');

        Route::get('/identity-form', 'Admin\IdentyVerifyFromController@index')->name('identify-form');
        Route::post('/identity-form', 'Admin\IdentyVerifyFromController@store')->name('identify-form.store');
        Route::post('/identity-form/action', 'Admin\IdentyVerifyFromController@action')->name('identify-form.action');


        /* ====== Transaction Log =====*/
        Route::get('/transaction', 'Admin\LogController@transaction')->name('transaction');
        Route::get('/transaction-search', 'Admin\LogController@transactionSearch')->name('transaction.search');


        /*====Manage Users ====*/
        Route::get('/users', 'Admin\UsersController@index')->name('users');
        Route::post('/user/login', 'Admin\UsersController@userLogin')->name('userLogin');
        Route::get('/users/search', 'Admin\UsersController@search')->name('users.search');
        Route::post('/users-active', 'Admin\UsersController@activeMultiple')->name('user-multiple-active');
        Route::post('/users-inactive', 'Admin\UsersController@inactiveMultiple')->name('user-multiple-inactive');
        Route::get('/user/edit/{id}', 'Admin\UsersController@userEdit')->name('user-edit');
        Route::post('/user/update/{id}', 'Admin\UsersController@userUpdate')->name('user-update');
        Route::post('/user/password/{id}', 'Admin\UsersController@passwordUpdate')->name('userPasswordUpdate');
        Route::post('/user/balance-update/{id}', 'Admin\UsersController@userBalanceUpdate')->name('user-balance-update');

        Route::get('/user/send-email/{id}', 'Admin\UsersController@sendEmail')->name('send-email');
        Route::post('/user/send-email/{id}', 'Admin\UsersController@sendMailUser')->name('user.email-send');
        Route::get('/user/transaction/{id}', 'Admin\UsersController@transaction')->name('user.transaction');
        Route::get('/user/fundLog/{id}', 'Admin\UsersController@funds')->name('user.fundLog');
        Route::get('/user/payoutLog/{id}', 'Admin\UsersController@payoutLog')->name('user.withdrawal');
        Route::get('/user/referralMember/{id}', 'Admin\UsersController@referralMember')->name('user.referralMember');

        Route::get('users/kyc/pending', 'Admin\UsersController@kycPendingList')->name('kyc.users.pending');
        Route::get('users/kyc', 'Admin\UsersController@kycList')->name('kyc.users');
        Route::put('users/kycAction/{id}', 'Admin\UsersController@kycAction')->name('users.Kyc.action');
        Route::get('user/{user}/kyc', 'Admin\UsersController@userKycHistory')->name('user.userKycHistory');

        Route::get('/email-send', 'Admin\UsersController@emailToUsers')->name('email-send');
        Route::post('/email-send', 'Admin\UsersController@sendEmailToUsers')->name('email-send.store');


        /*=====Payment Log=====*/
        Route::get('payment-methods', 'Admin\PaymentMethodController@index')->name('payment.methods');
        Route::get('payment-methods/create', 'Admin\PaymentMethodController@create')->name('create.payment.methods');
        Route::post('payment-methods/store', 'Admin\PaymentMethodController@store')->name('store.payment.methods');
        Route::post('payment-methods/deactivate', 'Admin\PaymentMethodController@deactivate')->name('payment.methods.deactivate');
        Route::get('payment-methods/deactivate', 'Admin\PaymentMethodController@deactivate')->name('payment.methods.deactivate');
        Route::get('payment-methods/edit/{id}', 'Admin\PaymentMethodController@edit')->name('edit.payment.methods');
        Route::put('payment-methods/update/{id}', 'Admin\PaymentMethodController@update')->name('update.payment.methods');


        Route::get('payment/log/{id?}', 'Admin\PaymentLogController@index')->name('payment.log');
        Route::get('payment/search', 'Admin\PaymentLogController@search')->name('payment.search');


        /*==========Payout Method============*/
        Route::get('/payout-method', 'Admin\PayoutRecordController@methodList')->name('payout.method');
        Route::post('/payout-method/status/{id}', 'Admin\PayoutRecordController@methodStatus')->name('payout.methodStatus');
        Route::any('/payout-method/edit/{id}', 'Admin\PayoutRecordController@methodEdit')->name('payout.method.edit');

        /*==========Payout Settings============*/
        Route::get('/payout-log/{id?}', 'Admin\PayoutRecordController@index')->name('payout-log');
        Route::get('/payout-log/search', 'Admin\PayoutRecordController@search')->name('payout-log.search');
        Route::get('/payout-request', 'Admin\PayoutRecordController@request')->name('payout-request');
        Route::put('/payout-action/{id}', 'Admin\PayoutRecordController@action')->name('payout-action');


        /* ===== Support Ticket ====*/
        Route::get('tickets/{status?}', 'Admin\TicketController@tickets')->name('ticket');
        Route::get('tickets/view/{id}', 'Admin\TicketController@ticketReply')->name('ticket.view');
        Route::put('ticket/reply/{id}', 'Admin\TicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'Admin\TicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'Admin\TicketController@ticketDelete')->name('ticket.delete');

        /* ===== Subscriber =====*/
        Route::get('subscriber', 'Admin\SubscriberController@index')->name('subscriber.index');
        Route::post('subscriber/remove', 'Admin\SubscriberController@remove')->name('subscriber.remove');
        Route::get('subscriber/send-email', 'Admin\SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/send-email', 'Admin\SubscriberController@sendEmail')->name('subscriber.mail');


        /* ======== Plugin =======*/
        Route::get('/plugin-config', 'Admin\ControlController@pluginConfig')->name('plugin.config');
        Route::match(['get', 'post'], 'tawk-config', 'Admin\ControlController@tawkConfig')->name('tawk.control');
        Route::match(['get', 'post'], 'fb-messenger-config', 'Admin\ControlController@fbMessengerConfig')->name('fb.messenger.control');
        Route::match(['get', 'post'], 'google-recaptcha', 'Admin\ControlController@googleRecaptchaConfig')->name('google.recaptcha.control');
        Route::match(['get', 'post'], 'google-analytics', 'Admin\ControlController@googleAnalyticsConfig')->name('google.analytics.control');


        /* ===== website controls =====*/
        Route::any('/basic-controls', 'Admin\BasicController@index')->name('basic-controls');
        Route::post('/basic-controls', 'Admin\BasicController@updateConfigure')->name('basic-controls.update');

        //Api setting
        Route::any('/api-setting', 'Admin\BasicController@api')->name('api-setting');
        Route::post('/api-setting', 'Admin\BasicController@updateApi')->name('api-setting.update');

        Route::any('/email-controls', 'Admin\EmailTemplateController@emailControl')->name('email-controls');
        Route::post('/email-controls', 'Admin\EmailTemplateController@emailConfigure')->name('email-controls.update');
        Route::post('/email-controls/action', 'Admin\EmailTemplateController@emailControlAction')->name('email-controls.action');
        Route::post('/email-controls/test', 'Admin\EmailTemplateController@testEmail')->name('testEmail');

        Route::get('/email-template', 'Admin\EmailTemplateController@show')->name('email-template.show');
        Route::get('/email-template/edit/{id}', 'Admin\EmailTemplateController@edit')->name('email-template.edit');
        Route::post('/email-template/update/{id}', 'Admin\EmailTemplateController@update')->name('email-template.update');

        /*========Sms control ========*/
        Route::match(['get', 'post'], '/sms-controls', 'Admin\SmsTemplateController@smsConfig')->name('sms.config');
        Route::post('/sms-controls/action', 'Admin\SmsTemplateController@smsControlAction')->name('sms-controls.action');
        Route::get('/sms-template', 'Admin\SmsTemplateController@show')->name('sms-template');
        Route::get('/sms-template/edit/{id}', 'Admin\SmsTemplateController@edit')->name('sms-template.edit');
        Route::post('/sms-template/update/{id}', 'Admin\SmsTemplateController@update')->name('sms-template.update');

        Route::get('/notify-config', 'Admin\NotifyController@notifyConfig')->name('notify-config');
        Route::post('/notify-config', 'Admin\NotifyController@notifyConfigUpdate')->name('notify-config.update');
        Route::get('/notify-template', 'Admin\NotifyController@show')->name('notify-template.show');
        Route::get('/notify-template/edit/{id}', 'Admin\NotifyController@edit')->name('notify-template.edit');
        Route::post('/notify-template/update/{id}', 'Admin\NotifyController@update')->name('notify-template.update');

        Route::get('/push/notify-config', 'Admin\PushNotifyController@notifyConfig')->name('push.notify-config');
        Route::post('/push/notify-config', 'Admin\PushNotifyController@notifyConfigUpdate')->name('push.notify-config.update');
        Route::get('/push/notify-template', 'Admin\PushNotifyController@show')->name('push.notify-template.show');
        Route::get('/push/notify-template/edit/{id}', 'Admin\PushNotifyController@edit')->name('push.notify-template.edit');
        Route::post('/push/notify-template/update/{id}', 'Admin\PushNotifyController@update')->name('push.notify-template.update');


        /* ===== ADMIN Language SETTINGS ===== */
        Route::get('language', 'Admin\LanguageController@index')->name('language.index');
        Route::get('language/create', 'Admin\LanguageController@create')->name('language.create');
        Route::post('language/create', 'Admin\LanguageController@store')->name('language.store');
        Route::get('language/{language}', 'Admin\LanguageController@edit')->name('language.edit');
        Route::put('language/{language}', 'Admin\LanguageController@update')->name('language.update');
        Route::delete('language/{language}', 'Admin\LanguageController@delete')->name('language.delete');
        Route::get('/language/keyword/{id}', 'Admin\LanguageController@keywordEdit')->name('language.keywordEdit');
        Route::put('/language/keyword/{id}', 'Admin\LanguageController@keywordUpdate')->name('language.keywordUpdate');
        Route::post('/language/importJson', 'Admin\LanguageController@importJson')->name('language.importJson');
        Route::post('store-key/{id}', 'Admin\LanguageController@storeKey')->name('language.storeKey');
        Route::put('update-key/{id}', 'Admin\LanguageController@updateKey')->name('language.updateKey');
        Route::delete('delete-key/{id}', 'Admin\LanguageController@deleteKey')->name('language.deleteKey');


        Route::get('/logo-seo', 'Admin\BasicController@logoSeo')->name('logo-seo');
        Route::put('/logoUpdate', 'Admin\BasicController@logoUpdate')->name('logoUpdate');
        Route::put('/seoUpdate', 'Admin\BasicController@seoUpdate')->name('seoUpdate');
        Route::get('/breadcrumb', 'Admin\BasicController@breadcrumb')->name('breadcrumb');
        Route::put('/breadcrumb', 'Admin\BasicController@breadcrumbUpdate')->name('breadcrumbUpdate');


        /* ===== ADMIN TEMPLATE SETTINGS ===== */
        Route::get('template/{section}', 'Admin\TemplateController@show')->name('template.show');
        Route::put('template/{section}/{language}', 'Admin\TemplateController@update')->name('template.update');
        Route::get('contents/{content}', 'Admin\ContentController@index')->name('content.index');
        Route::get('content-create/{content}', 'Admin\ContentController@create')->name('content.create');
        Route::put('content-create/{content}/{language?}', 'Admin\ContentController@store')->name('content.store');
        Route::get('content-show/{content}/{name?}', 'Admin\ContentController@show')->name('content.show');
        Route::put('content-update/{content}/{language?}', 'Admin\ContentController@update')->name('content.update');
        Route::delete('contents/{id}', 'Admin\ContentController@contentDelete')->name('content.delete');


        Route::get('push-notification-show', 'SiteNotificationController@showByAdmin')->name('push.notification.show');
        Route::get('push.notification.readAll', 'SiteNotificationController@readAllByAdmin')->name('push.notification.readAll');
        Route::get('push-notification-readAt/{id}', 'SiteNotificationController@readAt')->name('push.notification.readAt');
        Route::match(['get', 'post'], 'pusher-config', 'SiteNotificationController@pusherConfig')->name('pusher.config');
    });


});


Route::get('/language/{code?}', 'FrontendController@language')->name('language');


Route::get('/blog-details/{slug}/{id}', 'FrontendController@blogDetails')->name('blogDetails');
Route::get('/blog', 'FrontendController@blog')->name('blog');

Route::get('/', 'FrontendController@index')->name('home');
Route::get('/about', 'FrontendController@about')->name('about');
Route::get('/faq', 'FrontendController@faq')->name('faq');

Route::get('/contact', 'FrontendController@contact')->name('contact');
Route::post('/contact', 'FrontendController@contactSend')->name('contact.send');
Route::get('/buy/{gatewayId?}', 'FrontendController@buy')->name('buy');
Route::get('/sell/{gatewayId?}', 'FrontendController@sell')->name('sell');

Route::post('/subscribe', 'FrontendController@subscribe')->name('subscribe');

Route::get('/{getLink}/{content_id}', 'FrontendController@getLink')->name('getLink');




