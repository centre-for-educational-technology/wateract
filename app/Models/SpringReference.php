<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpringReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'spring_id', 'url', 'url_title'
    ];

    public function spring()
    {
        return $this->belongsTo('App\Models\Spring');
    }
}
