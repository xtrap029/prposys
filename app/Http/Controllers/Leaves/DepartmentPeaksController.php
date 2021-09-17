<?php

namespace App\Http\Controllers\Leaves;

use App\Department;
use App\DepartmentsPeak;
use App\DepartmentsUser;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentPeaksController extends Controller {

    public function index(Department $department) {
        return view('pages.leaves.departmentpeak.index')->with([
            'department' => $department
        ]);
    }
    
    public function create(Department $department) {
        return view('pages.leaves.departmentpeak.create')->with([
            'department' => $department
        ]);
    }

    public function store(Request $request, Department $department) {
        $data = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'remarks' => ['required']
        ]);
        $data['department_id'] = $department->id;

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        DepartmentsPeak::create($data);

        return redirect('leaves-department-peak/'.$department->id)->with('success', 'Department Peak'.__('messages.create_success'));
    }

    public function edit(DepartmentsPeak $departmentpeak) {
        return view('pages.leaves.departmentpeak.edit')->with([
            'department_peak' => $departmentpeak
        ]);
    }

    public function update(Request $request, DepartmentsPeak $departmentpeak) {
        $data = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'remarks' => ['required']
        ]);
        $data['updated_id'] = auth()->id();

        $departmentpeak->update($data);

        return redirect('/leaves-department-peak/'.$departmentpeak->department_id)->with('success', 'Department Peak'.__('messages.edit_success'));
    }

    public function destroy(DepartmentsPeak $departmentpeak) {
        $departmentpeak->updated_id = auth()->id();
        $departmentpeak->save();
        $departmentpeak->delete();

        return redirect('/leaves-department-peak/'.$departmentpeak->department_id)->with('success', 'Department Peak'.__('messages.delete_success'));
    }

    public function index_my() {
        return view('pages.leaves.departmentpeakmy.index')->with([
            // 'department' => $department
        ]);
    }

    public function create_my(Department $department) {
        if (DepartmentsUser::where('user_id', auth()->id())->where('department_id', $department->id)->where('is_approver', 1)->get()->count() == 0) {
            return back()->with('error', __('messages.cant_create'));
        }

        return view('pages.leaves.departmentpeakmy.create')->with([
            'department' => $department
        ]);
    }

    public function store_my(Request $request, Department $department) {
        if (DepartmentsUser::where('user_id', auth()->id())->where('department_id', $department->id)->where('is_approver', 1)->get()->count() == 0) {
            return redirect('leaves-department-peak/my')->with('error', __('messages.cant_create'));
        }

        $data = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'remarks' => ['required']
        ]);
        $data['department_id'] = $department->id;

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        DepartmentsPeak::create($data);

        return redirect('leaves-department-peak/my')->with('success', 'Department Peak'.__('messages.create_success'));
    }

    public function edit_my(DepartmentsPeak $departmentpeak) {
        if (DepartmentsUser::where('user_id', auth()->id())->where('department_id', $departmentpeak->department_id)->where('is_approver', 1)->get()->count() == 0) {
            return redirect('leaves-department-peak/my')->with('error', __('messages.cant_edit'));
        }

        return view('pages.leaves.departmentpeakmy.edit')->with([
            'department_peak' => $departmentpeak
        ]);
    }

    public function update_my(Request $request, DepartmentsPeak $departmentpeak) {
        if (DepartmentsUser::where('user_id', auth()->id())->where('department_id', $departmentpeak->department_id)->where('is_approver', 1)->get()->count() == 0) {
            return redirect('leaves-department-peak/my')->with('error', __('messages.cant_edit'));
        }

        $data = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
            'remarks' => ['required']
        ]);
        $data['updated_id'] = auth()->id();

        $departmentpeak->update($data);

        return redirect('/leaves-department-peak/my')->with('success', 'Department Peak'.__('messages.edit_success'));
    }

    public function destroy_my(DepartmentsPeak $departmentpeak) {
        if (DepartmentsUser::where('user_id', auth()->id())->where('department_id', $departmentpeak->department_id)->where('is_approver', 1)->get()->count() == 0) {
            return redirect('leaves-department-peak/my')->with('error', __('messages.cant_delete'));
        }

        $departmentpeak->updated_id = auth()->id();
        $departmentpeak->save();
        $departmentpeak->delete();

        return redirect('/leaves-department-peak/my')->with('success', 'Department Peak'.__('messages.delete_success'));
    }
}
