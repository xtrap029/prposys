<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceViewMonthDiffDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceViewMonthDiffData';

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
            CREATE OR REPLACE VIEW view_month_diff_data AS
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
        ");
    }
}
