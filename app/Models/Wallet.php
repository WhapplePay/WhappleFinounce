<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function crypto()
    {
        return $this->belongsTo(Currency::class, 'crypto_currency_id')->where('flag', 1);
    }

}
