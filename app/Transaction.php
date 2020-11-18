<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['trans_type',
                                        'trans_year',
                                        'trans_seq',
                                        'particulars.name',
                                        'particulars_custom',
                                        'currency',
                                        'amount',
                                        'purpose',
                                        'project.project',
                                        'payee',
                                        'due_at',
                                        'edit_count',
                                        'requested.name',
                                        'owner.name',
                                        'coatagging.name',
                                        'expense_type_description',
                                        // 'expensetype.name',
                                        'vattype.name',
                                        'control_type',
                                        'control_no',
                                        'releasedby.name',
                                        'released_at',
                                        'amount_issued',
                                        'cancellation_number',
                                        'cancellation_reason',
                                        'form_vat_code',
                                        'form_vat_name',
                                        'form_vat_vat',
                                        'form_vat_wht',
                                        'form_amount_unit',
                                        'form_amount_subtotal',
                                        'form_amount_vat',
                                        'form_amount_wht',
                                        'form_amount_payable',
                                        'formapprover.name',
                                        'liquidationapprover.name',
                                        'depo_type',
                                        // 'bank.name',
                                        'bankbranch.name',
                                        'depo_ref',
                                        'depo_date',
                                        'depo_slip',
                                        'updatedby.name',
                                        'status.name',
                                        'is_deposit',
                                        'is_bills',
                                        'is_hr'
                                        ];
    protected static $logName = 'Transaction';
    protected static $logOnlyDirty = true;

    public function particulars() {
        return $this->belongsTo(Particulars::class)->withTrashed();
    }

    public function project() {
        return $this->belongsTo(CompanyProject::class)->withTrashed();
    }

    public function coatagging() {
        return $this->belongsTo(CoaTagging::class, 'coa_tagging_id')->withTrashed();
    }

    public function expensetype() {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id')->withTrashed();
    }

    public function vattype() {
        return $this->belongsTo(VatType::class, 'vat_type_id')->withTrashed();
    }

    public function requested() {
        return $this->belongsTo(User::class);
    }

    public function releasedby() {
        return $this->belongsTo(ReleasedBy::class, 'released_by_id')->withTrashed();
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }

    public function formapprover() {
        return $this->belongsTo(User::class, 'form_approver_id');
    }

    public function liquidationapprover() {
        return $this->belongsTo(User::class, 'liquidation_approver_id');
    }

    public function status() {
        return $this->belongsTo(TransactionStatus::class);
    }

    public function transaction_description() {
        return $this->hasMany(TransactionsDescription::class);
    }

    public function liquidation() {
        return $this->hasMany(TransactionsLiquidation::class);
    }
    
    public function attachments() {
        return $this->hasMany(TransactionsAttachment::class);
    }
    
    // public function bank() {
    //     return $this->belongsTo(Bank::class, 'depo_bank_id');
    // }

    public function bankbranch() {
        return $this->belongsTo(BankBranch::class, 'depo_bank_branch_id')->withTrashed();
    }
}
