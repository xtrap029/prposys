<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UaLevel extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'UA Level';
    protected static $logOnlyDirty = true;
}