<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Transaction;
use App\User;
use App\Settings;
use App\Helpers\TransactionHelper;

class NotificationsController extends Controller {

    public function almost_due() {
        $user = User::where('id', auth()->id())->first();
        $company_id = $user->company_id;
        $due = [];

        $due_days = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_DAYS')->first()->value;
        $due_days_2 = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_DAYS_2')->first()->value;
        $cc = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_CC')->first()->value;

        if ($user->is_accounting_head) {
            $due = Transaction::whereHas('project', function($query) use($company_id) {
                $query->where('company_id', $company_id);
            })
            ->where('trans_type', '!=', 'pc')
            ->whereRaw("DATEDIFF('".now()."',status_updated_at) > ".$due_days)
            ->whereRaw("DATEDIFF('".now()."',status_updated_at) < ".$due_days_2)
            ->whereIn('status_id', config('global.form_issued'))
            ->orderBy('status_updated_at', 'asc')
            ->get();
            
            foreach ($due as $key => $value) {
                $mailable = new \App\Mail\NotificationsAlmostDueMail([
                    'to' => $value->requested->email,
                    'name' => $value->requested->name,
                    'url' => env('APP_URL').'/transaction-form/view/'.$value->id,
                    'project' => $value->project->project,
                    'no' => strtoupper($value->trans_type)."-".$value->trans_year."-".sprintf('%05d',$value->trans_seq),
                    'purpose' => $value->purpose,
                    'amount' => $value->amount,
                    'cc' => array_filter(explode(';', $cc)),
                ]);
                $to = $value->requested->email;
                app()->terminating(fn() => Mail::to($to)->send($mailable));
            }

            return redirect('/sequence-dashboard')->with('success', 'Notification Sent!');
        } else {
            return abort(401);
        }
    }

    public function past_due() {
        $user = User::where('id', auth()->id())->first();
        $company_id = $user->company_id;
        $due = [];

        $due_days = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_DAYS')->first()->value;
        $due_days_2 = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_DAYS_2')->first()->value;
        $cc = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_CC')->first()->value;

        if ($user->is_accounting_head) {
            $due = Transaction::whereHas('project', function($query) use($company_id) {
                $query->where('company_id', $company_id);
            })
            ->where('trans_type', '!=', 'pc')
            ->whereRaw("DATEDIFF('".now()."',status_updated_at) > ".$due_days_2)
            ->whereIn('status_id', config('global.form_issued'))
            ->orderBy('status_updated_at', 'asc')
            ->get();
            
            foreach ($due as $key => $value) {
                $mailable = new \App\Mail\NotificationsPastDueMail([
                    'to' => $value->requested->email,
                    'name' => $value->requested->name,
                    'url' => env('APP_URL').'/transaction-form/view/'.$value->id,
                    'project' => $value->project->project,
                    'no' => strtoupper($value->trans_type)."-".$value->trans_year."-".sprintf('%05d',$value->trans_seq),
                    'purpose' => $value->purpose,
                    'amount' => $value->amount,
                    'cc' => array_filter(explode(';', $cc)),
                ]);
                $to = $value->requested->email;
                app()->terminating(fn() => Mail::to($to)->send($mailable));
            }

            return redirect('/sequence-dashboard')->with('success', 'Notification Sent!');
        } else {
            return abort(401);
        }
    }

    public function sendVendorIssuedMail($transaction) {
        if ($transaction->vendor_id && !$transaction->is_reimbursement && $transaction->issue_slip) {
            $cc = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_CC')->first()->value;
            $attachmentKey = TransactionHelper::generateAttachmentKey($transaction);
            $vendorMailable = new \App\Mail\NotificationsIssuedVendorMail([
                'to' => $transaction->vendor->email,
                'name' => $transaction->vendor->name,
                'purpose' => $transaction->purpose,
                'requestor_email' => $transaction->requested->email,
                'requestor_name' => $transaction->requested->name,
                'url' => env('APP_URL').'/attachments/issue_slip/'.$transaction->issue_slip,
                'key' => $attachmentKey->key,
                'key_expiry_days' => now()->diffInDays($attachmentKey->expires_at),
                'cc' => array_filter(explode(';', $cc)),
            ]);
            $vendorTo = $transaction->vendor->email;
            app()->terminating(fn() => Mail::to($vendorTo)->send($vendorMailable));
        }
    }

    public function issued($transaction) {
        if ($transaction->status_id == config('global.form_issued')[0]) {
            $cc = Settings::where('type', 'SEQUENCE_ISSUED_NOTIFY_CC')->first()->value;

            $this->sendVendorIssuedMail($transaction);

            $issuedMailable = new \App\Mail\NotificationsIssuedMail([
                'to' => $transaction->requested->email,
                'name' => $transaction->requested->name,
                'url' => env('APP_URL').'/transaction-form/view/'.$transaction->id,
                'project' => $transaction->project->project,
                'no' => strtoupper($transaction->trans_type)."-".$transaction->trans_year."-".sprintf('%05d',$transaction->trans_seq),
                'purpose' => $transaction->purpose,
                'amount' => $transaction->amount,
                'cc' => array_filter(explode(';', $cc)),
            ]);
            $issuedTo = $transaction->requested->email;
            app()->terminating(fn() => Mail::to($issuedTo)->send($issuedMailable));
            return;
        } else {
            return abort(401);
        }
    }
}
