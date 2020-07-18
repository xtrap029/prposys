<?php

namespace App\Helpers;

use App\Settings;
use App\Transaction;
use App\User;

final class TransactionHelper {
    public static function check_unliquidated_balance($user) {
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
        $trans_bal['limit_amount'] = $trans_amount_limit;
        $trans_bal['used_amount'] = $trans_amount;
        $trans_bal['count'] = $trans_count_limit - $trans_count;
        $trans_bal['limit_count'] = $trans_count_limit;
        $trans_bal['used_count'] = $trans_count;
        $trans_bal['percentage_amount'] = ($trans_bal['used_amount']/$trans_amount_limit) * 100;
        $trans_bal['percentage_count'] = ($trans_bal['used_count']/$trans_count_limit) * 100;

        return $trans_bal;
    }
}
?>