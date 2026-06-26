<?php

namespace App\Http\Controllers\Admin;

use App\ClassType;
use App\ClassTypeCompanyApprover;
use App\Company;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassTypeCompanyApproversController extends Controller {

    public function index(Company $company) {
        $classTypes = ClassType::whereRaw("FIND_IN_SET(?, companies)", [$company->id])
            ->orderBy('name')
            ->get();

        $approvers = ClassTypeCompanyApprover::where('company_id', $company->id)
            ->with('user', 'classtype')
            ->get()
            ->groupBy('class_type_id');

        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();

        return view('pages.admin.classtypecompanyapprovers.index')->with([
            'company'    => $company,
            'classTypes' => $classTypes,
            'approvers'  => $approvers,
            'users'      => $users,
        ]);
    }

    public function store(Request $request, Company $company) {
        $data = $request->validate([
            'class_type_id' => 'required|exists:class_types,id',
            'user_id'       => 'required|exists:users,id',
        ]);

        ClassTypeCompanyApprover::firstOrCreate([
            'class_type_id' => $data['class_type_id'],
            'company_id'    => $company->id,
            'user_id'       => $data['user_id'],
        ]);

        return redirect("/company-class-approver/{$company->id}")->with('success', 'Approver added.');
    }

    public function destroy(ClassTypeCompanyApprover $classTypeCompanyApprover) {
        $company_id = $classTypeCompanyApprover->company_id;
        $classTypeCompanyApprover->delete();

        return redirect("/company-class-approver/{$company_id}")->with('success', 'Approver removed.');
    }
}
