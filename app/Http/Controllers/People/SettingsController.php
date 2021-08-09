<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Role;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller {

    public function index() {
        return view('pages.people.settings.index')->with([
            'settings' => Settings::get(),
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'SESSION_LIFETIME' => ['required', 'integer'],
            'FOOTER_LABEL' => ['required'],
            'SITE_LOGO' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'SITE_COLOR' => ['required'],
            'SITE_LOGO_LEAVES' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'SITE_COLOR_LEAVES' => ['required'],
            'SITE_LOGO_PEOPLE' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'SITE_COLOR_PEOPLE' => ['required'],
        ]);

        if ($request->file('SITE_LOGO')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_LOGO')->first()->value);
            $data['SITE_LOGO'] = basename($request->file('SITE_LOGO')->store('public/images/site settings'));
        }

        if ($request->file('SITE_LOGO_LEAVES')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_LOGO_LEAVES')->first()->value);
            $data['SITE_LOGO_LEAVES'] = basename($request->file('SITE_LOGO_LEAVES')->store('public/images/site settings'));
        }

        if ($request->file('SITE_LOGO_PEOPLE')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_LOGO_PEOPLE')->first()->value);
            $data['SITE_LOGO_PEOPLE'] = basename($request->file('SITE_LOGO_PEOPLE')->store('public/images/site settings'));
        }

        foreach ($data as $key => $value) {
            $settings = Settings::where('type', $key)->first();
            $settings->update(['value' => $value]);
        }

        return redirect('/people-settings')->with('success', 'Settings'.__('messages.edit_success'));
    }
}