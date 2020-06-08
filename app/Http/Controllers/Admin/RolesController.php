<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolesController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::orderBy('id', 'asc')->get();
        return view('pages.admin.roles.index')->with([
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $attributes = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('roles')->whereNull('deleted_at')]
        ]);
        $attributes['owner_id'] = auth()->id();
        $attributes['updated_id'] = auth()->id();

        Role::create($attributes);

        return redirect('role');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role) {
        return view('pages.admin.roles.edit')->with([
            'role' => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role) {
        $attributes = $request->validate([
            'name' => ['required', 'min:3', Rule::unique('roles')->ignore($role->id)->whereNull('deleted_at')]
        ]);
        $attributes['owner_id'] = auth()->id();
        $attributes['updated_id'] = auth()->id();

        $role->update($attributes);

        return redirect('/role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
        $role->updated_id = auth()->id();
        $role->save();
        $role->delete();

        return redirect('/role');
    }
}
