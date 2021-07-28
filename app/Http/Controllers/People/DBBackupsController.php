<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Company;
use App\DbBackup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ZanySoft\Zip\Zip;
use \DB;
use Illuminate\Filesystem\Filesystem;

class DBBackupsController extends Controller {

    public function db_backups() {
        $db = DbBackup::orderBy('id', 'asc')->get();

        $file = new Filesystem;
        $file->cleanDirectory('storage/public/db-backups-zip/');

        return view('pages.people.dbbackups.index')->with([
            'db' => $db
        ]);
    }

    public function db_backups_zip() {
        $zip_name = "ALL-" . Carbon::now()->format('Y-m-d') . "-" . substr(md5(mt_rand()), 0, 7);
        Zip::create('storage/public/db-backups-zip/'.$zip_name.'.zip')->add('storage/public/db-backups/');

        return \Redirect::to('storage/public/db-backups-zip/'.$zip_name.'.zip');
    }

    public function db_backups_generate() {
        \Artisan::call("database:backup");

        return redirect('/db-backups')->with('success', 'Backup'.__('messages.create_success'));
    }
}
