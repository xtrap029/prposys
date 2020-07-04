<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CoaTagging;
use App\Company;
use App\CompanyProject;
use App\ExpenseType;
use App\Particulars;
use App\Settings;
use App\Transaction;
use App\TransactionStatus;
use App\User;
use App\VatType;
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
        
        if (!empty($_GET['s'])) {
            $transactions = Transaction::whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.page_form'))
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$_GET['s']."%")
                                    ->orderBy('id', 'desc')->paginate(10);
            $transactions->appends(['s' => $_GET['s']]);
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
            'approvers' => $approvers
        ]);
    }

    public function create() {
        if (empty($_GET['key']) || !$this->check_can_create($_GET['key'])) {
            return back()->with('error', __('messages.make_not_allowed'));
        }

        $transaction = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $_GET['key'])->first();

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

        $coa_taggings = CoaTagging::orderBy('name', 'asc')->get();
        $expense_types = ExpenseType::orderBy('name', 'asc')->get();
        $vat_types = VatType::where('is_'.$transaction->trans_type, 1)->orderBy('id', 'asc')->get();

        return view('pages.admin.transactionform.create')->with([
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'transaction' => $transaction,
            'coa_taggings' => $coa_taggings,
            'expense_types' => $expense_types,
            'vat_types' => $vat_types
        ]);
    }

    public function store(Request $request) {
        // if can edit
        if (!$this->check_can_create($request->key)) {
            return back()->with('error', __('messages.cant_create'));
        } else {
            $transaction = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $request->key)->first();
        }

        // validate input
        $data = $request->validate([
            'coa_tagging_id' => ['required', 'exists:coa_taggings,id'],
            'expense_type_id' => ['required', 'exists:expense_types,id'],
            'vat_type_id' => ['required', 'exists:vat_types,id'],
        ]);

        $data['edit_count'] = 0;
        $data['status_id'] = 5;
        $data['updated_id'] = auth()->id();

        $transaction->update($data);

        return redirect('/transaction-form/view/'.$transaction->id);
    }

    public function show(Transaction $transaction) {
        $logs = Activity::where('subject_id', $transaction->id)
                ->where('subject_type', 'App\Transaction')
                ->orderBy('id', 'desc')->get();
        $perms['can_edit'] = $this->check_can_edit($transaction->id);
        $perms['can_cancel'] = $this->check_can_cancel($transaction->id);
        $perms['can_reset'] = $this->check_can_reset($transaction->id);
        $perms['can_approval'] = $this->check_can_approval($transaction->id);
        $perms['can_print'] = $this->check_can_print($transaction->id);
        $perms['can_issue'] = $this->check_can_issue($transaction->id);

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
            'approvers' => $approvers
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

        $coa_taggings = CoaTagging::orderBy('name', 'asc')->get();
        $expense_types = ExpenseType::orderBy('name', 'asc')->get();
        $vat_types = VatType::where('is_'.$transaction->trans_type, 1)->orderBy('id', 'asc')->get();
        
        return view('pages.admin.transactionform.edit')->with([
            'transaction' => $transaction,
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'coa_taggings' => $coa_taggings,
            'expense_types' => $expense_types,
            'vat_types' => $vat_types
        ]);
    }

    public function update(Request $request, Transaction $transaction) {
        // if can edit
        if (!$this->check_can_edit($transaction->id)) {
            return back()->with('error', __('messages.cant_edit'));
        }

        // validate input
        $data = $request->validate([
            'coa_tagging_id' => ['required', 'exists:coa_taggings,id'],
            'expense_type_id' => ['required', 'exists:expense_types,id'],
            'vat_type_id' => ['required', 'exists:vat_types,id'],
        ]);

        $data['edit_count'] = $transaction->edit_count + 1;
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

            $data['status_id'] = 3;
            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction Form'.__('messages.cancel_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function approval(Request $request, Transaction $transaction) {
        if ($this->check_can_approval($transaction->id)) {
            $data = $request->validate([
                'form_approver_id' => ['required', 'exists:users,id']
            ]);

            $custom_vat = $transaction->amount * (abs($transaction->vattype->vat) * 0.01);
            $custom_wht = $transaction->amount * ($transaction->vattype->wht * 0.01);
            $custom_subtotal = $transaction->amount;
            $custom_total_payable = $transaction->amount - $custom_wht;
            
            if ($transaction->vattype->vat >= 0) {
                $custom_subtotal = $transaction->amount - $custom_vat;
            } else {
                $custom_total_payable = $custom_total_payable + $custom_vat;
            }
            
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

        return view('pages.admin.transactionform.print')->with([
            'transaction' => $transaction,
            'trans_page' => $trans_page
        ]);
    }

    public function issue(Request $request, Transaction $transaction) {
        if ($this->check_can_issue($transaction->id)) {
            $data = $request->validate([
                'control_type' => ['required', 'in:CN,PC'],
                'control_no' => ['required'],
                'released_at' => ['required', 'date'],
                'amount_issued' => ['required', 'min:0']
            ]);
            
            $data['status_id'] = 4;
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

    private function check_can_create($key) {
        $can_create = true;

        $result = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $key)
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
        $can_cancel = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated_form'))) {
            // check if not admin and not the owner
            if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
                $can_cancel = false;
            }
        } else {
            $can_cancel = false;
        }

        return $can_cancel;
    }

    private function check_can_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated_form'))) {
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
        if (!in_array($transaction->status_id, config('global.form_approval_printing'))) {
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
        if (!in_array($transaction->status_id, config('global.form_approval')) || $user->id != $transaction->form_approver_id) {
            $can_issue = false;
        }
        
        return $can_issue;
    }
}
