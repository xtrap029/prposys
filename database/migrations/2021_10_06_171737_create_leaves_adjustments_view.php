<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesAdjustmentsView extends Migration
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
            CREATE VIEW view_leaves_adjustments_data AS
                SELECT
                    u.id,
                    IFNULL (SUM(la.quantity), 0) AS adjustment
                FROM
                    users u
                    LEFT JOIN leaves_adjustments la
                    ON u.id = la.user_id
                WHERE YEAR (la.created_at) = YEAR (CURRENT_DATE)
                GROUP BY u.id
            ";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return "DROP VIEW IF EXISTS `view_leaves_adjustments_data`";
    }
}
