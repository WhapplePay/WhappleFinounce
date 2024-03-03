<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $fillable = ['name', 'image', 'status','color','input_form'];
    protected $casts = [
        'currencies' => 'object',
        'input_form' => 'object',
    ];

}
