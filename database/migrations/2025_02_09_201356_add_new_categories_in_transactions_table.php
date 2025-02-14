<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCategoriesInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_tdsa_bill')->default(0)->after('is_bank');
            $table->boolean('is_tdsa_payment')->default(0)->after('is_tdsa_bill');
            $table->boolean('is_aec_bill')->default(0)->after('is_tdsa_payment');
            $table->boolean('is_aec_payment')->default(0)->after('is_aec_bill');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('is_tdsa_bill');
            $table->dropColumn('is_tdsa_payment');
            $table->dropColumn('is_aec_bill');
            $table->dropColumn('is_aec_payment');
        });
    }
}
