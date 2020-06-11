<?php

namespace App\Http\Controllers\Admin;

use App\ExpenseType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseTypesController extends Controller {

    public function index() {
        return view('pages.admin.expensetype.index')->with([
            'expense_types' => ExpenseType::orderBy('name', 'asc')->get()
        ]);
    }
    
    public function create() {
        return view('pages.admin.expensetype.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('expense_types')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        ExpenseType::create($data);

        return redirect('/expense-type')->with('success', 'Expense Type'.__('messages.create_success'));
    }

    public function edit(ExpenseType $expenseType) {
        return view('pages.admin.expensetype.edit')->with([
            'expense_type' => $expenseType
        ]);
    }

    public function update(Request $request, ExpenseType $expenseType) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('expense_types')->ignore($expenseType->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $expenseType->update($data);

        return redirect('/expense-type')->with('success', 'Expense Type'.__('messages.edit_success'));
    }

    public function destroy(ExpenseType $expenseType) {
        $expenseType->updated_id = auth()->id();
        $expenseType->save();
        $expenseType->delete();

        return redirect('/expense-type')->with('success', 'Expense Type'.__('messages.delete_success'));
    }
}
