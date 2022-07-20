<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Company;
use App\Transaction;
use App\TransactionStatus;
use App\TransactionsAttachment;
use App\TransactionsLiquidation;
use App\TransactionsDescription;
use App\User;
use Illuminate\Http\Request;
use Auth;
use \DB;

class ControlPanelsController extends Controller {
    
    public function revert_status_prev() {
        $transaction = null;

        if (!empty($_GET['company_id']) && !empty($_GET['trans'])) {
            $key = $_GET['trans'];
            $trans_company = $_GET['company_id'];

            $transaction = Transaction::whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->where(static function ($query) use ($key) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%");
                                    })->first();

            if (!$transaction || !$transaction->status_prev_id) {
                return back()->with('error', __('messages.not_found'));
            }
        }

        $companies = Company::orderBy('name', 'asc')->get();

        return view('pages.admin.controlpanel.revertstatusprev.index')->with([
            'companies' => $companies,
            'transaction' => $transaction
        ]);
    }

    public function revert_status() {
        $transaction = null;

        if (!empty($_GET['company_id']) && !empty($_GET['trans'])) {
            $key = $_GET['trans'];
            $trans_company = $_GET['company_id'];

            $transaction = Transaction::whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->where('is_deposit', 0)
                                    ->where('is_bills', 0)
                                    ->where('is_hr', 0)
                                    ->where('is_reimbursement', 0)
                                    ->where('is_bank', 0)
                                    ->where(static function ($query) use ($key) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%");
                                    })->first();

            if ($transaction && $transaction->status_id == config('global.cancelled')) {
                return back()->with('error', __('messages.not_found'));
            } else if (!$transaction) {
                return redirect('/control-panel/revert-status-prev?company_id='.$_GET['company_id'].'&trans='.$_GET['trans']);
            }
        }

        $status_order = [1,5,6,4,7,8];
        $status = TransactionStatus::orderBy('id', 'asc')->get();
        $status = $status->sortBy(function($model) use ($status_order) {
            return array_search($model->getKey(), $status_order);
        });

        $companies = Company::orderBy('name', 'asc')->get();

        return view('pages.admin.controlpanel.revertstatus.index')->with([
            'companies' => $companies,
            'transaction' => $transaction,
            'status' => $status
        ]);
    }

    public function revert_status_store_prev(Request $request) {
        $data = $request->validate([
            'id' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('id', auth()->id())->first();
        $request->request->add(['email' => $user->email]);
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $transaction = Transaction::where('id', $data['id'])->first();
            $transaction->status_id = $transaction->status_prev_id;
            $transaction->save();

            return redirect('/control-panel/revert-status')->with('success', 'Transaction '.__('messages.edit_success'));
        } else {
            return back()->with('error', __('messages.invalid_access'));
        }
    }

    public function revert_status_store(Request $request) {
        $data = $request->validate([
            'id' => ['required', 'exists:transactions,id'],
            'password' => ['required'],
            'status' => ['required', 'exists:transaction_status,id'],
        ]);

        $user = User::where('id', auth()->id())->first();
        $request->request->add(['email' => $user->email]);
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $transaction = Transaction::where('id', $data['id'])->first();            
            
            if (in_array($data['status'], [8, 7, 4, 6, 5, 1])) {
                $transaction->depo_type = null;
                $transaction->depo_bank_branch_id = null;
                $transaction->depo_ref = null;
                $transaction->depo_received_by = null;
                $transaction->depo_date = null;
                $transaction->depo_slip = null;
                $transaction->liquidation_approver_id = null;
                
                if (in_array($data['status'], [4, 6, 5, 1])) {
                    TransactionsAttachment::where('transaction_id', $transaction->id)->delete();
                    TransactionsLiquidation::where('transaction_id', $transaction->id)->delete();
                    
                    if (in_array($data['status'], [6, 5, 1])) {
                        $transaction->control_type = null;
                        $transaction->control_no = null;
                        $transaction->released_at = null;
                        $transaction->amount_issued = null;
                        $transaction->depo_slip = null;
                        $transaction->issue_slip = null;
                        $transaction->released_by_id = null;
                        $transaction->form_company_id = null;
                        $transaction->currency_2 = null;
                        $transaction->currency_2_rate = null;
                        $transaction->form_service_charge = null;
                        
                        if (in_array($data['status'], [5, 1])) {
                            $transaction->form_vat_code = null;
                            $transaction->form_vat_name = null;
                            $transaction->form_vat_vat = null;
                            $transaction->form_vat_wht = null;
                            $transaction->form_amount_unit = null;
                            $transaction->form_amount_vat = null;
                            $transaction->form_amount_wht = null;
                            $transaction->form_amount_subtotal = null;
                            $transaction->form_amount_payable = null;
                            
                            if (in_array($data['status'], [1])) {
                                $transaction->coa_tagging_id = null;
                                $transaction->vat_type_id = null;
                                TransactionsDescription::where('transaction_id', $transaction->id)->delete();
                            }
                        }
                    }
                }
            }
            
            $transaction->updated_id = auth()->id();
            $transaction->status_id = $data['status'];

            $transaction->save();

            return redirect('/control-panel/revert-status')->with('success', 'Transaction '.__('messages.edit_success'));
        } else {
            return back()->with('error', __('messages.invalid_access'));
        }
    }

    public function force_cancel() {
        $transaction = null;

        if (!empty($_GET['company_id']) && !empty($_GET['trans'])) {
            $key = $_GET['trans'];
            $trans_company = $_GET['company_id'];

            $transaction = Transaction::whereHas('project', function($query) use($trans_company) {
                                        $query->where('company_id', $trans_company);
                                    })
                                    ->whereNotIn('status_id', config('global.cancelled'))
                                    ->where(static function ($query) use ($key) {
                                        $query->where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), 'LIKE', "%".$key."%");
                                    })->first();

            if (!$transaction) {
                return back()->with('error', __('messages.not_found'));
            }
        }

        $companies = Company::orderBy('name', 'asc')->get();

        return view('pages.admin.controlpanel.forcecancel.index')->with([
            'companies' => $companies,
            'transaction' => $transaction
        ]);
    }

    public function force_cancel_store(Request $request) {
        $data = $request->validate([
            'id' => ['required'],
            'password' => ['required'],
            'cancellation_reason' => ['required'],
        ]);

        $user = User::where('id', auth()->id())->first();
        $request->request->add(['email' => $user->email]);
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $transaction = Transaction::where('id', $data['id'])->first();
            $transaction->status_prev_id = $transaction->status_id;
            $transaction->status_id = config('global.cancelled')[0];
            $transaction->cancellation_number = rand(100000000, 999999999);
            $transaction->cancellation_reason = $data['cancellation_reason'];
            $transaction->save();

            return redirect('/control-panel/force-cancel')->with('success', 'Transaction '.__('messages.cancel_success'));
        } else {
            return back()->with('error', __('messages.invalid_access'));
        }
    }
}
