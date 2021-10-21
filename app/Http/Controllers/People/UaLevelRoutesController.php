<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\UaRoute;
use App\UaLevel;
use App\UaRouteOption;
use App\UaLevelRoute;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UaLevelRoutesController extends Controller {
    
    public function index() {
        $ua_levels = UaLevel::orderBy('order', 'desc')->get();
        $ua_routes = UaRoute::orderBy('order', 'asc')->get();
        $ua_options = UaRouteOption::orderBy('order', 'asc')->get();
        $ua_level_routes = UaLevelRoute::get();

        return view('pages.people.ualevelroute.index')->with([
            'ua_levels' => $ua_levels,
            'ua_routes' => $ua_routes,
            'ua_options' => $ua_options,
            'ua_level_routes' => $ua_level_routes,
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'route.*' => ['required', 'exists:ua_routes,id'],
            'level.*' => ['required', 'exists:ua_levels,id'],
            'option.*' => ['required', 'exists:ua_route_options,id'],
        ]);

        foreach ($data['route'] as $key => $route) {
            $column = UaLevelRoute::where('ua_route_id', $data['route'][$key])->where('ua_level_id', $data['level'][$key]);

            if ($column->count() > 0) {
                UaLevelRoute::find($column->first()->id)->update([
                    'ua_route_option_id' => $data['option'][$key],
                    'updated_id' => auth()->id()
                ]);
            } else {
                $new_column = [];

                $new_column['ua_route_id'] = $data['route'][$key];
                $new_column['ua_level_id'] = $data['level'][$key];
                $new_column['ua_route_option_id'] = $data['option'][$key];

                $new_column['owner_id'] = auth()->id();
                $new_column['updated_id'] = auth()->id();

                UaLevelRoute::create($new_column);
            }
        }

        return back()->with('success', 'User Access Level Route'.__('messages.edit_success'));
    }
}
