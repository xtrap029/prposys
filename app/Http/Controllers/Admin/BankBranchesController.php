<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use App\BankBranch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BankBranchesController extends Controller {
    public function create() {
        $banks = Bank::orderBy('name', 'asc')->get();

        return view('pages.admin.bankbranch.create')->with([
            'banks' => $banks
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required'],
            'bank_id' => ['required', 'exists:banks,id']
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        BankBranch::create($data);

        return redirect('/bank')->with('success', 'Bank Branch'.__('messages.create_success'));
    }

    public function edit(BankBranch $bank_branch) {
        $banks = Bank::orderBy('name', 'desc')->get();

        return view('pages.admin.bankbranch.edit')->with([
            'bank_branch' => $bank_branch,
            'banks' => $banks
        ]);
    }

    public function update(Request $request, BankBranch $bank_branch) {
        $data = $request->validate([
            'name' => ['required'],
            'bank_id' => ['required', 'exists:banks,id']
        ]);
        $data['updated_id'] = auth()->id();

        $bank_branch->update($data);

        return redirect('/bank')->with('success', 'Bank Branch'.__('messages.edit_success'));
    }

    public function destroy(BankBranch $bank_branch) {
        $bank_branch->updated_id = auth()->id();
        $bank_branch->save();
        $bank_branch->delete();

        return redirect('/bank')->with('success', 'Bank Branch'.__('messages.delete_success'));
    }
}
