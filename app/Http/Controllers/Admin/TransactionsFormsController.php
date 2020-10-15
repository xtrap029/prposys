<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CoaTagging;
use App\Company;
use App\CompanyProject;
use App\ExpenseType;
use App\Particulars;
use App\ReleasedBy;
use App\Settings;
use App\Transaction;
use App\TransactionsDescription;
use App\TransactionStatus;
use App\User;
use App\VatType;
use App\Helpers\TransactionHelper;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \DB;

class TransactionsFormsController extends Controller {

    public function index($trans_page, $trans_company = '') {
        $trans_page_url = $trans_page;

        switch ($trans_page) {
            case 'prpo':
                $page_label_index = "Payment Release / Purchase Order";
                $trans_types = ['pr', 'po'];
                $trans_page = 'prpo-form';
            break;  
            case 'pc':
                $page_label_index = "Petty Cash";
                $trans_types = ['pc'];
                $trans_page = 'pc-form';
                break;            
            default:
                abort(404);
                break;
        }

        $companies = Company::orderBy('name', 'asc')->get();
        $company = Company::where('id', $trans_company)->first();

        $released_by = ReleasedBy::orderBy('name', 'asc')->get();
        
        if (!empty($_GET['s']) || !empty($_GET['type']) || !empty($_GET['status'])) {
            
            if ($_GET['type'] != "") {
                $type = $_GET['type'];
                $trans_types = [$type];
            }
            
            $key = $_GET['s'];
            $transactions = Transaction::whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.page_form'))
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->where(static function ($query) use ($key) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%")
                                            ->orWhereHas('particulars', function($query) use($key) {
                                                $query->where('name', $key);
                                            })
                                            ->orWhereHas('project', function($query) use($key) {
                                                $query->where('project', $key);
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
                                            ->orWhere('amount_issued', str_replace(',', '', $key))
                                            ->orWhere('amount', str_replace(',', '', $key));
                                    });
            
            if ($_GET['status'] != "") {
                switch ($_GET['status']) {
                    case 'requested':
                        $transactions = $transactions->where('requested_id', auth()->id());
                        break;
                    case 'prepared':
                        $transactions = $transactions->where('owner_id', auth()->id());
                        break;
                    case 'approval':
                        $transactions = $transactions->whereIn('status_id', config('global.form_approval'));
                        if (!in_array(User::where('id', auth()->id())->first()->role_id, [1, 2])) {
                            $transactions->where('requested_id', auth()->id());
                        }
                        break;
                    case 'issued':
                        $transactions = $transactions->whereIn('status_id', config('global.form_issued'));
                        if (!in_array(User::where('id', auth()->id())->first()->role_id, [1, 2])) {
                            $transactions->where('requested_id', auth()->id());
                        }
                        break;
                    default:
                        break;
                }
            }
                                    
            $transactions = $transactions->orderBy('id', 'desc')->paginate(10);
            $transactions->appends(['s' => $_GET['s']]);
            $transactions->appends(['type' => $_GET['type']]);
            $transactions->appends(['status' => $_GET['status']]);
        } else {
            $transactions = Transaction::whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.page_form'))
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })->orderBy('id', 'desc')->paginate(10);
        }


        foreach ($transactions as $key => $value) {
            $transactions[$key]->can_edit = $this->check_can_edit($value->id);
            $transactions[$key]->can_cancel = $this->check_can_cancel($value->id);
            $transactions[$key]->can_issue = $this->check_can_issue($value->id);
            $transactions[$key]->can_reset = $this->check_can_reset($value->id);
            $transactions[$key]->can_approval = $this->check_can_approval($value->id);
            $transactions[$key]->can_print = $this->check_can_print($value->id);
        }

        $approvers = User::whereIn('role_id', config('global.approver_form'))->orderBy('name', 'asc')->get();
        
