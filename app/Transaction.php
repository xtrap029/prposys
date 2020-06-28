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
                                        'control_type',
                                        'control_no',
                                        'released_at',
                                        'amount_issued',
                                        'cancellation_reason',
                                        'updatedby.name',
                                        'status.name'
                                        ];
    protected static $logName = 'Transaction';
    protected static $logOnlyDirty = true;

    public function particulars() {
        return $this->belongsTo(Particulars::class);
    }

    public function project() {
        return $this->belongsTo(CompanyProject::class);
    }

    public function requested() {
        return $this->belongsTo(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }

    public function status() {
        return $this->belongsTo(TransactionStatus::class);
    }
}
