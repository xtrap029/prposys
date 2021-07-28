<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\Company;
use App\Transaction;
use App\User;
use App\Helpers\TransactionHelper;

class DashboardController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        $companies = Company::orderBy('name', 'asc')->get();
        $company_id = $user->company_id;

        $generated = Transaction::whereHas('project', function($query) use($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->where('trans_type', '!=', 'pc')
                        ->where('owner_id', auth()->id())
                        ->whereIn('status_id', config('global.form_generated'))
                        ->orderBy('updated_at', 'desc')
                        ->limit(6)
                        ->get();

        $unliquidated = Transaction::whereHas('project', function($query) use($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->where('trans_type', '!=', 'pc')
                        ->where('requested_id', auth()->id())
                        ->whereIn('status_id', config('global.form_issued'))
                        ->orderBy('updated_at', 'desc')
                        ->limit(6)
                        ->get();

        $cleared = Transaction::whereHas('project', function($query) use($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->where('trans_type', '!=', 'pc')
                        ->where('requested_id', auth()->id())
                        ->whereIn('status_id', config('global.liquidation_cleared'))
                        ->orderBy('updated_at', 'desc')
                        ->limit(6)
                        ->get();

        $for_issue = Transaction::whereHas('project', function($query) use($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->where('trans_type', '!=', 'pc')
                        ->whereIn('status_id', config('global.form_approval'))
                        ->orderBy('updated_at', 'desc')
                        ->limit(6)
                        ->get();

        $for_clearing = Transaction::whereHas('project', function($query) use($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->where('trans_type', '!=', 'pc')
                        ->whereIn('status_id', config('global.liquidations'))
                        ->orderBy('updated_at', 'desc')
                        ->limit(6)
                        ->get();

        $deposited = Transaction::whereHas('project', function($query) use($company_id) {
                        $query->where('company_id', $company_id);
                    })
                    ->where('trans_type', '!=', 'pc')
                    ->whereIn('status_id', config('global.liquidation_cleared'))
                    ->where('is_deposit', '1')
                    ->orderBy('updated_at', 'desc')
                    ->limit(6)
                    ->get();

        $unliquidated_bal = TransactionHelper::check_unliquidated_balance(auth()->id());
        $liquidated_bal = TransactionHelper::check_liquidated_balance(auth()->id());
        // $logs = Activity::where('causer_id', auth()->id())
        //         ->orderBy('id', 'desc')
        //         ->limit(10)->get();
        
        // $prepared = Transaction::where('owner_id', auth()->id())
        //              ->orderBy('id', 'desc')
        //              ->limit(5)->get();

        $stats['cancelled'] = Transaction::where('trans_type', '!=', 'pc')->whereIn('status_id', config('global.cancelled'))->where('requested_id', auth()->id())->count();
        $stats['generated'] = Transaction::where('trans_type', '!=', 'pc')->whereIn('status_id', config('global.generated'))->where('requested_id', auth()->id())->count();
        // $stats['forms'] = Transaction::where('trans_type', '!=', 'pc')->whereIn('status_id', config('global.forms'))->where('requested_id', auth()->id())->count();
        $stats['issued'] = Transaction::where('trans_type', '!=', 'pc')->whereIn('status_id', config('global.form_issued'))->where('requested_id', auth()->id())->count();
        // $stats['liquidation'] = Transaction::where('trans_type', '!=', 'pc')->whereIn('status_id', config('global.liquidations'))->where('requested_id', auth()->id())->count();
        $stats['cleared'] = Transaction::where('trans_type', '!=', 'pc')->whereIn('status_id', config('global.liquidation_cleared'))->where('requested_id', auth()->id())->count();

        return view('pages.admin.dashboard.index')->with([
            'user' => $user,
            'companies' => $companies,
            'generated' => $generated,
            'unliquidated' => $unliquidated,
            'unliquidated_bal' => $unliquidated_bal,
            'liquidated_bal' => $liquidated_bal,
            // 'logs' => $logs,
            'for_issue' => $for_issue,
            'for_clearing' => $for_clearing,
            // 'prepared' => $prepared,
            'cleared' => $cleared,
            'deposited' => $deposited,
            'stats' => $stats
        ]);
    }
}
