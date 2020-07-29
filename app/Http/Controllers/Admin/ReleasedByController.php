<?php

namespace App\Http\Controllers\Admin;

use App\ReleasedBy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReleasedByController extends Controller {

    public function index() {
        $released_by = ReleasedBy::orderBy('id', 'asc')->get();
        return view('pages.admin.releasedby.index')->with([
            'released_by' => $released_by
        ]);
    }

    public function create() {
        return view('pages.admin.releasedby.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('released_by')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        ReleasedBy::create($data);

        return redirect('/released-by')->with('success', 'Released By'.__('messages.create_success'));
    }

    public function edit(ReleasedBy $released_by) {
        return view('pages.admin.releasedby.edit')->with([
            'released_by' => $released_by
        ]);
    }

    public function update(Request $request, ReleasedBy $released_by) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('released_by')->ignore($released_by->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $released_by->update($data);

        return redirect('/released-by')->with('success', 'Released By'.__('messages.edit_success'));
    }

    public function destroy(ReleasedBy $released_by) {
        $released_by->updated_id = auth()->id();
        $released_by->save();
        $released_by->delete();

        return redirect('/released-by')->with('success', 'Released By'.__('messages.delete_success'));
    }
}
