<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['date_formatted'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
    }

    public function getDateFormattedAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

}
