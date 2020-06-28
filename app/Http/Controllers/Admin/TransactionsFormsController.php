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
            // $transactions[$key]->can_edit = $this->check_can_edit($value->id);
            // $transactions[$key]->can_cancel = $this->check_can_cancel($value->id);
            // $transactions[$key]->can_reset = $this->check_can_reset($value->id);
        }
        
        return view('pages.admin.transactionform.index')->with([
            'trans_page' => $trans_page,
            'trans_page_url' => $trans_page_url,
            'trans_types' => $trans_types,
            'page_label' => $page_label_index,
            'companies' => $companies,
            'company' => $company,
            'transactions' => $transactions,
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

        return view('pages.admin.transactionform.create')->with([
            'trans_page_url' => $trans_page_url,
            'trans_page' => $trans_page,
            'transaction' => $transaction,
            'coa_taggings' => $coa_taggings,
            'expense_types' => $expense_types
        ]);
    }

    private function check_can_create($key) {
        $can_create = true;

        $result = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $key)
            ->where('owner_id', auth()->id())
            ->whereIn('status_id', config('global.generated'))
            ->count();

        if ($result == 0) $can_create = false;

        return $can_create;
    }
}
