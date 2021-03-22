<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable()->after('observation_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->dateTime('photo_taken')->nullable()->after('thumbnail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('photo_taken');
        });
    }
}
