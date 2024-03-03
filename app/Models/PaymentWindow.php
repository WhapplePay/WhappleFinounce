<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaymentWindow extends Model
{
    use HasFactory, SoftDeletes;

    public function getNameAttribute($value)
    {
        return $value . ' Minutes';
    }

    public function advertises()
    {
        return $this->hasMany(Advertisment::class, 'payment_window_id');
    }
}
