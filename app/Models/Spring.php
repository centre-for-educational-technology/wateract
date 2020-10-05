<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spring extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'kkr_code', 'latitude', 'longitude', 'county', 'municipality', 'description', 'folklore'
    ];
}
