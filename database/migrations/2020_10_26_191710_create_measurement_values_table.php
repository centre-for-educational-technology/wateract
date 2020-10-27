<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurement_field_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('measurement_id')->unsigned()->index();
            $table->foreign('measurement_id')->references('id')->on('measurements')->onDelete('cascade');
            $table->bigInteger('field_id')->unsigned()->index();
            $table->foreign('field_id')->references('id')->on('measurement_fields');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurement_field_values');
    }
}
