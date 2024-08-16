<?php

namespace App\Http\Controllers\Admin;

use App\PurposeOption;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurposesController extends Controller {

    public function index() {
        $purposes = PurposeOption::orderBy('code', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();

        return view('pages.admin.purpose.index')->with([
            'purposes' => $purposes,
            'companies' => $companies,
        ]);
    }

    public function create() {
        $companies = Company::orderBy('name', 'asc')->get();
        return view('pages.admin.purpose.create')->with([
            'companies' => $companies
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('purpose_options')->whereNull('deleted_at')],
            'name' => ['required'],
            'description' => ['required'],
            'companies.*' => [],
        ]);

        if ($request->companies) {
            $data['companies'] = implode(',',$data['companies']);
        }

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        PurposeOption::create($data);

        return redirect('/purpose')->with('success', 'Purpose'.__('messages.create_success'));
    }

    public function edit(PurposeOption $purpose) {
        $companies = Company::orderBy('name', 'asc')->get();

        return view('pages.admin.purpose.edit')->with([
            'purpose' => $purpose,
            'companies' => $companies
        ]);
    }

    public function update(Request $request, PurposeOption $purpose) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('purpose_options')->ignore($purpose->id)->whereNull('deleted_at')],
            'name' => ['required'],
            'description' => ['required'],
            'companies.*' => [],
        ]);
        $data['companies'] = implode(',', $request->companies ? $data['companies'] : []);
        $data['updated_id'] = auth()->id();

        $purpose->update($data);

        return redirect('/purpose')->with('success', 'Purpose'.__('messages.edit_success'));
    }

    public function batch(Request $request) {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'purpose.*' => ['required', 'exists:purpose_options,id'],
        ]);

        $data['purpose'] = isset($data['purpose']) ? $data['purpose'] : [];

        foreach (PurposeOption::get() as $item) {
            if (in_array($item->id, $data['purpose'])) {
                if (!in_array($data['company_id'], explode(',', $item->companies))) {
                    $purpose_companies = implode(',', array_merge(explode(',', $item->companies), [$data['company_id']]));
                    $purpose_option = PurposeOption::find($item->id)->update([
                        'companies' => $purpose_companies
                    ]);
                }
            } else {
                if (in_array($data['company_id'], explode(',', $item->companies))) {
                    $purpose_companies =  implode(',', array_diff(explode(',', $item->companies), [$data['company_id']]));
                    $purpose_option = PurposeOption::find($item->id)->update([
                        'companies' => $purpose_companies
                    ]);
                }
            }         
        }
        
        return redirect('/purpose?company='.$data['company_id'])->with('success', 'Purpose'.__('messages.edit_success'));
    }

    public function destroy(PurposeOption $purpose) {
        $purpose->updated_id = auth()->id();
        $purpose->save();
        $purpose->delete();

        return redirect('/purpose')->with('success', 'Purpose'.__('messages.delete_success'));
    }
}
