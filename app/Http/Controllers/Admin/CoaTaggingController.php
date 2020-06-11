<?php

namespace App\Http\Controllers\Admin;

use App\CoaTagging;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CoaTaggingController extends Controller {

    public function index() {
        return view('pages.admin.coatagging.index')->with([
            'coa_taggings' => CoaTagging::orderBy('name', 'asc')->get()
        ]);
    }

    public function create() {
        return view('pages.admin.coatagging.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('coa_taggings')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        CoaTagging::create($data);

        return redirect('/coa-tagging')->with('success', 'COA Tagging'.__('messages.create_success'));
    }

    public function edit(CoaTagging $coaTagging) {
        return view('pages.admin.coatagging.edit')->with([
            'coa_tagging' => $coaTagging
        ]);
    }

    public function update(Request $request, CoaTagging $coaTagging) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('coa_taggings')->ignore($coaTagging->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $coaTagging->update($data);

        return redirect('/coa-tagging')->with('success', 'COA Tagging'.__('messages.edit_success'));
    }

    public function destroy(CoaTagging $coaTagging) {
        $coaTagging->updated_id = auth()->id();
        $coaTagging->save();
        $coaTagging->delete();

        return redirect('/coa-tagging')->with('success', 'COA Tagging'.__('messages.delete_success'));
    }
}
