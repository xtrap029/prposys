<?php

namespace App\Http\Controllers\Admin;

use App\PurposeOption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurposesController extends Controller {

    public function index() {
        $purposes = PurposeOption::orderBy('code', 'asc')->get();
        return view('pages.admin.purpose.index')->with([
            'purposes' => $purposes
        ]);
    }

    public function create() {
        return view('pages.admin.purpose.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('purpose_options')->whereNull('deleted_at')],
            'name' => ['required', Rule::unique('purpose_options')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        PurposeOption::create($data);

        return redirect('/purpose')->with('success', 'Purpose'.__('messages.create_success'));
    }

    public function edit(PurposeOption $purpose) {
        return view('pages.admin.purpose.edit')->with([
            'purpose' => $purpose
        ]);
    }

    public function update(Request $request, PurposeOption $purpose) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('purpose_options')->ignore($purpose->id)->whereNull('deleted_at')],
            'name' => ['required', Rule::unique('purpose_options')->ignore($purpose->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $purpose->update($data);

        return redirect('/purpose')->with('success', 'Purpose'.__('messages.edit_success'));
    }

    public function destroy(PurposeOption $purpose) {
        $purpose->updated_id = auth()->id();
        $purpose->save();
        $purpose->delete();

        return redirect('/purpose')->with('success', 'Purpose'.__('messages.delete_success'));
    }
}
