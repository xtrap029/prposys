<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller {

    public function index() {
        $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();
        $users_inactive = User::whereNull('role_id')->orderBy('name', 'asc')->get();
        
        return view('pages.admin.users.index')->with([
            'users' => $users,
            'users_inactive' => $users_inactive
        ]);
    }

    public function create() {
        return view('pages.admin.users.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['nullable', 'integer'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => ['nullable', 'integer'],
        ]);

        $data['avatar'] = basename($request->file('avatar')->store('public/images/users'));

        User::create([
            'avatar' => $data['avatar'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => $data['LIMIT_UNLIQUIDATEDPR_AMOUNT'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => $data['LIMIT_UNLIQUIDATEDPR_COUNT'],
        ]);

        return redirect('/user')->with('success', 'User'.__('messages.create_success'));
    }

    public function edit(User $user) {
        $roles = Role::orderBy('id', 'desc')->get();

        return view('pages.admin.users.edit')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user) {
        $validation_rules = [
            'role_id' => ['nullable', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['nullable', 'integer'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => ['nullable', 'integer'],
        ];

        if ($request->password) {
            $validation_rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $data = $request->validate($validation_rules);

        if ($request->file('avatar')) {
            Storage::delete('public/images/companies/' . $user->avatar);
            $data['avatar'] = basename($request->file('avatar')->store('public/images/users'));
        }
        
        if ($request->password) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect('/user')->with('success', 'User'.__('messages.edit_success'));
    }
}
