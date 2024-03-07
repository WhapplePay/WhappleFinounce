<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['currency_rate', 'gateways'];
    protected $casts = [
        'gateway_id' => 'object'
    ];

    public function fiatCurrency()
    {
        return $this->belongsTo(Currency::class, 'fiat_id', 'id')->where('flag', 0);
    }

    public function cryptoCurrency()
    {
        return $this->belongsTo(Currency::class, 'crypto_id', 'id')->where('flag', 1);
    }

    public function paymentWindow()
    {
        return $this->belongsTo(PaymentWindow::class, 'payment_window_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getCurrencyRateAttribute()
    {
        $fiatCode = optional($this->fiatCurrency)->code;
        $cryptoCode = optional($this->cryptoCurrency)->code;
        return $fiatCode . '/' . $cryptoCode;
    }

    public function getGatewaysAttribute()
    {
        if ($this->gateway_id) {
            return Gateway::whereIn('id', $this->gateway_id)->get();
        }
        return 0;
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'advertisement_id', 'id')->orderBy('id', 'desc');
    }
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'advertisement_id', 'id')->where('creator_id', );
    }


    public function userfeedbacks()
    {
        return $this->hasOne(Feedback::class, 'advertisement_id', 'id')->where('reviewer_id', auth()->id());
    }

    public function like()
    {
        return $this->hasMany(Feedback::class, 'advertisement_id')->where('position', 'like');
    }

    public function dislike()
    {
        return $this->hasMany(Feedback::class, 'advertisement_id')->where('position', 'dislike');
    }

}
