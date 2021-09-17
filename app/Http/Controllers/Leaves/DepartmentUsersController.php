<?php

namespace App\Http\Controllers\Leaves;

use App\Department;
use App\DepartmentsUser;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentUsersController extends Controller {
    public function create(Department $department) {
        $departments = Department::orderBy('name', 'asc')->get();
        $department_users = DepartmentsUser::where('department_id', $department->id)->pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $department_users)->orderBy('name', 'asc')->get();

        return view('pages.leaves.departmentuser.create')->with([
            'departments' => $departments,
            'users' => $users,
            'department_sel' => $department
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'is_approver' => ['required', 'between:0,1'],
        ]);

        
        if (DepartmentsUser::where('department_id', $request->department_id)->where('user_id', $request->user_id)->count()) {
            return back()->with('error', __('messages.invalid_command'));
        }

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        DepartmentsUser::create($data);

        return redirect('/leaves-department')->with('success', 'Department Member'.__('messages.create_success'));
    }

    public function edit(DepartmentsUser $department_user) {
        return view('pages.leaves.departmentuser.edit')->with([
            'department_user' => $department_user
        ]);
    }

    public function update(Request $request, DepartmentsUser $department_user) {
        $data = $request->validate([
            'is_approver' => ['required', 'between:0,1']
        ]);
        $data['updated_id'] = auth()->id();

        $department_user->update($data);

        return redirect('/leaves-department')->with('success', 'Department Member'.__('messages.edit_success'));
    }

    public function destroy(DepartmentsUser $department_user) {
        $department_user->updated_id = auth()->id();
        $department_user->save();
        $department_user->delete();

        return redirect('/leaves-department')->with('success', 'Department Member'.__('messages.delete_success'));
    }
}