        return view('pages.admin.transactionform.index')->with([
            'trans_page' => $trans_page,
            'trans_page_url' => $trans_page_url,
            'trans_types' => $trans_types,
            'page_label' => $page_label_index,
            'companies' => $companies,
            'company' => $company,
            'transactions' => $transactions,
            'approvers' => $approvers,
            'released_by' => $released_by
        ]);
    }

    public function create() {
        if (empty($_GET['key']) || empty($_GET['company']) || !$this->check_can_create($_GET['key'], $_GET['company'])) {
            return back()->with('error', __('messages.make_not_allowed'));
        }

        $company = $_GET['company'];
        $transaction = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $_GET['key'])
            ->whereHas('project', function($query) use($company) {
                $query->where('company_id', $company);
            })
            ->first();

        switch ($transaction->trans_type) {
            case 'pr':
            case 'po':
                $trans_page = "prpo-form";
                $trans_page_url = "prpo";
            break;  
            case 'pc':
                $trans_page = "pc-form";
                $trans_page_url = "pc";
                break;            
            default:
                abort(404);
                break;
        }

        $coa_taggings = CoaTagging::where('company_id', $transaction->project->company_id)->orderBy('name', 'asc')->get();
        $particulars = Particulars::where('type', $transaction->trans_type)->get();
        $vat_types = VatType::where('is_'.$transaction->trans_type, 1)->orderBy('id', 'asc')->get();

        return view('pages.admin.transactionform.create')->with([
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'transaction' => $transaction,
            'coa_taggings' => $coa_taggings,
            'particulars' => $particulars,
            'vat_types' => $vat_types
        ]);
    }

    public function store(Request $request) {
        // if can edit
        if (!$this->check_can_create($request->key, $request->company)) {
            return back()->with('error', __('messages.cant_create'));
        } else {
            $company = $request->company;
            $transaction = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $request->key)
                ->whereHas('project', function($query) use($company) {
                    $query->where('company_id', $company);
                })
                ->first();
        }

        // validate input
        $data = $request->validate([
            'coa_tagging_id' => ['required', 'exists:coa_taggings,id'],
            // 'expense_type_id' => ['required', 'exists:expense_types,id'],
            // 'expense_type_description' => ['required'],
            'vat_type_id' => ['required', 'exists:vat_types,id'],
        ]);

        $data_desc = $request->validate([
            'qty.*' => ['required', 'min:1'],
            'particulars_id.*' => ['required', 'exists:particulars,id'],
            'description.*' => ['required'],
            'amount.*' => ['required', 'min:0']
        ]);

        $attr_desc['transaction_id'] = $transaction->id;
        $attr_desc['owner_id'] = auth()->id();
        $attr_desc['updated_id'] = auth()->id();

        foreach ($data_desc['qty'] as $key => $value) {
            $attr_desc['qty'] = $value;
            $attr_desc['description'] = $data_desc['description'][$key];
            $attr_desc['particulars_id'] = $data_desc['particulars_id'][$key];
            $attr_desc['amount'] = $data_desc['amount'][$key];

            TransactionsDescription::create($attr_desc);
        }

        
        $data['edit_count'] = 0;
        $data['status_id'] = 5;
        $data['updated_id'] = auth()->id();
        
        $transaction->update($data);

        return redirect('/transaction-form/view/'.$transaction->id);
    }

    public function show(Transaction $transaction) {
        $logs = Activity::where('subject_id', $transaction->id)
                ->where('subject_type', 'App\Transaction')
                ->orderBy('id', 'desc')->paginate(15)->onEachSide(1);
        $perms['can_edit'] = $this->check_can_edit($transaction->id);
        $perms['can_cancel'] = $this->check_can_cancel($transaction->id);
        $perms['can_reset'] = $this->check_can_reset($transaction->id);
        $perms['can_approval'] = $this->check_can_approval($transaction->id);
        $perms['can_print'] = $this->check_can_print($transaction->id);
        $perms['can_issue'] = $this->check_can_issue($transaction->id);

        $released_by = ReleasedBy::orderBy('name', 'asc')->get();

        switch ($transaction->trans_type) {
            case 'pr':
            case 'po':
                $trans_page_url = "prpo";
                $trans_page = "prpo-form";
                break;  
            case 'pc':
                $trans_page_url = "pc";
                $trans_page = "pc-form";
                break;            
            default:
                abort(404);
                break;
        }

        $transaction->custom_vat = $transaction->amount * (abs($transaction->vattype->vat) * 0.01);
        $transaction->custom_wht = $transaction->amount * ($transaction->vattype->wht * 0.01);
        $transaction->custom_subtotal = $transaction->amount;
        $transaction->custom_total_payable = $transaction->amount - $transaction->custom_wht;
        
        if ($transaction->vattype->vat >= 0) {
            $transaction->custom_subtotal = $transaction->amount - $transaction->custom_vat;
        } else {
            $transaction->custom_total_payable = $transaction->custom_total_payable + $transaction->custom_vat;
        }

        $approvers = User::whereIn('role_id', config('global.approver_form'))->orderBy('name', 'asc')->get();

        return view('pages.admin.transactionform.show')->with([
            'transaction' => $transaction,
            'perms' => $perms,
            'logs' => $logs,
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'approvers' => $approvers,
            'released_by' => $released_by
        ]);
    }

    public function edit(Transaction $transaction) {
        if (!$this->check_can_edit($transaction->id) && !$this->check_can_issue($transaction->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        switch ($transaction->trans_type) {
            case 'pr':
            case 'po':
                $trans_page_url = "prpo";
                $trans_page = "prpo-form";
            break;  
            case 'pc':
                $trans_page_url = "pc";
                $trans_page = "pc-form";
                break;            
            default:
                abort(404);
                break;
        }

        $coa_taggings = CoaTagging::where('company_id', $transaction->project->company_id)->orderBy('name', 'asc')->get();
        // $expense_types = ExpenseType::orderBy('name', 'asc')->get();
        $vat_types = VatType::where('is_'.$transaction->trans_type, 1)->orderBy('id', 'asc')->get();
        $particulars = Particulars::where('type', $transaction->trans_type)->get();
        $projects = CompanyProject::where('company_id', $transaction->project->company_id)->get();
        $users = User::whereNotNull('role_id')->get();
        
        return view('pages.admin.transactionform.edit')->with([
            'transaction' => $transaction,
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'coa_taggings' => $coa_taggings,
            'vat_types' => $vat_types,
            'particulars' => $particulars,
            'users' => $users,
            'projects' => $projects
        ]);
    }

    public function update(Request $request, Transaction $transaction) {
        // if can edit
        if (!$this->check_can_edit($transaction->id) && !$this->check_can_issue($transaction->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        // validate input
        $data = $request->validate([
            'coa_tagging_id' => ['required', 'exists:coa_taggings,id'],
            // 'expense_type_id' => ['required', 'exists:expense_types,id'],
            // 'expense_type_description' => ['required'],
            'vat_type_id' => ['required', 'exists:vat_types,id'],

            'amount' => ['required', 'min:0'],
            'purpose' => ['required'],
            'project_id' => ['required', 'exists:company_projects,id'],
            'payee' => ['required'],
            'currency' => ['required'],
            'due_at' => ['required', 'date'],
            'requested_id' => ['required', 'exists:users,id']
        ]);

        $data_desc = $request->validate([
            'qty.*' => ['required', 'min:1'],
            'particulars_id.*' => ['required', 'exists:particulars,id'],
            'description.*' => ['required'],
            'amount_desc.*' => ['required', 'min:0']
        ]);

        $attr_desc['transaction_id'] = $transaction->id;
        $attr_desc['owner_id'] = auth()->id();
        $attr_desc['updated_id'] = auth()->id();

        TransactionsDescription::where('transaction_id', $transaction->id)->delete();

        foreach ($data_desc['qty'] as $key => $value) {
            $attr_desc['qty'] = $value;
            $attr_desc['description'] = $data_desc['description'][$key];
            $attr_desc['particulars_id'] = $data_desc['particulars_id'][$key];
            $attr_desc['amount'] = $data_desc['amount_desc'][$key];

            TransactionsDescription::create($attr_desc);
        }

        // if non admin requestor, validate limit applicable for pr only
        if (User::where('id', $data['requested_id'])->first()->role_id != 1 && $transaction->trans_type == 'pr') {
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

        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $data['edit_count'] = $transaction->edit_count + 1;
        }

        if ($this->check_can_issue($transaction->id)) {
            $data['status_id'] = 5;
        }
        
        $data['updated_id'] = auth()->id();

        $transaction->update($data);

        return redirect('/transaction-form/view/'.$transaction->id);
    }

    public function reset(Transaction $transaction) {
        $user = User::where('id', auth()->id())->first();

        if ($user->role_id == 1) {
            $transaction->update(['edit_count' => 0]);
            return back()->with('success', 'Transaction Form'.__('messages.reset_success'));
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
            return back()->with('success', 'Transaction Form'.__('messages.cancel_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    // public function approval(Request $request, Transaction $transaction) {
    public function approval(Transaction $transaction) {
        if ($this->check_can_approval($transaction->id)) {
            // $data = $request->validate([
            //     'form_approver_id' => ['required', 'exists:users,id']
            // ]);

            $custom_vat = $transaction->amount * (abs($transaction->vattype->vat) * 0.01);
            $custom_wht = $transaction->amount * ($transaction->vattype->wht * 0.01);
            $custom_subtotal = $transaction->amount;
            $custom_total_payable = $transaction->amount - $custom_wht;
            
            if ($transaction->vattype->vat >= 0) {
                $custom_subtotal = $transaction->amount - $custom_vat;
            } else {
                $custom_total_payable = $custom_total_payable + $custom_vat;
            }
            
            $data = [];
            $data['form_vat_code'] = $transaction->vattype->code;
            $data['form_vat_name'] = $transaction->vattype->name;
            $data['form_vat_vat'] = $transaction->vattype->vat;
            $data['form_vat_wht'] = $transaction->vattype->wht;
            $data['form_amount_unit'] = $transaction->amount;
            $data['form_amount_vat'] = $custom_vat;
            $data['form_amount_wht'] = $custom_wht;
            $data['form_amount_subtotal'] = $custom_subtotal;
            $data['form_amount_payable'] = $custom_total_payable;

            $data['status_id'] = 6;
            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction Form'.__('messages.approval_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function print (Transaction $transaction) {
        if (!$this->check_can_print($transaction->id)) {
            return back()->with('error', __('messages.cant_print'));
        }

        switch ($transaction->trans_type) {
            case 'pr':
                $trans_page = "Payment Release";
                break;  
            case 'po':
                $trans_page = "Purchase Order";
                break;  
            case 'pc':
                $trans_page = "Make Cash Request";
                break;            
            default:
                abort(404);
                break;
        }

        $final_approver = User::where(
            'id', Settings::where('type', 'AUTHORIZED_BY')
                ->select('value')->first()->value
        )->first()->name;

        return view('pages.admin.transactionform.print')->with([
            'transaction' => $transaction,
            'trans_page' => $trans_page,
            'final_approver' => $final_approver
        ]);
    }

    public function print_issued () {
        $trans_company = '';
        $trans_from = '';
        $trans_to = '';

        $transactions = Transaction::whereIn('status_id', config('global.form_issued'))->orderBy('id', 'desc');

        if (!empty($_GET['type'])) {
            if (!in_array($_GET['type'], config('global.trans_types'))) {
                $transactions = $transactions->where('trans_type', $_GET['type']);
            } else {
                abort(404);
            }
        }

        if (!empty($_GET['company'])) {
            $trans_company = $_GET['company'];
            $transactions = $transactions->whereHas('project', function($query) use($trans_company) {
                $query->where('company_id', $trans_company);
            });
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

        $final_approver = User::where(
            'id', Settings::where('type', 'AUTHORIZED_BY')
                ->select('value')->first()->value
        )->first()->name;

        return view('pages.admin.transactionform.printissued')->with([
            'transactions' => $transactions,
            'final_approver' => $final_approver
        ]);
    }

    public function issue(Request $request, Transaction $transaction) {
        if ($this->check_can_issue($transaction->id)) {
            $data = $request->validate([
                'control_type' => ['required'],
                'control_no' => ['required'],
                'released_at' => ['required', 'date'],
                'amount_issued' => ['required', 'min:0'],
                'released_by_id' => ['required', 'exists:released_by,id']
            ]);
            
            $data['status_id'] = 4;
            $data['form_approver_id'] = auth()->id();
            $transaction->update($data);

            return back()->with('success', 'Transaction'.__('messages.issue_success'));
        } else {
            return back()->with('error', __('messages.cant_issue'));
        }
    }

    public function report() {
        switch ($_GET['type']) {
            case 'pr':
            case 'po':
                $trans_page_url = "prpo";
                $trans_page = "prpo-form";
            break;  
            case 'pc':
                $trans_page_url = "pc";
                $trans_page = "pc-form";
                break;            
            default:
                abort(404);
                break;
        }

        $transactions = Transaction::where('trans_type', $_GET['type'])->whereIn('status_id', config('global.page_form'));
        
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
        $status = TransactionStatus::whereIn('id', config('global.page_form'))->orderBy('id', 'asc')->get();

        return view('pages.admin.transactionform.report')->with([
            'trans_page' => $trans_page,
            'trans_page_url' => $trans_page_url,
            'companies' => $companies,
            'status' => $status,
            'status_sel' => $status_sel,
            'transactions' => $transactions
        ]);
    }

    private function check_can_create($key, $company) {
        $can_create = true;

        $result = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $key)
            ->whereHas('project', function($query) use($company) {
                $query->where('company_id', $company);
            })
            ->whereIn('status_id', config('global.generated'));

        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $result = $result->where('owner_id', auth()->id());
        }

        $result = $result->count();

        if ($result == 0) $can_create = false;

        return $can_create;
    }

    private function check_can_reset($transaction, $user = '') {
        $can_reset = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if reset
        if (!in_array($transaction->status_id, config('global.generated_form')) || $user->role_id != 1 || in_array($transaction->trans_type, ['pc'])) {
            $can_reset = false;
        }

        return $can_reset;
    }

    private function check_can_cancel($transaction, $user = '') {
        // $can_cancel = true;

        // if (!$user) {
        //     $user = auth()->id();
        // }
        // $user = User::where('id', $user)->first();

        // $transaction = Transaction::where('id', $transaction)->first();

        // // check if unliquidated
        // if (in_array($transaction->status_id, config('global.generated_form'))) {
        //     // check if not admin and not the owner
        //     if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
        //         $can_cancel = false;
        //     }
        // } else {
        //     $can_cancel = false;
        // }

        // return $can_cancel;
        return false;
    }

    private function check_can_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        // if (in_array($transaction->status_id, config('global.generated_form'))) {
        if (in_array($transaction->status_id, config('global.generated_form')) || ($user->role_id == 1 && in_array($transaction->status_id, config('global.form_approval')))) {
            // check if not admin
            if ($user->role_id != 1) {
                // check if owned
                if ($user->id == $transaction->owner_id) {
                    // check if pr, not po
                    if ($transaction->trans_type != 'pc') {
                        // check role limit
                        if ($user->role_id == 2) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_PRPOFORM_USER_2')->first()->value;
                        } else if ($user->role_id == 3) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_PRPOFORM_USER_3')->first()->value;
                        } else {
                            $can_edit = false;
                        }

                        // check if role limit is enough
                        if ($transaction->edit_count >= $edit_limit) {
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

    private function check_can_approval($transaction, $user = '') {
        $can_approve = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated_form'))) {
            // check if not admin and not the owner
            if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
                $can_approve = false;
            }
        } else {
            $can_approve = false;
        }

        return $can_approve;
    }

    private function check_can_print($transaction) {
        $can_print = true;

        $transaction = Transaction::where('id', $transaction)->first();

        //  check if for approval
        if (!in_array($transaction->status_id, config('global.form_approval_printing')) && !in_array($transaction->status_id, config('global.page_liquidation'))) {
            $can_print = false;
        }

        return $can_print;
    }

    private function check_can_issue($transaction, $user = '') {
        $can_issue = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if not unliquidated and not designated approver
        // if (!in_array($transaction->status_id, config('global.form_approval')) || $user->id != $transaction->form_approver_id) {
        if (!in_array($transaction->status_id, config('global.form_approval')) || !in_array($user->role_id, config('global.approver_form'))) {
            $can_issue = false;
        }
        
        return $can_issue;
    }
}
