<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TravelRole extends Model {
    protected $table = 'travel_roles';
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'Travel Role';
    protected static $logOnlyDirty = true;
}
