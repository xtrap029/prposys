<?php

namespace App\Http\Controllers\People;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        $user_newest = User::where('ua_level_id', '!=', config('global.ua_inactive'))
            ->whereNotNull('e_hire_date')
            ->orderBy('e_hire_date', 'desc')
            ->limit(5)
            ->get();

        $user_oldest = User::where('ua_level_id', '!=', config('global.ua_inactive'))
            ->whereNotNull('e_hire_date')
            ->orderBy('e_hire_date', 'asc')
            ->limit(5)
            ->get();

        $activity = Activity::orderBy('id', 'desc')
            ->whereNotIn('log_name', ['Transaction Liquidation', 'Transaction Descriptions', 'Transaction Attachments', 'Transaction Notes'])
            ->where('causer_id', auth()->id())
            ->limit(5)
            ->get();
        
        $user_bday = User::where('ua_level_id', '!=', config('global.ua_inactive'))
            ->whereMonth('e_dob', Carbon::now()->month)
            ->orderBy('e_dob', 'asc')
            ->get();
        
        return view('pages.people.dashboard.index')->with([
            'user' => $user,
            'user_newest' => $user_newest,
            'user_oldest' => $user_oldest,
            'activity' => $activity,
            'user_bday' => $user_bday,
        ]);
    }
}
