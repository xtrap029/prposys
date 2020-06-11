<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Settings extends Model {    
    protected $guarded = ['type'];

    use LogsActivity;
    protected static $logAttributes = ['type', 'value'];
    protected static $logName = 'Settings';
}
