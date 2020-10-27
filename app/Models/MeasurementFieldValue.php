<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_id', 'field', 'value'
    ];

    public function field()
    {
        return $this->belongsTo('App\Models\MeasurementField');
    }

    public function measurement()
    {
        return $this->belongsTo('App\Models\Measurement');
    }

}
