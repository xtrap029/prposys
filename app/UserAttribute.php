<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UserAttribute extends Model {
    use SoftDeletes;
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = [
        'name',
        'order'
    ];
    protected static $logName = 'User Attribute';
    protected static $logOnlyDirty = true;
}