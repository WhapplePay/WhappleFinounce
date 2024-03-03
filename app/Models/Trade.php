<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['gateways'];
    protected $casts = [
        'payment_method' => 'object'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function disputeBy()
    {
        return $this->belongsTo(User::class, 'dispute_by', 'id');
    }

    public function cancelBy()
    {
        return $this->belongsTo(User::class, 'cancel_by', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function receiverCurrency()
    {
        return $this->belongsTo(Currency::class, 'receiver_currency_id', 'id');
    }

    public function getGatewaysAttribute()
    {
        if ($this->payment_method) {
            return Gateway::whereIn('id', $this->payment_method)->get();
        }
        return 0;
    }

    public function advertise()
    {
        return $this->belongsTo(Advertisment::class, 'advertise_id', 'id');
    }

}
