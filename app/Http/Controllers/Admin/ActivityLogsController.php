<?php

namespace App\Http\Controllers\Admin;

use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller {
    
    public function index() {
        return view('pages.admin.activitylog.index')->with([
            'activity_logs' => Activity::orderBy('id', 'desc')->paginate(10)
        ]);
    }
}
