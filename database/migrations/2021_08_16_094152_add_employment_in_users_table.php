<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmploymentInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('e_emp_no')->after('is_smt')->nullable();
            $table->date('e_hire_date')->after('e_emp_no')->nullable();
            $table->string('e_emp_status')->after('e_hire_date')->nullable();
            $table->date('e_reg_date')->after('e_emp_status')->nullable();
            $table->string('e_position')->after('e_reg_date')->nullable();
            $table->string('e_rank')->after('e_position')->nullable();
            $table->string('e_department')->after('e_rank')->nullable();
            $table->string('e_payroll')->after('e_department')->nullable();
            $table->date('e_dob')->after('e_payroll')->nullable();
            $table->string('e_gender')->after('e_dob')->nullable();
            $table->string('e_civil')->after('e_gender')->nullable();
            $table->string('e_mail_address')->after('e_civil')->nullable();
            $table->string('e_contact')->after('e_mail_address')->nullable();
            $table->string('e_email')->after('e_contact')->nullable();
            $table->string('e_emergency_name')->after('e_email')->nullable();
            $table->string('e_emergency_contact')->after('e_emergency_name')->nullable();
            $table->string('e_tin')->after('e_emergency_contact')->nullable();
            $table->string('e_sss')->after('e_tin')->nullable();
            $table->string('e_phic')->after('e_sss')->nullable();
            $table->string('e_hmdf')->after('e_phic')->nullable();
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
            $table->dropColumn('e_emp_no');
            $table->dropColumn('e_hire_date');
            $table->dropColumn('e_emp_status');
            $table->dropColumn('e_reg_date');
            $table->dropColumn('e_position');
            $table->dropColumn('e_rank');
            $table->dropColumn('e_department');
            $table->dropColumn('e_payroll');
            $table->dropColumn('e_dob');
            $table->dropColumn('e_gender');
            $table->dropColumn('e_civil');
            $table->dropColumn('e_mail_address');
            $table->dropColumn('e_contact');
            $table->dropColumn('e_email');
            $table->dropColumn('e_emergency_name');
            $table->dropColumn('e_emergency_contact');
            $table->dropColumn('e_tin');
            $table->dropColumn('e_sss');
            $table->dropColumn('e_phic');
            $table->dropColumn('e_hmdf');
        });
    }
}
