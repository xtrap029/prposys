<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ClassType extends Model {
    use SoftDeletes;
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['code', 'name'];
    protected static $logName = 'Class Type';
    protected static $logOnlyDirty = true;
}