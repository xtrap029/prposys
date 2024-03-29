<?php

namespace App\Http\Controllers\Admin;

use App\CoaTagging;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CoaTaggingController extends Controller {

    public function index() {
        return view('pages.admin.coatagging.index')->with([
            'companies' => Company::orderBy('name', 'asc')->get(),
            'coanull' => CoaTagging::whereNull('company_id')->orderBy('name', 'asc')->get()
        ]);
    }

    public function create() {
        $companies = Company::orderBy('name', 'desc')->get();
        
        return view('pages.admin.coatagging.create')->with([
            'companies' => $companies
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required'],
            'notes' => ['nullable', 'string'],
            'company_id' => ['sometimes', 'nullable', 'exists:companies,id']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        CoaTagging::create($data);

        return redirect('/coa-tagging')->with('success', 'Category/Class'.__('messages.create_success'));
    }

    public function edit(CoaTagging $coaTagging) {
        $companies = Company::orderBy('name', 'desc')->get();

        return view('pages.admin.coatagging.edit')->with([
            'coa_tagging' => $coaTagging,
            'companies' => $companies
        ]);
    }

    public function update(Request $request, CoaTagging $coaTagging) {
        $data = $request->validate([
            'name' => ['required'],
            'notes' => ['nullable', 'string'],
            'company_id' => ['sometimes', 'nullable', 'exists:companies,id']
        ]);
        $data['updated_id'] = auth()->id();

        $coaTagging->update($data);

        return redirect('/coa-tagging')->with('success', 'Category/Class'.__('messages.edit_success'));
    }

    public function destroy(CoaTagging $coaTagging) {
        $coaTagging->updated_id = auth()->id();
        $coaTagging->save();
        $coaTagging->delete();

        return redirect('/coa-tagging')->with('success', 'Category/Class'.__('messages.delete_success'));
    }
}
