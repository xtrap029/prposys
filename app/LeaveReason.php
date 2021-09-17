<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveReason extends Model {
    use SoftDeletes;
    protected $table = 'leaves_reasons';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name', 'color'];
    protected static $logName = 'Leave Reason';
    protected static $logOnlyDirty = true;
}
