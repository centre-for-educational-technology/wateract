<?php

namespace App\Exports;

use App\Models\ModelField;
use App\Models\Observation;
use App\Models\ObservationFieldValue;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObservationsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return Observation::where('status', '!=', 'draft')->orderBy('created_at', 'asc')->with('spring')->get();
    }

    public function map($observation): array
    {
        return [
            $observation->spring->name,
            $observation->spring->code,
            date('d.m.Y H:i', strtotime($observation->measurement_time)),
            User::where('id', '=', $observation->user_id)->value('name'),
            $observation->odor,
            $observation->taste,
            $observation->color,
            $observation->description,
            $this->getFieldValue("water_temperature", $observation->id),
            $this->getFieldValue("air_temperature", $observation->id),
            $this->getFieldValue("ph", $observation->id),
            $this->getFieldValue("electrical_conductivity", $observation->id),
            $this->getFieldValue("total_dissolved_solids", $observation->id),
            $this->getFieldValue("nitrate", $observation->id),
            $this->getFieldValue("bicarbonate", $observation->id),
            $this->getFieldValue("redox_potential ", $observation->id),
            $this->getFieldValue("dissolved_oxygen_percentage", $observation->id),
            $this->getFieldValue("dissolved_oxygen_ppm ", $observation->id),
            $this->getFieldValue("discharge", $observation->id),
            $this->getFieldValue("discharge_measurement_method", $observation->id)
        ];
    }

    public function getFieldValue($field_name, $observation_id) {
        $field = ModelField::where('name', $field_name)
                    ->where('model', 'observation')->first();
        return ObservationFieldValue::where('observation_id', $observation_id)
            ->where('field_id', $field->id)->value('value');
    }

    public function headings() : array {
        return [
            "Spring Name",
            "Wateract Code",
            "Measurement Time",
            "Creator",
            "Odor",
            "Taste",
            "Color",
            "Description",
            "Water Temperature",
            "Air temperature",
            "pH",
            "Electrical Conductivity",
            "Total Dissolved Solids",
            "Nitrate (NO3)",
            "Bicarbonate (HCO3)",
            "Redox Potential",
            "Dissolved Oxygen (%)",
            "Dissolved Oxygen (ppm)",
            "Discharge",
            "Discharge Measurement Method"
        ];
    }

}
