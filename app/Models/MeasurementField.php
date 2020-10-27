<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeasurementField extends Model
{
    const TABLE_MEASUREMENT_FIELD_VALUES = 'measurement_field_values';
    use HasFactory;

    public function field_value(int $measurement_id)
    {
        $field_value = DB::table(self::TABLE_MEASUREMENT_FIELD_VALUES)
            ->where('measurement_id', $measurement_id)
            ->where('field_id', $this->id)
            ->first();
        if ($field_value) {
            return $field_value->value;
        }
        return '';
    }

}
