<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionsSoa extends Model {
    use SoftDeletes;
    protected $table = 'transactions_soa';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['transaction.trans_type',
                                        'transaction.trans_year',
                                        'transaction.trans_seq',
                                        'description',
                                        'file'
                                        ];
    protected static $logName = 'Transaction SOA';
    protected static $logOnlyDirty = true;

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
