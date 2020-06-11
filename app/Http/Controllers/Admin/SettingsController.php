<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settings;
use App\Role;
use Illuminate\Http\Request;

class SettingsController extends Controller {

    public function index() {
        return view('pages.admin.settings.index')->with([
            'settings' => Settings::get(),
            'role_2' => Role::where('id', 2)->first(),
            'role_3' => Role::where('id', 3)->first()
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
        ]);

        foreach ($data as $key => $value) {
            $settings = Settings::where('type', $key)->first();
            $settings->update(['value' => $value]);
        }

        return redirect('/settings')->with('success', 'Settings'.__('messages.edit_success'));
    }
}
