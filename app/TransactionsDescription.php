<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionsDescription extends Model {
    use SoftDeletes;
    protected $table = 'transactions_description';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['transaction.trans_type',
                                        'transaction.trans_year',
                                        'transaction.trans_seq',
                                        'description',
                                        'qty',
                                        'particulars.name',
                                        'amount'
                                        ];
    protected static $logName = 'Transaction Descriptions';
    protected static $logOnlyDirty = true;

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function particulars() {
        return $this->belongsTo(Particulars::class);
    }
}
