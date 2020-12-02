<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_counties', function (Blueprint $table) {

            $table->unsignedBigInteger('county_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_counties_model_id_model_type_index');
            $table->foreign('county_id')
                ->references('id')
                ->on('counties')
                ->onDelete('cascade');
            $table->primary(['county_id', 'model_id', 'model_type'],
                'model_has_counties_county_model_type_primary');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_counties');
    }
}
