<?php

namespace App\Exports;

use App\Models\Spring;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SpringsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return Spring::where('status', '!=', 'draft')->get();

    }

    public function map($spring): array
    {
        return [
            $spring->name,
            date('d.m.Y H:i', strtotime($spring->created_at)),
            date('d.m.Y H:i', strtotime($spring->updated_at)),
            User::where('id', '=', $spring->user_id)->value('name'),
            $spring->kkr_code,
            $spring->code,
            $spring->latitude,
            $spring->longitude,
            $spring->country,
            $spring->county,
            $spring->settlement,
            $spring->description,
            $spring->folklore,
            $spring->status
        ];
    }

    public function headings() : array {
        return [
            "Spring Name",
            "Created At",
            "Updated At",
            "Creator",
            "KKR Code",
            "Wateract Code",
            "Latitude",
            "Longitude",
            "Country",
            "County",
            "Settlement",
            "Description",
            "Folklore",
            "Spring Status"
        ];
    }
}
