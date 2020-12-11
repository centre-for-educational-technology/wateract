<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spring_id')->unsigned()->index()->nullable();
            $table->foreign('spring_id')->references('id')->on('springs')->onDelete('cascade');
            $table->bigInteger('observation_id')->unsigned()->index()->nullable();
            $table->foreign('observation_id')->references('id')->on('observations')->onDelete('set null');
            $table->string('path');
            $table->string('thumbnail');
            $table->string('caption')->nullable();
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
        Schema::dropIfExists('photos');
    }
}
