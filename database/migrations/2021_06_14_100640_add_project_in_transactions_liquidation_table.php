<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectInTransactionsLiquidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions_liquidation', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->after('date')->nullable();

            $table->foreign('project_id')->references('id')->on('company_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions_liquidation', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
