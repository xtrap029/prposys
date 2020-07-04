<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormsInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('coa_tagging_id')->after('requested_id')->nullable();
            $table->unsignedBigInteger('expense_type_id')->after('coa_tagging_id')->nullable();
            $table->unsignedBigInteger('vat_type_id')->after('expense_type_id')->nullable();

            $table->foreign('coa_tagging_id')->references('id')->on('coa_taggings')->onDelete('cascade');
            $table->foreign('expense_type_id')->references('id')->on('expense_types')->onDelete('cascade');
            $table->foreign('vat_type_id')->references('id')->on('vat_types')->onDelete('cascade');
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
            $table->dropColumn('coa_tagging_id');
            $table->dropColumn('expense_type_id');
            $table->dropColumn('vat_type_id');
            $table->dropColumn('purpose');
        });
    }
}
