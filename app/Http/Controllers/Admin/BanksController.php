<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BanksController extends Controller {
    public function index() {
        $banks = Bank::orderBy('id', 'asc')->get();
        return view('pages.admin.bank.index')->with([
            'banks' => $banks
        ]);
    }

    public function create() {
        return view('pages.admin.bank.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('banks')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Bank::create($data);

        return redirect('/bank')->with('success', 'Bank'.__('messages.create_success'));
    }

    public function edit(Bank $bank) {
        return view('pages.admin.bank.edit')->with([
            'bank' => $bank
        ]);
    }

    public function update(Request $request, Bank $bank) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('banks')->ignore($bank->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $bank->update($data);

        return redirect('/bank')->with('success', 'Bank'.__('messages.edit_success'));
    }

    public function destroy(Bank $bank) {
        $bank->updated_id = auth()->id();
        $bank->save();
        $bank->delete();

        return redirect('/bank')->with('success', 'Bank'.__('messages.delete_success'));
    }
}
