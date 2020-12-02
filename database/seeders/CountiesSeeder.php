<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ee = DB::table('countries')->insertGetId([
            'name' => 'Estonia',
            'code' => 'EE',
            'visible' => 1,
        ]);

        $lv = DB::table('countries')->insertGetId([
            'name' => 'Latvia',
            'code' => 'LV',
            'visible' => 1,
        ]);

        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Harju maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Hiiu maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Ida-Viru maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Jõgeva maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Järva maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Lääne maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Lääne-Viru maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Põlva maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Pärnu maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Rapla maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Saare maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Tartu maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Valga maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Viljandi maakond',
        ]);
        DB::table('counties')->insert([
            'country_id' => $ee,
            'name' => 'Võru maakond',
        ]);

        DB::table('counties')->insert([
            'country_id' => $lv,
            'name' => 'Abavas pagasts',
        ]);
    }
}
