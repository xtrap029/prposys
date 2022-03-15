<?php

namespace App\Helpers;

use App\Settings;
use App\Transaction;
use App\TransactionsLiquidation;
use App\TransactionsNote;
use App\User;

final class TransactionHelper {
    public static function check_liquidated_balance($user, $company = null) {
        $user = User::where('id', $user)->first();

        $transactions = Transaction::where('requested_id', $user->id)
                        ->where('trans_type', 'pr')
                        ->where('is_reimbursement', 0)
                        ->whereIn('status_id', config('global.liquidation_cleared'));
                        
        if ($company) {
            $transactions = $transactions->join('company_projects', 'transactions.project_id', '=', 'company_projects.id')
                            ->where('company_projects.company_id', $company);
        }

        $transactions = $transactions->get();
                                        
        $trans_liq_bal['liq_amount_sum'] = 0;
        foreach ($transactions as $value) {
            $liq_sum = TransactionsLiquidation::where('transaction_id', $value->id)
                ->whereHas('transaction', function($q) {
                    $q->where('is_reimbursement', 0);
                })
                ->get();
            $liq_sum = $liq_sum->sum('amount');
            $trans_liq_bal['liq_amount_sum'] += $liq_sum;
        }

        $trans_liq_bal['issued_amount_sum'] = $transactions->sum('amount_issued');
        $trans_liq_bal['percentage_amount'] = 0;
        if ($trans_liq_bal['issued_amount_sum'] > 0) {
            $trans_liq_bal['percentage_amount'] = ($trans_liq_bal['liq_amount_sum']/$trans_liq_bal['issued_amount_sum']) * 100;
        }
        $trans_liq_bal['balance'] = $trans_liq_bal['issued_amount_sum'] - $trans_liq_bal['liq_amount_sum'];

        return $trans_liq_bal;
    }

    public static function check_unliquidated_balance($user, $company = null) {
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

        if ($company) {
            $transactions = $transactions->join('company_projects', 'transactions.project_id', '=', 'company_projects.id')
                            ->where('company_projects.company_id', $company);
        }

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