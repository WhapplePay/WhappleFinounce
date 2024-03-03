<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'form_field' =>'object',
        'receiver_form' =>'object'
    ];

    public function getStatusMessageAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-light">
            <i class="fa fa-circle text-danger danger font-12"></i> '. trans('Deactive') . '</span>';
        }
        return '<span class="badge badge-light">
        <i class="fa fa-circle text-success success font-12"></i> '. trans('Active') . '</span>';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function advertisesCrypto()
    {
       return $this->hasMany(Advertisment::class,'crypto_id');
    }

    public function advertisesFiat()
    {
        return $this->hasMany(Advertisment::class,'fiat_id');
    }
}
