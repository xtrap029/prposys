<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\Faq;
use App\Form;
use App\User;
use App\Settings;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        $announcement = Settings::where('type', 'ANNOUNCEMENT')->first()->value;
        $forms = Form::orderBy('updated_at', 'desc')->limit(10)->get();
        $faqs_updated = Faq::orderBy('updated_at', 'desc')->limit(5)->get();
        $faqs_recent = Faq::orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('pages.resources.dashboard.index')->with([
            'user' => $user,
            'forms' => $forms,
            'faqs_updated' => $faqs_updated,
            'faqs_recent' => $faqs_recent,
            'announcement' => $announcement,
        ]);
    }
}
