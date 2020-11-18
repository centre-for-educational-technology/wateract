<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spring extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'code', 'kkr_code', 'latitude', 'longitude', 'country', 'county', 'settlement', 'description', 'folklore', 'classification', 'groundwater_body', 'geology', 'ownership', 'status', 'needs_attention', 'featured'
    ];

    public function references()
    {
        return $this->hasMany('App\Models\SpringReference');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\Photo');
    }

    public function database_links()
    {
        return $this->hasMany('App\Models\SpringDatabaseLink');
    }

    public function observations()
    {
        return $this->hasMany('App\Models\Observation');
    }

    public function measurements()
    {
        return $this->hasMany('App\Models\Measurement');
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

}
