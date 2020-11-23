<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelFieldSeeder extends Seeder
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

        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'sodium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'potassium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'calcium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'magnesium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'chloride',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'sulfate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'bicarbonate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'total_hardness',
            'unit' => 'mmol/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'carbonate_hardness',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'manganese',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'water_type',
            'unit' => '',
            'type' => 'text',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'calcite_saturation_index',
            'unit' => '',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'dolomite_saturation_index',
            'unit' => '',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'total_iron',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'total_phosphorus',
            'unit' => 'mgP/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'phosphate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'ammonia',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'nitrite_no2',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'nitrite_no3',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'total_nitrogen',
            'unit' => 'mgN/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'organic_carbon',
            'unit' => 'mgC/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'dissolved_organic_carbon',
            'unit' => 'mgC/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'chemical_oxygen_demand_mn',
            'unit' => 'mgO/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'chemical_oxygen_demand_cr',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'biochemical_oxygen_demand',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'fluoride',
            'unit' => '',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'bromide',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'dissolved_inorganic_carbon',
            'unit' => 'mgC/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'aluminium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'arsenic',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'boron',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'barium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'beryllium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'cadmium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'cobalt',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'chromium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'copper',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'lithium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'molybdenum',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'nickel',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'phosphorus',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'lead',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'sulfur',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'antimony',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'selemium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'silicon',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'silica',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'strontium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'titanium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'thallium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'vanadium',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'zinc',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'oxygen_18',
            'unit' => '‰',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'deuterium',
            'unit' => '‰',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'acrylamide',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'benzene',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'benzoapyrene',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'bromate',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => '1_2_dichloroethane',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'mercury',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'epichlorohydrin',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'pesticides',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'sum_of_pesticides',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'polycyclic_aromatic_hydrocarbons',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'tetrachloroethane_and_trichloroethene',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'sum_of_trihalomethanes',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'cyanide',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'vinyl_chloride',
            'unit' => 'µg/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'turbidity',
            'unit' => 'NTU',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'tritium',
            'unit' => 'Bq/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'indicative_dose_of_raditation',
            'unit' => 'mSv',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'radon',
            'unit' => 'Bq/l',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'escherichia_coli',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'enterococci',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'pseudomonas_aeruginosa',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'colonies_22C',
            'unit' => '/ ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'colonies_37C',
            'unit' => '/ ml',
            'type' => 'number',
            'position' => $position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'measurement',
            'name' => 'clostridium_perfringens',
            'unit' => '/ 250 ml',
            'type' => 'number',
            'position' => $position,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);


        //observation fields
        $observation_field_position = 1;
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'water_temperature',
            'unit' => 'C',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'air_temperature',
            'unit' => 'C',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'ph',
            'unit' => '',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'specific_conductance',
            'unit' => 'µS/cm',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'electrical_conductivity',
            'unit' => 'µS/cm',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'total_dissolved_solids',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'nitrate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'bicarbonate',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'redox_potential',
            'unit' => 'mV',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'dissolved_oxygen_percentage',
            'unit' => '%',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'dissolved_oxygen_ppm',
            'unit' => 'mg/l',
            'type' => 'number',
            'position' => $observation_field_position++,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('model_fields')->insert([
            'model' => 'observation',
            'name' => 'discharge',
            'unit' => 'l/s',
            'type' => 'number',
            'position' => $observation_field_position,
            'visible' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

    }
}
