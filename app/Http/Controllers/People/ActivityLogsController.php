<?php

namespace App\Http\Controllers\People;

use Spatie\Activitylog\Models\Activity;
use App\Transaction;
use App\User;
use App\Helpers\UAHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller {
    
    public function index() {
        $activity = Activity::orderBy('id', 'desc');
        
        $activity = $activity->whereNotIn('log_name', ['Transaction Liquidation', 'Transaction Descriptions', 'Transaction Attachments', 'Transaction Notes']);
        
        if (UAHelper::get()['peo_activity'] == config('global.ua_own')) {
            $activity = $activity->where('causer_id', auth()->id());
        }

        if (!empty($_GET['log_name']) && $_GET['log_name'] != "") $activity = $activity->where('log_name', $_GET['log_name']);

        $activity = $activity->paginate(10);
        if (!empty($_GET['log_name'])) $activity->appends(['log_name' => $_GET['log_name']]);

        $log_name = Activity::select('log_name')->distinct('log_name')->get();

        foreach ($activity as $key => $value) {
            $activity[$key]->is_confidential = 0;
            if ($value->subject_type == 'App\Transaction') {
                $activity[$key]->is_confidential = Transaction::find($value->subject_id)->is_confidential;
                if ($activity[$key]->is_confidential == 0) {
                    $activity[$key]->is_confidential = Transaction::find($value->subject_id)->is_confidential_own;
                }
                $activity[$key]->owner_id = Transaction::find($value->subject_id)->owner_id;
            }
        }

        return view('pages.people.activitylog.index')->with([
            'log_name' => $log_name,
            'activity_logs' => $activity
        ]);
    }
}
