<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\User;
use App\Settings;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        $announcement = Settings::where('type', 'ANNOUNCEMENT')->first()->value;
        
        return view('pages.resources.dashboard.index')->with([
            'user' => $user,
            'announcement' => $announcement,
        ]);
    }
}
