<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeObservationsAndMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('observations', function (Blueprint $table) {
            $table->dateTime('measurement_time')->change();
        });

        Schema::table('measurements', function (Blueprint $table) {
            $table->dateTime('analysis_time')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('observations', function (Blueprint $table) {
            $table->timestamp('measurement_time')->change();
        });

        Schema::table('measurements', function (Blueprint $table) {
            $table->timestamp('analysis_time')->change();
        });
    }
}
