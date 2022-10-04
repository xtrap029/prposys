<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Form extends Model {
    protected $guarded = [];

    use LogsActivity;
    protected static $logAttributes = ['name', 'description', 'attachment', 'ua_level_ids', 'category'];
    protected static $logName = 'Form';
    protected static $logOnlyDirty = true;
}