<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use App\Company;
use App\Transaction;
use App\User;
use App\Settings;
use App\Helpers\TransactionHelper;
use App\Helpers\UAHelper;

class DashboardController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        $companies = Company::orderBy('name', 'asc')->get();
        $company_id = $user->company_id;
        $generated = [];
        $unliquidated = [];
        $cleared = [];
        $for_issue = [];
        $for_clearing = [];
        $deposited = [];

        if (UAHelper::get()['trans_view'] != config('global.ua_non')) {
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
                            ->whereIn('status_id', config('global.form_approval'));
            if (UAHelper::get()['trans_view'] == config('global.ua_own')) {
                $user_id = $user->id;
                $for_issue = $for_issue->where(static function ($query) use ($user_id) {
                    $query->where('requested_id', $user_id)
                    ->orWhere('owner_id',  $user_id);
                });
            }
            $for_issue = $for_issue->orderBy('updated_at', 'desc')->limit(6)->get();

            $for_clearing = Transaction::whereHas('project', function($query) use($company_id) {
                                $query->where('company_id', $company_id);
                            })
                            ->where('trans_type', '!=', 'pc')
                            ->whereIn('status_id', config('global.liquidations'));
            if (UAHelper::get()['trans_view'] == config('global.ua_own')) {
                $user_id = $user->id;
                $for_clearing = $for_clearing->where(static function ($query) use ($user_id) {
                    $query->where('requested_id', $user_id)
                    ->orWhere('owner_id',  $user_id);
                });
            }
            $for_clearing = $for_clearing->orderBy('updated_at', 'desc')->limit(6)->get();

            $deposited = Transaction::whereHas('project', function($query) use($company_id) {
                        $query->where('company_id', $company_id);
                    })
                    ->where('trans_type', '!=', 'pc')
                    ->whereIn('status_id', config('global.liquidation_cleared'))
                    ->where('is_deposit', '1');
            if (UAHelper::get()['trans_view'] == config('global.ua_own')) {
                $user_id = $user->id;
                $deposited = $deposited->where(static function ($query) use ($user_id) {
                    $query->where('requested_id', $user_id)
                    ->orWhere('owner_id',  $user_id);
                });
            }
            $deposited = $deposited->orderBy('updated_at', 'desc')->limit(6)->get();
        }
        $unliquidated_bal = TransactionHelper::check_unliquidated_balance(auth()->id(), $user->company_id);
        $liquidated_bal = TransactionHelper::check_liquidated_balance(auth()->id(), $user->company_id);
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

        $announcement = Settings::where('type', 'ANNOUNCEMENT')->first()->value;

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
            'announcement' => $announcement,
            'stats' => $stats,
        ]);
    }
}
