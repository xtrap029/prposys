<?php

namespace App\Http\Controllers\Travels;

use App\TravelsRequestType;
use App\TravelsRequestTypeOption;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TravelsRequestTypeOptionsController extends Controller {
    public function create() {
        $request_types = TravelsRequestType::orderBy('name', 'asc')->get();

        return view('pages.travels.requesttypeoption.create')->with([
            'request_types' => $request_types
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required'],
            'travels_request_type_id' => ['required', 'exists:travels_request_types,id']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        TravelsRequestTypeOption::create($data);

        return redirect('/travels-request-type')->with('success', 'Request Type'.__('messages.create_success'));
    }

    public function edit(TravelsRequestTypeOption $request_type_option) {
        $request_types = TravelsRequestType::orderBy('name', 'desc')->get();

        return view('pages.travels.requesttypeoption.edit')->with([
            'request_type_option' => $request_type_option,
            'request_types' => $request_types
        ]);
    }

    public function update(Request $request, TravelsRequestTypeOption $request_type_option) {
        $data = $request->validate([
            'name' => ['required'],
            'travels_request_type_id' => ['required', 'exists:travels_request_types,id']
        ]);
        $data['updated_id'] = auth()->id();

        $request_type_option->update($data);

        return redirect('/travels-request-type')->with('success', 'Request Type'.__('messages.edit_success'));
    }

    public function destroy(TravelsRequestTypeOption $request_type_option) {
        $request_type_option->updated_id = auth()->id();
        $request_type_option->save();
        $request_type_option->delete();

        return redirect('/travels-request-type')->with('success', 'Request Type'.__('messages.delete_success'));
    }
}
