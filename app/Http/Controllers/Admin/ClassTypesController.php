<?php

namespace App\Http\Controllers\Admin;

use App\ClassType;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassTypesController extends Controller {

    public function index() {
        $class_types = ClassType::orderBy('code', 'asc')->get();

        return view('pages.admin.classtype.index')->with([
            'class_types' => $class_types,
        ]);
    }

    public function create() {
        return view('pages.admin.classtype.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('class_types')->whereNull('deleted_at')],
            'name' => ['required'],
        ]);

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        ClassType::create($data);

        return redirect('/class-type')->with('success', 'Class Type'.__('messages.create_success'));
    }

    public function edit(ClassType $class_type) {

        return view('pages.admin.classtype.edit')->with([
            'class_type' => $class_type,
        ]);
    }

    public function update(Request $request, ClassType $class_type) {
        $data = $request->validate([
            'code' => ['required', Rule::unique('class_types')->ignore($class_type->id)->whereNull('deleted_at')],
            'name' => ['required'],
        ]);
        $data['updated_id'] = auth()->id();
        $class_type->update($data);

        return redirect('/class-type')->with('success', 'Classs Type'.__('messages.edit_success'));
    }

    public function destroy(ClassType $class_type) {
        $class_type->updated_id = auth()->id();
        $class_type->save();
        $class_type->delete();

        return redirect('/class-type')->with('success', 'Class Type'.__('messages.delete_success'));
    }
}
