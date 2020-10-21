<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $fillable = [
        'spring_id', 'user_id', 'measurement_time', 'odor', 'taste', 'color', 'water_temperature', 'air_temperature', 'ph', 'specific_conductance', 'electrical_conductivity', 'total_dissolved_solids', 'nitrate', 'bicarbonate', 'redox_potential', 'dissolved_oxygen_percentage', 'dissolved_oxygen_ppm', 'discharge', 'description'
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
