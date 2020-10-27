<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Measurement extends Model
{
    const TABLE_MEASUREMENT_FIELD_VALUES = 'measurement_field_values';
    const TABLE_MEASUREMENT_FIELDS = 'measurement_fields';
    use HasFactory;

    protected $fillable = [
        'spring_id', 'user_id', 'analysis_time'
    ];

    public function spring()
    {
        return $this->belongsTo('App\Models\Spring');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFieldValues() {
        return DB::table(self::TABLE_MEASUREMENT_FIELD_VALUES)
            ->join(self::TABLE_MEASUREMENT_FIELDS, 'field_id', '=', self::TABLE_MEASUREMENT_FIELDS .'.id')
            ->where('measurement_id', $this->id)
            ->orderBy(self::TABLE_MEASUREMENT_FIELDS . '.position', 'asc')->get();
    }

}
