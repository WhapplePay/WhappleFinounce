<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhappleFinounce extends Model
{
    use HasFactory;


    protected $table = 'whapplefinounce';

   protected $fillable = ([
    'user_id',
    'status'

    ]);

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
