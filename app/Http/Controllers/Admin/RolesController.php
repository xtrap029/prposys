<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolesController extends Controller {

    public function index() {
        $roles = Role::orderBy('id', 'asc')->get();
        return view('pages.admin.roles.index')->with([
            'roles' => $roles
        ]);
    }

    public function create() {
        return view('pages.admin.roles.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('roles')->whereNull('deleted_at')]
        ]);
        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        Role::create($data);

        return redirect('/role')->with('success', 'Role'.__('messages.create_success'));
    }

    public function edit(Role $role) {
        return view('pages.admin.roles.edit')->with([
            'role' => $role
        ]);
    }

    public function update(Request $request, Role $role) {
        $data = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('roles')->ignore($role->id)->whereNull('deleted_at')]
        ]);
        $data['updated_id'] = auth()->id();

        $role->update($data);

        return redirect('/role')->with('success', 'Role'.__('messages.edit_success'));
    }

    public function destroy(Role $role) {
        $role->updated_id = auth()->id();
        $role->save();
        $role->delete();

        return redirect('/role')->with('success', 'Settings'.__('messages.delete_success'));
    }
}
