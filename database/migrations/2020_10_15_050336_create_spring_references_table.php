<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpringReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spring_references', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spring_id')->unsigned()->index();
            $table->foreign('spring_id')->references('id')->on('springs')->onDelete('cascade');
            $table->string('url');
            $table->string('url_title')->nullable();
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
        Schema::dropIfExists('spring_references');
    }
}
