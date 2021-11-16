<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\User;
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
                'print',
                'issue',
                'clear',
                'clear_edit',
            ];

            // check levels
            if (in_array(explode('@', Route::getCurrentRoute()->getActionName())[1], $confidentials)
                && (
                        User::find(auth()->id())->ualevel->code < $transaction->owner->ualevel->code
                        && auth()->id() != $transaction->requested_id
                    )
                ) {
                return abort(401);
            }

            // check confidential parallel
            if (
                in_array(explode('@', Route::getCurrentRoute()->getActionName())[1], $confidentials)
                && User::find(auth()->id())->ualevel->code == $transaction->owner->ualevel->code
                && $transaction->is_confidential
                && auth()->id() != $transaction->owner->id
                && auth()->id() != $transaction->requested_id
            ) {
                return abort(401);
            }
        }

        return $next($request);
    }
}
