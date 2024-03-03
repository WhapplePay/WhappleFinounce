<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $guarded = ['id'];
    protected $table = "funds";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function crypto()
    {
        return $this->belongsTo(Currency::class, 'crypto_currency_id');
    }
}
