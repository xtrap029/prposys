<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthDiffPastView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement($this->dropView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function createView(): string
    {
        return "
            CREATE VIEW view_month_diff_past_data AS
            SELECT
                id,
                GREATEST (
                    IFNULL (
                        TIMESTAMPDIFF (
                            MONTH,
                            (
                            CASE
                                WHEN (
                                e_hire_date <= CONCAT ((YEAR (CURDATE()) - 1), '-01-01')
                                )
                                THEN CONCAT ((YEAR (CURDATE()) - 2), '-12-31')
                                ELSE e_hire_date
                            END
                            ),
                            CONCAT ((YEAR (CURDATE()) - 1), '-12-31')
                        ),
                        0
                    ),
                    0
                ) AS month_diff
            FROM
                users
            ";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return "DROP VIEW IF EXISTS `view_month_diff_past_data`";
    }
}
