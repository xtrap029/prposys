<?php

namespace App\Http\Controllers\People;

use Spatie\Activitylog\Models\Activity;
use App\Transaction;
use App\User;
use App\Helpers\TransactionHelper;
use App\Helpers\UAHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogsController extends Controller {
    
    public function index() {
        $activity = Activity::orderBy('id', 'desc');
        
        $activity = $activity->whereNotIn('log_name', ['Transaction Liquidation', 'Transaction Descriptions', 'Transaction Attachments', 'Transaction Notes']);
        
        if (UAHelper::get()['peo_activity'] == config('global.ua_own')) {
            $activity = $activity->where('causer_id', auth()->id());
        }

        if (!empty($_GET['log_name']) && $_GET['log_name'] != "") $activity = $activity->where('log_name', $_GET['log_name']);

        $activity = $activity->paginate(10);
        if (!empty($_GET['log_name'])) $activity->appends(['log_name' => $_GET['log_name']]);

        $log_name = Activity::select('log_name')->distinct('log_name')->get();

        // child records (SOA, notes, ...) follow their parent transaction's confidentiality
        $child_types = [
            'App\TransactionsSoa',
            'App\TransactionsNote',
            'App\TransactionsAttachment',
            'App\TransactionsLiquidation',
            'App\TransactionsDescription',
        ];

        $parent_ids = [];
        foreach ($child_types as $type) {
            $ids = $activity->where('subject_type', $type)->pluck('subject_id');
            if ($ids->isNotEmpty()) {
                $parent_ids[$type] = DB::table((new $type)->getTable())->whereIn('id', $ids)->pluck('transaction_id', 'id');
            }
        }

        $transaction_ids = $activity->where('subject_type', 'App\Transaction')->pluck('subject_id');
        foreach ($parent_ids as $ids) {
            $transaction_ids = $transaction_ids->merge($ids->values());
        }

        $transactions = Transaction::withTrashed()->with('owner')->whereIn('id', $transaction_ids->unique())->get()->keyBy('id');

        foreach ($activity as $key => $value) {
            $activity[$key]->is_confidential = 0;

            if ($value->subject_type == 'App\Transaction') {
                $transaction_id = $value->subject_id;
            } elseif (in_array($value->subject_type, $child_types)) {
                $transaction_id = isset($parent_ids[$value->subject_type]) ? $parent_ids[$value->subject_type]->get($value->subject_id) : null;
            } else {
                continue;
            }

            $transaction = $transaction_id ? $transactions->get($transaction_id) : null;
            // missing transactions are hidden as confidential
            $activity[$key]->is_confidential = !$transaction || TransactionHelper::isConfidentialFor($transaction) ? 1 : 0;
            $activity[$key]->owner_id = $transaction ? $transaction->owner_id : null;
        }

        return view('pages.people.activitylog.index')->with([
            'log_name' => $log_name,
            'activity_logs' => $activity
        ]);
    }
}
