<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spring_id')->unsigned()->index();
            $table->foreign('spring_id')->references('id')->on('springs')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('measurement_time')->nullable();
            $table->string('odor')->nullable();
            $table->string('taste')->nullable();
            $table->string('color')->nullable();
            $table->double('water_temperature')->nullable();
            $table->double('air_temperature')->nullable();
            $table->double('ph')->nullable();
            $table->double('specific_conductance')->nullable();
            $table->double('electrical_conductivity')->nullable();
            $table->double('total_dissolved_solids')->nullable();
            $table->double('nitrate')->nullable();
            $table->double('bicarbonate')->nullable();
            $table->double('redox_potential')->nullable();
            $table->double('dissolved_oxygen_percentage')->nullable();
            $table->double('dissolved_oxygen_ppm')->nullable();
            $table->double('discharge')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('observations');
    }
}
