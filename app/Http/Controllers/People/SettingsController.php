<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Role;
use App\Settings;
use App\User;
use App\AppExternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller {

    public function index() {
        return view('pages.people.settings.index')->with([
            'settings' => Settings::get(),
            'app_externals' => AppExternal::orderBy('id', 'asc')->get(),
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
            'SITE_LOGO_RESOURCES' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'SITE_COLOR_RESOURCES' => ['required'],
            'SITE_LOGO_LOGOUT' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'SITE_BANNER_LOGIN' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:15480'],
            'SITE_BANNER_HOME' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:15480'],
            'SITE_DASHBOARD_SLIDER' => ['required'],
            'SITE_LOGIN_GREETING' => ['required'],
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

        if ($request->file('SITE_LOGO_RESOURCES')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_LOGO_RESOURCES')->first()->value);
            $data['SITE_LOGO_RESOURCES'] = basename($request->file('SITE_LOGO_RESOURCES')->store('public/images/site settings'));
        }

        if ($request->file('SITE_LOGO_LOGOUT')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_LOGO_LOGOUT')->first()->value);
            $data['SITE_LOGO_LOGOUT'] = basename($request->file('SITE_LOGO_LOGOUT')->store('public/images/site settings'));
        }

        if ($request->file('SITE_BANNER_LOGIN')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_BANNER_LOGIN')->first()->value);
            $data['SITE_BANNER_LOGIN'] = basename($request->file('SITE_BANNER_LOGIN')->store('public/images/site settings'));
        }
        
        if ($request->file('SITE_BANNER_HOME')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_BANNER_HOME')->first()->value);
            $data['SITE_BANNER_HOME'] = basename($request->file('SITE_BANNER_HOME')->store('public/images/site settings'));
        }

        foreach ($data as $key => $value) {
            $settings = Settings::where('type', $key)->first();
            $settings->update(['value' => $value]);
        }

        $apps = $request->validate([
            'app_externals_name.*' => ['required'],
            'app_externals_icon.*' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'app_externals_path.*' => ['required'],
            'app_externals_id.*' => ['sometimes'],
        ]);
        
        // $app_external_old = AppExternal::orderBy('id', 'asc')->get()->toArray();
        $app_external = [];

        // remove 1st
        // 2nd will have image of first

        if (array_key_exists('app_externals_name', $apps)) {
            foreach ($apps['app_externals_name'] as $key => $value) {
                $app_external[$key]['name'] = $value;
                $app_external[$key]['url'] = $apps['app_externals_path'][$key];
                
                if (array_key_exists('app_externals_icon', $apps) && array_key_exists($key, $apps['app_externals_icon'])) {
                    $app_external[$key]['icon'] = basename($request->file('app_externals_icon')[$key]->store('public/images/app-externals'));
                } else {
                    $current_existing = AppExternal::where('id', $apps['app_externals_id'][$key])->first();
                    
                    if ($current_existing) {
                        $app_external[$key]['icon'] = $current_existing->icon;
                    } else {
                        // no entry
                        return redirect('/people-settings')->with('success', 'Settings'.__('messages.edit_success'));
                    }
                }
            }
        }

        AppExternal::truncate();
        AppExternal::insert($app_external);


        return redirect('/people-settings')->with('success', 'Settings'.__('messages.edit_success'));
    }
}
