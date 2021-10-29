<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsYesnoDeletedAtInUaRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ua_routes', function (Blueprint $table) {
            $table->boolean('is_yesno')->after('name')->default(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ua_routes', function (Blueprint $table) {
            $table->dropColumn('is_yesno');
        });
    }
}
