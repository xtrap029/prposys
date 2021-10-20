<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UaRoute extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'UA Route';
    protected static $logOnlyDirty = true;
}