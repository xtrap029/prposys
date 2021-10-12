<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceViewMonthDiffPastDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceViewMonthDiffPastData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or Replace SQL View';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_month_diff_past_data AS
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
        ");
    }
}
