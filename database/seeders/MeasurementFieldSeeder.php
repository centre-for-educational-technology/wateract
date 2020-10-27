<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $position = 1;
        $now = Carbon::now();

        DB::table('measurement_fields')->insert([
            'name' => 'sodium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'potassium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'calcium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'magnesium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'chloride',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'sulfate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'bicarbonate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'total_hardness',
            'unit' => 'mmol/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'carbonate_hardness',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'manganese',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'water_type',
            'unit' => '',
            'type' => 'text',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'calcite_saturation_index',
            'unit' => '',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'dolomite_saturation_index',
            'unit' => '',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'total_iron',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'total_phosphorus',
            'unit' => 'mgP/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'phosphate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'ammonia',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'nitrite_no2',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'nitrite_no3',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'total_nitrogen',
            'unit' => 'mgN/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'organic_carbon',
            'unit' => 'mgC/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'dissolved_organic_carbon',
            'unit' => 'mgC/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'chemical_oxygen_demand_mn',
            'unit' => 'mgO/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'chemical_oxygen_demand_cr',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'biochemical_oxygen_demand',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'fluoride',
            'unit' => '',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'bromide',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'dissolved_inorganic_carbon',
            'unit' => 'mgC/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'aluminium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'arsenic',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'boron',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'barium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'beryllium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'cadmium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'cobalt',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'chromium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'copper',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'lithium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'molybdenum',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'nickel',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'phosphorus',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'lead',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'sulfur',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'antimony',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'selemium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'silicon',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'silica',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'strontium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'titanium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'thallium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'vanadium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'zinc',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'oxygen_18',
            'unit' => '‰',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'deuterium',
            'unit' => '‰',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'acrylamide',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'benzene',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'benzoapyrene',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'bromate',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => '1_2_dichloroethane',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'mercury',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'epichlorohydrin',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'pesticides',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'sum_of_pesticides',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'polycyclic_aromatic_hydrocarbons',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'tetrachloroethane_and_trichloroethene',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'sum_of_trihalomethanes',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'cyanide',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'vinyl_chloride',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'turbidity',
            'unit' => 'NTU',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'tritium',
            'unit' => 'Bq/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'indicative_dose_of_raditation',
            'unit' => 'mSv',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'radon',
            'unit' => 'Bq/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'escherichia_coli',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'enterococci',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'pseudomonas_aeruginosa',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'colonies_22C',
            'unit' => '/ ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'colonies_37C',
            'unit' => '/ ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('measurement_fields')->insert([
            'name' => 'clostridium_perfringens',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

    }
}
