<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Transaction;
use App\User;
use App\Helpers\TransactionHelper;

class HomeController extends Controller {

    public function index() {
        $user = User::where('id', auth()->id())->first();
        $company_id = $user->company_id;

        $unliquidated = Transaction::where('requested_id', auth()->id())
                        ->whereIn('status_id', config('global.form_issued'))
                        ->where('requested_id', auth()->id())
                        ->orderBy('updated_at', 'desc')
                        ->limit(5)
                        ->get();

        $cleared = Transaction::where('requested_id', auth()->id())
                        ->whereIn('status_id', config('global.liquidation_cleared'))
                        ->where('requested_id', auth()->id())
                        ->orderBy('updated_at', 'desc')
                        ->limit(5)
                        ->get();
        $requested = Transaction::where('requested_id', auth()->id())
                        ->whereHas('project', function($query) use($company_id) {
                            $query->where('company_id', $company_id);
                        })
                        ->whereIn('status_id', config('global.form_approval'))
                        ->where('requested_id', auth()->id())
                        ->orderBy('id', 'desc')
                        ->limit(5)
                        ->get();

        $unliquidated_bal = TransactionHelper::check_unliquidated_balance(auth()->id());
        $logs = Activity::where('causer_id', auth()->id())
                ->orderBy('id', 'desc')
                ->limit(10)->get();
        
        $prepared = Transaction::where('owner_id', auth()->id())
                     ->orderBy('id', 'desc')
                     ->limit(5)->get();

        $stats['cancelled'] = Transaction::whereIn('status_id', config('global.cancelled'))->where('requested_id', auth()->id())->count();
        $stats['generated'] = Transaction::whereIn('status_id', config('global.generated'))->where('requested_id', auth()->id())->count();
        $stats['forms'] = Transaction::whereIn('status_id', config('global.forms'))->where('requested_id', auth()->id())->count();
        $stats['issued'] = Transaction::whereIn('status_id', config('global.form_issued'))->where('requested_id', auth()->id())->count();
        $stats['liquidation'] = Transaction::whereIn('status_id', config('global.liquidations'))->where('requested_id', auth()->id())->count();
        $stats['cleared'] = Transaction::whereIn('status_id', config('global.liquidation_cleared'))->where('requested_id', auth()->id())->count();

        return view('home')->with([
            'user' => $user,
            'unliquidated' => $unliquidated,
            'unliquidated_bal' => $unliquidated_bal,
            'logs' => $logs,
            'requested' => $requested,
            'prepared' => $prepared,
            'cleared' => $cleared,
            'stats' => $stats
        ]);
    }
}
