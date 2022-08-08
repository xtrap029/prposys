<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserTransactionLimit extends Model {
    protected $guarded = [];
    protected $table = 'user_transaction_limit';

    use LogsActivity;
    protected static $logAttributes = [
        'user.email',
        'company.name',
        'amount_limit',
        'transaction_limit',
        'owner.name',
        'updatedby.name',
    ];
    protected static $logName = 'User Transaction Limit';
    protected static $logOnlyDirty = true;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function updatedby() {
        return $this->belongsTo(User::class, 'updated_id');
    }
}