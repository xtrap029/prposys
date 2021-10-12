<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthDiffView extends Migration
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
            CREATE VIEW view_month_diff_data AS
                SELECT
                    id,
                    IFNULL (
                        TIMESTAMPDIFF (
                            MONTH,
                            (
                                CASE
                                    WHEN (e_hire_date < MAKEDATE (YEAR (NOW()), 1))
                                    THEN MAKEDATE (YEAR (NOW()), 1)
                                    ELSE e_hire_date
                                END
                            ),
                            CURDATE()
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
        return "DROP VIEW IF EXISTS `view_month_diff_data`";
    }
}
