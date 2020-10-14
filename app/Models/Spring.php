<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spring extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'code', 'kkr_code', 'latitude', 'longitude', 'county', 'settlement', 'description', 'folklore', 'classification', 'groundwater_body', 'geology', 'ownership', 'status'
    ];

    public function references()
    {
        return $this->hasMany('App\Models\SpringReference');
    }

    public function observationData()
    {
        return $this->hasMany('App\Models\SpringObservationData');
    }


}
