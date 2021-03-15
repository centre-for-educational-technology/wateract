<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Spring;
use App\Models\SpringDatabaseLink;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CsvFileController extends Controller
{

    public function update(Request $request)
    {
        $user = User::where('id', Auth::id())->first();
        if ( ! $user->can('administrate') ) {
            var_dump('no permission');
            exit;
        }

        if ($request->hasFile('csv_file')) {
            $contents = file_get_contents($request->file('csv_file'));
            $lines = explode("\r\n", $contents);
            $field_names = explode(";", array_shift($lines));
            $ai_codes = array();
            $res = array();
            foreach ($lines as $line) {
                // Skip the empty line
                if (empty($line)) continue;
                $fields = explode(";", $line);
                $_res = array();
                foreach ($field_names as $key => $f) {
                        if ($f == "ai_kood") {
                            $ai_codes []= $fields[$key];
                        }
                        $_res[$f] = $fields[$key];
                }
                $res[] = $_res;
            }

            // check that springs with those codes wouldn't exist
            $existing_springs = Spring::whereIn('code', $ai_codes)->pluck('code')->toArray();
            if (count($existing_springs) > 0) {
                var_dump('fail');exit;
                return 'failure';
            }

            $ee_country_id = Country::where('code', 'EE')->first()->id;
            $lv_country_id = Country::where('code', 'LV')->first()->id;

            $i = 0;
            foreach($res as $spring) {
                $i++;
                //if ($i > 5) break;
                //var_dump($spring);
                if ($spring['ai_kood']) {

                    // create spring
                    $new_spring = new Spring();
                    $new_id = DB::table('springs')->max('id') + 1;
                    $code = sprintf('%s%05d', 'LV', $new_id);
                    $new_spring->code = $code;
                    //$new_spring->kkr_code = $spring['KKR_kood'];
                    $new_spring->name = $spring['Nimi'];
                    $latitude = str_replace(',', '.', $spring['N']);
                    $new_spring->latitude = doubleval($latitude);
                    $longitude = str_replace(',', '.', $spring['E']);
                    $new_spring->longitude = doubleval($longitude);
                    $new_spring->country = 'LV';
                    $new_spring->country_id = $lv_country_id;
                    $description = trim($spring['Lood olude kirjeldus']);
                    if (!$description) {
                        $description = $spring['Nimi'];
                    }
                    $new_spring->description = $description;
                    $geology = null;
                    if (trim($spring['Geoloogia']) !== '') {
                        $geology = $spring['Geoloogia'];
                    }
                    $new_spring->geology = $geology;
                    $new_spring->status = 'submitted';
                    $new_spring->save();

                    // save database links
                    $databases = array("KKR", "ÜOB", "Ürg", "Kult", "Pär", "Seir", "VEP", "LD");
                    foreach ($databases as $database) {

                        $database_name = $database;
                        $database_code = "";
                        if (!empty($spring[$database . '_kood'])) {
                            $database_code = $spring[$database . '_kood'];
                        }
                        $database_link = "";
                        if (!empty($spring[$database . '_link'])) {
                            $database_link = $spring[$database . '_link'];
                        }
                        $database_spring_name = "";
                        if (!empty($spring[$database . '_allik'])) {
                            $database_spring_name = $spring[$database . '_allik'];
                        }

                        if (!empty(trim($database_code))
                            || !empty(trim($database_link))
                            || !empty(trim($database_spring_name)))
                        {

                            $spring_database_link = new SpringDatabaseLink();
                            $spring_database_link->spring_id = $new_spring->id;
                            $spring_database_link->database_name = $database_name;
                            $spring_database_link->code = $database_code;
                            $spring_database_link->spring_name = $database_spring_name;
                            $spring_database_link->url = $database_link;
                            $spring_database_link->save();

                        }

                    }

                }
            }

            exit;
            var_dump($contents_array);
        } else {
            var_dump('dont have');
        }

        exit;

    }
}
