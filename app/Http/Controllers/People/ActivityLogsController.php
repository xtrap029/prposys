<?php

namespace App\Http\Controllers\People;

use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller {
    
    public function index() {
        $activity = Activity::orderBy('id', 'desc');
        
        if (!empty($_GET['log_name']) && $_GET['log_name'] != "") $activity = $activity->where('log_name', $_GET['log_name']);

        $activity = $activity->paginate(10);
        if (!empty($_GET['log_name'])) $activity->appends(['log_name' => $_GET['log_name']]);

        $log_name = Activity::select('log_name')->distinct('log_name')->get();

        return view('pages.people.activitylog.index')->with([
            'log_name' => $log_name,
            'activity_logs' => $activity
        ]);
    }
}
