<?php

namespace App\Helpers;

use \DB;
use App\Transaction;
use App\Settings;
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

    public static function check_can_generate_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated'))) {
            // check if not admin
            if ($user->role_id != 1) {
                // check if owned
                if ($user->id == $transaction->owner_id) {
                    // check if pr, not po
                    if ($transaction->trans_type == 'pr') {
                        // check role limit
                        if ($user->role_id == 2) {
                            $edit_pr_limit = Settings::where('type', 'LIMIT_EDIT_GENPR_USER_2')->first()->value;
                        } else if ($user->role_id == 3) {
                            $edit_pr_limit = Settings::where('type', 'LIMIT_EDIT_GENPR_USER_3')->first()->value;
                        } else {
                            $can_edit = false;
                        }

                        // check if role limit is enough
                        if ($transaction->edit_count >= $edit_pr_limit) {
                            $can_edit = false;
                        } 
                    }
                } else {
                    $can_edit = false;
                }
            }
        } else {
            $can_edit = false;
        }

        return $can_edit;
    }

    public static function check_can_generate_cancel($transaction, $user = '') {
        $can_cancel = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated'))) {
            // check if not admin and not the owner
            if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
                $can_cancel = false;
            }
        } else {
            $can_cancel = false;
        }

        return $can_cancel;
    }

    public static function check_can_generate_reset($transaction, $user = '') {
        $can_reset = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if reset
        if (!in_array($transaction->status_id, config('global.generated')) || $user->role_id != 1 || in_array($transaction->trans_type, ['po', 'pc'])) {
            $can_reset = false;
        }

        return $can_reset;
    }

    public static function check_can_form_create($key) {
        $can_create = true;

        $result = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $key)
            ->whereIn('status_id', config('global.generated'));

        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $result = $result->where('owner_id', auth()->id());
        }

        $result = $result->count();

        if ($result == 0) $can_create = false;

        return $can_create;
    }

    public static function check_can_form_reset($transaction, $user = '') {
        $can_reset = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if reset
        if (!in_array($transaction->status_id, config('global.generated_form')) || $user->role_id != 1 || in_array($transaction->trans_type, ['pc'])) {
            $can_reset = false;
        }

        return $can_reset;
    }

    public static function check_can_form_cancel($transaction, $user = '') {
        // $can_cancel = true;

        // if (!$user) {
        //     $user = auth()->id();
        // }
        // $user = User::where('id', $user)->first();

        // $transaction = Transaction::where('id', $transaction)->first();

        // // check if unliquidated
        // if (in_array($transaction->status_id, config('global.generated_form'))) {
        //     // check if not admin and not the owner
        //     if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
        //         $can_cancel = false;
        //     }
        // } else {
        //     $can_cancel = false;
        // }

        // return $can_cancel;
        return false;
    }

    public static function check_can_form_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated_form'))) {
            // check if not admin
            if ($user->role_id != 1) {
                // check if owned
                if ($user->id == $transaction->owner_id) {
                    // check if pr, not po
                    if ($transaction->trans_type != 'pc') {
                        // check role limit
                        if ($user->role_id == 2) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_PRPOFORM_USER_2')->first()->value;
                        } else if ($user->role_id == 3) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_PRPOFORM_USER_3')->first()->value;
                        } else {
                            $can_edit = false;
                        }

                        // check if role limit is enough
                        if ($transaction->edit_count >= $edit_limit) {
                            $can_edit = false;
                        } 
                    }
                } else {
                    $can_edit = false;
                }
            }
        } else {
            $can_edit = false;
        }

        return $can_edit;
    }

    public static function check_can_form_approval($transaction, $user = '') {
        $can_approve = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.generated_form'))) {
            // check if not admin and not the owner
            if ($user->role_id != 1 && $user->id != $transaction->owner_id) {
                $can_approve = false;
            }
        } else {
            $can_approve = false;
        }

        return $can_approve;
    }

    public static function check_can_form_print($transaction) {
        $can_print = true;

        $transaction = Transaction::where('id', $transaction)->first();

        //  check if for approval
        if (!in_array($transaction->status_id, config('global.form_approval_printing')) && !in_array($transaction->status_id, config('global.page_liquidation'))) {
            $can_print = false;
        }

        return $can_print;
    }

    public static function check_can_form_issue($transaction, $user = '') {
        $can_issue = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if not unliquidated and not designated approver
        // if (!in_array($transaction->status_id, config('global.form_approval')) || $user->id != $transaction->form_approver_id) {
        if (!in_array($transaction->status_id, config('global.form_approval')) || !in_array($user->role_id, config('global.approver_form'))) {
            $can_issue = false;
        }
        
        return $can_issue;
    }

    public static function check_can_liquidation_create($key) {
        $can_create = true;

        $result = Transaction::where(DB::raw("CONCAT(`trans_type`, '-', `trans_year`, '-', LPAD(`trans_seq`, 5, '0'))"), '=', $key)
            ->whereIn('status_id', config('global.form_issued'));

        if (User::where('id', auth()->id())->first()->role_id != 1) {
            $result = $result->where('requested_id', auth()->id());
        }

        $result = $result->count();

        if ($result == 0) $can_create = false;

        return $can_create;
    }

    public static function check_can_liquidation_edit($transaction, $user = '') {
        $can_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.liquidation_generated'))) {
            // check if not admin
            if ($user->role_id != 1) {
                // check if requestor
                if ($user->id == $transaction->requested_id) {
                    // check if pr, not po
                    if ($transaction->trans_type != 'pc') {
                        // check role limit
                        if ($user->role_id == 2) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_LIQFORM_USER_2')->first()->value;
                        } else if ($user->role_id == 3) {
                            $edit_limit = Settings::where('type', 'LIMIT_EDIT_LIQFORM_USER_3')->first()->value;
                        } else {
                            $can_edit = false;
                        }

                        // check if role limit is enough
                        if ($transaction->edit_count >= $edit_limit) {
                            $can_edit = false;
                        } 
                    }
                } else {
                    $can_edit = false;
                }
            }
        } else if (in_array($transaction->status_id, config('global.liquidation_approval')) && $user->role_id == 1) {
            // if admin and for approval status
        } else {
            $can_edit = false;
        }

        return $can_edit;
    }

    public static function check_can_liquidation_reset($transaction, $user = '') {
        $can_reset = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if reset
        if (!in_array($transaction->status_id, config('global.liquidation_generated')) || $user->role_id != 1 || in_array($transaction->trans_type, ['pc'])) {
            $can_reset = false;
        }

        return $can_reset;
    }

    public static function check_can_liquidation_approval($transaction, $user = '') {
        $can_approve = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if unliquidated
        if (in_array($transaction->status_id, config('global.liquidation_generated'))) {
            // check if not admin and not the requestor
            if ($user->role_id != 1 && $user->id != $transaction->requested_id) {
                $can_approve = false;
            }
        } else {
            $can_approve = false;
        }

        return $can_approve;
    }

    public static function check_can_liquidation_print($transaction) {
        $can_print = true;

        $transaction = Transaction::where('id', $transaction)->first();

        //  check if for approval
        if (!in_array($transaction->status_id, config('global.liquidation_approval')) && !in_array($transaction->status_id, config('global.liquidation_cleared'))) {
            $can_print = false;
        }

        return $can_print;
    }

    public static function check_can_liquidation_clear($transaction, $user = '') {
        $can_clear = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if not for approval and not designated approver
        // if (!in_array($transaction->status_id, config('global.liquidation_approval')) || $user->id != $transaction->liquidation_approver_id) {
        if (!in_array($transaction->status_id, config('global.liquidation_approval')) || !in_array($user->role_id, config('global.approver_form'))) {
            $can_clear = false;
        }
        
        return $can_clear;
    }

    public static function check_can_liquidation_clear_edit($transaction, $user = '') {
        $can_clear_edit = true;

        if (!$user) {
            $user = auth()->id();
        }
        $user = User::where('id', $user)->first();

        $transaction = Transaction::where('id', $transaction)->first();

        // check if not for approval and not designated approver
        if (in_array($transaction->status_id, config('global.liquidation_cleared')) && $user->id == 1) {
        } else {
            $can_clear_edit = false;
        }
        
        return $can_clear_edit;
    }
}
?>