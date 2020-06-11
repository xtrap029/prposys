<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('LIMIT_UNLIQUIDATEDPR_AMOUNT')->nullable();
            $table->integer('LIMIT_UNLIQUIDATEDPR_COUNT')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('LIMIT_UNLIQUIDATEDPR_AMOUNT');
            $table->dropColumn('LIMIT_UNLIQUIDATEDPR_COUNT');
        });
    }
}
