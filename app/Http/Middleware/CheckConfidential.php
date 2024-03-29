<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\UaLevelRoute;
use App\Transaction;

class CheckConfidential
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $prefix = Route::getCurrentRoute()->action['prefix'];

        $transaction = Route::current()->parameter('transaction');

        if ($transaction && in_array($prefix, ['/transaction', '/transaction-form', '/transaction-liquidation'])) {
            
            $confidentials = [
                'duplicate',
                'edit',
                'edit_reimbursement',
                'update',
                'update_reimbursement',
                'update_issued',
                'update_issued_company',
                'update_issued_clear',
                'show',
                'manage',
                'note',
                'destroy_note',
                'reset',
                'cancel',
                'approval',
                // 'print',
                // 'issue',
                'clear',
                'clear_edit',
            ];

            $confidentials_2 = [
                'duplicate',
                // 'edit',
                // 'edit_reimbursement',
                // 'update',
                // 'update_reimbursement',
                // 'update_issued',
                // 'update_issued_company',
                // 'update_issued_clear',
                'show',
                'manage',
                'note',
                'destroy_note',
                'reset',
                'cancel',
                'approval',
                // 'print',
                // 'issue',
                // 'clear',
                // 'clear_edit',
            ];

            $user = User::where('id', auth()->id())->first();
            $ua_level_route = UaLevelRoute::select('ua_route_option_id')->where('ua_route_id', config('global.ua_trans_view_conf'))->where('ua_level_id', $user->ualevel->id)->first();
            
            // check levels
            if (in_array(explode('@', Route::getCurrentRoute()->getActionName())[1], $confidentials)
                && (
                        User::find(auth()->id())->ualevel->code < $transaction->owner->ualevel->code
                        && auth()->id() != $transaction->requested_id
                    )
                ) {

                if (explode('@', Route::getCurrentRoute()->getActionName())[1] != 'show' && !$user->is_accounting) {
                    return abort(401);
                }
            }

            // check confidential parallel
            if (
                in_array(explode('@', Route::getCurrentRoute()->getActionName())[1], $confidentials_2)
                // && User::find(auth()->id())->ualevel->code == $transaction->owner->ualevel->code
                && User::find(auth()->id())->ualevel->code <= $transaction->owner->ualevel->code
                && $transaction->is_confidential
                && auth()->id() != $transaction->owner->id
                && auth()->id() != $transaction->requested_id
            ) {
                if ($ua_level_route->ua_route_option_id != config('global.is_yesno_id')[0] || explode('@', Route::getCurrentRoute()->getActionName())[1] != 'show') {
                    return abort(401);
                }
            }

            // check confidential own
            if (
                in_array(explode('@', Route::getCurrentRoute()->getActionName())[1], $confidentials)
                && $transaction->is_confidential_own
                && auth()->id() != $transaction->owner->id
                && auth()->id() != $transaction->requested_id
            ) {
                return abort(401);
            }
        }

        return $next($request);
    }
}
