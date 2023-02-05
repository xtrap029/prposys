<?php

namespace App\Http\Controllers\Travels;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\User;
use App\Travel;
use App\TravelsPassenger;
use App\Settings;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {

        $created_travels = Travel::where('owner_id', auth()->id())->orderBy('id', 'desc')->limit('5')->get();
        
        $tagged_travels = TravelsPassenger::select('travel_id')->where('user_id', auth()->id())->groupBy('travel_id')->pluck('travel_id')->toArray();
        $tagged_travels = Travel::whereIn('id', $tagged_travels)->orderBy('id', 'desc')->limit('5')->get();

        $user = User::where('id', auth()->id())->first();
        $announcement = Settings::where('type', 'ANNOUNCEMENT')->first()->value;
        
        return view('pages.travels.dashboard.index')->with([
            'user' => $user,
            'announcement' => $announcement,
            'created_travels' => $created_travels,
            'tagged_travels' => $tagged_travels,
        ]);
    }
}
