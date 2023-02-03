<?php

namespace App\Http\Controllers\Travels;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\User;
use App\Travel;
use App\Settings;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {

        $created_travels = Travel::where('owner_id', auth()->id())->orderBy('id', 'desc')->limit('5')->get();
        foreach ($created_travels as $key => $value) {
            $traveling_users = explode('--', $value->traveling_users);
            $travelers = [];
            foreach ($traveling_users as $key2 => $value2) {
                $travelers[] = str_replace('-', '', $value2);
            }
            $created_travels[$key]->travelers = User::select('name')->find($travelers);
        }

        $tagged_travels = Travel::where('traveling_users', 'LIKE', '%-'.auth()->id().'-%')->orderBy('id', 'desc')->limit('5')->get();
        foreach ($tagged_travels as $key => $value) {
            $traveling_users = explode('--', $value->traveling_users);
            $travelers = [];
            foreach ($traveling_users as $key2 => $value2) {
                $travelers[] = str_replace('-', '', $value2);
            }
            $tagged_travels[$key]->travelers = User::select('name')->find($travelers);
        }

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
