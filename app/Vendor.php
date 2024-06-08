<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Vendor extends Model {
    use SoftDeletes;
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'name',
        'address',
        'file',
        'contact_no',
        'contact_person',
        'email',
        'tin',
        'account_bank',
        'account_name',
        'account_number',
    ];
    protected static $logName = 'Vendor';
    protected static $logOnlyDirty = true;
}