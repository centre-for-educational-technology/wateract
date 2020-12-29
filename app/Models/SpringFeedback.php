<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpringFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'spring_id', 'user_id', 'spring_name', 'latitude', 'longitude', 'feedback'
    ];

    public function spring()
    {
        return $this->belongsTo('App\Models\Spring');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
