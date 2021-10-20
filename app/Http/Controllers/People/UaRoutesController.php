<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\UaRoute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UaRoutesController extends Controller {
    
    public function index() {
        $ua_routes = UaRoute::orderBy('order', 'asc')->get();

        return view('pages.people.uaroute.index')->with([
            'ua_routes' => $ua_routes
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'name.*' => ['required'],
            'id.*' => ['required', 'exists:ua_routes,id'],
        ]);

        foreach ($data['id'] as $key => $id) {
            $column = UaRoute::find($id)->update([
                'name' => $data['name'][$key]
            ]);
        }

        return back()->with('success', 'User Access Route'.__('messages.edit_success'));
    }
}
