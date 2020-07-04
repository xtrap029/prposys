<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\VatType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VatTypesController extends Controller {
    
    public function index() {
        $vat_types = VatType::orderBy('id', 'asc')->get();

        return view('pages.admin.vattype.index')->with([
            'vat_types' => $vat_types
        ]);
    }

    public function create() {
        return view('pages.admin.vattype.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('vat_types')->whereNull('deleted_at')],
            'name' => ['required'],
            'vat' => ['required', 'integer', 'max:100'],
            'wht' => ['required', 'integer', 'max:100'],
            'is_pr' => ['required', 'boolean'],
            'is_po' => ['required', 'boolean'],
            'is_pc' => ['required', 'boolean']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        VatType::create($data);

        return redirect('/vat-type')->with('success', 'VAT Type'.__('messages.create_success'));
    }

    public function edit(VatType $vatType) {
        return view('pages.admin.vattype.edit')->with([
            'vat_type' => $vatType
        ]);
    }

    public function update(Request $request, VatType $vatType) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('vat_types')->ignore($vatType->id)->whereNull('deleted_at')],
            'name' => ['required'],
            'vat' => ['required', 'integer', 'max:100'],
            'wht' => ['required', 'integer', 'max:100'],
            'is_pr' => ['required', 'boolean'],
            'is_po' => ['required', 'boolean'],
            'is_pc' => ['required', 'boolean']
        ]);
        $data['updated_id'] = auth()->id();

        $vatType->update($data);

        return redirect('/vat-type')->with('success', 'VAT Type'.__('messages.edit_success'));
    }

    public function destroy(VatType $vatType) {
        $vatType->updated_id = auth()->id();
        $vatType->save();
        $vatType->delete();

        return redirect('/vat-type')->with('success', 'VAT Type'.__('messages.delete_success'));
    }
}
