<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spring extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'code', 'kkr_code', 'latitude', 'longitude', 'country', 'country_id', 'county', 'county_id', 'settlement', 'description', 'folklore', 'classification', 'groundwater_body', 'geology', 'ownership', 'status', 'needs_attention', 'featured'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function country_info()
    {
        return $this->belongsTo('App\Models\Country', 'country_id', 'id');
    }

    public function references()
    {
        return $this->hasMany('App\Models\SpringReference');
    }

    public function all_photos()
    {
        return $this->hasMany('App\Models\Photo');
    }

    public function photos() {
        return $this->all_photos()->where('observation_id', null);
    }

    public function database_links()
    {
        return $this->hasMany('App\Models\SpringDatabaseLink');
    }

    public function feedback()
    {
        return $this->hasMany('App\Models\SpringFeedback');
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
