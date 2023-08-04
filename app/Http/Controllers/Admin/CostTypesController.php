<?php

namespace App\Http\Controllers\Admin;

use App\CostType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CostTypesController extends Controller {

    public function index() {
        $cost_types = CostType::orderBy('control_no', 'asc')->get();
        return view('pages.admin.costtype.index')->with([
            'cost_types' => $cost_types
        ]);
    }

    public function create() {
        return view('pages.admin.costtype.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'control_no' => ['required', Rule::unique('cost_types')->whereNull('deleted_at')],
            'name' => ['required', Rule::unique('cost_types')->whereNull('deleted_at')],
            'description' => ['required']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        CostType::create($data);

        return redirect('/cost-type')->with('success', 'Cost Type'.__('messages.create_success'));
    }

    public function edit(CostType $cost_type) {
        return view('pages.admin.costtype.edit')->with([
            'cost_type' => $cost_type
        ]);
    }

    public function update(Request $request, CostType $cost_type) {
        $data = $request->validate([
            'control_no' => ['required', Rule::unique('cost_types')->ignore($cost_type->id)->whereNull('deleted_at')],
            'name' => ['required', Rule::unique('cost_types')->ignore($cost_type->id)->whereNull('deleted_at')],
            'description' => ['required', Rule::unique('cost_types')->ignore($cost_type->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $cost_type->update($data);

        return redirect('/cost-type')->with('success', 'Cost Type'.__('messages.edit_success'));
    }

    public function destroy(CostType $cost_type) {
        $cost_type->updated_id = auth()->id();
        $cost_type->save();
        $cost_type->delete();

        return redirect('/cost-type')->with('success', 'Cost Type'.__('messages.delete_success'));
    }
}
