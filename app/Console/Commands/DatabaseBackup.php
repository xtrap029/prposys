<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\DbBackup;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $filename = Carbon::now()->format('Y-m-d') . "-" . substr(md5(mt_rand()), 0, 7) . ".gz";

        $db = new DbBackup;
        $db->name = $filename;
        $db->save();

        $count = DbBackup::count();
        $to_delete_files = DbBackup::latest()->take($count)->skip(15)->get();
        $to_delete_data = clone $to_delete_files;
        $to_delete_data->each(function($row){ $row->delete(); });
        // print(storage_path());
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password='" . env('DB_PASSWORD') . "' --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/public/public/db-backups/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);

        foreach ($to_delete_files as $value) {
            unlink(storage_path() . "/app/public/public/db-backups/" . $value->name);
        }
    }
}
