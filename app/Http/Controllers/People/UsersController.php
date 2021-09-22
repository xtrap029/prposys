<?php

namespace App\Http\Controllers\People;

use App\Company;
use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller {

    public function index() {
        $users = User::orderBy('name', 'asc');
        
        if (!isset($_GET['all'])) {
            $users = $users->whereNotNull('role_id');
        }

        $users = $users->get();
        // $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();
        // $users_inactive = User::whereNull('role_id')->orderBy('name', 'asc')->get();
        
        return view('pages.people.users.index')->with([
            'users' => $users,
            // 'users_inactive' => $users_inactive
        ]);
    }

    public function create() {
        $companies = Company::orderBy('name', 'asc')->get();
        $roles = Role::orderBy('id', 'desc')->get();

        return view('pages.people.users.create')->with([
            'companies' => $companies,
            'roles' => $roles
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            // 'company_id' =>  ['required', 'exists:companies,id'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['nullable', 'integer'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => ['nullable', 'integer'],
            'e_emp_no' => ['nullable'],
            'e_hire_date' => ['nullable', 'date'],
            'e_emp_status' => ['nullable'],
            'e_reg_date' => ['nullable', 'date'],
            'e_position' => ['nullable'],
            'e_rank' => ['nullable'],
            'e_department' => ['nullable'],
            'e_payroll' => ['nullable'],
            'e_dob' => ['nullable'],
            'e_gender' => ['nullable'],
            'e_civil' => ['nullable'],
            'e_mail_address' => ['nullable'],
            'e_contact' => ['nullable'],
            'e_email' => ['nullable', 'email'],
            'e_emergency_name' => ['nullable'],
            'e_emergency_contact' => ['nullable'],
            'e_tin' => ['nullable'],
            'e_sss' => ['nullable'],
            'e_phic' => ['nullable'],
            'e_hmdf' => ['nullable'],
            'app_control.*' => ['nullable'],
            'company_control.*' => ['nullable'],
        ]);

        $data['apps'] = $request->app_control ? implode(",", $request->app_control) : "";
        $data['companies'] = $request->company_control ? implode(",", $request->company_control) : "";
        $data['company_id'] = $request->company_control ? $request->company_control[0] : null;

        $data['avatar'] = basename($request->file('avatar')->store('public/images/users'));
        User::create([
            'avatar' => $data['avatar'],
            'name' => $data['name'],
            'role_id' => $data['role_id'],
            'apps' => $data['apps'],
            'companies' => $data['companies'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => $data['LIMIT_UNLIQUIDATEDPR_AMOUNT'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => $data['LIMIT_UNLIQUIDATEDPR_COUNT'],
            'e_emp_no' => $data['e_emp_no'],
            'e_hire_date' => $data['e_hire_date'],
            'e_emp_status' => $data['e_emp_status'],
            'e_reg_date' => $data['e_reg_date'],
            'e_position' => $data['e_position'],
            'e_rank' => $data['e_rank'],
            'e_department' => $data['e_department'],
            'e_payroll' => $data['e_payroll'],
            'e_dob' => $data['e_dob'],
            'e_gender' => $data['e_gender'],
            'e_civil' => $data['e_civil'],
            'e_mail_address' => $data['e_mail_address'],
            'e_contact' => $data['e_contact'],
            'e_email' => $data['e_email'],
            'e_emergency_name' => $data['e_emergency_name'],
            'e_emergency_contact' => $data['e_emergency_contact'],
            'e_tin' => $data['e_tin'],
            'e_sss' => $data['e_sss'],
            'e_phic' => $data['e_phic'],
            'e_hmdf' => $data['e_hmdf'],
        ]);

        return redirect('/user')->with('success', 'User'.__('messages.create_success'));
    }

    public function edit(User $user) {
        $roles = Role::orderBy('id', 'desc')->get();
        $companies = Company::orderBy('name', 'asc')->get();

        return view('pages.people.users.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'companies' => $companies
        ]);
    }

    public function update(Request $request, User $user) {
        $validation_rules = [
            'role_id' => ['nullable', 'exists:roles,id'],
            // 'company_id' =>  ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['nullable', 'integer'],
            'LIMIT_UNLIQUIDATEDPR_COUNT' => ['nullable', 'integer'],
            'e_emp_no' => ['nullable'],
            'e_hire_date' => ['nullable', 'date'],
            'e_emp_status' => ['nullable'],
            'e_reg_date' => ['nullable', 'date'],
            'e_position' => ['nullable'],
            'e_rank' => ['nullable'],
            'e_department' => ['nullable'],
            'e_payroll' => ['nullable'],
            'e_dob' => ['nullable'],
            'e_gender' => ['nullable'],
            'e_civil' => ['nullable'],
            'e_mail_address' => ['nullable'],
            'e_contact' => ['nullable'],
            'e_email' => ['nullable', 'email'],
            'e_emergency_name' => ['nullable'],
            'e_emergency_contact' => ['nullable'],
            'e_tin' => ['nullable'],
            'e_sss' => ['nullable'],
            'e_phic' => ['nullable'],
            'e_hmdf' => ['nullable'],
            'app_control.*' => ['nullable'],
            'company_control.*' => ['nullable'],
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

        $data['apps'] = $request->app_control ? implode(",", $request->app_control) : "";
        unset($data['app_control']);

        $data['companies'] = $request->company_control ? implode(",", $request->company_control) : "";
        $data['company_id'] = $request->company_control ? $request->company_control[0] : null;
        unset($data['company_control']);

        $user->update($data);

        return redirect('/user')->with('success', 'User'.__('messages.edit_success'));
    }
}
