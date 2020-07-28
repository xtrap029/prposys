<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller {
    
    public function index() {
        $companies = Company::orderBy('code', 'asc')->get();

        return view('pages.admin.company.index')->with([
            'companies' => $companies
        ]);
    }

    public function create() {
        return view('pages.admin.company.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('companies')->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:150'],
            'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $data['logo'] = basename($request->file('logo')->store('public/images/companies'));
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Company::create($data);

        return redirect('/company')->with('success', 'Company'.__('messages.create_success'));
    }

    public function edit(Company $company) {
        return view('pages.admin.company.edit')->with([
            'company' => $company
        ]);
    }

    public function update(Request $request, Company $company) {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('companies')->ignore($company->id)->whereNull('deleted_at')],
            'name' => ['required', 'string', 'max:150'],
            'logo' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ]);

        if($request->file('logo')) {
            Storage::delete('public/images/companies/' . $company->logo);
            $data['logo'] = basename($request->file('logo')->store('public/images/companies'));
        }

        $data['updated_id'] = auth()->id();

        $company->update($data);

        return redirect('/company')->with('success', 'Company '.__('messages.edit_success'));
    }

    public function destroy(Company $company) {
        $company->updated_id = auth()->id();
        $company->save(); 
        $company->delete();

        return redirect('/company')->with('success', 'Company'.__('messages.delete_success'));
    }
}
