<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\UaLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UaLevelsController extends Controller {
    
    public function index() {
        $ua_levels = UaLevel::orderBy('order', 'asc')->get();

        return view('pages.people.ualevel.index')->with([
            'ua_levels' => $ua_levels
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'name.*' => ['required'],
            'id.*' => ['required', 'exists:ua_levels,id'],
        ]);

        foreach ($data['id'] as $key => $id) {
            $column = UaLevel::find($id)->update([
                'name' => $data['name'][$key]
            ]);
        }

        return back()->with('success', 'User Access Level'.__('messages.edit_success'));
    }
}
