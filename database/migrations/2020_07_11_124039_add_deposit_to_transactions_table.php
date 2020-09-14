<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepositToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('depo_type', ['BANK', 'CHECK', 'ONLINE'])->after('liquidation_approver_id')->nullable();
            $table->unsignedBigInteger('depo_bank_id')->after('depo_type')->nullable();
            $table->string('depo_ref')->after('depo_bank_id')->nullable();
            $table->date('depo_date')->after('depo_ref')->nullable();
            $table->text('depo_slip')->after('depo_date')->nullable();

            $table->foreign('depo_bank_id')->references('id')->on('banks')->onDelete('cascade');
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
            $table->dropColumn('depo_type');
            $table->dropColumn('depo_bank_id');
            $table->dropColumn('depo_ref');
            $table->dropColumn('depo_date');
            $table->dropColumn('depo_slip');
        });
    }
}
