<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesYtdPastView extends Migration
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
            CREATE VIEW view_leaves_ytd_past_data AS
                SELECT
                    id,
                    (
                        (
                            (SELECT
                                VALUE
                            FROM
                                settings
                            WHERE TYPE = 'LEAVES_ANNUAL') / 12
                        ) * IFNULL (
                            GREATEST (
                                LEAST (
                                    TIMESTAMPDIFF (
                                    MONTH,
                                    e_hire_date,
                                    MAKEDATE (YEAR (NOW()), 1)
                                    ),
                                    12
                                ),
                                0
                            ),
                            0
                        )
                    ) AS leaves_ytd
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
        return "DROP VIEW IF EXISTS `view_leaves_ytd_past_data`";
    }
}
