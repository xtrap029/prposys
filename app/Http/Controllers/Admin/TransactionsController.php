<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Transaction;
use App\Company;
use App\Particulars;
use App\CompanyProject;
use App\User;
use App\Settings;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller {

    public function index($trans_page, $trans_company = '') {

        switch ($trans_page) {
            case 'prpo':
                $page_label_index = "Payment Release / Purchase Order";
                break;  
            case 'pc':
                $page_label_index = "Petty Cash";
                break;            
            default:
                abort(404);
                break;
        }

        $companies = Company::orderBy('name', 'asc')->get();
        $company = Company::where('id', $trans_company)->first();
        $transactions = Transaction::whereIn('trans_type', ['pr', 'po'])
                                    ->whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);

        foreach ($transactions as $key => $value) {
            $transactions[$key]->can_edit = $this->check_can_edit($value->id);
        }

        $edit_pr_limit[2] = Settings::where('type', 'LIMIT_EDIT_GENPR_USER_2')->first()->value;
        $edit_pr_limit[3] = Settings::where('type', 'LIMIT_EDIT_GENPR_USER_3')->first()->value;
        
        return view('pages.admin.transaction.index')->with([
            'trans_page' => $trans_page,
            'page_label' => $page_label_index,
            'companies' => $companies,
            'company' => $company,
            'transactions' => $transactions,
            'edit_pr_limit' => $edit_pr_limit
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
        if (in_array($request->trans_type, ['pr', 'po'])) {
            $trans_type = $request->trans_type;

            $data = $request->validate([
                'trans_type' => ['required', 'in:pr,po'],
                'particulars_id' => ['required', 'exists:particulars,id'],
                'currency' => ['required', 'in:PHP'],
                'amount' => ['required', 'min:0'],
                'purpose' => ['required'],
                'project_id' => ['required', 'exists:company_projects,id'],
                'payee' => ['required'],
                'due_at' => ['required', 'date'],
                'requested_id' => ['required', 'exists:users,id']
            ]);

            $trans_company = CompanyProject::where('id', $data['project_id'])->first()->company_id;

            // if non admin requestor, validate limit applicable for pr only
            if (User::where('id', $data['requested_id'])->first()->role_id != 1 && $trans_type == 'pr') {
                $trans_bal = $this->check_unliquidated_balance($data['requested_id']);

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
        $latest_trans = Transaction::where('trans_year', now()->year)->where('trans_type', $data['trans_type'])->orderBy('trans_seq', 'desc')->first();
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
        $data = $request->validate([
            'particulars_id' => ['required', 'exists:particulars,id'],
            'amount' => ['required', 'min:0'],
            'purpose' => ['required'],
            'project_id' => ['required', 'exists:company_projects,id'],
            'payee' => ['required']
        ]);

        // if not pr, not admin, amount does exceed limit
        if ($transaction->trans_type == 'pr' 
            && $transaction->requested->role_id != 1
            && $data['amount'] - $transaction->amount > $this->check_unliquidated_balance($transaction->requested_id)['amount']) {

            $validator = \Validator::make(request()->all(), []);    
            $validator->errors()->add('amount', __('messages.exceed_amount_unliq'));

            return redirect('/transaction/edit/'.$transaction->id)->withErrors($validator);
        }
        
        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $data['edit_count'] = $transaction->edit_count + 1;
        }

        $data['status_id'] = 2;
        $data['updated_id'] = auth()->id();

        $transaction->update($data);

        return redirect('/transaction/view/'.$transaction->id);
    }

    public function show(Transaction $transaction) {
        $logs = Activity::where('subject_id', $transaction->id)
                ->where('subject_type', 'App\Transaction')
                ->orderBy('id', 'desc')->get();

        return view('pages.admin.transaction.show')->with([
            'transaction' => $transaction,
            'logs' => $logs
        ]);
    }

    private function check_unliquidated_balance($user) {
        $user = User::where('id', $user)->first();

        if ($user->LIMIT_UNLIQUIDATEDPR_AMOUNT) {
            $trans_amount_limit = $user->LIMIT_UNLIQUIDATEDPR_AMOUNT;
        } else {
            $trans_amount_limit = Settings::where('type', 'LIMIT_UNLIQUIDATEDPR_AMOUNT')->first()->value;
        }
        
        if ($user->LIMIT_UNLIQUIDATEDPR_COUNT) {
            $trans_count_limit = $user->LIMIT_UNLIQUIDATEDPR_COUNT;
        } else {
            $trans_count_limit = Settings::where('type', 'LIMIT_UNLIQUIDATEDPR_COUNT')->first()->value;
        }

        $transactions = Transaction::where('requested_id', $user->id)
                        ->where('trans_type', 'pr')
                        ->whereIn('status_id', config('global.unliquidated'));
        $trans_amount = $transactions->sum('amount');
        $trans_count = $transactions->count();

        $trans_bal['amount'] = $trans_amount_limit - $trans_amount;
        $trans_bal['count'] = $trans_count_limit - $trans_count;

        return $trans_bal;
    }

    private function check_can_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();


        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.unliquidated'))) {
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
}
