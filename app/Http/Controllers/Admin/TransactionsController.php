<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Bank;
use App\Company;
use App\CostType;
use App\CompanyProject;
use App\ExpenseType;
use App\Particulars;
use App\ReleasedBy;
use App\ReportTemplate;
use App\Settings;
use App\Transaction;
use App\TransactionsSoa;
use App\TransactionStatus;
use App\TransactionsNote;
use App\TransactionsLiquidation;
use App\User;
use App\VatType;
use App\UaLevelRoute;
use App\Helpers\TransactionHelper;
use App\Helpers\UserHelper;
use App\Helpers\UAHelper;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use \DB;
use \File;
use \Storage;
use Carbon\Carbon;

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

        if (!in_array($trans_company, explode(',', User::where('id', auth()->id())->first()->companies))) {
            return abort(401);
        }

        $trans_status = TransactionStatus::whereIn('id', config('global.status'))->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $company = Company::where('id', $trans_company)->first();
        $projects = CompanyProject::where('company_id', $trans_company)->get();
        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();

        $transactions = new Transaction;

        $user_logged = User::where('id', auth()->id())->first();

        if (UAHelper::get()['trans_view'] == config('global.ua_own')) {
            $user_id = $user_logged->id;
            $transactions = $transactions->where(static function ($query) use ($user_id) {
                $query->where('requested_id', $user_id)
                ->orWhere('owner_id',  $user_id);
            });
        }

        if (!isset($_GET['is_confidential']) || (isset($_GET['is_confidential']) && $_GET['is_confidential'] == "")) {
            // $transactions = $transactions->whereHas('owner', function($query) use($user_logged) {
            //     $query->whereHas('ualevel', function($query2) use($user_logged) {
            //         $query2->where('code', '<=', $user_logged->ualevel->code);
            //     });
            // });
        }

        if (!empty($_GET['s'])
            || !empty($_GET['is_confidential'])
            || (isset($_GET['is_confidential']) && $_GET['is_confidential'] != "")
            || !empty($_GET['type'])
            || !empty($_GET['category'])
            || !empty($_GET['status'])
            || !empty($_GET['user_req'])
            || !empty($_GET['user_prep'])
            || !empty($_GET['project'])
            || !empty($_GET['due_from'])
            || !empty($_GET['due_to'])
            || !empty($_GET['amount'])
            || !empty($_GET['bal'])) {
            
            if ($_GET['type'] != "") {
                $type = $_GET['type'];
                $trans_types = [$type];
            }
            
            $key = $_GET['s'];
            
            $transactions = $transactions->whereIn('trans_type', $trans_types)
                                    ->whereIn('status_id', config('global.status'))                                    
                                    ->whereDoesntHave('project', function($query) use($trans_company) {
                                        $query->where('company_id', '!=', $trans_company);
                                    })                            
                                    ->where(static function ($query) use ($key) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%")
                                            ->orWhereHas('particulars', function($query) use($key) {
                                                $query->where('name', 'like', "%{$key}%");
                                            })
                                            ->orWhere('particulars_custom', 'like', "%{$key}%")
                                            ->orWhere('cost_control_no', 'like', "%{$key}%")
                                            ->orWhere('purpose', 'like', "%{$key}%")
                                            ->orWhere('payee', 'like', "%{$key}%")
                                            ->orWhereHas('coatagging', function($query) use($key) {
                                                $query->where('name', 'like', "%{$key}%");
                                            })
                                            ->orWhere('expense_type_description', 'like', "%{$key}%")
                                            ->orWhereHas('expensetype', function($query) use($key) {
                                                $query->where('name', 'like', "%{$key}%");
                                            })
                                            ->orWhereHas('vattype', function($query) use($key) {
                                                $query->where('name', 'like', "%{$key}%");
                                            })
                                            ->orWhereHas('vattype', function($query) use($key) {
                                                $query->where('code', 'like', "%{$key}%");
                                            })
                                            ->orWhere('control_no', 'like', "%{$key}%")
                                            ->orWhere('control_type', 'like', "%{$key}%")
                                            ->orWhere('cancellation_reason', 'like', "%{$key}%")
                                            ->orWhere('cancellation_number', 'like', "%{$key}%");
                                            // ->orWhere('amount_issued', 'like', str_replace(',', '', "%{$key}%"))
                                            // ->orWhere('amount_issued', '=', str_replace(',', '', $key))
                                            // ->orWhere('form_amount_payable', 'like', str_replace(',', '', "%{$key}%"))
                                            // ->orWhere('form_amount_payable', '=', str_replace(',', '', $key))
                                            // ->orWhere('amount', 'like', str_replace(',', '', "%{$key}%"))
                                            // ->orWhere('amount', '=', str_replace(',', '', $key));
                                    });
                                    
            if ($_GET['status'] != "") $transactions = $transactions->whereIn('status_id', explode(',', $_GET['status']));
            if ($_GET['user_req'] != "") $transactions = $transactions->where('requested_id', $_GET['user_req']);
            if ($_GET['user_prep'] != "") $transactions = $transactions->where('owner_id', $_GET['user_prep']);
            if ($_GET['project'] != "") $transactions = $transactions->where('project_id', $_GET['project']);
            if ($_GET['is_confidential'] != "") {
                if ($_GET['is_confidential'] == "2") {
                    $transactions = $transactions->where('is_confidential_own', 1);
                } else {
                    $transactions = $transactions->where('is_confidential', $_GET['is_confidential']);
                }
            }
            if ($_GET['due_from'] != "") $transactions = $transactions->whereDate('due_at', '>=', $_GET['due_from']);
            if ($_GET['due_to'] != "") $transactions = $transactions->whereDate('due_at', '<=', $_GET['due_to']);
            if ($_GET['amount'] != "") $transactions = $transactions->where('amount', $_GET['amount']);

            if ($_GET['category'] != "") {
                if ($_GET['category'] == 'is_reg') {
                    foreach (config('global.trans_category_column') as $key => $value) {
                        if ($value != '') {
                            $transactions = $transactions->where($value, 0);
                        }
                    }
                } else {
                    $transactions = $transactions->where($_GET['category'], 1);
                }
            }
            
            if ($_GET['bal'] == "0" && $_GET['bal'] != "") {
                $transactions = $transactions->whereHas('liquidation', function($query){
                    $query->havingRaw('sum(amount) = transactions.amount_issued');
                });
            } else if ($_GET['bal'] == "1") {
                $transactions = $transactions->whereHas('liquidation', function($query){
                    $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) > 0');
                });
            } else if ($_GET['bal'] == "-1") {
                $transactions = $transactions->whereHas('liquidation', function($query){
                    $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) < 0');
                });
            }

            $transactions = $transactions->orderBy('id', 'desc')->paginate(10);

            $transactions->appends(['s' => $_GET['s']]);
            $transactions->appends(['type' => $_GET['type']]);
            $transactions->appends(['status' => $_GET['status']]);
            $transactions->appends(['category' => $_GET['category']]);
            $transactions->appends(['user_req' => $_GET['user_req']]);
            $transactions->appends(['user_prep' => $_GET['user_prep']]);
            $transactions->appends(['project' => $_GET['project']]);
            $transactions->appends(['due_from' => $_GET['due_from']]);
            $transactions->appends(['due_to' => $_GET['due_to']]);
            $transactions->appends(['amount' => $_GET['amount']]);
            $transactions->appends(['bal' => $_GET['bal']]);
            $transactions->appends(['is_confidential' => $_GET['is_confidential']]);
        } else {
            $transactions = $transactions->whereIn('trans_type', $trans_types)
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
            if (in_array($value->status_id, config('global.page_form'))
                || (in_array($value->status_id, config('global.cancelled')) && in_array($value->status_prev_id, config('global.page_form')))) {
                $transactions[$key]->url_view .= "-form";
            } else if (in_array($value->status_id, config('global.page_liquidation'))
                || (in_array($value->status_id, config('global.cancelled')) && in_array($value->status_prev_id, config('global.page_liquidation')))) {
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
            'projects' => $projects,
            'users' => $users,
            'users_inactive' => $users_inactive,
            'can_view_confidential' => $this->check_can_view_confidential(),
            'transactions' => $transactions
        ]);
    }

    public function api_search(Request $request) {

        switch ($request->trans_page) {
            case 'prpo':
                $trans_types = ['pr', 'po'];
            break;  
            case 'pc':
                $trans_types = ['pc'];
                break;            
            default:
                abort(404);
                break;
        }
            
        if ($request->type != "") {
            $type = $request->type;
            $trans_types = [$type];
        }
        
        $key = $request->s;
        $trans_company = $request->trans_company;

        $transactions = new Transaction;

        $user_logged = User::where('id', auth()->id())->first();
        
        if (UAHelper::get()['trans_view'] == config('global.ua_own')) {
            $user_id = $user_logged->id;
            $transactions = $transactions->where(static function ($query) use ($user_id) {
                $query->where('requested_id', $user_id)
                ->orWhere('owner_id',  $user_id);
            });
        } else if (UAHelper::get()['trans_view'] == config('global.ua_none')) {
            $transactions = $transactions->where('id', 0);
        }

        if (!isset($request->is_confidential) || (isset($request->is_confidential) && $request->is_confidential == "")) {
            // $transactions = $transactions->whereHas('owner', function($query) use($user_logged) {
            //     $query->whereHas('ualevel', function($query2) use($user_logged) {
            //         $query2->where('code', '<=', $user_logged->ualevel->code);
            //     });
            // });
        }

        $transactions = $transactions->whereIn('trans_type', $trans_types)
                                ->whereIn('status_id', config('global.status'))
                                ->whereDoesntHave('project', function($query) use($trans_company) {
                                    $query->where('company_id', '!=', $trans_company);
                                }) 
                                ->where(static function ($query) use ($key) {
                                    $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%")
                                        ->orWhereHas('particulars', function($query) use($key) {
                                            $query->where('name', 'like', "%{$key}%");
                                        })
                                        ->orWhere('particulars_custom', 'like', "%{$key}%")
                                        ->orWhere('cost_control_no', 'like', "%{$key}%")
                                        ->orWhere('purpose', 'like', "%{$key}%")
                                        ->orWhere('payee', 'like', "%{$key}%")
                                        ->orWhereHas('coatagging', function($query) use($key) {
                                            $query->where('name', 'like', "%{$key}%");
                                        })
                                        ->orWhere('expense_type_description', 'like', "%{$key}%")
                                        ->orWhereHas('expensetype', function($query) use($key) {
                                            $query->where('name', 'like', "%{$key}%");
                                        })
                                        ->orWhereHas('vattype', function($query) use($key) {
                                            $query->where('name', 'like', "%{$key}%");
                                        })
                                        ->orWhereHas('vattype', function($query) use($key) {
                                            $query->where('code', 'like', "%{$key}%");
                                        })
                                        ->orWhere('control_no', 'like', "%{$key}%")
                                        ->orWhere('control_type', 'like', "%{$key}%")
                                        ->orWhere('cancellation_reason', 'like', "%{$key}%")
                                        ->orWhere('cancellation_number', 'like', "%{$key}%");
                                        // ->orWhere('amount_issued', 'like', str_replace(',', '', "%{$key}%"))
                                        // ->orWhere('amount_issued', '=', str_replace(',', '', $key))
                                        // ->orWhere('form_amount_payable', 'like', str_replace(',', '', "%{$key}%"))
                                        // ->orWhere('form_amount_payable', '=', str_replace(',', '', $key))
                                        // ->orWhere('amount', 'like', str_replace(',', '', "%{$key}%"))
                                        // ->orWhere('amount', '=', str_replace(',', '', $key));
                                });
                                
        if ($request->status != "") $transactions = $transactions->whereIn('status_id', explode(',', $request->status));
        if ($request->user_req != "") $transactions = $transactions->where('requested_id', $request->user_req);
        if ($request->user_prep != "") $transactions = $transactions->where('owner_id', $request->user_prep);
        if ($request->project != "") $transactions = $transactions->where('project_id', $request->project);
        if ($request->due_from != "") $transactions = $transactions->whereDate('due_at', '>=', $request->due_from);
        if ($request->due_to != "") $transactions = $transactions->whereDate('due_at', '<=', $request->due_to);
        if ($request->amount != "") $transactions = $transactions->where('amount', $request->amount);
        if ($request->is_confidential != "") {
            if ($request->is_confidential == "2") {
                $transactions = $transactions->where('is_confidential_own', 1);
            } else {
                $transactions = $transactions->where('is_confidential', $request->is_confidential);
            }
        }

        if ($request->category != "") {
            if ($request->category == 'is_reg') {
                foreach (config('global.trans_category_column') as $key => $value) {
                    if ($value != '') {
                        $transactions = $transactions->where($value, 0);
                    }
                }
            } else {
                $transactions = $transactions->where($request->category, 1);
            }
        }

        if ($request->bal == "0" && $request->bal != "") {
            $transactions = $transactions->whereHas('liquidation', function($query){
                $query->havingRaw('sum(amount) = transactions.amount_issued');
            });
        } else if ($request->bal == "1") {
            $transactions = $transactions->whereHas('liquidation', function($query){
                $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) > 0');
            });
        } else if ($request->bal == "-1") {
            $transactions = $transactions->whereHas('liquidation', function($query){
                $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) < 0');
            });
        }

        $transactions = $transactions->orderBy('id', 'desc')->limit(5)->get();

        $can_view_confidential = $this->check_can_view_confidential();

        foreach ($transactions as $key => $value) {
            $confidential = 0;

            // if req by
            if (auth()->id() != $value->requested_id) {
                // check levels
                // if (User::find(auth()->id())->ualevel->code < $value->owner->ualevel->code) $confidential = 1;
                // check level parallel confidential
                // if (User::find(auth()->id())->ualevel->code == $value->owner->ualevel->code && $value->is_confidential && auth()->id() != $value->owner->id) $confidential = 1;
                if (User::find(auth()->id())->ualevel->code <= $value->owner->ualevel->code && $value->is_confidential && auth()->id() != $value->owner->id && !$can_view_confidential) $confidential = 1;
                // check level own confidential
                if ($value->is_confidential_own && auth()->id() != $value->owner->id) $confidential = 1;
            }

            $transactions[$key]->trans_seq = sprintf("%05d", $value->trans_seq);
            $transactions[$key]->trans_type = strtoupper($value->trans_type);
            $transactions[$key]->status_name = strtoupper($value->status->name);
            $transactions[$key]->requested_by = $value->requested->name;
            $transactions[$key]->amount = number_format($value->form_amount_payable ?: $value->amount, 2, '.', ',');
            $transactions[$key]->created = Carbon::parse($value->created_at)->format('Y-m-d');
            $transactions[$key]->released = Carbon::parse($value->released_at)->format('Y-m-d');

            $transactions[$key]->is_confidential = $confidential;

            $transactions[$key]->url_view = "transaction";
            if (in_array($value->status_id, config('global.page_form'))
                || (in_array($value->status_id, config('global.cancelled')) && in_array($value->status_prev_id, config('global.page_form')))) {
                $transactions[$key]->url_view .= "-form";
            } else if (in_array($value->status_id, config('global.page_liquidation'))
                || (in_array($value->status_id, config('global.cancelled')) && in_array($value->status_prev_id, config('global.page_liquidation')))) {
                $transactions[$key]->url_view .= "-liquidation";
            }
        }

        return $transactions->toJson();
    }

    public function create($trans_type, $trans_company) {
        if (!in_array($trans_company, explode(',', User::where('id', auth()->id())->first()->companies))) return abort(401);

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

        // $particulars = Particulars::where('type', $trans_type)->get();
        $projects = CompanyProject::where('company_id', $trans_company)->orderBy('project', 'asc')->get();
        $users = User::whereNotNull('role_id')->orderBy('name', 'asc')->get();
        $company = Company::where('id', $trans_company)->first();
        $cost_types = CostType::orderBy('control_no', 'asc')->get();

        return view('pages.admin.transaction.create')->with([
            'trans_type' => $trans_type,
            'trans_company' => $trans_company,
            'trans_page' => $trans_page,
            // 'particulars' => $particulars,
            'projects' => $projects,
            'users' => $users,
            'cost_types' => $cost_types,
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
                'cost_type_id' => ['nullable', 'exists:cost_types,id'],
                'due_at' => ['required', 'date'],
                'requested_id' => ['required', 'exists:users,id'],
                'trans_category' => ['required', 'in:'.implode(',', config('global.trans_category'))],
                'soa' => ['sometimes', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
                'is_confidential' => ['required', 'between:0,1'],
                'is_confidential_own' => ['required', 'between:0,1'],
            ];

            // if ($trans_type == 'pc') {
            //     $validation['particulars_custom'] = ['required'];
            // } else {
            //     $validation['particulars_id'] = ['required', 'exists:particulars,id'];
            // }
            
            $data = $request->validate($validation);

            // ALLOW ALL CATEGORIES
            // if ($request->file('soa')) {
            //     $data['soa'] = basename($request->file('soa')->store('public/attachments/soa'));
            // }

            $data['is_deposit'] = 0;
            $data['is_bills'] = 0;
            $data['is_hr'] = 0;
            $data['is_reimbursement'] = 0;
            $data['is_bank'] = 0;

            switch ($data['trans_category']) {
                case config('global.trans_category')[1]:
                    $data['is_deposit'] = 1;
                    break;
                case config('global.trans_category')[2]:
                    $data['is_bills'] = 1;
                    break;
                case config('global.trans_category')[3]:
                    $data['is_hr'] = 1;
                    break;
                case config('global.trans_category')[4]:
                    $data['is_reimbursement'] = 1;
                    break;
                case config('global.trans_category')[5]:
                    $data['is_bank'] = 1;
                    break;
                default:
                    break;
            }

            unset($data['trans_category']);

            $trans_company = CompanyProject::where('id', $data['project_id'])->first()->company_id;

            // if non admin requestor, validate limit applicable for pr only
            if (User::where('id', $data['requested_id'])->first()->role_id != 1 && $trans_type == 'pr') {
                $trans_bal = TransactionHelper::check_unliquidated_balance($data['requested_id'], $trans_company);

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
        if ($latest_trans) {
            $data['trans_seq'] = $latest_trans->trans_seq+1;
        } else {
            $data['trans_seq'] = 1;
        }
        
        if ($data['cost_type_id']) {
            $latest_cost = Transaction::whereHas('project', function($query) use($trans_company) {
                    $query->where('company_id', $trans_company);
                })
                ->orderBy('cost_seq', 'desc')->first();
    
            if ($latest_cost->cost_seq) {
                $latest_cost_seq = $latest_cost->cost_seq + 1;
            } else {
                $latest_cost_seq = 1;
            }

            $data['cost_seq'] = $latest_cost_seq;

            $project = CompanyProject::where('id', $data['project_id'])->first();
            $cost_type = CostType::find($data['cost_type_id']);
            $data['cost_control_no'] = $project->company->qb_code.'.'.$project->company->qb_no.$cost_type->control_no.'.'.sprintf("%03d", $data['cost_seq']).'.'.config('global.cost_control_v');
        }

        $data['owner_id'] = auth()->id();
        $data['updated_id'] = auth()->id();
        $data['status_prev_id'] = 1;

        $transaction = Transaction::create($data);

        $data_attach = $request->validate([
            'file.*' => ['required', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description.*' => ['required']
        ]);

        $attr_file['transaction_id'] = $transaction->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        if (isset($data_attach['attachment_description'])) {
            foreach ($data_attach['attachment_description'] as $key => $value) {
                $attr_file['description'] = $value;
                $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/soa'));
                
                TransactionsSoa::create($attr_file);
            }
        }

        if ($request->note_content && $request->note_content != "") {
            TransactionsNote::create([
                'transaction_id' => $transaction->id,
                'content' => $request->note_content,
                'user_id' => auth()->id(),
                'created_at' => now()
            ]);
        }

        return redirect('/transaction/view/'.$transaction->id);
    }

    public function duplicate(Transaction $transaction) {

        if (
            (UAHelper::get()['trans_dup'] == config('global.ua_own') && auth()->id() != $transaction->owner_id && auth()->id() != $transaction->requested_id)
        ) {
            abort(404);
        }            

        $new_trans = new Transaction;
        $new_trans->trans_type = $transaction->trans_type;
        $new_trans->currency = $transaction->currency;
        $new_trans->amount = $transaction->amount;
        $new_trans->purpose = $transaction->purpose;
        $new_trans->project_id = $transaction->project_id;
        $new_trans->payee = $transaction->payee;
        $new_trans->due_at = $transaction->due_at;
        $new_trans->requested_id = $transaction->requested_id;

        $new_trans->is_deposit = $transaction->is_deposit;
        $new_trans->is_bills = $transaction->is_bills;
        $new_trans->is_hr = $transaction->is_hr;
        $new_trans->is_reimbursement = $transaction->is_reimbursement;
        $new_trans->is_bank = $transaction->is_bank;

        if ($transaction->soa) {
            $new_trans->soa = substr(md5(mt_rand()), 0, 7).'_'.$transaction->soa;
            Storage::disk('public')->copy('public/attachments/soa/'.$transaction->soa, 'public/attachments/soa/'.$new_trans->soa);
        }

        $trans_company = CompanyProject::where('id', $transaction->project_id)->first()->company_id;

        // generate transaction code
        $latest_trans = Transaction::where('trans_year', now()->year)
            ->where('trans_type', $transaction->trans_type)
            ->whereHas('project', function($query) use($trans_company) {
                $query->where('company_id', $trans_company);
            })
            ->orderBy('trans_seq', 'desc')->first();
        $new_trans->trans_year = now()->year;
        if($latest_trans) {
            $new_trans->trans_seq = $latest_trans->trans_seq+1;
        } else {
            $new_trans->trans_seq = 1;
        }

        $new_trans->owner_id = auth()->id();
        $new_trans->updated_id = auth()->id();
        $new_trans->status_prev_id = 1;

        $new_trans->save(); 

        return redirect('/transaction/view/'.$new_trans->id)->with('success', 'Transaction'.__('messages.duplicate_success'));
    }

    public function edit(Transaction $transaction) {
        if (!in_array($transaction->project->company_id, explode(',', User::where('id', auth()->id())->first()->companies))) return abort(401);

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

        // $particulars = Particulars::where('type', $transaction->trans_type)->get();
        $projects = CompanyProject::where('company_id', $transaction->project->company_id)->orderBy('project', 'asc')->get();
        $cost_types = CostType::orderBy('control_no', 'asc')->get();
        
        return view('pages.admin.transaction.edit')->with([
            'transaction' => $transaction,
            'company' => $transaction->project->company,
            'projects' => $projects,
            'cost_types' => $cost_types,
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
            'currency' => ['required'],
            'purpose' => ['required'],
            'project_id' => ['required', 'exists:company_projects,id'],
            'payee' => ['required'],
            'trans_category' => ['required', 'in:'.implode(',', config('global.trans_category'))],
            'soa' => ['sometimes', 'mimes:jpeg,png,jpg,pdf', 'max:6048'],
            // 'cost_control_no' => [],
            'cost_type_id' => ['nullable', 'exists:cost_types,id'],
        ];

        // if ($transaction->trans_type == 'pc') {
        //     $validation['particulars_custom'] = ['required'];
        // } else {
        //     $validation['particulars_id'] = ['required', 'exists:particulars,id'];
        // }

        $data = $request->validate($validation);

        $attach_liq = $request->validate([
            'file.*' => ['mimes:jpeg,png,jpg,pdf', 'max:6048'],
            'attachment_description_old.*' => ['required'],
            'attachment_description.*' => ['sometimes', 'required'],
            'attachment_id_old.*' => ['required']
        ]);

        $desc_key = 0;
        $attach_liq['attachment_id_old'] = isset($attach_liq['attachment_id_old']) ? $attach_liq['attachment_id_old'] : [];
        foreach ($transaction->transaction_soa as $key => $value) {
            $transaction_attachment = TransactionsSoa::find($value->id);

            // check if item is retained
            if (in_array($value->id, $attach_liq['attachment_id_old'])) {
                // check if item is replaced
                if (!empty($request->file('file_old')) && array_key_exists($key, $request->file('file_old'))) {
                    // item is replaced
                    $transaction_attachment->file = basename($request->file('file_old')[$key]->store('public/attachments/soa'));        
                    $transaction_attachment->updated_id = auth()->id();
                }

                // replace description
                $transaction_attachment->description = $attach_liq['attachment_description_old'][$desc_key];
                
                // store changes
                $transaction_attachment->save();
                $desc_key++;
            } else {
                // the item is deleted
                $transaction_attachment->delete();
            }
        }        

        $trans_company = CompanyProject::where('id', $data['project_id'])->first()->company_id;
        if ($data['cost_type_id'] && $transaction->cost_type_id == NULL) {
            $latest_cost = Transaction::whereHas('project', function($query) use($trans_company) {
                    $query->where('company_id', $trans_company);
                })
                ->orderBy('cost_seq', 'desc')->first();
    
            if ($latest_cost->cost_seq) {
                $latest_cost_seq = $latest_cost->cost_seq + 1;
            } else {
                $latest_cost_seq = 1;
            }

            $data['cost_seq'] = $latest_cost_seq;
        } else if ($data['cost_type_id'] == NULL) {
            $data['cost_seq'] = NULL;
            $data['cost_control_no'] = NULL;
        } 
        
        if ($data['cost_type_id']) {
            $project = CompanyProject::where('id', $data['project_id'])->first();
            $cost_type = CostType::find($data['cost_type_id']);
            $data['cost_control_no'] = $project->company->qb_code.'.'.$project->company->qb_no.$cost_type->control_no.'.'.sprintf("%03d", isset($data['cost_seq']) ? $data['cost_seq'] : $transaction->cost_seq).'.'.config('global.cost_control_v');
        }

        $attr_file['transaction_id'] = $transaction->id;
        $attr_file['owner_id'] = auth()->id();
        $attr_file['updated_id'] = auth()->id();

        if (array_key_exists('attachment_description', $attach_liq)) {
            foreach ($attach_liq['attachment_description'] as $key => $value) {
                $attr_file['description'] = $value;
                $attr_file['file'] = basename($request->file('file')[$key]->store('public/attachments/soa'));
                TransactionsSoa::create($attr_file);
            }
        }

        // if ($request->file('soa')) {
        //     $data['soa'] = basename($request->file('soa')->store('public/attachments/soa'));
        // } else if ($transaction->trans_type != 'po' && $request->trans_category != 'bp') {
        //     $data['soa'] = '';
        // }

        $data['is_deposit'] = 0;
        $data['is_bills'] = 0;
        $data['is_hr'] = 0;
        $data['is_reimbursement'] = 0;
        $data['is_bank'] = 0;

        switch ($data['trans_category']) {
            case config('global.trans_category')[1]:
                $data['is_deposit'] = 1;
                break;
            case config('global.trans_category')[2]:
                $data['is_bills'] = 1;
                break;
            case config('global.trans_category')[3]:
                $data['is_hr'] = 1;
                break;
            case config('global.trans_category')[4]:
                $data['is_reimbursement'] = 1;
                break;
            case config('global.trans_category')[5]:
                $data['is_bank'] = 1;
                break;
            default:
                break;
        }

        unset($data['trans_category']);

        // $data['is_deposit'] = $request->has('is_deposit') ?: '0';

        // if not pr, not admin, amount does exceed limit
        $trans_company = CompanyProject::where('id', $data['project_id'])->first()->company_id;
        
        if ($transaction->trans_type == 'pr' 
            && $transaction->requested->role_id != 1
            && $data['amount'] > TransactionHelper::check_unliquidated_balance($transaction->requested_id, $trans_company)['amount'] + $transaction->amount) {

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
        if (
            (!in_array($transaction->project->company_id, explode(',', User::where('id', auth()->id())->first()->companies)))
            ||
            (UAHelper::get()['trans_view'] == config('global.ua_own') && $transaction->requested_id != auth()->id() && $transaction->owner_id != auth()->id())
         ) return abort(401);

        if (in_array($transaction->status_id, config('global.page_form'))) return redirect('/transaction-form/view/'.$transaction->id);
        if (in_array($transaction->status_id, config('global.page_liquidation'))) return redirect('/transaction-liquidation/view/'.$transaction->id);
         
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
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
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
            'company' => $transaction->project->company,
            'perms' => $perms,
            'logs' => $logs,
            'users' => $users,
            'users_inactive' => $users_inactive,
            'releasing_users' => $releasing_users,
            'trans_page' => $trans_page
        ]);
    }

    public function manage(Request $request, Transaction $transaction) {
        if ($this->check_can_manage($transaction->id)) {

            // if (in_array($transaction->status_id, config('global.form_issued'))
            //     || in_array($transaction->status_id, config('global.liquidations'))
            //     || in_array($transaction->status_id, config('global.liquidation_cleared'))) {
            //     $data = $request->validate([
            //         'requested_id' => ['required', 'exists:users,id'],
            //         'owner_id' => ['required', 'exists:users,id'],
            //         'released_at' => ['required', 'date'],
            //         'released_by_id' => ['required', 'exists:released_by,id']
            //     ]); 
            // } else {
                $data = $request->validate([
                    'requested_id' => ['required', 'exists:users,id'],
                    'owner_id' => ['required', 'exists:users,id'],
                    'currency' => ['required'],
                    'due_at' => ['required', 'date']
                ]); 
            // }


            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction'.__('messages.reassign_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }
    
    public function note(Request $request, Transaction $transaction) {
        $validation = [
            'content' => ['required'],
        ];

        $data = $request->validate($validation);
        $data['transaction_id'] = $transaction->id;
        $data['user_id'] = auth()->id();
        $data['created_at'] = NOW();

        $note = TransactionsNote::create($data);

        return back()->with('success', 'Note'.__('messages.create_success'));
    }

    public function edit_note(Request $request, Transaction $transaction, TransactionsNote $transactionNote) {
        if ($transactionNote->user_id == auth()->id()) {
            $transactionNote->content = $request->note;
            $transactionNote->save();

            return back()->with('success', 'Note'.__('messages.edit_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function destroy_note(Transaction $transaction, TransactionsNote $transactionNote) {
        if ($transactionNote->user_id == auth()->id()) {
            $transactionNote->save();
            $transactionNote->delete();

            return back()->with('success', 'Note'.__('messages.delete_success'));
        } else {
            return back()->with('error', __('messages.cant_delete'));
        }
    }

    public function reset(Transaction $transaction) {
        $user = User::where('id', auth()->id())->first();

        if (
            (UAHelper::get()['trans_reset'] == config('global.ua_own') && $user->id != $transaction->owner_id && $transaction->requested_id != auth()->id())
            || UAHelper::get()['trans_reset'] == config('global.ua_none')
        ) {
            return back()->with('error', __('messages.cant_edit'));
        } else {
            $transaction->update(['edit_count' => 0]);
            return back()->with('success', 'Transaction'.__('messages.reset_success'));
        }
    }
    
    public function cancel(Request $request, Transaction $transaction) {
        if ($this->check_can_cancel($transaction->id)) {
            $data = $request->validate([
                'cancellation_reason' => ['required']
            ]);
            
            $data['cancellation_number'] = rand(100000000, 999999999);
            $data['status_prev_id'] = $transaction->status_id;
            $data['status_id'] = 3;
            $data['updated_id'] = auth()->id();
            $transaction->update($data);
            return back()->with('success', 'Transaction'.__('messages.cancel_success'));
        } else {
            return back()->with('error', __('messages.cant_edit'));
        }
    }

    public function update_company(Request $request) {
        if (UserHelper::switch_company($request->company_id)) {
            return redirect('/transaction/prpo/'.$request->company_id);
        } else {
            return back()->with('error', __('messages.invalid_command'));
        }
    }

    public function toggle_confidential($id) {
        $transaction = Transaction::where('id', $id)->first();
        
        if (
            (UAHelper::get()['trans_toggle_conf'] == config('global.ua_own') && auth()->id() != $transaction->owner_id && $transaction->requested_id != auth()->id())
            || UAHelper::get()['trans_toggle_conf'] == config('global.ua_none')
        ) {
            return back()->with('error', __('messages.invalid_command'));
        } else {   
            $is_confidential = $transaction->is_confidential;
            $transaction = Transaction::where('id', $id)->first();
            $transaction->is_confidential = $is_confidential == 1 ? 0 : 1;
            $transaction->save();
            return back()->with('success', 'Transaction'.__('messages.edit_success'));
        }
    }

    public function toggle_confidential_own($id) {
        $transaction = Transaction::where('id', $id)->first();
        
        if (
            (UAHelper::get()['trans_toggle_conf_own'] == config('global.ua_own') && auth()->id() != $transaction->owner_id && $transaction->requested_id != auth()->id())
            || UAHelper::get()['trans_toggle_conf_own'] == config('global.ua_none')
        ) {
            return back()->with('error', __('messages.invalid_command'));
        } else {   
            $is_confidential_own = $transaction->is_confidential_own;
            $transaction = Transaction::where('id', $id)->first();
            $transaction->is_confidential_own = $is_confidential_own == 1 ? 0 : 1;
            $transaction->save();
            return back()->with('success', 'Transaction'.__('messages.edit_success'));
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

    public function print_cancelled(Transaction $transaction) {
        if (!in_array($transaction->project->company_id, explode(',', User::where('id', auth()->id())->first()->companies))) return abort(401);

        return view('pages.admin.transaction.printcancelled')->with([
            'transaction' => $transaction,
            'company' => $transaction->project->company
        ]);
    }

    public function report_all() {
        $trans_page = '';
        $status_sel = '';
        $trans_type = '';
        $trans_company = '';
        $trans_from = '';
        $trans_to = '';
        $trans_year = '';
        $trans_min = '';
        $trans_max = '';
        $trans_status = '';
        $trans_category = '';
        $trans_req = '';
        $trans_bal= '';
        $trans_amount= '';
        $trans_template= '';

        $trans_s= '';

        $trans_prep = '';
        $trans_rel = '';
        $trans_updated = '';
        $trans_bank = '';
        $trans_appr_form = '';
        $trans_due_from = '';
        $trans_due_to = '';
        $trans_depo_from = '';
        $trans_depo_to = '';
        $trans_rel_from = '';
        $trans_rel_to = '';
        $trans_project = '';
        $trans_depo_type = '';
        $trans_vat_type = '';
        $trans_particulars = '';
        $trans_currency = '';
        $trans_control_no = '';
        $trans_is_confidential = '';
        $trans_amt_bal = '';

        $status_name = [];

        $transactions = Transaction::orderBy('id', 'desc');

        $user_logged = User::where('id', auth()->id())->first();
        $user_id = $user_logged->id;
        if (UAHelper::get()['trans_view'] == config('global.ua_own')
            || UAHelper::get()['trans_report'] == config('global.ua_own')) {
            $transactions = $transactions->where(static function ($query) use ($user_id) {
                $query->where('requested_id', $user_id)
                ->orWhere('owner_id',  $user_id);
            });
        } else if (UAHelper::get()['trans_view'] == config('global.ua_none')
            || UAHelper::get()['trans_report'] == config('global.ua_none')) {
            $transactions = $transactions->where('id', 0);
        } else {
            // $ua_code = User::find(auth()->id())->ualevel->code;
            // $transactions = $transactions->where(static function ($query) use ($user_id, $ua_code) {
            //     $query->where('requested_id', $user_id)
            //     ->orWhereHas('owner', function($q) use($ua_code) {
            //         $q->whereHas('ualevel', function($q2) use($ua_code){
            //             $q2->where('code', '<=', $ua_code);
            //         });        
            //     });
            // });
        }


         // if (!User::find(auth()->id())->is_smt) {
        //     $transactions = $transactions->where('is_confidential', 0);
        // }

        if (!empty($_GET['s'])) {
            $trans_s = $_GET['s'];
            $transactions = $transactions->where(static function ($query) use ($trans_s) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$trans_s."%")
                                            ->orWhereHas('particulars', function($query) use($trans_s) {
                                                $query->where('name', 'like', "%{$trans_s}%");
                                            })
                                            ->orWhere('particulars_custom', 'like', "%{$trans_s}%")
                                            ->orWhere('purpose', 'like', "%{$trans_s}%")
                                            ->orWhere('payee', 'like', "%{$trans_s}%")
                                            ->orWhereHas('coatagging', function($query) use($trans_s) {
                                                $query->where('name', 'like', "%{$trans_s}%");
                                            })
                                            ->orWhere('expense_type_description', 'like', "%{$trans_s}%")
                                            ->orWhereHas('expensetype', function($query) use($trans_s) {
                                                $query->where('name', 'like', "%{$trans_s}%");
                                            })
                                            ->orWhereHas('vattype', function($query) use($trans_s) {
                                                $query->where('name', 'like', "%{$trans_s}%");
                                            })
                                            ->orWhereHas('vattype', function($query) use($trans_s) {
                                                $query->where('code', 'like', "%{$trans_s}%");
                                            })
                                            ->orWhere('control_no', 'like', "%{$trans_s}%")
                                            ->orWhere('control_type', 'like', "%{$trans_s}%")
                                            ->orWhere('cancellation_reason', 'like', "%{$trans_s}%")
                                            ->orWhere('cancellation_number', 'like', "%{$trans_s}%")
                                            ->orWhere('amount_issued', 'like', str_replace(',', '', "%{$trans_s}%"))
                                            ->orWhere('amount_issued', '=', str_replace(',', '', $trans_s))
                                            ->orWhere('form_amount_payable', 'like', str_replace(',', '', "%{$trans_s}%"))
                                            ->orWhere('form_amount_payable', '=', str_replace(',', '', $trans_s))
                                            ->orWhere('amount', 'like', str_replace(',', '', "%{$trans_s}%"))
                                            ->orWhere('amount', '=', str_replace(',', '', $trans_s));
                                    });
        }
        
        $trans_type_csv = "All";
        if (!empty($_GET['type'])) {
            $trans_type = $_GET['type'];

            switch ($_GET['type']) {
                case 'pr':
                    $trans_page = "prpo";
                    $trans_type_csv = "Payment Release";
                case 'po':
                    $trans_page = "prpo";
                    $trans_type_csv = "Purchase Order";
                break;  
                case 'pc':
                    $trans_page = "pc";
                    $trans_type_csv = "Petty Cash";
                    break;            
                default:
                    abort(404);
                    break;
            }

            $transactions = $transactions->where('trans_type', $trans_type);
        }
        if (!empty($_GET['company'])) {
            $trans_company = $_GET['company'];
            $transactions = $transactions->whereDoesntHave('project', function($query) use($trans_company) {
                $query->where('company_id', '!=', $trans_company);
            });
        } else {
            $projects = CompanyProject::whereIn('company_id', explode(',', User::where('id', auth()->id())->first()->companies))->pluck('id')->toArray();
            $transactions = $transactions->whereIn('project_id', $projects);
        }        
        if (!empty($_GET['status'])) {
            $transactions = $transactions->whereIn('status_id', $_GET['status']);
            // $status_sel = TransactionStatus::where('id', $_GET['status'])->first()->name;
            $trans_status = $_GET['status'];
            
            if ($_GET['status'] != "") {
                foreach ($_GET['status'] as $key => $value) {
                    $status_name[] = config('global.status_filter_reports')[array_search($value, array_column(config('global.status_filter_reports'), 1))][0];
                }
            }
            $status_name = implode(', ', $status_name);
        } else {
            $status_name = 'All';
        }
        if (!empty($_GET['category'])) {
            if ($_GET['category'] == 'is_reg') {
                foreach (config('global.trans_category_column') as $key => $value) {
                    if ($value != '') {
                        $transactions = $transactions->where($value, 0);
                    }
                }
            } else {
                $transactions = $transactions->where($_GET['category'], 1);
                // $status_sel = TransactionStatus::where('id', $_GET['status'])->first()->name;
            }
            $trans_category = $_GET['category'];
        }
        if (!empty($_GET['from'])) {
            $transactions = $transactions->whereDate('created_at', '>=', $_GET['from']);
            $trans_from = $_GET['from'];
        }
        if (!empty($_GET['to'])) {
            $transactions = $transactions->whereDate('created_at', '<=', $_GET['to']);
            $trans_to = $_GET['to'];
        }
        if (!empty($_GET['due_from'])) {
            $transactions = $transactions->whereDate('due_at', '>=', $_GET['due_from']);
            $trans_due_from = $_GET['due_from'];
        }
        if (!empty($_GET['due_to'])) {
            $transactions = $transactions->whereDate('due_at', '<=', $_GET['due_to']);
            $trans_due_to = $_GET['due_to'];
        }   
        if (!empty($_GET['depo_from'])) {
            $transactions = $transactions->whereDate('depo_date', '>=', $_GET['depo_from']);
            $trans_depo_from = $_GET['depo_from'];
        }
        if (!empty($_GET['depo_to'])) {
            $transactions = $transactions->whereDate('depo_date', '<=', $_GET['depo_to']);
            $trans_depo_to = $_GET['depo_to'];
        }  
        if (!empty($_GET['rel_from'])) {
            $transactions = $transactions->whereDate('released_at', '>=', $_GET['rel_from']);
            $trans_rel_from = $_GET['rel_from'];
        }
        if (!empty($_GET['rel_to'])) {
            $transactions = $transactions->whereDate('released_at', '<=', $_GET['rel_to']);
            $trans_depo_type = $_GET['rel_to'];
        }        
        if (!empty($_GET['series_year'])) {
            $transactions = $transactions->where('trans_year', $_GET['series_year']);
            $trans_year = $_GET['series_year'];
        }
        if (!empty($_GET['series_min'])) {
            $transactions = $transactions->where('trans_seq', '>=', $_GET['series_min']);
            $trans_min = $_GET['series_min'];
        }
        if (!empty($_GET['series_max'])) {
            $transactions = $transactions->where('trans_seq', '<=', $_GET['series_max']);
            $trans_max = $_GET['series_max'];
        }
        if (!empty($_GET['user_req'])) {
            $transactions = $transactions->where('requested_id', $_GET['user_req']);
            $trans_req = $_GET['user_req'];
        }
        if (!empty($_GET['user_prep'])) {
            $transactions = $transactions->where('owner_id', $_GET['user_prep']);
            $trans_prep = $_GET['user_prep'];
        }
        if (!empty($_GET['user_rel'])) {
            $transactions = $transactions->where('released_by_id', $_GET['user_rel']);
            $trans_updated = $_GET['user_rel'];
        }
        if (!empty($_GET['user_updated'])) {
            $transactions = $transactions->where('updated_id', $_GET['user_updated']);
            $trans_prep = $_GET['user_updated'];
        }
        if (!empty($_GET['bank'])) {
            $transactions = $transactions->where('depo_bank_branch_id', $_GET['bank']);
            $trans_bank = $_GET['bank'];
        }
        if (!empty($_GET['user_approver_form'])) {
            $transactions = $transactions->where('form_approver_id', $_GET['user_approver_form']);
            $trans_appr_form = $_GET['user_approver_form'];
        }
        if (!empty($_GET['depo_type'])) {
            $transactions = $transactions->where('depo_type', $_GET['depo_type']);
            $trans_appr_form = $_GET['depo_type'];
        }
        if (!empty($_GET['vat_type'])) {
            $transactions = $transactions->where('vat_type_id', $_GET['vat_type']);
            $trans_vat_type = $_GET['vat_type'];
        }
        if (!empty($_GET['particulars'])) {
            $transactions = $transactions->where('particulars_id', $_GET['particulars']);
            $trans_particulars = $_GET['particulars'];
        }
        if (!empty($_GET['currency'])) {
            $transactions = $transactions->where('currency', $_GET['currency']);
            $trans_currency = $_GET['currency'];
        }
        if (!empty($_GET['control_no'])) {
            $transactions = $transactions->where('control_no', $_GET['control_no']);
            $trans_control_no = $_GET['control_no'];
        }
        if (!empty($_GET['is_confidential']) || (isset($_GET['is_confidential']) && $_GET['is_confidential'] != "")) {
            $transactions = $transactions->whereDate('created_at', '>=', $_GET['is_confidential']);
            $trans_is_confidential = $_GET['is_confidential'];

            if ($_GET['is_confidential'] != "") {
                $transactions = $transactions->where('is_confidential', $_GET['is_confidential']);
            }
        }
        if (isset($_GET['bal'])) {
            if ($_GET['bal'] == "0" && $_GET['bal'] != "") {
                $transactions = $transactions->whereHas('liquidation', function($query){
                    $query->havingRaw('sum(amount) = transactions.amount_issued');
                });
            } else if ($_GET['bal'] == "1") {
                $transactions = $transactions->whereHas('liquidation', function($query){
                    $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) > 0');
                });
            } else if ($_GET['bal'] == "-1") {
                $transactions = $transactions->whereHas('liquidation', function($query){
                    $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) < 0');
                });
            }

            $trans_bal = $_GET['bal'];
        }
        if (!empty($_GET['amount'])) {
            $transactions = $transactions->where('amount', $_GET['amount']);
            $trans_amount = $_GET['amount'];
        }
        if (!empty($_GET['project'])) {
            $transactions = $transactions->where('project_id', $_GET['project']);
            $trans_project = $_GET['project'];
        }
        if (!empty($_GET['amt_bal'])) {
            // foreach ($transactions as $key => $value) {

            // }
            // return ($config_confidential ? '-' : (number_format(($item->liquidation->sum('amount') ?: 0) - ($item->amount_issued ?: 0), 2, '.', ',')));
            // return ($config_confidential ? '-' : (($item->currency_2 ?: $item->currency).' '.number_format(($item->liquidation->sum('amount') ?: 0) - ($item->amount_issued ?: 0), 2, '.', ',')));

            $transactions = $transactions->whereHas('liquidation', function($query){
                $query->havingRaw('sum(amount) != transactions.amount_issued AND (sum(amount) - transactions.amount_issued) = '.$_GET['amt_bal']);
            });
            $trans_amt_bal = $_GET['amt_bal'];
        }

        $report_template = ReportTemplate::orderBy('id', 'asc');
        if (isset($_GET['template'])) {
            $report_template = $report_template->where('id', $_GET['template']);

            $trans_template = $_GET['template'];
        }
        $report_template = $report_template->first();

        $column_codes = [];
        foreach ($report_template->templatecolumn as $key => $value) {
             $column_codes[] = $value->column->name;
        }

        $transactions = $transactions->get();

        
        $users = User::where('ua_level_id', '!=', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $users_inactive = User::where('ua_level_id', config('global.ua_inactive'))->orderBy('name', 'asc')->get();
        $particulars = Particulars::orderBy('type', 'asc')->orderBy('name', 'asc')->get();
        $projects = CompanyProject::orderBy('project', 'asc')->get();
        $releasers = ReleasedBy::orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $banks = Bank::orderBy('name', 'asc')->get();
        $vat_types = VatType::orderBy('name', 'asc')->get();
        $status = TransactionStatus::whereIn('id', config('global.status'))->orderBy('id', 'asc')->get();
        $report_templates = ReportTemplate::orderBy('name', 'asc')->get();

        if (isset($_GET['csv'])) {
            $fileName = 'PRPOSYS-REPORT_'.Carbon::now().'.csv';
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment;   filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $file = fopen('php://output', 'w');
            $columns2 = array('Type', 'Status', 'Start Date', 'End Date', 'Generated By', 'Date Generated');
            // fputcsv($file, $columns);
            $columns3 = array(
                $trans_type_csv,
                $status_name,
                $trans_from != '' ? $trans_from : '-',
                $trans_to != '' ? $trans_to : '-',
                User::where('id', auth()->id())->first()->name,
                Carbon::now());
            // fputcsv($file, $columns);
            $columns4 = array('');
            // fputcsv($file, $columns);
            
            $columns = [];
            foreach ($report_template->templatecolumn as $key => $value) {
                $columns[] = $value->label;
            }
            
            $temp_column = $report_template->templatecolumn;
            $callback = function() use($transactions, $columns, $columns2, $columns3, $columns4, $temp_column) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, $columns2);
                fputcsv($file, $columns3);
                fputcsv($file, $columns4);
                fputcsv($file, $columns);

                foreach ($transactions as $item) {
                    
                    $config_confidential = 0;

                    $confidential = 0;

                    // if req by
                    if (auth()->id() != $item->requested_id) {
                        // check levels
                        // if (User::find(auth()->id())->ualevel->code < $item->owner->ualevel->code) $confidential = 1;
                        // check level parallel confidential
                        // if (User::find(auth()->id())->ualevel->code == $item->owner->ualevel->code && $item->is_confidential && auth()->id() != $item->owner->id) $confidential = 1;
                        if (User::find(auth()->id())->ualevel->code <= $item->owner->ualevel->code && $item->is_confidential && auth()->id() != $item->owner->id && !$this->check_can_view_confidential()) $confidential = true;
                        if ($item->is_confidential_own && auth()->id() != $item->owner->id) $confidential = 1;
                    }

                    if (!$confidential) {
                        $row = [];
                        foreach ($temp_column as $key => $value) {
                            $row[] = eval($value->column->code);
                        }
    
                        fputcsv($file, $row);
                    } 
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } else {
            return view('pages.admin.transaction.reportall')->with([
                'report_template' => $report_template,
                'column_codes' => $column_codes,
                'report_templates' => $report_templates,
                'companies' => $companies,
                'users' => $users,
                'users_inactive' => $users_inactive,
                'releasers' => $releasers,
                'banks' => $banks,
                'projects' => $projects,
                'vat_types' => $vat_types,
                'particulars' => $particulars,
                'transactions' => $transactions,
                'trans_type' => $trans_type,
                'trans_company' => $trans_company,
                'trans_from' => $trans_from,
                'trans_to' => $trans_to,
                'trans_due_from' => $trans_due_from,
                'trans_due_to' => $trans_due_to,
                'trans_depo_from' => $trans_depo_from,
                'trans_depo_to' => $trans_depo_to,
                'trans_rel_from' => $trans_rel_from,
                'trans_rel_to' => $trans_rel_to,
                'trans_year' => $trans_year,
                'trans_min' => $trans_min,
                'trans_max' => $trans_max,
                'trans_status' => $trans_status,
                'trans_category' => $trans_category,
                'trans_req' => $trans_req,
                'trans_prep' => $trans_prep,
                'trans_rel' => $trans_rel,
                'trans_updated' => $trans_updated,
                'trans_bank' => $trans_bank,
                'trans_appr_form' => $trans_appr_form,
                'trans_depo_type' => $trans_depo_type,
                'trans_vat_type' => $trans_vat_type,
                'trans_particulars' => $trans_particulars,
                'trans_currency' => $trans_currency,
                'trans_control_no' => $trans_control_no,
                'trans_is_confidential' => $trans_is_confidential,
                'trans_amt_bal' => $trans_amt_bal,
                'trans_bal' => $trans_bal,
                'trans_amount' => $trans_amount,
                'trans_project' => $trans_project,
                'trans_template' => $trans_template,
                'trans_s' => $trans_s,
                'status_name' => $status_name,
                'can_view_confidential' => $this->check_can_view_confidential(),
            ]);
        }
    }

    public function report_projects() {
        $trans_company = '';
        $trans_type = '';     
        $trans_from = '';
        $trans_to = '';
        $trans_status = '';
        $trans_own = false;

        $companies = Company::orderBy('name', 'asc')->get();

        $query = Company::orderBy('name', 'asc');

        if (UAHelper::get()['trans_view'] == config('global.ua_own')
            || UAHelper::get()['trans_report'] == config('global.ua_own')) {
            $trans_own = true;
        } else if (UAHelper::get()['trans_view'] == config('global.ua_none')
            || UAHelper::get()['trans_report'] == config('global.ua_none')) {
            $query = $query->where('id', 0);
        }

        $projects = [];

        if (!empty($_GET['company'])) {
            $trans_company = $_GET['company'];

            if (str_starts_with($_GET['company'], 'C')) {
                $query = $query->where('id', ltrim($trans_company, 'C'));
            } else {
                $query = $query->where('id', CompanyProject::find($trans_company)->company_id);
            }
        }
        if (!empty($_GET['status'])) {
            $trans_status = $_GET['status'];

            if ($_GET['status'] != "") {
                foreach ($_GET['status'] as $key => $value) {
                    $status_name[] = config('global.status_filter_reports')[array_search($value, array_column(config('global.status_filter_reports'), 1))][0];
                }
            }
            $status_name = implode(', ', $status_name);
        } else {
            $status_name = 'All';
        }
        if (!empty($_GET['type'])) $trans_type = $_GET['type'];
        if (!empty($_GET['from'])) $trans_from = $_GET['from'];
        if (!empty($_GET['to'])) $trans_to = $_GET['to'];
        
        $query = $query->get();
        

        if (isset($_GET['csv'])) {
            $fileName = 'PRPOSYS-REPORT-PROJECTS_'.Carbon::now().'.csv';
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment;   filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $file = fopen('php://output', 'w');
            $columns2 = array('Type', 'Status', 'Start Date', 'End Date', 'Generated By', 'Date Generated');
            $columns3 = array(
                'All',
                $status_name,
                $trans_from != '' ? $trans_from : '-',
                $trans_to != '' ? $trans_to : '-',
                User::where('id', auth()->id())->first()->name,
                Carbon::now());
            $columns4 = array('');
            $columns5 = array(
                'Company',
                'Project',
                'PR/PO',
                'Date',
                'Type',
                'Location/Route',
                'Receipt',
                'Status',
                'Description',
                'Amount',
            );
            
            $callback = function() use($query, $columns2, $columns3, $columns4, $columns5, $trans_type, $trans_status, $trans_from, $trans_to, $trans_own) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, $columns2);
                fputcsv($file, $columns3);
                fputcsv($file, $columns4);
                fputcsv($file, $columns5);

                foreach ($query as $item) {

                    
                    foreach ((!empty($trans_company) && !str_starts_with($trans_company, 'C') ? $item->companyProject->where('id', $trans_company) : $item->companyProject) as $project) {
                        
                        $project_liquidations = TransactionsLiquidation::where('transactions_liquidation.project_id', $project->id)
                            ->select(
                                'transactions.trans_type as trans_type',
                                'transactions.trans_year as trans_year',
                                'transactions.trans_seq as trans_seq',
                                'expense_types.name as expense_type',
                                'transaction_status.name as status',
                                'transactions_liquidation.date as date',
                                'transactions_liquidation.location as location',
                                'transactions_liquidation.receipt as receipt',
                                'transactions_liquidation.description as description',
                                'transactions_liquidation.amount as amount'
                            )
                            ->join('transactions', 'transactions_liquidation.transaction_id', '=', 'transactions.id')
                            ->join('expense_types', 'transactions_liquidation.expense_type_id', '=', 'expense_types.id')
                            ->join('transaction_status', 'transactions.status_id', '=', 'transaction_status.id');

                        if (!empty($trans_type)) {
                            $project_liquidations = $project_liquidations->where('transactions.trans_type', $trans_type);
                        }
                        if (!empty($trans_status)) {
                            $project_liquidations = $project_liquidations->whereIn('transactions.status_id', $trans_status);
                        }
                        if (!empty($trans_from)) {
                            $project_liquidations = $project_liquidations->whereDate('transactions.created_at', '>=', $trans_from);
                        }
                        if (!empty($trans_to)) {
                            $project_liquidations = $project_liquidations->whereDate('transactions.created_at', '<=', $trans_to);
                        }
                        if (!empty($trans_own)) {
                            $project_liquidations = $project_liquidations->where('transactions.requested_id', auth()->id());
                        }

                        $project_liquidations = $project_liquidations->orderBy('transactions_liquidation.transaction_id')->orderBy('transactions_liquidation.date');

                        foreach ($project_liquidations->get() as $liquidation) {
                            $row = [];
                            $row[] = $item->name;
                            $row[] = $project->project;
                            $row[] = strtoupper($liquidation->trans_type).'-'.$liquidation->trans_year.'-'.sprintf('%05d',$liquidation->trans_seq);
                            $row[] = $liquidation->date;
                            $row[] = $liquidation->expense_type;
                            $row[] = $liquidation->location;
                            $row[] = $liquidation->receipt ? 'Y' : 'N';
                            $row[] = $liquidation->status;
                            $row[] = $liquidation->description;
                            $row[] = $liquidation->amount;
                            fputcsv($file, $row);
                        }
                        
                    }

                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
            
        } else {
            return view('pages.admin.transaction.reportprojects')->with([
                'companies' => $companies,
                'query' => $query,
                'trans_own' => $trans_own,
                'trans_company' => $trans_company,
                'trans_type' => $trans_type,
                'trans_status' => $trans_status,
                'trans_from' => $trans_from,
                'trans_to' => $trans_to,
            ]);
        }
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
            // check if access = own and transaction is owned or if access = none
            if (
                (UAHelper::get()['trans_edit'] == config('global.ua_own') && $user->id != $transaction->owner_id  && $user->id != $transaction->requested_id)
                || UAHelper::get()['trans_edit'] == config('global.ua_none')
            ) {
                $can_edit = false;
            } else {
                if ($user->ualevel->code < $transaction->owner->ualevel->code && $user->id != $transaction->owner->id) $can_edit = false;

                // bypass hierarchy check and check if is_accounting
                if ($user->is_accounting) $can_edit = true;
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
            // check if access = own and transaction is owned or if access = none
            if (
                (UAHelper::get()['trans_cancel'] == config('global.ua_own') && $user->id != $transaction->owner_id && $user->id != $transaction->requested_id)
                || UAHelper::get()['trans_cancel'] == config('global.ua_none')
            ) {
                $can_cancel = false;
            } else {
                if ($user->ualevel->code < $transaction->owner->ualevel->code && $user->id != $transaction->owner->id) $can_cancel = false;

                // bypass hierarchy check and check if is_accounting
                if ($user->is_accounting) $can_cancel = true;
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
        if (!in_array($transaction->status_id, config('global.generated')) || in_array($transaction->trans_type, ['po', 'pc'])) {
            $can_reset = false;
        }

        // if (
        //     (UAHelper::get()['trans_reset'] == config('global.ua_own') && $user->id != $transaction->owner_id)
        //     || UAHelper::get()['trans_reset'] == config('global.ua_none')
        // ) {
        //     $can_reset = false;
        // }

        return $can_reset;
    }

    private function check_can_manage($transaction, $user = '') {
        $can_reassign = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();
        $transaction = Transaction::where('id', $transaction)->first();

        // check if access = own and transaction is owned or if access = none
        if (
            (UAHelper::get()['trans_manage'] == config('global.ua_own') && $user->id != $transaction->owner_id && $user->id != $transaction->requested_id)
            || UAHelper::get()['trans_manage'] == config('global.ua_none')
        ) {
            $can_reassign = false;
        }

        return $can_reassign;
    }

    private function check_can_view_confidential($user = '') {
        $can_view = true;

        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        $ua_level_route = UaLevelRoute::select('ua_route_option_id')->where('ua_route_id', config('global.ua_trans_view_conf'))->where('ua_level_id', $user->ualevel->id)->first();
        if ($ua_level_route->ua_route_option_id != config('global.is_yesno_id')[0]) {
            $can_view = false;
        }

        return $can_view;
    }
}
