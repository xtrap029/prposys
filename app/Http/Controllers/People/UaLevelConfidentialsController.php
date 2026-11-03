<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\UaLevel;
use App\UaLevelConfidential;
use App\Helpers\UAHelper;
use Illuminate\Http\Request;

class UaLevelConfidentialsController extends Controller {

    public function index() {
        $ua_levels = UaLevel::where('id', '!=', config('global.ua_inactive'))->orderBy('order', 'desc')->get();
        $ua_level_confidentials = UaLevelConfidential::get();

        return view('pages.people.ualevelconfidential.index')->with([
            'ua_levels' => $ua_levels,
            'ua_level_confidentials' => $ua_level_confidentials,
            'can_manage' => UAHelper::get()['peo_confidential_manage'] != config('global.ua_none'),
        ]);
    }

    public function update(Request $request) {
        $data = $request->validate([
            'level' => ['required', 'exists:ua_levels,id'],
            'viewer' => ['nullable', 'array'],
            'viewer.*' => ['exists:ua_levels,id'],
        ]);

        $viewers = array_unique($data['viewer'] ?? []);

        // delete and create per row so each change is recorded in the activity log
        UaLevelConfidential::where('ua_level_id', $data['level'])
            ->whereNotIn('viewer_ua_level_id', $viewers)
            ->get()
            ->each->delete();

        $existing = UaLevelConfidential::where('ua_level_id', $data['level'])->pluck('viewer_ua_level_id')->all();

        foreach (array_diff($viewers, $existing) as $viewer) {
            UaLevelConfidential::create([
                'ua_level_id' => $data['level'],
                'viewer_ua_level_id' => $viewer,
                'owner_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Confidential Visibility'.__('messages.edit_success'));
    }
}
