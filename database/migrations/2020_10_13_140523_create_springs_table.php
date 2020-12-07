<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('springs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('code');
            $table->string('kkr_code')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('country')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->string('county')->nullable();
            $table->bigInteger('county_id')->unsigned()->nullable();
            $table->string('settlement')->nullable();
            $table->text('description');
            $table->text('folklore')->nullable();
            $table->string('classification')->nullable();
            $table->string('groundwater_body')->nullable();
            $table->text('geology')->nullable();
            $table->string('ownership')->nullable();
            $table->string('status')->default('draft');
            $table->tinyInteger('needs_attention')->default(0);
            $table->tinyInteger('featured')->default(0);
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
        Schema::dropIfExists('springs');
    }
}
