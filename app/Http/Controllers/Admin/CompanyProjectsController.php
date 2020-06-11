<?php

namespace App\Http\Controllers\Admin;

use App\CompanyProject;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyProjectsController extends Controller {

    public function index(Company $company) {
        $company_projects = CompanyProject::where('company_id', $company->id)->orderBy('project', 'asc')->get();
        
        return view('pages.admin.companyprojects.index')->with([
            'company_projects' => $company_projects,
            'company' => $company
        ]);
    }

    public function create(Company $company) {
        return view('pages.admin.companyprojects.create')->with([
            'company' => $company
        ]);
    }

    public function store(Request $request, Company $company) {
        $data = $request->validate([
            'project' => ['required', Rule::unique('company_projects')->where('company_id', $company->id)->whereNull('deleted_at')]
        ]);
        $data['company_id'] = $company->id;

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        CompanyProject::create($data);

        return redirect('company-project/'.$company->id)->with('success', 'Company Project'.__('messages.create_success'));
    }

    public function edit(CompanyProject $companyProject) {
        return view('pages.admin.companyprojects.edit')->with([
            'company_project' => $companyProject
        ]);
    }

    public function update(Request $request, CompanyProject $companyProject) {
        $data = $request->validate([
            'project' => ['required', Rule::unique('company_projects')->ignore($companyProject->id)->where('company_id', $companyProject->company_id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $companyProject->update($data);

        return redirect('/company-project/'.$companyProject->company_id)->with('success', 'Company Project'.__('messages.edit_success'));
    }

    public function destroy(CompanyProject $companyProject) {
        $companyProject->updated_id = auth()->id();
        $companyProject->save();
        $companyProject->delete();

        return redirect('/company-project/'.$companyProject->company_id)->with('success', 'Company Project'.__('messages.delete_success'));
    }
}
