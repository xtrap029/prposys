<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Particulars;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticularsController extends Controller {

    public function index() {
        $particulars_pr = Particulars::where('type', 'pr')->orderBy('name', 'asc')->get();
        $particulars_po = Particulars::where('type', 'po')->orderBy('name', 'asc')->get();

        return view('pages.admin.particular.index')->with([
            'particulars_pr' => $particulars_pr,
            'particulars_po' => $particulars_po
        ]);
    }

    public function create() {
        if (!empty($_GET['type']) && in_array($_GET['type'], ['pr', 'po'])) {
            return view('pages.admin.particular.create')->with([
                'type' => $_GET['type']
            ]);
        } else {
            return redirect('/particular');
        }
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('particulars')->where('type', $request->type)->whereNull('deleted_at')],
            'notes' => ['nullable', 'string'],
            'type' => ['required'],
        ]);

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Particulars::create($data);

        return redirect('/particular')->with('success', 'Particulars'.__('messages.create_success'));
    }

    public function edit(Particulars $particular) {
        return view('pages.admin.particular.edit')->with([
            'particular' => $particular
        ]);
    }

    public function update(Request $request, Particulars $particular) {
        $data = $request->validate([
            'name' => ['required', Rule::unique('particulars')->ignore($particular->id)->where('type', $particular->type)->whereNull('deleted_at')],
            'notes' => ['nullable', 'string'],
        ]);
        $data['updated_id'] = auth()->id();

        $particular->update($data);

        return redirect('/particular')->with('success', 'Particulars'.__('messages.edit_success'));
    }

    public function destroy(Particulars $particular) {
        $particular->updated_id = auth()->id();
        $particular->save();
        $particular->delete();

        return redirect('/particular')->with('success', 'Particulars'.__('messages.delete_success'));
    }
}
