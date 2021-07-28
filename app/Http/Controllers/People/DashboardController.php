<?php

namespace App\Http\Controllers\People;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\User;

class DashboardController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        
        return view('pages.people.dashboard.index')->with([
            'user' => $user,
        ]);
    }
}
