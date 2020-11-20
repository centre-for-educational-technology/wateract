<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_fields', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('name');
            $table->unique(['model', 'name']);
            $table->string('unit')->nullable();
            $table->string('type');
            $table->tinyInteger('position');
            $table->tinyInteger('visible')->default(1);
            $table->tinyInteger('deleted')->default(0);
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
        Schema::dropIfExists('model_fields');
    }
}
