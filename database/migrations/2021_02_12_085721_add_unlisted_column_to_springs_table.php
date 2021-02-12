<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnlistedColumnToSpringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('springs', function (Blueprint $table) {
            $table->tinyInteger('unlisted')->after('featured')->default(0);
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
            $table->dropColumn('unlisted');
        });
    }
}
