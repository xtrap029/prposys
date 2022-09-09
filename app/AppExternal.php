<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AppExternal extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name', 'icon', 'url'];
    protected static $logName = 'App External';
    protected static $logOnlyDirty = true;
}