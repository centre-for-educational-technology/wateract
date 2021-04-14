<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotASpringColumnToSpringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('springs', function (Blueprint $table) {
            $table->tinyInteger('not_a_spring')->after('unlisted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('springs', function (Blueprint $table) {
            $table->dropColumn('not_a_spring');
        });
    }
}
