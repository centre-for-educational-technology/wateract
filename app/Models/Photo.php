<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'spring_id', 'observation_id', 'path', 'thumbnail', 'caption'
    ];

    public function spring()
    {
        return $this->belongsTo('App\Models\Spring');
    }

    public function observation()
    {
        return $this->belongsTo('App\Models\Observation');
    }
}
