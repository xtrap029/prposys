<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller {

    public function index() {
        return view('pages.admin.settings.index')->with([
            'settings' => Settings::get(),
            'role_2' => Role::where('id', 2)->first(),
            'role_3' => Role::where('id', 3)->first(),
            'users' => User::whereNotNull('role_id')->orderBy('name', 'asc')->get()
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['required', 'integer'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => ['required', 'integer'],
            'LIMIT_EDIT_GENPR_USER_2' => ['required', 'integer'],
            'LIMIT_EDIT_GENPR_USER_3' => ['required', 'integer'],
            'LIMIT_EDIT_PRPOFORM_USER_2' => ['required', 'integer'],
            'LIMIT_EDIT_PRPOFORM_USER_3' => ['required', 'integer'],
            'LIMIT_EDIT_LIQFORM_USER_2' => ['required', 'integer'],
            'LIMIT_EDIT_LIQFORM_USER_3' => ['required', 'integer'],
            'LIMIT_EDIT_DEPOSIT_USER_2' => ['required', 'integer'],
            'AUTHORIZED_BY' => ['required', 'exists:users,id'],
            'SESSION_LIFETIME' => ['required', 'integer'],
            'SITE_LOGO' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:548'],
            'FOOTER_LABEL' => ['required'],
        ]);

        if ($request->file('SITE_LOGO')) {
            Storage::delete('public/images/site settings/' . Settings::where('type', 'SITE_LOGO')->first()->value);
            $data['SITE_LOGO'] = basename($request->file('SITE_LOGO')->store('public/images/site settings'));
        }

        foreach ($data as $key => $value) {
            $settings = Settings::where('type', $key)->first();
            $settings->update(['value' => $value]);
        }

        return redirect('/settings')->with('success', 'Settings'.__('messages.edit_success'));
    }
}
