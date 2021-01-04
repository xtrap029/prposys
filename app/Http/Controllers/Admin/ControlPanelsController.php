<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Company;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Auth;
use \DB;

class ControlPanelsController extends Controller {
    
    public function revert_status() {
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
        // $user = User::where('id', auth()->id())->first();

        return view('pages.admin.controlpanel.revertstatus.index')->with([
            // 'company' => $user->company_id,
            'companies' => $companies,
            'transaction' => $transaction
        ]);
    }

    public function revert_status_store(Request $request) {
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
}
