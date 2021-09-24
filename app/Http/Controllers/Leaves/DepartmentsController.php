<?php

namespace App\Http\Controllers\Leaves;

use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentsController extends Controller {

    public function index() {
        return view('pages.leaves.department.index')->with([
            'departments' => Department::orderBy('name', 'asc')->get()
        ]);
    }
    
    public function create() {
        return view('pages.leaves.department.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('departments')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Department::create($data);

        return redirect('/leaves-department')->with('success', 'Department'.__('messages.create_success'));
    }

    public function edit(Department $leavesDepartment) {
        return view('pages.leaves.department.edit')->with([
            'department' => $leavesDepartment
        ]);
    }

    public function update(Request $request, Department $leavesDepartment) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('departments')->ignore($leavesDepartment->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $leavesDepartment->update($data);

        return redirect('/leaves-department')->with('success', 'Department'.__('messages.edit_success'));
    }

    public function destroy(Department $leavesDepartment) {
        $leavesDepartment->updated_id = auth()->id();
        $leavesDepartment->save();
        $leavesDepartment->delete();

        return redirect('/leaves-department')->with('success', 'Department'.__('messages.delete_success'));
    }

    public function index_my(Department $leavesDepartment) {
        return view('pages.leaves.departmentmy.index')->with([
            'department' => $leavesDepartment,
        ]);
    }
}
