<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class VendorsController extends Controller {

    public function index() {
        $vendors = Vendor::orderBy('name', 'asc')->get();
        return view('pages.admin.vendor.index')->with([
            'vendors' => $vendors
        ]);
    }

    public function create() {
        return view('pages.admin.vendor.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('vendors')->whereNull('deleted_at')],
            'address' => ['required'],
            'contact_no' => ['required'],
            'contact_person' => ['required'],
            'email' => ['required', 'email'],
            'tin' => ['required'],
            'account_bank' => ['required'],
            'account_name' => ['required'],
            'account_number' => ['required'],
            'file' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
        ]);
        $data['file'] = basename($request->file('file')->store('public/attachments/2303'));
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Vendor::create($data);

        return redirect('/vendor')->with('success', 'Vendor'.__('messages.create_success'));
    }

    public function edit(Vendor $vendor) {
        return view('pages.admin.vendor.edit')->with([
            'vendor' => $vendor
        ]);
    }

    public function update(Request $request, Vendor $vendor) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('vendors')->ignore($vendor->id)->whereNull('deleted_at')],
            'address' => ['required'],
            'contact_no' => ['required'],
            'contact_person' => ['required'],
            'email' => ['required', 'email'],
            'tin' => ['required'],
            'account_bank' => ['required'],
            'account_name' => ['required'],
            'account_number' => ['required'],
            'file' => ['sometimes', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
        ]);

        if($request->file('file')) {
            Storage::delete('public/attachments/2303/' . $vendor->file);
            $data['file'] = basename($request->file('file')->store('public/attachments/2303'));
        }

        $data['updated_id'] = auth()->id();

        $vendor->update($data);

        return redirect('/vendor')->with('success', 'Vendor'.__('messages.edit_success'));
    }

    public function destroy(Vendor $vendor) {
        $vendor->updated_id = auth()->id();
        $vendor->save();
        $vendor->delete();

        return redirect('/vendor')->with('success', 'Vendor'.__('messages.delete_success'));
    }
}
