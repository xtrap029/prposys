<?php

namespace App\Http\Controllers\Travels;

use App\Http\Controllers\Controller;
use App\TravelsRequestType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class TravelsRequestTypesController extends Controller {
    
    public function index(Request $request) {
        $request_types = TravelsRequestType::orderBy('name', 'asc')->get();

        
        return view('pages.travels.requesttype.index')->with([
            'request_types' => $request_types,
        ]);
    }

    public function create() {
        return view('pages.travels.requesttype.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('travels_request_types')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        TravelsRequestType::create($data);

        return redirect('/travels-request-type')->with('success', 'Request Type'.__('messages.create_success'));
    }

    public function edit(TravelsRequestType $travels_request_type) {
        return view('pages.travels.requesttype.edit')->with([
            'travels_request_type' => $travels_request_type
        ]);
    }

    public function update(Request $request, TravelsRequestType $travels_request_type) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('travels_request_types')->ignore($travels_request_type->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $travels_request_type->update($data);

        return redirect('/travels-request-type')->with('success', 'Request Type'.__('messages.edit_success'));
    }

    public function destroy(TravelsRequestType $travels_request_type) {
        $travels_request_type->updated_id = auth()->id();
        $travels_request_type->save();
        $travels_request_type->delete();

        return redirect('/travels-request-type')->with('success', 'Request Type'.__('messages.delete_success'));
    }
}
