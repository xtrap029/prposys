<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ReleasedBy extends Model {
    use SoftDeletes;
    protected $table = 'released_by';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name'];
    protected static $logName = 'Released By';
    protected static $logOnlyDirty = true;
}
