<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionsLiquidation extends Model {
    use SoftDeletes;
    protected $table = 'transactions_liquidation';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['transaction.trans_type',
                                        'transaction.trans_year',
                                        'transaction.trans_seq',
                                        'date',
                                        'project.project',
                                        'expensetype.name',
                                        'description',
                                        'location',
                                        'receipt',
                                        'amount',
                                        ];
    protected static $logName = 'Transaction Liquidation';
    protected static $logOnlyDirty = true;

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function expensetype() {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id')->withTrashed();
    }

    public function project() {
        return $this->belongsTo(CompanyProject::class, 'project_id');
    }
}
