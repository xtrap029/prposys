<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVatToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('form_vat_code')->after('cancellation_reason')->nullable();
            $table->string('form_vat_name')->after('form_vat_code')->nullable();
            $table->string('form_vat_vat')->after('form_vat_name')->nullable();
            $table->string('form_vat_wht')->after('form_vat_vat')->nullable();
            $table->string('form_amount_unit')->after('form_vat_wht')->nullable();
            $table->string('form_amount_subtotal')->after('form_amount_unit')->nullable();
            $table->string('form_amount_vat')->after('form_amount_subtotal')->nullable();
            $table->string('form_amount_wht')->after('form_amount_vat')->nullable();
            $table->string('form_amount_payable')->after('form_amount_wht')->nullable();
            $table->unsignedBigInteger('form_approver_id')->after('form_amount_payable')->nullable();

            $table->foreign('form_approver_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropColumn('form_vat_code');
            $table->dropColumn('form_vat_name');
            $table->dropColumn('form_vat_vat');
            $table->dropColumn('form_vat_wht');
            $table->dropColumn('form_amount_unit');
            $table->dropColumn('form_amount_subtotal');
            $table->dropColumn('form_amount_vat');
            $table->dropColumn('form_amount_wht');
            $table->dropColumn('form_amount_payable');
            $table->dropColumn('form_approver_id');
        });
    }
}
