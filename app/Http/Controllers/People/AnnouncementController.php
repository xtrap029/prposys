<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Role;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller {

    public function index() {
        return view('pages.people.announcement.index')->with([
            'announcement' => Settings::where('type', 'ANNOUNCEMENT')->first()->value,
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'announcement' => [],
        ]);

        $settings = Settings::where('type', 'ANNOUNCEMENT')->first();
        $settings->update(['value' => $data['announcement']]);

        return redirect('/people-announcement')->with('success', 'Announcement'.__('messages.edit_success'));
    }
}
