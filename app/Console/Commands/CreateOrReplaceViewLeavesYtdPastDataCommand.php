<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceViewLeavesYtdPastDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceViewLeavesYtdPastData';

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
            CREATE OR REPLACE VIEW view_leaves_ytd_past_data AS
            SELECT
            users.id,
            (
                (
                (SELECT
                    VALUE
                FROM
                    settings
                WHERE TYPE = 'LEAVES_ANNUAL') / 12
                ) * IFNULL (
                view_month_diff_past_data.month_diff,
                0
                )
            ) AS leaves_ytd
            FROM
            users
            LEFT JOIN
            view_month_diff_past_data ON users.id = view_month_diff_past_data.id
            ORDER BY users.id
        ");
    }
}
