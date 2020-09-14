<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class BankBranch extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name', 'bank.name'];
    protected static $logName = 'Bank Branch';
    protected static $logOnlyDirty = true;

    public function bank() {
        return $this->belongsTo(Bank::class);
    }
}
