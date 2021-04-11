<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Spring extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'code', 'kkr_code', 'latitude', 'longitude', 'country', 'country_id', 'county', 'county_id', 'settlement', 'description', 'folklore', 'classification', 'groundwater_body', 'geology', 'ownership', 'status', 'needs_attention', 'featured', 'unlisted'
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
        return $this->hasMany('App\Models\Observation')->where('status', 'submitted');
    }

    public function measurements()
    {
        return $this->hasMany('App\Models\Measurement')->where('status', 'submitted');
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function canEdit() {
        $user = Auth::user();
        if ( !$user ) {
            return false;
        }
        if ( $user->hasRole(['admin', 'super-admin']) ) {
            return true;
        } else if ( $user->hasRole(['editor'])) {
            $user_counties_ids = $user->user_counties_ids();
            // can edit if I am editor of this county
            if ( in_array( $this->county_id, $user_counties_ids) ) {
                return true;
            }
        }
        return false;
    }

}
