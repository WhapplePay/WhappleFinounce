<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPaymentInfo extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'information' => 'object'
    ];

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }
}
