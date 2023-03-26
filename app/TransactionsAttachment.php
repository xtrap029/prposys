<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionsAttachment extends Model {
    use SoftDeletes;
    protected $table = 'transactions_attachments';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['transaction.trans_type',
                                        'transaction.trans_year',
                                        'transaction.trans_seq',
                                        'description',
                                        'file',
                                        'type'
                                        ];
    protected static $logName = 'Transaction Attachments';
    protected static $logOnlyDirty = true;

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
