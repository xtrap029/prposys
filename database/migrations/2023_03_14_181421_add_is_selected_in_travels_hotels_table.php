<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSelectedInTravelsHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('travels_hotels', function (Blueprint $table) {
            $table->boolean('is_selected')->default(0)->after('fee_land')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('travels_hotels', function (Blueprint $table) {
            $table->dropColumn('is_selected');
        });
    }
}
