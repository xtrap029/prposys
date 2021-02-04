<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeCurrencyInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('currency_2', ['PHP', 'USD', 'EUR'])->nullable()->after('currency');
            $table->decimal('currency_2_rate', 10, 2)->nullable()->after('currency_2');
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
            $table->dropColumn('currency_2');
            $table->dropColumn('currency_2_rate');
        });
    }
}
