<?php

namespace App\Http\Controllers\Travels;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\TravelRole;

class TravelsRolesController extends Controller {

    public function index() {
        $roles = TravelRole::orderBy('id', 'asc')->get();
        
        return view('pages.travels.role.index')->with([
            'roles' => $roles,
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'name.*' => ['required'],
            'id.*' => ['required', 'exists:travel_roles,id'],
        ]);

        foreach ($data['id'] as $key => $id) {
            $column = TravelRole::find($id);
            
            $column->timestamps = false;
            $column->update([
                'name' => $data['name'][$key]
            ]);
        }

        return back()->with('success', 'Travel Role'.__('messages.edit_success'));
    }
}
