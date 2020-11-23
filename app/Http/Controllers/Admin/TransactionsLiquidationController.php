<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Bank;
use App\Company;
use App\ExpenseType;
use App\Transaction;
use App\TransactionsAttachment;
use App\TransactionsLiquidation;
use App\TransactionStatus;
use App\Settings;
use App\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use \DB;

class TransactionsLiquidationController extends Controller {
    
    public function index($trans_page, $trans_company = '') {
        $trans_page_url = $trans_page;

        switch ($trans_page) {
            case 'prpo':
                $page_label_index = "Payment Release / Purchase Order";
                $trans_types = ['pr', 'po'];
                $trans_page = 'prpo-liquidation';
            break;  
            case 'pc':
                $page_label_index = "Petty Cash";
                $trans_types = ['pc'];
                $trans_page = 'pc-liquidation';
                break;            
            default:
                abort(404);
                break;
        }

        $companies = Company::orderBy('name', 'asc')->get();
        $company = Company::where('id', $trans_company)->first();
        
        if (!empty($_GET['s']) || !empty($_GET['type']) || !empty($_GET['status'])) {
            
            if ($_GET['type'] != "") {
                $type = $_GET['type'];
                $trans_types = [$type];
            }
            
            $key = $_GET['s'];
            $transactions = Transaction::whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.page_liquidation'))
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
                                            ->orWhere('amount_issued', 'like', str_replace(',', '', "%{$key}%"))
                                            ->orWhere('amount_issued', '=', str_replace(',', '', $key))
                                            ->orWhere('form_amount_payable', 'like', str_replace(',', '', "%{$key}%"))
                                            ->orWhere('form_amount_payable', '=', str_replace(',', '', $key))
                                            ->orWhere('amount', 'like', str_replace(',', '', "%{$key}%"))
                                            ->orWhere('amount', '=', str_replace(',', '', $key));
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
                        $transactions = $transactions->whereIn('status_id', config('global.liquidation_approval'));
                        if (!in_array(User::where('id', auth()->id())->first()->role_id, [1, 2])) {
                            $transactions->where('requested_id', auth()->id());
                        }
                        break;
                    case 'cleared':
                        $transactions = $transactions->whereIn('status_id', config('global.liquidation_cleared'));
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
                                    ->whereIn('status_id', config('global.page_liquidation'))
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })->orderBy('id', 'desc')->paginate(10);
        }

        $can_edit_cleared = User::where('id', auth()->id())->first()->role_id == 1 ? true : false;
        foreach ($transactions as $key => $value) {
            $transactions[$key]->can_edit = $this->check_can_edit($value->id);
            $transactions[$key]->can_reset = $this->check_can_reset($value->id);
            $transactions[$key]->can_approval = $this->check_can_approval($value->id);
            $transactions[$key]->can_print = $this->check_can_print($value->id);
            $transactions[$key]->can_clear = $this->check_can_clear($value->id);
            $transactions[$key]->can_edit_cleared = $can_edit_cleared;
        }

        $approvers = User::whereIn('role_id', config('global.approver_liquidation'))->orderBy('name', 'asc')->get();
        
        return view('pages.admin.transactionliquidation.index')->with([
            'trans_page' => $trans_page,
            'trans_page_url' => $trans_page_url,
            'trans_types' => $trans_types,
            'page_label' => $page_label_index,
            'companies' => $companies,
            'company' => $company,
            'transactions' => $transactions,
            'approvers' => $approvers
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
                $trans_page = "prpo-liquidation";
                $trans_page_url = "prpo";
            break;  
            case 'pc':
                $trans_page = "pc-liquidation";
                $trans_page_url = "pc";
                break;            
            default:
                abort(404);
                break;
        }

        $expense_types = ExpenseType::orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name', 'asc')->get();
        $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();

        return view('pages.admin.transactionliquidation.create')->with([
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'transaction' => $transaction,
            'expense_types' => $expense_types,
            'banks' => $banks,
            'users' => $users,
        ]);
    }

    public function store(Request $request) {
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

        // validation rules
        $validate = [
            'file.*' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description.*' => ['required']
        ];

        if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr) {
            $validate['date.*'] = ['required', 'date'];
            $validate['expense_type_id.*'] = ['required', 'exists:expense_types,id'];
            $validate['description.*'] = ['required'];
            $validate['location.*'] = ['required'];
            $validate['receipt.*'] = ['in:1,0'];
            $validate['amount.*'] = ['required', 'min:0'];
        } else if ($transaction->is_deposit)  {
            $validate['depo_type'] = ['required', 'in:'.implode(',', config('global.deposit_type'))];
            $validate['depo_bank_branch_id'] = ['required', 'exists:bank_branches,id'];
            $validate['depo_ref'] = ['required'];
            $validate['depo_date'] = ['required', 'date'];
            $validate['depo_received_by'] = [];
            $validate['liquidation_approver_id'] = ['required', 'exists:users,id'];
        }

        // validate input
        $data = $request->validate($validate);

        if (!$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr) {
            $attr_liq['transaction_id'] = $transaction->id;
            $attr_liq['owner_id'] = auth()->id();
            $attr_liq['updated_id'] = auth()->id();

            foreach ($data['date'] as $key => $value) {
                $attr_liq['date'] = $value;
                $attr_liq['expense_type_id'] = $data['expense_type_id'][$key];
                $attr_liq['description'] = $data['description'][$key];
                $attr_liq['location'] = $data['location'][$key];
                $attr_liq['receipt'] = $data['receipt'][$key];
                $attr_liq['amount'] = $data['amount'][$key];

                TransactionsLiquidation::create($attr_liq);
            }
        }

        $attr_file['transaction_id'] = $transaction->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        foreach ($data['attachment_description'] as $key => $value) {
            $attr_file['description'] = $value;
            $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/liquidation'));
            TransactionsAttachment::create($attr_file);
        }

        if ($transaction->is_deposit) {
            $transaction->depo_type = $data['depo_type'];
            $transaction->depo_bank_branch_id = $data['depo_bank_branch_id'];
            $transaction->depo_ref = $data['depo_ref'];
            $transaction->depo_date = $data['depo_date'];
            $transaction->depo_received_by = $data['depo_received_by'];
            $transaction->liquidation_approver_id = $data['liquidation_approver_id'];
        }
        
        $transaction->status_id = !$transaction->is_deposit && !$transaction->is_bills && !$transaction->is_hr ? config('global.liquidation_generated')[0] : config('global.liquidation_cleared')[0];
        $transaction->edit_count = 0;
        $transaction->updated_id = auth()->id();
        $transaction->update();

        return redirect('/transaction-liquidation/view/'.$transaction->id);
    }

    public function show(Transaction $transaction) {
        $transaction_liquidations = TransactionsLiquidation::where('transaction_id', $transaction->id)->pluck('id')->toArray();
        $transaction_attachments = TransactionsAttachment::where('transaction_id', $transaction->id)->pluck('id')->toArray();
        $transaction_id = $transaction->id;

        $transaction_summary = TransactionsLiquidation::select(DB::raw('sum(amount) as amount'), 'expense_types.name as name')
            ->join('expense_types', 'transactions_liquidation.expense_type_id', '=', 'expense_types.id')
            ->where('transaction_id', $transaction->id)
            ->groupBy('expense_types.name')
            ->orderBy('expense_types.name', 'asc')
            ->get();

        $logs = Activity::where(function($query) use ($transaction_id){
                            $query->where('subject_id', $transaction_id);
                            $query->where('subject_type', 'App\Transaction');
                        })
                        ->orWhere(function($query) use ($transaction_liquidations){
                            $query->whereIn('subject_id', $transaction_liquidations);
                            $query->where('subject_type', 'App\TransactionsLiquidation');
                        })
                        ->orWhere(function($query) use ($transaction_attachments){
                            $query->whereIn('subject_id', $transaction_attachments);
                            $query->where('subject_type', 'App\TransactionsAttachment');
                        })
                        ->orderBy('id', 'desc')->paginate(15)->onEachSide(1);

        $perms['can_edit'] = $this->check_can_edit($transaction->id);
        $perms['can_reset'] = $this->check_can_reset($transaction->id);
        $perms['can_approval'] = $this->check_can_approval($transaction->id);
        $perms['can_print'] = $this->check_can_print($transaction->id);
        $perms['can_clear'] = $this->check_can_clear($transaction->id);
        $perms['can_edit_cleared'] = $this->check_can_clear_edit($transaction->id);

        switch ($transaction->trans_type) {
            case 'pr':
            case 'po':
                $trans_page_url = "prpo";
                $trans_page = "prpo-liquidation";
                break;  
            case 'pc':
                $trans_page_url = "pc";
                $trans_page = "pc-liquidation";
                break;            
            default:
                abort(404);
                break;
        }

        $transaction->liq_subtotal = number_format($transaction->liquidation->sum('amount'), 2, '.', '');
        $transaction->liq_before_vat = $transaction->liq_subtotal * 0.12;
        $transaction->liq_vat = $transaction->liq_subtotal - $transaction->liq_before_vat;
        $transaction->liq_balance = $transaction->liq_subtotal - $transaction->amount_issued;  

        $approvers = User::whereIn('role_id', config('global.approver_liquidation'))->orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name', 'asc')->get();

        return view('pages.admin.transactionliquidation.show')->with([
            'transaction' => $transaction,
            'transaction_summary' => $transaction_summary,
            'perms' => $perms,
            'logs' => $logs,
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'approvers' => $approvers,
            'banks' => $banks
        ]);
    }

    public function edit(Transaction $transaction) {
        if (!$this->check_can_edit($transaction->id)) {
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

        $expense_types = ExpenseType::orderBy('name', 'asc')->get();
        
        return view('pages.admin.transactionliquidation.edit')->with([
            'transaction' => $transaction,
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'expense_types' => $expense_types
        ]);
    }

    public function update(Request $request, Transaction $transaction) {
        // if can edit
        if (!$this->check_can_edit($transaction->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        // validate input
        $data = $request->validate([
            'date.*' => ['required', 'date'],
            'expense_type_id.*' => ['required', 'exists:expense_types,id'],
            'description.*' => ['required'],
            'location.*' => ['required'],
            'receipt.*' => ['in:1,0'],
            'amount.*' => ['required', 'min:0'],
            'file.*' => ['mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description_old.*' => ['required'],
            'attachment_description.*' => ['sometimes', 'required'],
            'attachment_id_old.*' => ['required']
        ]);        

        TransactionsLiquidation::where('transaction_id', $transaction->id)->delete();

        $attr_liq['transaction_id'] = $transaction->id;
        $attr_liq['owner_id'] = auth()->id();
        $attr_liq['updated_id'] = auth()->id();

        foreach ($data['date'] as $key => $value) {
            $attr_liq['date'] = $value;
            $attr_liq['expense_type_id'] = $data['expense_type_id'][$key];
            $attr_liq['description'] = $data['description'][$key];
            $attr_liq['location'] = $data['location'][$key];
            $attr_liq['receipt'] = $data['receipt'][$key];
            $attr_liq['amount'] = $data['amount'][$key];

            TransactionsLiquidation::create($attr_liq);
        }

        $desc_key = 0;
        foreach ($transaction->attachments as $key => $value) {
            $transaction_attachment = TransactionsAttachment::find($value->id);

            // check if item is retained
            if (in_array($value->id, $data['attachment_id_old'])) {
                // check if item is replaced
                if (!empty($request->file('file_old')) && array_key_exists($key, $request->file('file_old'))) {
                    // item is replaced
                    $transaction_attachment->file = basename($request->file('file_old')[$key]->store('public/attachments/liquidation'));        
                    $transaction_attachment->updated_id = auth()->id();
                }

                // replace description
                $transaction_attachment->description = $data['attachment_description_old'][$desc_key];
                
                // store changes
                $transaction_attachment->save();
                $desc_key++;
            } else {
                // the item is deleted
                $transaction_attachment->delete();
            }
        }

        $attr_file['transaction_id'] = $transaction->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        if (array_key_exists('attachment_description', $data)) {
            foreach ($data['attachment_description'] as $key => $value) {
                $attr_file['description'] = $value;
                $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/liquidation'));
                TransactionsAttachment::create($attr_file);
            }
        }

        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $transaction->edit_count = $transaction->edit_count + 1;
        }        
        $transaction->updated_id = auth()->id();
        $transaction->update();

        return redirect('/transaction-liquidation/view/'.$transaction->id);
    }

    public function reset(Transaction $transaction) {
        $user = User::where('id', auth()->id())->first();

        if ($user->role_id == 1) {
            $transaction->update(['edit_count' => 0]);
            return back()->with('success', 'Transaction Liquidation'.__('messages.reset_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    // public function approval(Request $request, Transaction $transaction) {
    public function approval(Transaction $transaction) {
        if ($this->check_can_approval($transaction->id)) {
            // $data = $request->validate([
            //     'liquidation_approver_id' => ['required', 'exists:users,id']
            // ]);

            $data['status_id'] = 8;
            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction Liquidation'.__('messages.approval_success'));
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

        $transaction_summary = TransactionsLiquidation::select(DB::raw('sum(amount) as amount'), 'expense_types.name as name')
            ->join('expense_types', 'transactions_liquidation.expense_type_id', '=', 'expense_types.id')
            ->where('transaction_id', $transaction->id)
            ->groupBy('expense_types.name')
            ->orderBy('expense_types.name', 'asc')
            ->get();

        $transaction->liq_subtotal = $transaction->liquidation->sum('amount');
        $transaction->liq_before_vat = $transaction->liq_subtotal * 0.12;
        $transaction->liq_vat = $transaction->liq_subtotal - $transaction->liq_before_vat;
        $transaction->liq_balance = $transaction->liq_subtotal - $transaction->amount_issued;         
        
        $final_approver = User::where(
            'id', Settings::where('type', 'AUTHORIZED_BY')
                ->select('value')->first()->value
        )->first()->name;

        return view('pages.admin.transactionliquidation.print')->with([
            'transaction' => $transaction,
            'transaction_summary' => $transaction_summary,
            'trans_page' => $trans_page,
            'final_approver' => $final_approver
        ]);
    }

    public function clear(Request $request, Transaction $transaction) {
        if ($this->check_can_clear($transaction->id)) {
            $data = [];

            if (number_format($transaction->liquidation->sum('amount'), 2, '.', '') - $transaction->amount_issued != 0) {
                $data = $request->validate([
                    'depo_type' => ['required', 'in:'.implode(',', config('global.deposit_type'))],
                    'depo_bank_branch_id' => ['required', 'exists:bank_branches,id'],
                    'depo_ref' => [],
                    'depo_received_by' => ['required'],
                    'depo_date' => ['required', 'date'],
                    'depo_slip' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:6048']
                ]);
                
                $data['depo_slip'] = basename($request->file('depo_slip')->store('public/attachments/deposit_slip'));
            }

            $data['status_id'] = 9;
            $data['liquidation_approver_id'] = auth()->id();
            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction Liquidation'.__('messages.clear_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function report() {
        switch ($_GET['type']) {
            case 'pr':
            case 'po':
                $trans_page_url = "prpo";
                $trans_page = "prpo-liquidation";
            break;  
            case 'pc':
                $trans_page_url = "pc";
                $trans_page = "pc-liquidation";
                break;            
            default:
                abort(404);
                break;
        }

        $transactions = Transaction::where('trans_type', $_GET['type'])->whereIn('status_id', config('global.page_liquidation'));
        
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
        $status = TransactionStatus::whereIn('id', config('global.page_liquidation'))->orderBy('id', 'asc')->get();

        return view('pages.admin.transactionliquidation.report')->with([
            'trans_page' => $trans_page,
            'trans_page_url' => $trans_page_url,
            'companies' => $companies,
            'status' => $status,
            'status_sel' => $status_sel,
            'transactions' => $transactions
        ]);
    }

    public function report_deposit() {
        switch ($_GET['type']) {
            case 'pr':
            case 'po':
                $trans_page_url = "prpo";
                $trans_page = "prpo-liquidation";
            break;  
            case 'pc':
                $trans_page_url = "pc";
                $trans_page = "pc-liquidation";
                break;            
            default:
                abort(404);
                break;
        }

        $transactions = Transaction::where('trans_type', $_GET['type'])
            ->where('is_deposit', '1')
            ->where('status_id', config('global.liquidation_cleared')[0]);
        
        if (!empty($_GET['company'])) {
            $req_company = $_GET['company'];
            $transactions = $transactions->whereHas('project', function($query) use($req_company) {
                $query->where('company_id', $req_company);
            });
        }

        if (!empty($_GET['from'])) {
            $transactions = $transactions->whereDate('created_at', '>=', $_GET['from']);
        }
        if (!empty($_GET['to'])) {
            $transactions = $transactions->whereDate('created_at', '<=', $_GET['to']);
        }

        $transactions = $transactions->orderBy('id', 'desc')->get();

        $companies = Company::orderBy('name', 'asc')->get();

        $status_sel = TransactionStatus::where('id', config('global.liquidation_cleared')[0])->first()->name;

        return view('pages.admin.transactionliquidation.reportdeposit')->with([
            'trans_page' => $trans_page,
            'trans_page_url' => $trans_page_url,
            'companies' => $companies,
            'status_sel' => $status_sel,
            'transactions' => $transactions
        ]);
    }

    public function print_cleared() {
        $trans_company = '';
        $trans_from = '';
        $trans_to = '';

        $transactions = Transaction::whereIn('status_id', config('global.liquidation_cleared'))->orderBy('id', 'desc');

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

        $transaction_loop = [];
        $transaction_summary_loop = [];
        foreach ($transactions as $key => $item) {
            $transaction_summary_loop[$key] = TransactionsLiquidation::select(DB::raw('sum(amount) as amount'), 'expense_types.name as name')
                ->join('expense_types', 'transactions_liquidation.expense_type_id', '=', 'expense_types.id')
                ->where('transaction_id', $item->id)
                ->groupBy('expense_types.name')
                ->orderBy('expense_types.name', 'asc')
                ->get();

            $item->liq_subtotal = $item->liquidation->sum('amount');
            $item->liq_before_vat = $item->liq_subtotal * 0.12;
            $item->liq_vat = $item->liq_subtotal - $item->liq_before_vat;
            $item->liq_balance = $item->liq_subtotal - $item->amount_issued;         
            $transaction_loop[$key] = $item;

        }

        $transactions = $transaction_loop;
        $transactions_summary = $transaction_summary_loop;

        $final_approver = User::where(
            'id', Settings::where('type', 'AUTHORIZED_BY')
                ->select('value')->first()->value
        )->first()->name;

        return view('pages.admin.transactionliquidation.printcleared')->with([
            'transactions' => $transactions,
            'transactions_summary' => $transactions_summary,
            'final_approver' => $final_approver
        ]);
    }

    public function finder_liquidation(TransactionsLiquidation $transaction) {
        return redirect('/transaction-liquidation/view/'.$transaction->transaction_id);
    } 

    public function finder_attachment(TransactionsAttachment $transaction) {
        return redirect('/transaction-liquidation/view/'.$transaction->transaction_id);
    } 

    public function clear_edit(Request $request, Transaction $transaction) {
        if (User::where('id', auth()->id())->first()->role_id == 1) {
            $data = $request->validate([
                'depo_type' => ['required', 'in:'.implode(',', config('global.deposit_type'))],
                'depo_bank_branch_id' => ['required', 'exists:bank_branches,id'],
                'depo_ref' => [],
                'depo_received_by' => ['required'],
                'depo_date' => ['required', 'date'],
                'depo_slip' => ['mimes:jpeg,png,jpg,pdf', 'max:6048']
            ]);

            if ($request->file('depo_slip')) {
                $data['depo_slip'] = basename($request->file('depo_slip')->store('public/attachments/deposit_slip'));
            }

            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction Liquidation'.__('messages.edit_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function check_can_create($key, $company) {
        $can_create = true;

        $result = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $key)
            ->whereHas('project', function($query) use($company) {
                $query->where('company_id', $company);
            })
            ->whereIn('status_id', config('global.form_issued'));

        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $result = $result->where('requested_id', auth()->id());
        }

        $result = $result->count();

        if ($result == 0) $can_create = false;

        return $can_create;
    }

    private function check_can_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.liquidation_generated'))) {
            // check if not admin
            if ($user->role_id != 1) {
                // check if requestor
                if ($user->id == $transaction->requested_id) {
                    // check if pr, not po
                    if ($transaction->trans_type != 'pc') {
                        // check role limit
                        if ($user->role_id == 2) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_LIQFORM_USER_2')->first()->value;
                        } else if ($user->role_id == 3) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_LIQFORM_USER_3')->first()->value;
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
        } else if (in_array($transaction->status_id, config('global.liquidation_approval')) && $user->role_id == 1) {
            // if admin and for approval status
        } else {
            $can_edit = false;
        }

        return $can_edit;
    }

    private function check_can_reset($transaction, $user = '') {
        $can_reset = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if reset
        if (!in_array($transaction->status_id, config('global.liquidation_generated')) || $user->role_id != 1 || in_array($transaction->trans_type, ['pc'])) {
            $can_reset = false;
        }

        return $can_reset;
    }

    private function check_can_approval($transaction, $user = '') {
        $can_approve = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.liquidation_generated'))) {
            // check if not admin and not the requestor
            if ($user->role_id != 1 && $user->id != $transaction->requested_id) {
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
        if (!in_array($transaction->status_id, config('global.liquidation_approval')) && !in_array($transaction->status_id, config('global.liquidation_cleared'))) {
            $can_print = false;
        }

        return $can_print;
    }

    private function check_can_clear($transaction, $user = '') {
        $can_clear = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if not for approval and not designated approver
        // if (!in_array($transaction->status_id, config('global.liquidation_approval')) || $user->id != $transaction->liquidation_approver_id) {
        if (!in_array($transaction->status_id, config('global.liquidation_approval')) || !in_array($user->role_id, config('global.approver_form'))) {
            $can_clear = false;
        }
        
        return $can_clear;
    }

    private function check_can_clear_edit($transaction, $user = '') {
        $can_clear_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if not for approval and not designated approver
        if (in_array($transaction->status_id, config('global.liquidation_cleared')) && $user->id == 1) {
        } else {
            $can_clear_edit = false;
        }
        
        return $can_clear_edit;
    }
}
