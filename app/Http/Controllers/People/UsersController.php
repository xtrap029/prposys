<?php

namespace App\Http\Controllers\People;

use App\Company;
use App\Role;
use App\UaLevel;
use App\TravelRole;
use App\User;
use App\UserTransactionLimit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller {

    public function index() {
        $users = User::orderBy('name', 'asc');

        if (!empty($_GET['s'])
            || !empty($_GET['status'])
            || !empty($_GET['level'])
            || !empty($_GET['is_accounting'])) {

            $key = $_GET['s'];
            
            $users = $users->where(static function ($query) use ($key) {
                $query->where('name', 'like', "%{$key}%")
                    ->orWhere('email', 'like', "%{$key}%")
                    ->orWhere('e_emp_no', 'like', "%{$key}%");
            });

            if ($_GET['status'] != "") {
                if ($_GET['status'] == 2) $users = $users->where('ua_level_id', '=', config('global.ua_inactive'));
            }

            if ($_GET['level'] != "") $users = $users->where('ua_level_id', '=', $_GET['level']);

            if ($_GET['is_accounting'] != "") {
                if ($_GET['is_accounting'] == 1) $users = $users->where('is_accounting', '=', 1);
                else if ($_GET['is_accounting'] == 2) $users = $users->where('is_accounting', '=', 0);
            }

            $users = $users->paginate(10);

            $users->appends(['s' => $_GET['s']]);
            $users->appends(['status' => $_GET['status']]);
            $users->appends(['levle' => $_GET['level']]);
            $users->appends(['is_accounting' => $_GET['is_accounting']]);
        } else {
            $users = $users->where('ua_level_id', '!=', config('global.ua_inactive'));
            $users = $users->paginate(10);
        }


        $levels = UaLevel::orderBy('order', 'asc')->get();
        $travel_roles = TravelRole::orderBy('id', 'asc')->get();
        // $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();
        // $users_inactive = User::whereNull('role_id')->orderBy('name', 'asc')->get();
        
        return view('pages.people.users.index')->with([
            'users' => $users,
            'levels' => $levels,
            'travel_roles' => $travel_roles,
            // 'users_inactive' => $users_inactive
        ]);
    }

    public function create() {
        $companies = Company::orderBy('name', 'asc')->get();
        $roles = Role::orderBy('id', 'desc')->get();
        $levels = UaLevel::orderBy('order', 'asc')->get();
        $travel_roles = TravelRole::orderBy('id', 'asc')->get();

        return view('pages.people.users.create')->with([
            'companies' => $companies,
            'roles' => $roles,
            'levels' => $levels,
            'travel_roles' => $travel_roles,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'ua_level_id' => ['nullable', 'exists:ua_levels,id'],
            'ua_level_control.*' => ['nullable'],
            'travel_role_control.*' => ['nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            // 'company_id' =>  ['required', 'exists:companies,id'],
            // 'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['nullable', 'integer'],
            // 'LIMIT_UNLIQUIDATEDPR_COUNT' => ['nullable', 'integer'],
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
            'is_read_only' => ['boolean'],
            'is_accounting' => ['boolean'],
            'is_accounting_head' => ['boolean'],
            'is_external' => ['boolean'],

            'LIMIT_UNLIQUIDATEDPR_COMPANY_ID.*' => ['required', 'exists:companies,id'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT.*' => ['nullable', 'numeric'],
            'LIMIT_UNLIQUIDATEDPR_COUNT.*' => ['nullable', 'integer'],
        ]);

        $data['apps'] = $request->app_control ? implode(",", $request->app_control) : "";
        $data['companies'] = $request->company_control ? implode(",", $request->company_control) : "";
        $data['company_id'] = $request->company_control ? $request->company_control[0] : null;
        $data['ua_levels'] = $request->ua_level_control ? implode(",", $request->ua_level_control) : "";
        $data['travel_roles'] = $request->travel_role_control ? implode(",", $request->travel_role_control) : "";

        $data['avatar'] = basename($request->file('avatar')->store('public/images/users'));
        $user = User::create([
            'avatar' => $data['avatar'],
            'name' => $data['name'],
            'role_id' => $data['role_id'],
            'ua_level_id' => $data['ua_level_id'],
            'ua_levels' => $data['ua_levels'],
            'travel_roles' => $data['travel_roles'],
            'apps' => $data['apps'],
            'companies' => $data['companies'],
            'is_read_only' => $data['role_id'] == 1 ? 0 : $data['is_read_only'],
            'is_accounting' => $data['is_accounting'],
            'is_accounting_head' => $data['is_accounting_head'],
            'is_external' => $data['is_external'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'],
            // 'LIMIT_UNLIQUIDATEDPR_AMOUNT' => $data['LIMIT_UNLIQUIDATEDPR_AMOUNT'],
            // 'LIMIT_UNLIQUIDATEDPR_COUNT' => $data['LIMIT_UNLIQUIDATEDPR_COUNT'],
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

        foreach ($request->LIMIT_UNLIQUIDATEDPR_COMPANY_ID as $key => $value) {
            UserTransactionLimit::create([
                'user_id' => $user->id,
                'company_id' => $value,
                'amount_limit' => $request->LIMIT_UNLIQUIDATEDPR_AMOUNT[$key],
                'transaction_limit' => $request->LIMIT_UNLIQUIDATEDPR_COUNT[$key],
                'owner_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }

        return redirect('/user')->with('success', 'User'.__('messages.create_success'));
    }

    public function edit(User $user) {
        $roles = Role::orderBy('id', 'desc')->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $levels = UaLevel::orderBy('order', 'asc')->get();
        $travel_roles = TravelRole::orderBy('id', 'asc')->get();

        return view('pages.people.users.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'companies' => $companies,
            'levels' => $levels,
            'travel_roles' => $travel_roles,
        ]);
    }

    public function update(Request $request, User $user) {
        $validation_rules = [
            'role_id' => ['nullable', 'exists:roles,id'],
            'ua_level_id' => ['nullable', 'exists:ua_levels,id'],
            'ua_level_control' => ['nullable'],
            'travel_role_control' => ['nullable'],
            // 'company_id' =>  ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            // 'LIMIT_UNLIQUIDATEDPR_AMOUNT' => ['nullable', 'integer'],
            // 'LIMIT_UNLIQUIDATEDPR_COUNT' => ['nullable', 'integer'],
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
            'is_read_only' => ['boolean'],
            'is_accounting' => ['boolean'],
            'is_accounting_head' => ['boolean'],
            'is_external' => ['boolean'],

            'LIMIT_UNLIQUIDATEDPR_COMPANY_ID.*' => ['required', 'exists:companies,id'],
            'LIMIT_UNLIQUIDATEDPR_AMOUNT.*' => ['nullable', 'numeric'],
            'LIMIT_UNLIQUIDATEDPR_COUNT.*' => ['nullable', 'integer'],
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
        $data['ua_levels'] = $request->ua_level_control ? implode(",", $request->ua_level_control) : "";
        $data['travel_roles'] = $request->travel_role_control ? implode(",", $request->travel_role_control) : "";
        $data['company_id'] = $request->company_control ? $request->company_control[0] : null;
        unset($data['company_control']);

        $data['is_read_only'] = $data['role_id'] == 1 ? 0 : $data['is_read_only'];

        $user->update($data);

        UserTransactionLimit::where('user_id', $user->id)->delete();
        foreach ($request->LIMIT_UNLIQUIDATEDPR_COMPANY_ID as $key => $value) {
            UserTransactionLimit::create([
                'user_id' => $user->id,
                'company_id' => $value,
                'amount_limit' => $request->LIMIT_UNLIQUIDATEDPR_AMOUNT[$key],
                'transaction_limit' => $request->LIMIT_UNLIQUIDATEDPR_COUNT[$key],
                'owner_id' => auth()->id(),
                'updated_id' => auth()->id(),
            ]);
        }

        return redirect('/user')->with('success', 'User'.__('messages.edit_success'));
    }
}
