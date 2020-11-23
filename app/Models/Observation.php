<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Observation extends Model
{
    const TABLE_OBSERVATION_FIELD_VALUES = 'observation_field_values';
    const TABLE_FIELDS = 'model_fields';
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

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFieldValues() {
        return DB::table(self::TABLE_OBSERVATION_FIELD_VALUES)
            ->join(self::TABLE_FIELDS, 'field_id', '=', self::TABLE_FIELDS .'.id')
            ->where('observation_id', $this->id)
            ->orderBy(self::TABLE_FIELDS . '.position', 'asc')->get();
    }
}
