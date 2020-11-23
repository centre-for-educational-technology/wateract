<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservationFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'observation_id', 'field', 'value'
    ];

    public function field()
    {
        return $this->belongsTo('App\Models\ModelField');
    }

    public function observation()
    {
        return $this->belongsTo('App\Models\Observation');
    }

}
