<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionsNote extends Model {
    protected $table = 'transactions_notes';
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
                                        'transaction.trans_type',
                                        'transaction.trans_year',
                                        'transaction.trans_seq',
                                        'content',
                                        'user.name',
                                        'user.email',
                                        ];
    protected static $logName = 'Transaction Notes';
    protected static $logOnlyDirty = true;

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
