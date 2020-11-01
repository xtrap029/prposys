<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Company;
use App\CompanyProject;
use App\Particulars;
use App\ReleasedBy;
use App\Settings;
use App\Transaction;
use App\TransactionStatus;
use App\User;
use App\Helpers\TransactionHelper;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \DB;

class TransactionsController extends Controller {

    public function index($trans_page, $trans_company = '') {
        switch ($trans_page) {
            case 'prpo':
                $page_label_index = "Payment Release / Purchase Order";
                $trans_types = ['pr', 'po'];
            break;  
            case 'pc':
                $page_label_index = "Petty Cash";
                $trans_types = ['pc'];
                break;            
            default:
                abort(404);
                break;
        }

        $trans_status = TransactionStatus::whereIn('id', config('global.status'))->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $company = Company::where('id', $trans_company)->first();
        $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();
        
        if (!empty($_GET['s'])
            || !empty($_GET['type'])
            || !empty($_GET['status'])
            || !empty($_GET['user_req'])
            || !empty($_GET['user_prep'])) {
            
            if ($_GET['type'] != "") {
                $type = $_GET['type'];
                $trans_types = [$type];
            }
            
            $key = $_GET['s'];
            
            $transactions = Transaction::whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.status'))
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->where(static function ($query) use ($key) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%")
                                            ->orWhereHas('particulars', function($query) use($key) {
                                                $query->where('name', $key);
                                            })
                                            ->orWhere('particulars_custom', 'like', "%{$key}%")
                                            ->orWhere('purpose', 'like', "%{$key}%")
                                            ->orWhere('payee', 'like', "%{$key}%")
                                            ->orWhereHas('coatagging', function($query) use($key) {
                                                $query->where('name', $key);
                                            })
                                            ->orWhere('expense_type_description', 'like', "%{$key}%")
                                            ->orWhereHas('expensetype', function($query) use($key) {
                                                $query->where('name', $key);
                                            })
                                            ->orWhereHas('vattype', function($query) use($key) {
                                                $query->where('name', $key);
                                            })
                                            ->orWhereHas('vattype', function($query) use($key) {
                                                $query->where('code', $key);
                                            })
                                            ->orWhere('control_no', $key)
                                            ->orWhere('control_type', $key)
                                            ->orWhere('cancellation_reason', 'like', "%{$key}%")
                                            ->orWhere('cancellation_number', $key)
                                            ->orWhere('amount_issued', str_replace(',', '', $key))
                                            ->orWhere('amount', str_replace(',', '', $key));
                                    });
                                    
            if ($_GET['status'] != "") $transactions = $transactions->where('status_id', $_GET['status']);
            if ($_GET['user_req'] != "") $transactions = $transactions->where('requested_id', $_GET['user_req']);
            if ($_GET['user_prep'] != "") $transactions = $transactions->where('owner_id', $_GET['user_prep']);

            $transactions = $transactions->orderBy('id', 'desc')->paginate(10);
            $transactions->appends(['s' => $_GET['s']]);
            $transactions->appends(['type' => $_GET['type']]);
            $transactions->appends(['status' => $_GET['status']]);
            $transactions->appends(['user_req' => $_GET['user_req']]);
            $transactions->appends(['user_prep' => $_GET['user_prep']]);
        } else {
            $transactions = Transaction::whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.status'))
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })->orderBy('id', 'desc')->paginate(10);
        }


        foreach ($transactions as $key => $value) {
            if (in_array($value->status_id, config('global.page_generated'))) {
                $transactions[$key]->can_edit = $this->check_can_edit($value->id);
                $transactions[$key]->can_cancel = $this->check_can_cancel($value->id);
                $transactions[$key]->can_reset = $this->check_can_reset($value->id);
            }

            $transactions[$key]->url_view = "transaction";
            if (in_array($value->status_id, config('global.page_form'))) {
                $transactions[$key]->url_view .= "-form";
            } else if (in_array($value->status_id, config('global.page_liquidation'))) {
                $transactions[$key]->url_view .= "-liquidation";
            }
        }
        
        return view('pages.admin.transaction.index')->with([
            'trans_page' => $trans_page,
            'trans_types' => $trans_types,
            'trans_status' => $trans_status,
            'page_label' => $page_label_index,
            'companies' => $companies,
            'company' => $company,
            'users' => $users,
            'transactions' => $transactions
        ]);
    }

    public function create($trans_type, $trans_company) {
        switch ($trans_type) {
            case 'pr':
            case 'po':
                $trans_page = "prpo";
                break;  
            case 'pc':
                $trans_page = "pc";
                break;            
            default:
                abort(404);
                break;
        }

        $particulars = Particulars::where('type', $trans_type)->get();
        $projects = CompanyProject::where('company_id', $trans_company)->get();
        $users = User::whereNotNull('role_id')->get();
        $company = Company::where('id', $trans_company)->first();

        return view('pages.admin.transaction.create')->with([
            'trans_type' => $trans_type,
            'trans_company' => $trans_company,
            'trans_page' => $trans_page,
            'particulars' => $particulars,
            'projects' => $projects,
            'users' => $users,
            'company' => $company
        ]);
    }

    public function store(Request $request) {
        // validation
        if (in_array($request->trans_type, ['pr', 'po', 'pc'])) {
            $trans_type = $request->trans_type;

            $validation = [
                'trans_type' => ['required', 'in:pr,po,pc'],
                'currency' => ['required'],
                'amount' => ['required', 'min:0'],
                'purpose' => ['required'],
                'project_id' => ['required', 'exists:company_projects,id'],
                'payee' => ['required'],
                'due_at' => ['required', 'date'],
                'requested_id' => ['required', 'exists:users,id'],
                'trans_category' => ['required', 'in:'.implode(',', config('global.trans_category'))],
            ];

            if ($trans_type == 'pc') {
                $validation['particulars_custom'] = ['required'];
            } else {
                $validation['particulars_id'] = ['required', 'exists:particulars,id'];
            }
            
            $data = $request->validate($validation);

            $data['is_deposit'] = 0;
            $data['is_bills'] = 0;
            
            if ($data['trans_category'] == 'hr') {
                $data['is_deposit'] = 1;
            } else if ($data['trans_category'] == 'bp') {
                $data['is_bills'] = 1;
            }

            unset($data['trans_category']);

            $trans_company = CompanyProject::where('id', $data['project_id'])->first()->company_id;

            // if non admin requestor, validate limit applicable for pr only
            if (User::where('id', $data['requested_id'])->first()->role_id != 1 && $trans_type == 'pr') {
                $trans_bal = TransactionHelper::check_unliquidated_balance($data['requested_id']);

                $validator = \Validator::make(request()->all(), []);

                if ($trans_bal['amount'] < $data['amount']) {
                    $validator->errors()->add('amount', __('messages.exceed_amount_unliq'));
                }
                
                if ($trans_bal['count'] < 1) {
                    $validator->errors()->add('particulars_id', __('messages.exceed_count_unliq'));
                }

                if ($validator->errors()->count() > 0) {
                    return redirect('/transaction/create/'.$request->trans_type.'/'.$trans_company)
                    ->withErrors($validator)
                    ->withInput();
                }
            }
        }

        // generate transaction code
        $latest_trans = Transaction::where('trans_year', now()->year)
            ->where('trans_type', $data['trans_type'])
            ->whereHas('project', function($query) use($trans_company) {
                $query->where('company_id', $trans_company);
            })
            ->orderBy('trans_seq', 'desc')->first();
        $data['trans_year'] = now()->year;
        if($latest_trans) {
            $data['trans_seq'] = $latest_trans->trans_seq+1;
        } else {
            $data['trans_seq'] = 1;
        }

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();

        $transaction = Transaction::create($data);

        return redirect('/transaction/view/'.$transaction->id);
    }

    public function edit(Transaction $transaction) {
        if (!$this->check_can_edit($transaction->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        switch ($transaction->trans_type) {
            case 'pr':
            case 'po':
                $trans_page = "prpo";
                break;  
            case 'pc':
                $trans_page = "pc";
                break;            
            default:
                abort(404);
                break;
        }

        $particulars = Particulars::where('type', $transaction->trans_type)->get();
        $projects = CompanyProject::where('company_id', $transaction->project->company_id)->get();
        
        return view('pages.admin.transaction.edit')->with([
            'transaction' => $transaction,
            'particulars' => $particulars,
            'projects' => $projects,
            'trans_page' => $trans_page
        ]);
    }

    public function update(Request $request, Transaction $transaction) {

        // if can edit
        if (!$this->check_can_edit($transaction->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        // validate input
        $validation = [
            'amount' => ['required', 'min:0'],
            'purpose' => ['required'],
            'project_id' => ['required', 'exists:company_projects,id'],
            'payee' => ['required'],
            'trans_category' => ['required', 'in:'.implode(',', config('global.trans_category'))],
        ];

        if ($transaction->trans_type == 'pc') {
            $validation['particulars_custom'] = ['required'];
        } else {
            $validation['particulars_id'] = ['required', 'exists:particulars,id'];
        }

        $data = $request->validate($validation);

        $data['is_deposit'] = 0;
        $data['is_bills'] = 0;
        
        if ($data['trans_category'] == 'hr') {
            $data['is_deposit'] = 1;
        } else if ($data['trans_category'] == 'bp') {
            $data['is_bills'] = 1;
        }

        unset($data['trans_category']);

        // $data['is_deposit'] = $request->has('is_deposit') ?: '0';

        // if not pr, not admin, amount does exceed limit
        if ($transaction->trans_type == 'pr' 
            && $transaction->requested->role_id != 1
            && $data['amount'] - $transaction->amount > TransactionHelper::check_unliquidated_balance($transaction->requested_id)['amount']) {

            $validator = \Validator::make(request()->all(), []);    
            $validator->errors()->add('amount', __('messages.exceed_amount_unliq'));

            return redirect('/transaction/edit/'.$transaction->id)->withErrors($validator);
        }
        
        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $data['edit_count'] = $transaction->edit_count + 1;
        }

        // $data['status_id'] = 2;
        $data['updated_id'] = auth()->id();

        $transaction->update($data);

        return redirect('/transaction/view/'.$transaction->id);
    }

    public function show(Transaction $transaction) {
        $logs = Activity::where('subject_id', $transaction->id)
                ->where('subject_type', 'App\Transaction')
                ->orderBy('id', 'desc')->paginate(15)->onEachSide(1);
        $perms['can_edit'] = $this->check_can_edit($transaction->id);
        $perms['can_cancel'] = $this->check_can_cancel($transaction->id);
        $perms['can_reset'] = $this->check_can_reset($transaction->id);
        $perms['can_manage'] = $this->check_can_manage($transaction->id);    
        $perms['can_create'] = app('App\Http\Controllers\Admin\TransactionsFormsController')->check_can_create(
            $transaction->trans_type."-".$transaction->trans_year."-".sprintf('%05d',$transaction->trans_seq),
            $transaction->project->company_id
        );

        $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();
        $releasing_users = ReleasedBy::orderBy('name', 'asc')->get();

        switch ($transaction->trans_type) {
            case 'pr':
            case 'po':
                $trans_page = "prpo";
                break;  
            case 'pc':
                $trans_page = "pc";
                break;            
            default:
                abort(404);
                break;
        }

        return view('pages.admin.transaction.show')->with([
            'transaction' => $transaction,
            'perms' => $perms,
            'logs' => $logs,
            'users' => $users,
            'releasing_users' => $releasing_users,
            'trans_page' => $trans_page
        ]);
    }

    public function manage(Request $request, Transaction $transaction) {
        if ($this->check_can_manage($transaction->id)) {

            if (in_array($transaction->status_id, config('global.form_issued'))
                || in_array($transaction->status_id, config('global.liquidations'))
                || in_array($transaction->status_id, config('global.liquidation_cleared'))) {
                $data = $request->validate([
                    'requested_id' => ['required', 'exists:users,id'],
                    'owner_id' => ['required', 'exists:users,id'],
                    'released_at' => ['required', 'date'],
                    'released_by_id' => ['required', 'exists:released_by,id']
                ]); 
            } else {
                $data = $request->validate([
                    'requested_id' => ['required', 'exists:users,id'],
                    'owner_id' => ['required', 'exists:users,id']
                ]); 
            }


            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction'.__('messages.reassign_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function reset(Transaction $transaction) {
        $user = User::where('id', auth()->id())->first();

        if ($user->role_id == 1) {
            $transaction->update(['edit_count' => 0]);
            return back()->with('success', 'Transaction'.__('messages.reset_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }
    
    public function cancel(Request $request, Transaction $transaction) {
        if ($this->check_can_cancel($transaction->id)) {
            $data = $request->validate([
                'cancellation_reason' => ['required']
            ]);

            $data['cancellation_number'] = rand(100000000, 999999999);
            $data['status_id'] = 3;
            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction'.__('messages.cancel_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function report() {
        switch ($_GET['type']) {
            case 'pr':
            case 'po':
                $trans_page = "prpo";
            break;  
            case 'pc':
                $trans_page = "pc";
                break;            
            default:
                abort(404);
                break;
        }

        $transactions = Transaction::where('trans_type', $_GET['type'])->whereIn('status_id', config('global.page_generated'));
        
        if (!empty($_GET['company'])) {
            $req_company = $_GET['company'];
            $transactions = $transactions->whereHas('project', function($query) use($req_company) {
                $query->where('company_id', $req_company);
            });
        }

        if (!empty($_GET['status'])) {
            $transactions = $transactions->where('status_id', $_GET['status']);
            $status_sel = TransactionStatus::where('id', $_GET['status'])->first()->name;
        } else {
            abort(404);
        }

        if (!empty($_GET['from'])) {
            $transactions = $transactions->whereDate('created_at', '>=', $_GET['from']);
        }
        if (!empty($_GET['to'])) {
            $transactions = $transactions->whereDate('created_at', '<=', $_GET['to']);
        }

        $transactions = $transactions->orderBy('id', 'desc')->get();

        $companies = Company::orderBy('name', 'asc')->get();
        $status = TransactionStatus::whereIn('id', config('global.page_generated'))->orderBy('id', 'asc')->get();

        return view('pages.admin.transaction.report')->with([
            'trans_page' => $trans_page,
            'companies' => $companies,
            'status' => $status,
            'status_sel' => $status_sel,
            'transactions' => $transactions
        ]);
    }

    public function report_all() {
        $trans_page = '';
        $status_sel = '';
        $trans_type = '';
        $trans_company = '';
        $trans_from = '';
        $trans_to = '';

        $transactions = Transaction::orderBy('id', 'desc');
        
        if (!empty($_GET['type'])) {
            $trans_type = $_GET['type'];
            switch ($_GET['type']) {
                case 'pr':
                    $trans_page = "prpo";
                case 'po':
                    $trans_page = "prpo";
                break;  
                case 'pc':
                    $trans_page = "pc";
                    break;            
                default:
                    abort(404);
                    break;
            }

            $transactions = $transactions->where('trans_type', $trans_type);
        }

        if (!empty($_GET['company'])) {
            $trans_company = $_GET['company'];
            $transactions = $transactions->whereHas('project', function($query) use($trans_company) {
                $query->where('company_id', $trans_company);
            });
        }
        
        if (!empty($_GET['status'])) {
            $transactions = $transactions->where('status_id', $_GET['status']);
            $status_sel = TransactionStatus::where('id', $_GET['status'])->first()->name;
        }

        if (!empty($_GET['from'])) {
            $transactions = $transactions->whereDate('created_at', '>=', $_GET['from']);
            $trans_from = $_GET['from'];
        }
        if (!empty($_GET['to'])) {
            $transactions = $transactions->whereDate('created_at', '<=', $_GET['to']);
            $trans_to = $_GET['to'];
        }

        $transactions = $transactions->get();

        $companies = Company::orderBy('name', 'asc')->get();
        $status = TransactionStatus::whereIn('id', config('global.status'))->orderBy('id', 'asc')->get();

        return view('pages.admin.transaction.reportall')->with([
            'companies' => $companies,
            'status' => $status,
            'status_sel' => $status_sel,
            'transactions' => $transactions,
            'trans_type' => $trans_type,
            'trans_company' => $trans_company,
            'trans_from' => $trans_from,
            'trans_to' => $trans_to,
        ]);
    }

    private function check_can_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated'))) {
            // check if not admin
            if ($user->role_id != 1) {
                // check if owned
                if ($user->id == $transaction->owner_id) {
                    // check if pr, not po
                    if ($transaction->trans_type == 'pr') {
                        // check role limit
                        if ($user->role_id == 2) {
                            $edit_pr_limit = Settings::where('type', 'LIMIT_EDIT_GENPR_USER_2')->first()->value;
                        } else if ($user->role_id == 3) {
                            $edit_pr_limit = Settings::where('type', 'LIMIT_EDIT_GENPR_USER_3')->first()->value;
                        } else {
                            $can_edit = false;
                        }

                        // check if role limit is enough
                        if ($transaction->edit_count >= $edit_pr_limit) {
                            $can_edit = false;
                        } 
                    }
                } else {
                    $can_edit = false;
                }
            }
        } else {
            $can_edit = false;
        }

        return $can_edit;
    }

    private function check_can_cancel($transaction, $user = '') {
        $can_cancel = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated'))) {
            // check if not admin and not the owner
            if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
                $can_cancel = false;
            }
        } else {
            $can_cancel = false;
        }

        return $can_cancel;
    }

    private function check_can_reset($transaction, $user = '') {
        $can_reset = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if reset
        if (!in_array($transaction->status_id, config('global.generated')) || $user->role_id != 1 || in_array($transaction->trans_type, ['po', 'pc'])) {
            $can_reset = false;
        }

        return $can_reset;
    }

    private function check_can_manage($transaction, $user = '') {
        $can_reassign = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        // check if reassign
        if ($user->role_id != 1) {
            $can_reassign = false;
        }

        return $can_reassign;
    }
}
