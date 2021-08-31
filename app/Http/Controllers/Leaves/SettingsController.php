<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Role;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller {

    public function index() {
        return view('pages.leaves.settings.index')->with([
            'settings' => Settings::get(),
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'LEAVES_ANNUAL' => ['required', 'integer', 'min:0'],
            'LEAVES_CARRY' => ['required', 'integer', 'min:0'],
            'LEAVES_EXPIRY' => ['required', 'integer', 'between:1,12'],
            'LEAVES_FILING_DAYS' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data as $key => $value) {
            $settings = Settings::where('type', $key)->first();
            $settings->update(['value' => $value]);
        }

        return redirect('/leaves-settings')->with('success', 'Settings'.__('messages.edit_success'));
    }
}
