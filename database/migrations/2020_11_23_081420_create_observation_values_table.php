<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observation_field_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('observation_id')->unsigned()->index();
            $table->foreign('observation_id')->references('id')->on('observations')->onDelete('cascade');
            $table->bigInteger('field_id')->unsigned()->index();
            $table->foreign('field_id')->references('id')->on('model_fields');
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
        Schema::dropIfExists('observation_field_values');
    }
}
