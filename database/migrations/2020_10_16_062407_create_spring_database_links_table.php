<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpringDatabaseLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spring_database_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spring_id')->unsigned()->index();
            $table->foreign('spring_id')->references('id')->on('springs')->onDelete('cascade');
            $table->string('database_name')->nullable();
            $table->string('code')->nullable();
            $table->string('spring_name')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('spring_database_links');
    }
}
