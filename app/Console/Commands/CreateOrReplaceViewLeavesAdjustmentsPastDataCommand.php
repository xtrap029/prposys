<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceViewLeavesAdjustmentsPastDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceViewLeavesAdjustmentsPastData';

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
            CREATE OR REPLACE VIEW view_leaves_adjustments_past_data AS
                SELECT
                    u.id,
                    IFNULL (SUM(la.quantity), 0) AS adjustment
                FROM
                    users u
                    LEFT JOIN leaves_adjustments la
                    ON u.id = la.user_id
                WHERE YEAR (la.created_at) = YEAR (CURRENT_DATE - INTERVAL 1 YEAR)
                GROUP BY u.id
        ");
    }
}
