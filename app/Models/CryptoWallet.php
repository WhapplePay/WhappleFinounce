<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoWallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'crypto_wallets';

    public function crypto()
    {
        return $this->belongsTo(Currency::class, 'crypto_currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
