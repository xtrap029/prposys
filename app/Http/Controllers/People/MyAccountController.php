<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Company;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MyAccountController extends Controller {
    
    public function index() {
        $companies = Company::orderBy('name', 'asc')->get();
        $user = User::where('id', auth()->id())->first();
        $roles = Role::orderBy('id', 'desc')->get();

        return view('pages.people.myaccount.index')->with([
            'companies' => $companies,
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request) {
        $validation_rules = [
            'company_id' =>  ['required', 'exists:companies,id'],
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];

        if ($request->password) {
            $validation_rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $user = User::where('id', auth()->id())->first();

        $data = $request->validate($validation_rules);

        if ($request->file('avatar')) {
            Storage::delete('public/images/companies/' . $user->avatar);
            $data['avatar'] = basename($request->file('avatar')->store('public/images/users'));
        }
        
        if ($request->password) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Account'.__('messages.edit_success'));
    }
}
