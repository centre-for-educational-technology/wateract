<?php

namespace App\Exports;

use App\Models\Measurement;
use App\Models\MeasurementFieldValue;
use App\Models\ModelField;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MeasurementsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return Measurement::where('status', '!=', 'draft')->orderBy('created_at', 'asc')->with('spring')->get();
    }

    public function map($measurement): array
    {
        $values = [
            $measurement->spring->name,
            $measurement->spring->code,
            date('d.m.Y H:i', strtotime($measurement->analysis_time)),
            User::where('id', '=', $measurement->user_id)->value('name'),
        ];

        $fields = $this->measurementFields();
        foreach($fields as $field) {
            $values []= $this->getFieldValue($field->name, $measurement->id);
        }

        return $values;
    }

    public function measurementFields() {
        return \App\Models\ModelField::where('model', 'measurement')->where('visible', 1)->orderBy('position')->select('name')->get();
    }

    public function getFieldValue($field_name, $measurement_id) {
        $field = ModelField::where('name', $field_name)
            ->where('model', 'measurement')->first();
        return MeasurementFieldValue::where('measurement_id', $measurement_id)
            ->where('field_id', $field->id)->value('value');
    }

    public function headings() : array {

        $headings = [
            "Spring Name",
            "Wateract Code",
            "Analysis Time",
            "Creator",
        ];

        $fields = $this->measurementFields();
        foreach($fields as $field) {
            $headings []= __('springs.measurement_fields.'.$field->name);
        }

        return $headings;
    }

}
